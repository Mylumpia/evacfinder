<?php
$summary = ModelCenters::mdlGetCenterSummary();
$allCenters = ModelCenters::mdlGetAllCenters();
?>

<div class="home-dashboard">
<div class="container-fluid flex-grow-1 container-p-y">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100 stats-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-primary fw-bold">Total Centers</p>
                        <h3 class="mb-0"><?php echo number_format($summary['total_centers']); ?></h3>
                    </div>
                    <div class="stats-card-icon stats-card-icon-primary text-white rounded-3">
                        <i class="fa fa-home"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100 stats-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-success fw-bold">Total Capacity</p>
                        <h3 class="mb-0"><?php echo number_format($summary['total_capacity']); ?></h3>
                    </div>
                    <div class="stats-card-icon stats-card-icon-success text-white rounded-3">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100 stats-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-warning fw-bold">Currently Occupied</p>
                        <h3 class="mb-0" id="currentlyOccupiedCount"><?php echo number_format(max(0, $summary['currently_occupied'])); ?></h3>
                    </div>
                    <div class="stats-card-icon stats-card-icon-warning text-white rounded-3">
                        <i class="fa fa-bed"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100 stats-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-info fw-bold">Active Centers</p>
                        <h3 class="mb-0"><?php echo number_format($summary['active_centers']); ?></h3>
                    </div>
                    <div class="stats-card-icon stats-card-icon-info text-white rounded-3">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Centers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Evacuation Centers</h5>
                    <div>
                        <a href="?route=centers" class="btn btn-primary btn-sm me-2">
                            <i class="fa fa-plus"></i> Add Evacuation Center
                        </a>
                        <a href="?route=active" class="btn btn-secondary btn-sm">
                            <i class="fa fa-refresh"></i> Refresh
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="centersTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th>Center Name</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Current Occupancy</th>
                                    <th>Status</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($allCenters) > 0): ?>
                                    <?php foreach($allCenters as $center): ?>
                                    <?php
                                        $statusText = htmlspecialchars($center['status']);
                                        $badgeClass = 'bg-secondary';
                                        if ($statusText === 'Active') {
                                            $badgeClass = 'bg-success';
                                        } elseif ($statusText === 'Inactive') {
                                            $badgeClass = 'bg-secondary';
                                        } elseif ($statusText === 'Full') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($statusText === 'Under Maintenance') {
                                            $badgeClass = 'bg-danger';
                                        }
                                        
                                        $db = new Connection();
                                        $pdo = $db->connect();
                                        $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
                                        $stmt->bindParam(":center_id", $center['center_id']);
                                        $stmt->execute();
                                        $fullCenter = $stmt->fetch(PDO::FETCH_ASSOC);
                                        
                                        // Get actual active evacuee count
                                        $stmtCount = $pdo->prepare("SELECT COUNT(*) as active_count FROM evacuees WHERE evacuation_center_id = :center_id AND evacuee_status = 'Active'");
                                        $stmtCount->bindParam(":center_id", $center['center_id']);
                                        $stmtCount->execute();
                                        $activeCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
                                        $actualOccupancy = $activeCount['active_count'];
                                        
                                        $stmtLGU = $pdo->prepare("SELECT u.first_name, u.last_name, u.position_role, u.phone_number FROM lgu_users u 
                                                                   JOIN userrights ur ON u.office_email_address = ur.email
                                                                   WHERE ur.userid = :userid");
                                        if($fullCenter['assigned_lgu_user_id']) {
                                            $stmtLGU->bindParam(":userid", $fullCenter['assigned_lgu_user_id']);
                                            $stmtLGU->execute();
                                            $assignedLGU = $stmtLGU->fetch(PDO::FETCH_ASSOC);
                                        } else {
                                            $assignedLGU = null;
                                        }
                                    ?>
                                    <tr class="center-row" data-center-id="<?php echo $center['center_id']; ?>" style="cursor: pointer;">
                                        <td class="expand-icon text-center">
                                            <i class="fa fa-chevron-right"></i>
                                        </td>
                                        <td><?php echo htmlspecialchars($center['center_name']); ?></td>
                                        <td><?php echo htmlspecialchars($center['category']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($center['barangay'] . ', ' . $center['city'] . ', ' . $center['province'])); ?></td>
                                        <td class="capacity-cell">
                                            <span class="capacity-display-<?php echo $center['center_id']; ?>"><?php echo number_format($center['capacity']); ?></span>
                                        </td>
                                        <td class="occupancy-cell">
                                            <span class="occupancy-display-<?php echo $center['center_id']; ?>"><?php echo number_format($actualOccupancy); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $badgeClass; ?> status-badge-<?php echo $center['center_id']; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-secondary print-report" 
                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Expanded details row -->
                                    <tr class="details-row-<?php echo $center['center_id']; ?> details-row" style="display: none;">
                                        <td colspan="8" class="p-0">
                                            <div class="card-body bg-light p-3">
                                                <!-- Action Buttons Row -->
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-primary edit-center-dropdown" 
                                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>"
                                                                    data-category="<?php echo htmlspecialchars($center['category']); ?>"
                                                                    data-status="<?php echo htmlspecialchars($statusText); ?>"
                                                                    data-barangay="<?php echo htmlspecialchars($center['barangay']); ?>"
                                                                    data-city="<?php echo htmlspecialchars($center['city']); ?>"
                                                                    data-province="<?php echo htmlspecialchars($center['province']); ?>"
                                                                    data-address="<?php echo htmlspecialchars($fullCenter['address']); ?>"
                                                                    data-capacity="<?php echo $center['capacity']; ?>"
                                                                    data-current-occupants="<?php echo $actualOccupancy; ?>"
                                                                    data-contact-number="<?php echo htmlspecialchars($fullCenter['contact_number']); ?>"
                                                                    data-contact-person="<?php echo htmlspecialchars($fullCenter['contact_person']); ?>"
                                                                    data-latitude="<?php echo $fullCenter['latitude']; ?>"
                                                                    data-longitude="<?php echo $fullCenter['longitude']; ?>"
                                                                    data-estimated-capacity="<?php echo $fullCenter['estimated_capacity']; ?>"
                                                                    data-accessibility="<?php echo htmlspecialchars($fullCenter['accessibility']); ?>"
                                                                    data-available-facilities="<?php echo htmlspecialchars($fullCenter['available_facilities']); ?>"
                                                                    data-remarks="<?php echo htmlspecialchars($fullCenter['remarks']); ?>">
                                                                <i class="fa fa-edit"></i> Edit Center
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-success add-evacuee-dropdown" 
                                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>"
                                                                    data-current-occupants="<?php echo $actualOccupancy; ?>"
                                                                    data-capacity="<?php echo $center['capacity']; ?>">
                                                                <i class="fa fa-user-plus"></i> Add Evacuee
                                                            </button>                                                            
                                                            <button type="button" class="btn btn-sm btn-info assign-lgu-dropdown" 
                                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>">
                                                                <i class="fa fa-user-md"></i> Assign LGU
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <!-- Center Information Panel -->
                                                    <div class="col-md-4">
                                                        <div class="card mb-3">
                                                            <div class="card-header bg-primary text-white">
                                                                <h6 class="mb-0">CENTER INFORMATION</h6>
                                                                <button type="button" class="btn btn-sm btn-warning view-history" 
                                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>">
                                                                <i class="fa fa-history"></i> History</button>
                                                            </div>
                                                            <div class="card-body">
                                                                <table class="table table-sm table-borderless mb-3">
                                                                    <tr><td width="40%"><strong>Center ID:</strong></td><td><?php echo htmlspecialchars($center['center_id']); ?></td></tr>
                                                                    <tr><td><strong>Category:</strong></td><td><?php echo htmlspecialchars($center['category']); ?></td></tr>
                                                                    <tr><td><strong>Location:</strong></td><td><?php echo htmlspecialchars(trim($center['barangay'] . ', ' . $center['city'])); ?></td></tr>
                                                                    <tr><td><strong>Province:</strong></td><td><?php echo htmlspecialchars($center['province']); ?></td></tr>
                                                                    <tr><td><strong>Capacity:</strong></td><td><?php echo number_format($center['capacity']); ?> persons</td></tr>
                                                                    <tr><td><strong>Current Occupants:</strong></td><td><?php echo number_format($actualOccupancy); ?> / <?php echo number_format($center['capacity']); ?></td></tr>
                                                                    <?php if($fullCenter['address']): ?>
                                                                    <tr><td><strong>Address:</strong></td><td><?php echo htmlspecialchars($fullCenter['address']); ?></td></tr>
                                                                    <?php endif; ?>
                                                                </table>
                                                                
                                                                <hr>
                                                                <h6 class="fw-bold mb-2">PERSON IN-CHARGE</h6>
                                                                <div id="person-incharge-<?php echo $center['center_id']; ?>">
                                                                    <?php if($assignedLGU): ?>
                                                                        <p class="mb-0"><?php echo htmlspecialchars($assignedLGU['first_name'] . ' ' . $assignedLGU['last_name']); ?></p>
                                                                        <small class="text-muted"><?php echo htmlspecialchars($assignedLGU['position_role']); ?></small><br>
                                                                        <small><?php echo htmlspecialchars($assignedLGU['phone_number']); ?></small>
                                                                    <?php else: ?>
                                                                        <p class="text-muted mb-1">No LGU assigned yet</p>
                                                                        <button type="button" class="btn btn-sm btn-outline-info assign-lgu-quick mt-2" data-center-id="<?php echo $center['center_id']; ?>" data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>">
                                                                            Assign LGU
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </div>
                                                                
                                                                <hr>
                                                                <h6 class="fw-bold mb-2">CONTACT INFORMATION</h6>
                                                                <p class="mb-0"><?php echo htmlspecialchars($fullCenter['contact_number'] ?: 'N/A'); ?></p>
                                                                <p><?php echo htmlspecialchars($fullCenter['contact_person'] ?: 'N/A'); ?></p>
                                                                
                                                                <?php if($fullCenter['remarks']): ?>
                                                                <hr>
                                                                <h6 class="fw-bold mb-2">REMARKS</h6>
                                                                <p class="mb-0 small text-muted"><?php echo nl2br(htmlspecialchars($fullCenter['remarks'])); ?></p>
                                                                <?php endif; ?>
                                                                
                                                                <hr>
                                                                <h6 class="fw-bold mb-2">STATUS HISTORY</h6>
                                                                <div id="status-history-<?php echo $center['center_id']; ?>" style="max-height: 150px; overflow-y: auto; font-size: 0.8rem;">
                                                                    <div class="text-muted">Loading...</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Evacuee List Panel -->
                                                    <div class="col-md-4">
                                                        <div class="card mb-3">
                                                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">EVACUEES</h6>
                                                                <button class="btn btn-sm btn-light refresh-evacuees" data-center-id="<?php echo $center['center_id']; ?>">
                                                                    Refresh
                                                                </button>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-2">
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="text" class="form-control evacuee-search-input" data-center-id="<?php echo $center['center_id']; ?>" placeholder="Search by name, contact, or status...">
                                                                        <button class="btn btn-outline-secondary clear-search" data-center-id="<?php echo $center['center_id']; ?>" type="button">Clear</button>
                                                                    </div>
                                                                </div>
                                                                <div class="evacuee-list-container" style="max-height: 400px; overflow-y: auto;">
                                                                    <div id="evacuee-list-<?php echo $center['center_id']; ?>">
                                                                        <div class="text-muted text-center p-3">Click to load evacuees...</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Activity History Panel -->
                                                    <div class="col-md-4">
                                                        <div class="card mb-3">
                                                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0">ACTIVITY HISTORY</h6>
                                                                <button class="btn btn-sm btn-light refresh-history" data-center-id="<?php echo $center['center_id']; ?>">
                                                                    Refresh
                                                                </button>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="history-container" style="max-height: 450px; overflow-y: auto;">
                                                                    <div id="history-list-<?php echo $center['center_id']; ?>">
                                                                        <div class="text-muted text-center p-3">Click to load history...</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            No evacuation centers found. 
                                            <a href="?route=centers" class="btn btn-primary btn-sm ms-2">
                                                <i class="fa fa-plus"></i> Add Your First Center
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Add Evacuee Modal -->
<div class="modal fade" id="addEvacueeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register New Evacuee</h5>
                <button type="button" class="close-modal-btn" data-modal="addEvacueeModal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addEvacueeForm">
                    <input type="hidden" id="evac_center_id" name="evacuation_center_id">
                    <input type="hidden" id="evac_center_name_display" name="center_name_display">
                    <input type="hidden" id="evac_encodedby" name="encodedby" value="<?php echo $_SESSION['userid']; ?>">
                    
                    <div class="alert alert-info mb-3">
                        <strong>Evacuation Center:</strong> <span id="selectedCenterName"></span>
                        <br>
                        <strong>Current Occupancy:</strong> <span id="selectedCenterOccupancy"></span> / <span id="selectedCenterCapacity"></span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="evac_registration_date" name="registration_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Arrival Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="evac_arrival_date" name="arrival_date" required>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Personal Information</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="evac_last_name" name="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="evac_first_name" name="first_name" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="evac_middle_name" name="middle_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Extension Name</label>
                            <input type="text" class="form-control" id="evac_extension_name" name="extension_name" placeholder="Jr., Sr., III">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Relation to Head</label>
                            <input type="text" class="form-control" id="evac_relation_to_head" name="relation_to_head" placeholder="Self, Spouse, Child">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Sex <span class="text-danger">*</span></label>
                            <select class="form-control" id="evac_sex" name="sex" required>
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Birth Date</label>
                            <input type="date" class="form-control" id="evac_birth_date" name="birth_date">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" id="evac_age" name="age" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Civil Status</label>
                            <select class="form-control" id="evac_civil_status" name="civil_status">
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="evac_occupation" name="occupation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="evac_contact_number" name="contact_number">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Complete Address</label>
                            <input type="text" class="form-control" id="evac_complete_address" name="complete_address" placeholder="Full home address">
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Emergency Contact</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Person</label>
                            <input type="text" class="form-control" id="evac_emergency_contact_person" name="emergency_contact_person">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Contact Number</label>
                            <input type="text" class="form-control" id="evac_emergency_contact_number" name="emergency_contact_number">
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Special Conditions</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evac_condition_pregnant" name="condition_pregnant" value="1">
                                <label class="form-check-label" for="evac_condition_pregnant">Pregnant</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evac_condition_lactating" name="condition_lactating" value="1">
                                <label class="form-check-label" for="evac_condition_lactating">Lactating</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evac_condition_elderly" name="condition_elderly" value="1">
                                <label class="form-check-label" for="evac_condition_elderly">Elderly</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evac_condition_pwd" name="condition_pwd" value="1">
                                <label class="form-check-label" for="evac_condition_pwd">PWD</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evac_condition_4ps" name="condition_4ps" value="1">
                                <label class="form-check-label" for="evac_condition_4ps">4Ps Beneficiary</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">PWD Type (if applicable)</label>
                            <select class="form-control" id="evac_pwd_type" name="pwd_type">
                                <option value="">Select</option>
                                <option value="Mobility">Mobility</option>
                                <option value="Visual">Visual</option>
                                <option value="Hearing">Hearing</option>
                                <option value="Speech">Speech</option>
                                <option value="Cognitive">Cognitive</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Medical Information</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Health Status</label>
                            <select class="form-control" id="evac_health_status" name="health_status">
                                <option value="">Select</option>
                                <option value="Good">Good</option>
                                <option value="With illness">With illness</option>
                                <option value="Under medication">Under medication</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Emergency Medical Condition</label>
                            <select class="form-control" id="evac_emergency_medical_condition" name="emergency_medical_condition">
                                <option value="">Select</option>
                                <option value="None">None</option>
                                <option value="Hypertension">Hypertension</option>
                                <option value="Diabetes">Diabetes</option>
                                <option value="Asthma">Asthma</option>
                                <option value="Heart Disease">Heart Disease</option>
                                <option value="Kidney Disease">Kidney Disease</option>
                                <option value="Epilepsy">Epilepsy</option>
                                <option value="Allergy">Allergy</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Medications Taken</label>
                            <textarea class="form-control" id="evac_medications_taken" name="medications_taken" rows="2" placeholder="List current medications"></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Known Allergies</label>
                            <textarea class="form-control" id="evac_known_allergies" name="known_allergies" rows="2" placeholder="List known allergies"></textarea>
                        </div>
                    </div>
                    
                    <input type="hidden" id="evac_evacuee_status" name="evacuee_status" value="Active">
                    <input type="hidden" id="evac_departure_date" name="departure_date" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-modal" data-modal="addEvacueeModal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddEvacuee">Register Evacuee</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Evacuee Status Modal -->
<div class="modal fade" id="editEvacueeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Evacuee Status</h5>
                <button type="button" class="close-modal-btn" data-modal="editEvacueeModal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editEvacueeForm">
                    <input type="hidden" id="edit_evacuee_id" name="evacuee_id">
                    <input type="hidden" id="edit_center_id" name="center_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Evacuee Name</label>
                        <input type="text" class="form-control" id="edit_evacuee_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_evacuee_status" name="evacuee_status" required>
                            <option value="Active">Active</option>
                            <option value="Departed">Departed</option>
                            <option value="Transferred">Transferred</option>
                            <option value="Missing">Missing</option>
                            <option value="Deceased">Deceased</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="departure_date_group" style="display: none;">
                        <label class="form-label">Departure Date</label>
                        <input type="date" class="form-control" id="edit_departure_date" name="departure_date">
                    </div>
                    
                    <div class="mb-3" id="transfer_center_group" style="display: none;">
                        <label class="form-label">Transfer To Center</label>
                        <select class="form-control" id="edit_transfer_center" name="transfer_center_id">
                            <option value="">Select Center</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" id="edit_remarks" name="remarks" rows="2" placeholder="Optional remarks"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-modal" data-modal="editEvacueeModal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateEvacuee">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Center Modal -->
<div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Evacuation Center</h5>
                <button type="button" class="close-modal-btn" data-modal="editCenterModal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCenterForm">
                    <input type="hidden" id="edit_center_id" name="center_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Center Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_center_name" name="center_name" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_category" name="category" required>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Tertiary">Tertiary</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Full">Full</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Complete Address</label>
                            <input type="text" class="form-control" id="edit_address" name="address">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="edit_barangay" name="barangay">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City / Municipality</label>
                            <input type="text" class="form-control" id="edit_city" name="city">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Province <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_province" name="province" required>
                                <option value="Negros Occidental">Negros Occidental</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estimated Capacity</label>
                            <input type="number" class="form-control" id="edit_estimated_capacity" name="estimated_capacity" min="0">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="contact_number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="edit_contact_person" name="contact_person">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="edit_latitude" name="latitude">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="edit_longitude" name="longitude">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-primary fw-bold">Current Occupants <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_current_occupants" name="current_occupants" min="0" required readonly>
                            <small class="text-muted">Current number of people in this center (calculated from active evacuees)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-primary fw-bold">Maximum Capacity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_capacity" name="capacity" min="0" required>
                            <small class="text-muted">Maximum number of people this center can hold</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Accessibility Features</label>
                            <input type="text" class="form-control" id="edit_accessibility" name="accessibility" placeholder="e.g., Wheelchair ramp, Braille signage">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Available Facilities</label>
                            <input type="text" class="form-control" id="edit_available_facilities" name="available_facilities" placeholder="e.g., Cots, Blankets, First aid">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Remarks / Notes</label>
                            <textarea class="form-control" id="edit_remarks" name="remarks" rows="3" placeholder="Additional notes about this evacuation center"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-modal" data-modal="editCenterModal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateCenter">Update Center</button>
            </div>
        </div>
    </div>
</div>

<!-- Assign LGU Modal -->
<div class="modal fade" id="assignLGMModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign LGU User to Center</h5>
                <button type="button" class="close-modal-btn" data-modal="assignLGMModal">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignLGUForm">
                    <input type="hidden" id="assign_center_id" name="center_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Evacuation Center:</label>
                        <input type="text" class="form-control" id="assign_center_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lgu_user_id" class="form-label">Select LGU User <span class="text-danger">*</span></label>
                        <select class="form-control" id="lgu_user_id" name="lgu_user_id" required>
                            <option value="">-- Select LGU User --</option>
                            <?php
                            require_once "models/centers.model.php";
                            $availableLGU = ModelCenters::mdlGetAvailableLGUUsers();
                            foreach ($availableLGU as $lgu) {
                                echo '<option value="' . $lgu['userid'] . '">' . 
                                     htmlspecialchars($lgu['first_name'] . ' ' . $lgu['last_name'] . ' - ' . $lgu['position_role']) . 
                                     '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        Assigned LGU users will be able to manage this evacuation center and view its reports.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-modal" data-modal="assignLGMModal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAssignLGU">Assign LGU User</button>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-history"></i> Center History - <span id="history_center_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Action</th>
                                <th>Changes Made</th>
                                <th>Remarks</th>
                                <th>Changed By</th>
                            </tr>
                        </thead>
                        <tbody id="history-tbody">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.close-modal-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #dc3545;
    cursor: pointer;
    padding: 0;
    margin: 0;
    line-height: 1;
    transition: all 0.2s ease;
}

.close-modal-btn:hover {
    color: #bb2d3b;
    transform: scale(1.1);
}

.close-modal-btn:focus {
    outline: none;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.center-row {
    transition: background-color 0.2s ease;
}

.center-row:hover {
    background-color: #f8f9fa;
}

.expand-icon i {
    transition: transform 0.2s ease;
    display: inline-block;
}

.details-row {
    background-color: #f8f9fa;
}

.evacuee-item {
    border-left: 3px solid #28a745;
    margin-bottom: 10px;
    padding: 10px;
    background: white;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.evacuee-item.departed,
.evacuee-item.inactive-status {
    border-left-color: #dc3545;
    background: #fff5f5;
}

.evacuee-item.transferred {
    border-left-color: #ffc107;
    background: #fffbeb;
}

.evacuee-item.missing {
    border-left-color: #6c757d;
    background: #f8f9fa;
}

.history-item {
    border-bottom: 1px solid #e9ecef;
    padding: 10px 0;
    font-size: 0.8rem;
}

.history-item:last-child {
    border-bottom: none;
}

.history-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 20px;
    font-weight: 500;
}

.history-badge.status-change {
    background-color: #17a2b8 !important;
    color: white !important;
}

.history-badge.evacuee-added {
    background-color: #28a745 !important;
    color: white !important;
}

.history-badge.evacuee-departed {
    background-color: #dc3545 !important;
    color: white !important;
}

.history-badge.evacuee-transferred {
    background-color: #ffc107 !important;
    color: #333 !important;
}

.history-badge.center-updated {
    background-color: #6c757d !important;
    color: white !important;
}

.status-history-item {
    padding: 5px 0;
    border-bottom: 1px dashed #dee2e6;
    font-size: 0.75rem;
}

.status-history-item:last-child {
    border-bottom: none;
}

.status-badge-active {
    background-color: #28a745;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
}

.status-badge-inactive {
    background-color: #6c757d;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
}

.status-badge-full {
    background-color: #ffc107;
    color: #333;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
}

.no-results {
    text-align: center;
    padding: 20px;
    color: #6c757d;
    font-style: italic;
}

.evacuee-search-input {
    border-radius: 4px 0 0 4px;
}

.btn-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var evacueeDataStore = {};
    
    function updateStatistics() {
        $.ajax({
            url: "ajax/get_statistics.ajax.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#currentlyOccupiedCount').text(response.currently_occupied);
                }
            }
        });
    }
    
    function closeModal(modalId) {
        $('#' + modalId).modal('hide');
        setTimeout(function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('overflow', '');
        }, 150);
    }
    
    $('.close-modal-btn, .cancel-modal').on('click', function() {
        var modalId = $(this).data('modal');
        closeModal(modalId);
    });
    
    $('.modal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
    
    $('.center-row').on('click', function(e) {
        if ($(e.target).closest('button, .btn, a').length) {
            return;
        }
        
        var centerId = $(this).data('center-id');
        var detailsRow = $('.details-row-' + centerId);
        var expandIcon = $(this).find('.expand-icon i');
        
        if (detailsRow.is(':visible')) {
            detailsRow.slideUp(300);
            expandIcon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
        } else {
            $('.details-row:visible').slideUp(300);
            $('.expand-icon i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
            
            detailsRow.slideDown(300);
            expandIcon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            
            if (detailsRow.find('.evacuee-list-container').data('loaded') !== true) {
                loadCenterDetails(centerId);
                detailsRow.find('.evacuee-list-container').data('loaded', true);
            }
        }
    });
    
    function loadCenterDetails(centerId) {
        loadEvacuees(centerId);
        loadHistory(centerId);
        loadStatusHistory(centerId);
    }
    
    function loadEvacuees(centerId) {
        $('#evacuee-list-' + centerId).html('<div class="text-muted text-center p-3">Loading evacuees...</div>');
        
        $.ajax({
            url: "ajax/get_center_evacuees.ajax.php",
            method: "POST",
            data: { center_id: centerId },
            dataType: "json",
            success: function(response) {
                if (response.success && response.evacuees) {
                    evacueeDataStore[centerId] = response.evacuees;
                    displayEvacuees(centerId, response.evacuees);
                    var activeCount = response.evacuees.filter(function(e) { return e.evacuee_status === 'Active'; }).length;
                    $('.occupancy-display-' + centerId).text(activeCount);
                    updateStatistics();
                } else {
                    $('#evacuee-list-' + centerId).html('<div class="text-muted text-center p-3">No evacuees found</div>');
                    evacueeDataStore[centerId] = [];
                    $('.occupancy-display-' + centerId).text('0');
                    updateStatistics();
                }
            },
            error: function() {
                $('#evacuee-list-' + centerId).html('<div class="text-danger text-center p-3">Error loading evacuees</div>');
            }
        });
    }
    
    function displayEvacuees(centerId, evacuees, searchTerm) {
        var html = '';
        var filteredEvacuees = evacuees;
        
        if (searchTerm && searchTerm.trim() !== '') {
            var term = searchTerm.toLowerCase();
            filteredEvacuees = evacuees.filter(function(evacuee) {
                return (evacuee.first_name && evacuee.first_name.toLowerCase().includes(term)) ||
                       (evacuee.last_name && evacuee.last_name.toLowerCase().includes(term)) ||
                       (evacuee.contact_number && evacuee.contact_number.toLowerCase().includes(term)) ||
                       (evacuee.evacuee_status && evacuee.evacuee_status.toLowerCase().includes(term));
            });
        }
        
        if (filteredEvacuees.length === 0) {
            if (searchTerm && searchTerm.trim() !== '') {
                html = '<div class="no-results">No matching evacuees found</div>';
            } else {
                html = '<div class="text-muted text-center p-3">No evacuees in this center</div>';
            }
        } else {
            filteredEvacuees.forEach(function(evacuee) {
                var statusClass = '';
                var statusColor = '';
                
                if (evacuee.evacuee_status === 'Active') {
                    statusClass = 'bg-success';
                    statusColor = 'active-status';
                } else if (evacuee.evacuee_status === 'Departed') {
                    statusClass = 'bg-secondary';
                    statusColor = 'inactive-status';
                } else if (evacuee.evacuee_status === 'Transferred') {
                    statusClass = 'bg-warning text-dark';
                    statusColor = 'transferred';
                } else if (evacuee.evacuee_status === 'Missing') {
                    statusClass = 'bg-dark';
                    statusColor = 'missing';
                } else if (evacuee.evacuee_status === 'Deceased') {
                    statusClass = 'bg-danger';
                    statusColor = 'inactive-status';
                } else {
                    statusClass = 'bg-secondary';
                    statusColor = 'inactive-status';
                }
                
                html += `
                    <div class="evacuee-item ${statusColor}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>${escapeHtml(evacuee.last_name)}, ${escapeHtml(evacuee.first_name)}</strong>
                                <div class="mt-1">
                                    <span class="badge ${statusClass}">${evacuee.evacuee_status}</span>
                                    <span class="text-muted ms-2">Arrival: ${evacuee.arrival_date || 'N/A'}</span>
                                </div>
                                <div class="mt-1 text-muted small">
                                    ${evacuee.sex || 'N/A'} | Age: ${evacuee.age || 'N/A'}
                                </div>
                                <div class="mt-1 small">
                                    Contact: ${evacuee.contact_number || 'N/A'}
                                    ${evacuee.condition_elderly || evacuee.condition_pwd || evacuee.condition_pregnant ? '<br><span class="text-warning">Special needs: ' + 
                                        (evacuee.condition_elderly ? 'Elderly ' : '') +
                                        (evacuee.condition_pwd ? 'PWD ' : '') +
                                        (evacuee.condition_pregnant ? 'Pregnant ' : '') +
                                        (evacuee.condition_lactating ? 'Lactating ' : '') + '</span>' : ''}
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary edit-evic-status" 
                                    data-evic-id="${evacuee.evacuee_id}"
                                    data-name="${escapeHtml(evacuee.first_name)} ${escapeHtml(evacuee.last_name)}"
                                    data-status="${evacuee.evacuee_status}"
                                    data-center-id="${centerId}">
                                Edit
                            </button>
                        </div>
                    </div>
                `;
            });
        }
        $('#evacuee-list-' + centerId).html(html);
    }
    
    $(document).on('input', '.evacuee-search-input', function() {
        var centerId = $(this).data('center-id');
        var searchTerm = $(this).val();
        var evacuees = evacueeDataStore[centerId] || [];
        displayEvacuees(centerId, evacuees, searchTerm);
    });
    
    $(document).on('click', '.clear-search', function() {
        var centerId = $(this).data('center-id');
        var searchInput = $('.evacuee-search-input[data-center-id="' + centerId + '"]');
        searchInput.val('');
        var evacuees = evacueeDataStore[centerId] || [];
        displayEvacuees(centerId, evacuees, '');
    });
    
    function loadHistory(centerId) {
        $('#history-list-' + centerId).html('<div class="text-muted text-center p-3">Loading history...</div>');
        
        $.ajax({
            url: "ajax/get_center_history.ajax.php",
            method: "POST",
            data: { center_id: centerId },
            dataType: "json",
            success: function(response) {
                if (response.success && response.history) {
                    displayHistory(centerId, response.history);
                } else {
                    $('#history-list-' + centerId).html('<div class="text-muted text-center p-3">No history records</div>');
                }
            },
            error: function() {
                $('#history-list-' + centerId).html('<div class="text-danger text-center p-3">Error loading history</div>');
            }
        });
    }
    
    function displayHistory(centerId, history) {
        var html = '';
        var uniqueHistory = [];
        var seenKeys = new Set();
        
        history.forEach(function(record) {
            var key = record.action_type + '_' + record.description + '_' + record.created_at;
            if (!seenKeys.has(key)) {
                seenKeys.add(key);
                uniqueHistory.push(record);
            }
        });
        
        if (uniqueHistory.length === 0) {
            html = '<div class="text-muted text-center p-3">No activity records found</div>';
        } else {
            uniqueHistory.forEach(function(record) {
                var badgeClass = '';
                var actionDisplay = record.action_type ? record.action_type.replace(/_/g, ' ') : 'Activity';
                
                if (record.action_type === 'EVACUEE_ADDED') {
                    badgeClass = 'evacuee-added';
                } else if (record.action_type === 'EVACUEE_STATUS_CHANGE') {
                    badgeClass = 'status-change';
                } else if (record.action_type === 'EVACUEE_DEPARTED') {
                    badgeClass = 'evacuee-departed';
                } else if (record.action_type === 'EVACUEE_TRANSFERRED') {
                    badgeClass = 'evacuee-transferred';
                } else if (record.action_type === 'CENTER_UPDATED') {
                    badgeClass = 'center-updated';
                } else {
                    badgeClass = 'bg-secondary';
                }
                
                html += `
                    <div class="history-item">
                        <div class="mb-1">
                            <span class="badge history-badge ${badgeClass}">${actionDisplay}</span>
                        </div>
                        <div class="mt-1 small">${record.description || 'No description'}</div>
                        <div class="mt-1">
                            <small class="text-muted">
                                By: ${record.performed_by || 'System'} | ${formatDate(record.created_at)}
                            </small>
                        </div>
                    </div>
                `;
            });
        }
        $('#history-list-' + centerId).html(html);
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        var date = new Date(dateString);
        return date.toLocaleString();
    }
    
    function loadStatusHistory(centerId) {
        $('#status-history-' + centerId).html('<div class="text-muted">Loading...</div>');
        
        $.ajax({
            url: "ajax/get_center_status_history.ajax.php",
            method: "POST",
            data: { center_id: centerId },
            dataType: "json",
            success: function(response) {
                if (response.success && response.history) {
                    displayStatusHistory(centerId, response.history);
                } else {
                    $('#status-history-' + centerId).html('<div class="text-muted">No status change history</div>');
                }
            },
            error: function() {
                $('#status-history-' + centerId).html('<div class="text-danger">Error loading status history</div>');
            }
        });
    }
    
    function displayStatusHistory(centerId, history) {
        var html = '';
        if (history.length === 0) {
            html = '<div class="text-muted">No status changes recorded</div>';
        } else {
            history.forEach(function(record) {
                var oldStatusClass = record.old_status === 'Active' ? 'status-badge-active' : 'status-badge-inactive';
                var newStatusClass = record.new_status === 'Active' ? 'status-badge-active' : (record.new_status === 'Full' ? 'status-badge-full' : 'status-badge-inactive');
                
                html += `
                    <div class="status-history-item">
                        <span class="badge ${oldStatusClass}">${record.old_status || 'Unknown'}</span>
                        <span> → </span>
                        <span class="badge ${newStatusClass}">${record.new_status}</span>
                        <br>
                        <small class="text-muted">
                            By: ${record.changed_by || 'System'} | ${record.changed_at ? formatDate(record.changed_at) : ''}
                        </small>
                    </div>
                `;
            });
        }
        $('#status-history-' + centerId).html(html);
    }
    
    $('.refresh-evacuees').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        loadEvacuees(centerId);
    });
    
    $('.refresh-history').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        loadHistory(centerId);
    });
    
    $(document).on('click', '.edit-evic-status', function(e) {
        e.stopPropagation();
        var evacueeId = $(this).data('evic-id');
        var name = $(this).data('name');
        var status = $(this).data('status');
        var centerId = $(this).data('center-id');
        
        $('#edit_evacuee_id').val(evacueeId);
        $('#edit_evacuee_name').val(name);
        $('#edit_evacuee_status').val(status);
        $('#edit_center_id').val(centerId);
        $('#edit_remarks').val('');
        
        if (status === 'Departed') {
            $('#departure_date_group').show();
            $('#edit_departure_date').val(new Date().toISOString().split('T')[0]);
        } else {
            $('#departure_date_group').hide();
        }
        
        if (status === 'Transferred') {
            $('#transfer_center_group').show();
            loadTransferCenters(centerId);
        } else {
            $('#transfer_center_group').hide();
        }
        
        $('#editEvacueeModal').modal('show');
    });
    
    function loadTransferCenters(currentCenterId) {
        $.ajax({
            url: "ajax/get_available_centers.ajax.php",
            method: "POST",
            data: { exclude_center_id: currentCenterId },
            dataType: "json",
            success: function(response) {
                if (response.success && response.centers) {
                    var options = '<option value="">Select Center</option>';
                    response.centers.forEach(function(center) {
                        options += `<option value="${center.center_id}">${escapeHtml(center.center_name)} (${center.current_occupants}/${center.capacity})</option>`;
                    });
                    $('#edit_transfer_center').html(options);
                }
            }
        });
    }
    
    $('#edit_evacuee_status').on('change', function() {
        var status = $(this).val();
        if (status === 'Departed') {
            $('#departure_date_group').slideDown();
            $('#edit_departure_date').val(new Date().toISOString().split('T')[0]);
            $('#transfer_center_group').slideUp();
        } else if (status === 'Transferred') {
            $('#transfer_center_group').slideDown();
            $('#departure_date_group').slideUp();
        } else {
            $('#departure_date_group').slideUp();
            $('#transfer_center_group').slideUp();
        }
    });
    
    $('#confirmUpdateEvacuee').on('click', function() {
        var evacueeId = $('#edit_evacuee_id').val();
        var newStatus = $('#edit_evacuee_status').val();
        var centerId = $('#edit_center_id').val();
        var departureDate = $('#edit_departure_date').val();
        var transferCenterId = $('#edit_transfer_center').val();
        var remarks = $('#edit_remarks').val();
        
        if (newStatus === 'Transferred' && !transferCenterId) {
            Swal.fire('Error', 'Please select a center to transfer to', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Update Evacuee Status?',
            text: 'Are you sure you want to change this evacuee\'s status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "ajax/update_evacuee_status.ajax.php",
                    method: "POST",
                    data: {
                        evacuee_id: evacueeId,
                        status: newStatus,
                        center_id: centerId,
                        departure_date: departureDate,
                        transfer_center_id: transferCenterId,
                        remarks: remarks
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                closeModal('editEvacueeModal');
                                loadEvacuees(centerId);
                                loadHistory(centerId);
                                updateStatistics();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update evacuee status', 'error');
                    }
                });
            }
        });
    });
    
    var today = new Date().toISOString().split('T')[0];
    $('#evac_registration_date').val(today);
    $('#evac_arrival_date').val(today);
    
    $('#evac_birth_date').on('change', function() {
        var birthDate = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age > 0 && age < 120) {
            $('#evac_age').val(age);
        } else {
            $('#evac_age').val('');
        }
    });
    
    $('.add-evacuee-dropdown').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        var currentOccupants = $(this).data('current-occupants');
        var capacity = $(this).data('capacity');
        
        var centerStatus = $('.status-badge-' + centerId).text().trim();
        if (centerStatus !== 'Active') {
            Swal.fire({
                title: 'Center Not Active',
                text: 'You can only add evacuees to Active centers. Please change the center status to Active first.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        $('#evac_center_id').val(centerId);
        $('#selectedCenterName').text(centerName);
        $('#selectedCenterOccupancy').text(currentOccupants);
        $('#selectedCenterCapacity').text(capacity);
        
        $('#addEvacueeForm')[0].reset();
        $('#evac_registration_date').val(today);
        $('#evac_arrival_date').val(today);
        $('#evac_center_id').val(centerId);
        $('#evac_evacuee_status').val('Active');
        
        $('#addEvacueeModal').modal('show');
    });
    
    $('.edit-center-dropdown').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        var category = $(this).data('category');
        var status = $(this).data('status');
        var barangay = $(this).data('barangay');
        var city = $(this).data('city');
        var province = $(this).data('province');
        var address = $(this).data('address');
        var capacity = $(this).data('capacity');
        var currentOccupants = $(this).data('current-occupants');
        var contactNumber = $(this).data('contact-number');
        var contactPerson = $(this).data('contact-person');
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        var estimatedCapacity = $(this).data('estimated-capacity');
        var accessibility = $(this).data('accessibility');
        var availableFacilities = $(this).data('available-facilities');
        var remarks = $(this).data('remarks');
        
        $('#edit_center_id').val(centerId);
        $('#edit_center_name').val(centerName);
        $('#edit_category').val(category);
        $('#edit_status').val(status);
        $('#edit_barangay').val(barangay || '');
        $('#edit_city').val(city || '');
        $('#edit_province').val(province || 'Negros Occidental');
        $('#edit_address').val(address || '');
        $('#edit_capacity').val(capacity);
        $('#edit_current_occupants').val(currentOccupants);
        $('#edit_contact_number').val(contactNumber || '');
        $('#edit_contact_person').val(contactPerson || '');
        $('#edit_latitude').val(latitude || '');
        $('#edit_longitude').val(longitude || '');
        $('#edit_estimated_capacity').val(estimatedCapacity || capacity);
        $('#edit_accessibility').val(accessibility || '');
        $('#edit_available_facilities').val(availableFacilities || '');
        $('#edit_remarks').val(remarks || '');
        
        $('#editCenterModal').modal('show');
    });
    
    $('.assign-lgu-dropdown, .assign-lgu-quick').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        
        $('#assign_center_id').val(centerId);
        $('#assign_center_name').val(centerName);
        $('#lgu_user_id').val('');
        
        $('#assignLGMModal').modal('show');
    });
    
    $('#confirmAddEvacuee').on('click', function() {
        var requiredFields = [
            { id: '#evac_last_name', label: 'Last Name' },
            { id: '#evac_first_name', label: 'First Name' },
            { id: '#evac_sex', label: 'Sex' }
        ];
        
        var errors = [];
        requiredFields.forEach(function(field) {
            if (!$(field.id).val()) {
                errors.push(field.label);
            }
        });
        
        if (errors.length > 0) {
            Swal.fire({
                title: 'Required Fields Missing',
                html: 'Please fill in: ' + errors.join(', '),
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        var currentOccupants = parseInt($('#selectedCenterOccupancy').text());
        var capacity = parseInt($('#selectedCenterCapacity').text());
        
        if (currentOccupants >= capacity) {
            Swal.fire({
                title: 'Center Full!',
                text: 'This evacuation center has reached its maximum capacity. Cannot add more evacuees.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        Swal.fire({
            title: 'Register Evacuee?',
            text: 'Are you sure you want to register this evacuee?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, register',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Registering...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                var formData = {
                    trans_type: 'New',
                    encodedby: $('#evac_encodedby').val(),
                    registration_date: $('#evac_registration_date').val(),
                    last_name: $('#evac_last_name').val(),
                    first_name: $('#evac_first_name').val(),
                    middle_name: $('#evac_middle_name').val(),
                    extension_name: $('#evac_extension_name').val(),
                    relation_to_head: $('#evac_relation_to_head').val(),
                    sex: $('#evac_sex').val(),
                    birth_date: $('#evac_birth_date').val(),
                    age: $('#evac_age').val(),
                    civil_status: $('#evac_civil_status').val(),
                    occupation: $('#evac_occupation').val(),
                    contact_number: $('#evac_contact_number').val(),
                    complete_address: $('#evac_complete_address').val(),
                    emergency_contact_person: $('#evac_emergency_contact_person').val(),
                    emergency_contact_number: $('#evac_emergency_contact_number').val(),
                    condition_pregnant: $('#evac_condition_pregnant').is(':checked') ? 1 : 0,
                    condition_lactating: $('#evac_condition_lactating').is(':checked') ? 1 : 0,
                    condition_elderly: $('#evac_condition_elderly').is(':checked') ? 1 : 0,
                    condition_pwd: $('#evac_condition_pwd').is(':checked') ? 1 : 0,
                    condition_4ps: $('#evac_condition_4ps').is(':checked') ? 1 : 0,
                    pwd_type: $('#evac_pwd_type').val(),
                    health_status: $('#evac_health_status').val(),
                    emergency_medical_condition: $('#evac_emergency_medical_condition').val(),
                    medications_taken: $('#evac_medications_taken').val(),
                    known_allergies: $('#evac_known_allergies').val(),
                    evacuation_center_id: $('#evac_center_id').val(),
                    arrival_date: $('#evac_arrival_date').val(),
                    departure_date: $('#evac_departure_date').val(),
                    evacuee_status: $('#evac_evacuee_status').val()
                };
                
                $.ajax({
                    url: "ajax/evacuees_save.ajax.php",
                    method: "POST",
                    data: formData,
                    dataType: "text",
                    success: function(response) {
                        if (response && response.trim() !== 'error' && response.trim() !== '') {
                            loadEvacuees($('#evac_center_id').val());
                            Swal.fire('Success!', 'Evacuee registered successfully!', 'success');
                            closeModal('addEvacueeModal');
                            updateStatistics();
                        } else {
                            Swal.fire('Error', 'Failed to register evacuee', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An error occurred', 'error');
                    }
                });
            }
        });
    });
    
    $('#confirmUpdateCenter').on('click', function() {
        var newStatus = $('#edit_status').val();
        var centerId = $('#edit_center_id').val();
        var oldStatus = $('.status-badge-' + centerId).text().trim();
        var currentOccupants = parseInt($('#edit_current_occupants').val());
        
        if (newStatus === 'Inactive' && oldStatus !== 'Inactive' && currentOccupants > 0) {
            Swal.fire({
                title: 'Warning: Center Has Active Evacuees!',
                html: `<p>This center currently has <strong>${currentOccupants}</strong> active evacuee(s).</p>
                       <p>Setting the center to INACTIVE will automatically change all active evacuees to DEPARTED status.</p>
                       <p>This action cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate center and remove evacuees',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateCenterWithEvacueeRemoval(centerId, newStatus);
                }
            });
            return;
        }
        
        var formData = {
            center_id: centerId,
            center_name: $('#edit_center_name').val(),
            category: $('#edit_category').val(),
            status: newStatus,
            address: $('#edit_address').val(),
            barangay: $('#edit_barangay').val(),
            city: $('#edit_city').val(),
            province: $('#edit_province').val(),
            capacity: $('#edit_capacity').val(),
            estimated_capacity: $('#edit_estimated_capacity').val(),
            current_occupants: currentOccupants,
            contact_number: $('#edit_contact_number').val(),
            contact_person: $('#edit_contact_person').val(),
            latitude: $('#edit_latitude').val(),
            longitude: $('#edit_longitude').val(),
            accessibility: $('#edit_accessibility').val(),
            available_facilities: $('#edit_available_facilities').val(),
            remarks: $('#edit_remarks').val()
        };
        
        var errors = [];
        if (!formData.center_name) errors.push('Center Name is required');
        if (!formData.category) errors.push('Category is required');
        if (!formData.status) errors.push('Status is required');
        if (!formData.province) errors.push('Province is required');
        if (!formData.capacity || formData.capacity < 0) errors.push('Valid Capacity is required');
        
        if (errors.length > 0) {
            Swal.fire({
                title: 'Validation Error',
                html: errors.join('<br>'),
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        Swal.fire({
            title: 'Update Center?',
            text: 'Are you sure you want to update this evacuation center?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: "ajax/update_center.ajax.php",
                    method: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', 'Center updated successfully', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to update center', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An error occurred while updating', 'error');
                    }
                });
            }
        });
    });
    
    function updateCenterWithEvacueeRemoval(centerId, newStatus) {
        Swal.fire({
            title: 'Processing...',
            text: 'Removing evacuees and updating center status',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: "ajax/deactivate_center_with_evacuees.ajax.php",
            method: "POST",
            data: {
                center_id: centerId,
                new_status: newStatus
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to process request', 'error');
            }
        });
    }
    
    $('#confirmAssignLGU').on('click', function() {
        var centerId = $('#assign_center_id').val();
        var lguUserId = $('#lgu_user_id').val();
        
        if (!lguUserId) {
            Swal.fire('Error', 'Please select an LGU user to assign', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Assign LGU User?',
            text: 'Are you sure you want to assign this LGU user to the center?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, assign',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Assigning...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: "ajax/assign_lgu_to_center.ajax.php",
                    method: "POST",
                    data: {
                        center_id: centerId,
                        lgu_user_id: lguUserId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to assign LGU user', 'error');
                    }
                });
            }
        });
    });
    
    $('.print-report').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        window.open('reports/center_report.php?center_id=' + centerId, '_blank');
    });
    
    function escapeHtml(text) {
        if (!text) return '';
        return text.toString()
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
});
</script>