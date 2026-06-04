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
$departedEvacuees = $report['departed_evacuees'];
$stats = $report['statistics'];

if (!$center) {
    die('Center not found');
}

$occupancy = $stats['total_evacuees'];
$capacity = $center['capacity'];
$percentage = ($capacity > 0) ? round(($occupancy / $capacity) * 100) : 0;

// Get center active date - only use date_established if exists
$activeDate = !empty($center['date_established']) && $center['date_established'] != '0000-00-00' 
    ? $center['date_established'] 
    : 'Not specified';

if ($activeDate != 'Not specified') {
    $activeDate = date('F d, Y', strtotime($activeDate));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Center Report - <?php echo htmlspecialchars($center['center_name']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            background: white;
            line-height: 1.4;
        }
        
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
        }
        
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        
        .header p {
            margin: 8px 0 0;
            font-size: 11px;
            color: #555;
        }
        
        .content {
            padding: 0;
        }
        
        .info-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .info-card table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-card td {
            padding: 6px 8px;
            vertical-align: top;
        }
        
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .stats-grid td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        
        .stats-grid h3 {
            font-size: 20px;
            margin: 0;
            font-weight: bold;
        }
        
        .stats-grid p {
            margin: 5px 0 0;
            font-size: 10px;
            color: #555;
        }
        
        .alert-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background: #f9f9f9;
            font-size: 11px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 12px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .evacuee-count {
            border: 1px solid #ddd;
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            margin-bottom: 12px;
            background: #f5f5f5;
        }
        
        .evacuee-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 20px;
        }
        
        .evacuee-table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            background: #f5f5f5;
        }
        
        .evacuee-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        
        .conditions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .condition-tag {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #ccc;
            font-size: 9px;
            background: #fafafa;
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fafafa;
            color: #666;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            text-align: center;
            width: 50%;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 0 8px;
        }
        
        .signature-label {
            font-size: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #777;
        }
        
        .btn-print {
            background: #333;
            color: white;
            padding: 8px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        .btn-print:hover {
            background: #555;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .btn-print {
                display: none;
            }
            .report-container {
                margin: 0;
                padding: 0;
            }
            .header {
                padding: 10px;
            }
            .evacuee-table {
                page-break-inside: avoid;
            }
            .stats-grid td {
                page-break-inside: avoid;
            }
            .info-card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">Print Report</button>
    
    <div class="report-container">
        <div class="header">
            <h1>EVACUATION CENTER REPORT</h1>
            <p>Generated on: <?php echo date('F d, Y h:i A'); ?></p>
        </div>
        
        <div class="content">
            <!-- Center Information -->
            <div class="info-card">
                <table>
                    <tr>
                        <td style="width: 50%;"><strong>Center Name:</strong> <?php echo htmlspecialchars($center['center_name']); ?></td>
                        <td style="width: 50%;"><strong>Category:</strong> <?php echo htmlspecialchars($center['category']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong> <?php echo htmlspecialchars($center['status']); ?></td>
                        <td><strong>Active Since:</strong> <?php echo $activeDate; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Location:</strong> <?php echo htmlspecialchars($center['barangay'] . ', ' . $center['city'] . ', ' . $center['province']); ?></td>
                        <td><strong>Contact Number:</strong> <?php echo htmlspecialchars($center['contact_number'] ?: 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Contact Person:</strong> <?php echo htmlspecialchars($center['contact_person'] ?: 'N/A'); ?></td>
                        <?php if ($center['assigned_lgu_name']): ?>
                        <td><strong>LGU In-Charge:</strong> <?php echo htmlspecialchars($center['assigned_lgu_name']); ?></td>
                        <?php else: ?>
                        <td></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td><strong>Capacity:</strong> <?php echo number_format($center['capacity']); ?> persons</span></td>
                        <td><strong>Current Occupancy:</strong> <?php echo number_format($occupancy); ?> / <?php echo number_format($capacity); ?> persons (<?php echo $percentage; ?>%)</span></td>
                    </tr>
                </table>
            </div>
            
            <!-- Capacity Alerts -->
            <?php if ($occupancy >= $capacity && $capacity > 0): ?>
            <div class="alert-box">
                <strong>AT CAPACITY:</strong> This center has reached its maximum capacity. No additional evacuees can be accommodated.
            </div>
            <?php elseif ($occupancy > ($capacity * 0.8) && $capacity > 0): ?>
            <div class="alert-box">
                <strong>NEAR CAPACITY:</strong> Only <?php echo number_format(max(0, $capacity - $occupancy)); ?> slot(s) remaining.
            </div>
            <?php endif; ?>
            
            <!-- Demographic Statistics -->
            <div class="section-title">Demographic Summary</div>
            <table class="stats-grid">
                <tr>
                    <td><h3><?php echo $stats['total_evacuees']; ?></h3><p>Active Evacuees</p></td>
                    <td><h3><?php echo $stats['total_departed']; ?></h3><p>Departed Evacuees</p></td>
                    <td><h3><?php echo $stats['male']; ?></h3><p>Male</p></td>
                    <td><h3><?php echo $stats['female']; ?></h3><p>Female</p></td>
                </tr>
                <tr>
                    <td><h3><?php echo $stats['children']; ?></h3><p>Children (Under 18)</p></td>
                    <td><h3><?php echo $stats['elderly']; ?></h3><p>Elderly (60+)</p></td>
                    <td><h3><?php echo $stats['pwd']; ?></h3><p>Persons with Disability</p></td>
                    <td><h3><?php echo $stats['pregnant']; ?></h3><p>Pregnant</p></td>
                </tr>
                <tr>
                    <td><h3><?php echo $stats['lactating']; ?></h3><p>Lactating</p></td>
                    <td colspan="3"><h3><?php echo $stats['total_evacuees'] + $stats['total_departed']; ?></h3><p>Total All Time</p></td>
                </tr>
            </table>
            
            <!-- Active Evacuees List -->
            <div class="section-title">Active Evacuees</div>
            
            <?php if (count($evacuees) > 0): ?>
            <div class="evacuee-count">Total Active: <?php echo count($evacuees); ?> evacuees</div>
            
            <table class="evacuee-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th>Full Name</th>
                        <th style="width: 45px;">Age</th>
                        <th style="width: 60px;">Sex</th>
                        <th>Conditions</th>
                        <th style="width: 85px;">Arrival Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evacuees as $index => $evacuee): ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($evacuee['last_name'] . ', ' . $evacuee['first_name']); ?></td>
                        <td style="text-align: center;"><?php echo $evacuee['age'] ?: 'N/A'; ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($evacuee['sex']); ?></td>
                        <td>
                            <div class="conditions-list">
                                <?php
                                $hasConditions = false;
                                if ($evacuee['condition_pregnant']): $hasConditions = true; ?>
                                    <span class="condition-tag">Pregnant</span>
                                <?php endif;
                                if ($evacuee['condition_lactating']): $hasConditions = true; ?>
                                    <span class="condition-tag">Lactating</span>
                                <?php endif;
                                if ($evacuee['condition_elderly']): $hasConditions = true; ?>
                                    <span class="condition-tag">Elderly</span>
                                <?php endif;
                                if ($evacuee['condition_pwd']): $hasConditions = true; ?>
                                    <span class="condition-tag">PWD</span>
                                <?php endif;
                                if ($evacuee['condition_4ps']): $hasConditions = true; ?>
                                    <span class="condition-tag">4Ps</span>
                                <?php endif;
                                if (!$hasConditions): ?>
                                    <span style="color: #999;">None</span>
                                <?php endif; ?>
                            </div>
                        </span>
                        <td style="text-align: center;"><?php echo date('M d, Y', strtotime($evacuee['arrival_date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                No active evacuees currently in this center.
            </div>
            <?php endif; ?>
            
            <!-- Departed Evacuees List -->
            <div class="section-title">Departed Evacuees</div>
            
            <?php if (count($departedEvacuees) > 0): ?>
            <div class="evacuee-count">Total Departed: <?php echo count($departedEvacuees); ?> evacuees</div>
            
            <table class="evacuee-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th>Full Name</th>
                        <th style="width: 45px;">Age</th>
                        <th style="width: 60px;">Sex</th>
                        <th>Conditions</th>
                        <th style="width: 85px;">Arrival Date</th>
                        <th style="width: 85px;">Departure Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departedEvacuees as $index => $evacuee): ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($evacuee['last_name'] . ', ' . $evacuee['first_name']); ?></td>
                        <td style="text-align: center;"><?php echo $evacuee['age'] ?: 'N/A'; ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($evacuee['sex']); ?></td>
                        <td>
                            <div class="conditions-list">
                                <?php
                                $hasConditions = false;
                                if ($evacuee['condition_pregnant']): $hasConditions = true; ?>
                                    <span class="condition-tag">Pregnant</span>
                                <?php endif;
                                if ($evacuee['condition_lactating']): $hasConditions = true; ?>
                                    <span class="condition-tag">Lactating</span>
                                <?php endif;
                                if ($evacuee['condition_elderly']): $hasConditions = true; ?>
                                    <span class="condition-tag">Elderly</span>
                                <?php endif;
                                if ($evacuee['condition_pwd']): $hasConditions = true; ?>
                                    <span class="condition-tag">PWD</span>
                                <?php endif;
                                if ($evacuee['condition_4ps']): $hasConditions = true; ?>
                                    <span class="condition-tag">4Ps</span>
                                <?php endif;
                                if (!$hasConditions): ?>
                                    <span style="color: #999;">None</span>
                                <?php endif; ?>
                            </div>
                        </span>
                        <td style="text-align: center;"><?php echo date('M d, Y', strtotime($evacuee['arrival_date'])); ?></td>
                        <td style="text-align: center;"><?php echo $evacuee['departure_date'] ? date('M d, Y', strtotime($evacuee['departure_date'])) : 'N/A'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                No departed evacuees recorded for this center.
            </div>
            <?php endif; ?>
            
            <?php if ($center['remarks']): ?>
            <div class="section-title">Remarks</div>
            <div style="border: 1px solid #ddd; padding: 12px; margin-top: 10px;">
                <?php echo nl2br(htmlspecialchars($center['remarks'])); ?>
            </div>
            <?php endif; ?>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Encoded By</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-label">Center Manager / Coordinator</div>
                </div>
            </div>
            
            <div class="footer">
                This report is system-generated and requires proper acknowledgment from concerned personnel.
                <br>EvacFinder System - <?php echo date('Y'); ?>
            </div>
        </div>
    </div>
</body>
</html>