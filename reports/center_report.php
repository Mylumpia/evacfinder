<?php
session_start();
require_once '../models/centers.model.php';

if (!isset($_GET['center_id'])) {
    die('Center ID required');
}

$center_id = $_GET['center_id'];
$report = ModelCenters::mdlGetCenterReport($center_id);
$center = $report['center'];
$evacuees = $report['evacuees'];
$stats = $report['statistics'];

if (!$center) {
    die('Center not found');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Center Report - <?php echo htmlspecialchars($center['center_name']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            color: #1e3c72;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .stats-section {
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .stat-card {
            background: #e8f4f8;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 20px;
            color: #1e3c72;
        }
        .stat-card p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1e3c72;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
            .stat-card {
                break-inside: avoid;
            }
            tr {
                break-inside: avoid;
            }
        }
        .btn-print {
            background: #1e3c72;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #2b4c7c;
        }
        .signature-line {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 250px;
        }
        .signature-line hr {
            margin: 20px 0 5px;
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">Print Report</button>
    
    <div class="header">
        <h1>EVACUATION CENTER REPORT</h1>
        <p>Generated on: <?php echo date('F d, Y h:i A'); ?></p>
    </div>
    
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="30%"><strong>Center Name:</strong></td>
                <td width="70%"><?php echo htmlspecialchars($center['center_name']); ?></td>
            </tr>
            <tr>
                <td><strong>Category:</strong></td>
                <td><?php echo htmlspecialchars($center['category']); ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td><?php echo htmlspecialchars($center['status']); ?></td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td><?php echo htmlspecialchars($center['address'] ?: $center['barangay'] . ', ' . $center['city'] . ', ' . $center['province']); ?></td>
            </tr>
            <tr>
                <td><strong>Capacity:</strong></td>
                <td><?php echo number_format($center['capacity']); ?> persons</td>
            </tr>
            <tr>
                <td><strong>Current Occupants:</strong></td>
                <td><?php echo number_format($center['current_occupants']); ?> persons</td>
            </tr>
            <tr>
                <td><strong>Contact Person:</strong></td>
                <td><?php echo htmlspecialchars($center['contact_person'] ?: 'N/A'); ?></td>
            </tr>
            <tr>
                <td><strong>Contact Number:</strong></td>
                <td><?php echo htmlspecialchars($center['contact_number'] ?: 'N/A'); ?></td>
            </tr>
            <?php if ($center['assigned_lgu_name']): ?>
            <tr>
                <td><strong>Assigned LGU Officer:</strong></td>
                <td><?php echo htmlspecialchars($center['assigned_lgu_name']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($center['remarks']): ?>
            <tr>
                <td><strong>Remarks:</strong></td>
                <td><?php echo nl2br(htmlspecialchars($center['remarks'])); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    
    <div class="stats-section">
        <h3>Demographic Summary</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $stats['total_evacuees']; ?></h3>
                <p>Total Evacuees</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['male']; ?></h3>
                <p>Male</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['female']; ?></h3>
                <p>Female</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['children']; ?></h3>
                <p>Children (under 18)</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['elderly']; ?></h3>
                <p>Elderly (60+)</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['pwd']; ?></h3>
                <p>PWD</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['pregnant']; ?></h3>
                <p>Pregnant</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['lactating']; ?></h3>
                <p>Lactating</p>
            </div>
        </div>
    </div>
    
    <h3>Active Evacuees List</h3>
    <?php if (count($evacuees) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Civil Status</th>
                <th>Contact Number</th>
                <th>Special Conditions</th>
                <th>Arrival Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evacuees as $index => $evacuee): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($evacuee['last_name'] . ', ' . $evacuee['first_name'] . ' ' . ($evacuee['middle_name'] ? $evacuee['middle_name'] . '.' : '')); ?></td>
                <td><?php echo $evacuee['age'] ?: 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($evacuee['sex']); ?></td>
                <td><?php echo htmlspecialchars($evacuee['civil_status'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($evacuee['contact_number'] ?: 'N/A'); ?></td>
                <td>
                    <?php
                    $conditions = [];
                    if ($evacuee['condition_pregnant']) $conditions[] = 'Pregnant';
                    if ($evacuee['condition_lactating']) $conditions[] = 'Lactating';
                    if ($evacuee['condition_elderly']) $conditions[] = 'Elderly';
                    if ($evacuee['condition_pwd']) $conditions[] = 'PWD';
                    if ($evacuee['condition_4ps']) $conditions[] = '4Ps';
                    echo !empty($conditions) ? implode(', ', $conditions) : 'None';
                    ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($evacuee['arrival_date'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No active evacuees currently in this center.</p>
    <?php endif; ?>
    
    <div class="signature-line">
        <div class="signature">
            <hr>
            <p>Encoded By</p>
        </div>
        <div class="signature">
            <hr>
            <p>Center Manager/Coordinator</p>
        </div>
    </div>
    
    <div class="footer">
        <p>This report is system-generated and requires proper acknowledgment from concerned personnel.</p>
        <p>EvacFinder System - <?php echo date('Y'); ?></p>
    </div>
</body>
</html>