<?php
date_default_timezone_set('Asia/Manila');
require_once "../models/centers.model.php";
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$center_id = $_POST['center_id'] ?? '';

if (empty($center_id)) {
    echo json_encode(['success' => false, 'message' => 'Center ID required']);
    exit;
}

try {
    $db = new Connection();
    $pdo = $db->connect();
    $pdo->exec("SET time_zone = '+08:00'");
    
    $pdo->beginTransaction();
    
    // Get center info before inactivation
    $stmt = $pdo->prepare("SELECT center_name, capacity, status FROM centers WHERE center_id = :center_id");
    $stmt->execute([':center_id' => $center_id]);
    $center = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Count active evacuees
    $countStmt = $pdo->prepare("SELECT COUNT(*) as active_count FROM evacuees WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
    $countStmt->execute([':center_id' => $center_id]);
    $activeCount = $countStmt->fetch(PDO::FETCH_ASSOC)['active_count'];
    
    // Update evacuees to departed
    $updateEvacuees = $pdo->prepare("UPDATE evacuees SET evacuee_status = 'Departed', departure_date = CURDATE() WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
    $updateEvacuees->execute([':center_id' => $center_id]);
    $departedCount = $updateEvacuees->rowCount();
    
    // Update center status
    $updateCenter = $pdo->prepare("UPDATE centers SET status = 'Inactive' WHERE center_id = :center_id");
    $updateCenter->execute([':center_id' => $center_id]);
    
    // Log to center_history
    $historySql = "INSERT INTO center_history (center_id, action_type, description, performed_by, created_at)
                   VALUES (:center_id, 'CENTER_INACTIVATED', :description, :performed_by, NOW())";
    $historyStmt = $pdo->prepare($historySql);
    $historyStmt->execute([
        ':center_id' => $center_id,
        ':description' => "Center manually inactivated. $departedCount evacuee(s) were marked as departed. Active evacuees before inactivation: $activeCount",
        ':performed_by' => $_SESSION['userid'] ?? 'System'
    ]);
    
    // Also log to status history
    $statusHistorySql = "INSERT INTO center_status_history (center_id, old_status, new_status, changed_by, changed_at)
                         VALUES (:center_id, :old_status, 'Inactive', :changed_by, NOW())";
    $statusHistoryStmt = $pdo->prepare($statusHistorySql);
    $statusHistoryStmt->execute([
        ':center_id' => $center_id,
        ':old_status' => $center['status'],
        ':changed_by' => $_SESSION['userid'] ?? 'System'
    ]);
    
    $pdo->commit();
    
    // Generate the report URL
    $report_url = "reports/inactivation_report.php?center_id=" . urlencode($center_id);
    
    $response['success'] = true;
    $response['message'] = "Center inactivated. $departedCount evacuee(s) marked as departed.";
    $response['report_url'] = $report_url;
    
} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>