<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../controllers/history.controller.php";
require_once __DIR__ . "/../models/history.model.php";

header('Content-Type: application/json');

// Fields to compare between history records
$comparableFields = [
    'center_name'          => 'Center Name',
    'category'             => 'Category',
    'status'               => 'Status',
    'barangay'             => 'Barangay',
    'city'                 => 'City',
    'province'             => 'Province',
    'address'              => 'Address',
    'capacity'             => 'Capacity',
    'current_occupants'    => 'Current Occupants',
    'contact_number'       => 'Contact Number',
    'contact_person'       => 'Contact Person',
    'accessibility'        => 'Accessibility',
    'available_facilities' => 'Available Facilities',
    'remarks'              => 'Remarks',
    'latitude'             => 'Latitude',
    'longitude'            => 'Longitude',
];

if (isset($_POST["center_id"])) {
    $center_id = $_POST["center_id"];
    $history   = ControllerHistory::ctrGetHistory($center_id);

    if ($history !== false) {
        $result = [];

        foreach ($history as $index => $record) {
            $changes = [];

            if ($index === 0) {
                // First record — Created, no comparison needed
                $changes[] = 'Initial record created.';
            } else {
                $prev = $history[$index - 1];

                foreach ($comparableFields as $field => $label) {
                    $oldVal = trim((string)($prev[$field] ?? ''));
                    $newVal = trim((string)($record[$field] ?? ''));

                    if ($oldVal !== $newVal) {
                        $oldDisplay = $oldVal !== '' ? $oldVal : '(empty)';
                        $newDisplay = $newVal !== '' ? $newVal : '(empty)';
                        $changes[] = "$label: $oldDisplay → $newDisplay";
                    }
                }

                if (empty($changes)) {
                    $changes[] = 'No field changes detected.';
                }
            }

            $result[] = [
                'history_date'    => $record['history_date'],
                'action_made'     => $record['action_made'],
                'status'          => $record['status'],
                'capacity'        => $record['capacity'],
                'current_occupants' => $record['current_occupants'],
                'contact_person'  => $record['contact_person'],
                'remarks'         => $record['remarks'],
                'changed_by_name' => $record['changed_by_name'],
                'changes'         => $changes,
            ];
        }

        // Reverse so newest shows first in the table
        $result = array_reverse($result);

        echo json_encode(["success" => true, "data" => $result]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to fetch history"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing center_id"]);
}
?>