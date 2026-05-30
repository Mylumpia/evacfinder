<?php
$summary = ModelCenters::mdlGetCenterSummary();
$allCenters = ModelCenters::mdlGetAllCenters();
?>

<style>
    .center-details-panel {
        background: #f8f9fa;
        border-top: 2px solid #1e3c72;
    }
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .clickable-row:hover {
        background-color: #f0f0f0 !important;
    }
    .expand-icon {
        display: inline-block;
        font-size: 12px;
        transition: transform 0.2s;
        color: #1e3c72;
    }
    .details-row td {
        padding: 0 !important;
    }
    .nav-tabs .nav-link {
        color: #1e3c72;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #1e3c72;
        font-weight: bold;
        border-bottom: 2px solid #1e3c72;
    }
    .info-table td {
        padding: 8px;
    }
    .evacuee-status-badge {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
    }
    .status-active { background: #d4edda; color: #155724; }
    .status-departed { background: #e2e3e5; color: #383d41; }
    .status-transferred { background: #d1ecf1; color: #0c5460; }
    .status-missing { background: #fff3cd; color: #856404; }
    .status-deceased { background: #f8d7da; color: #721c24; }
    .action-buttons-panel {
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .action-buttons-panel .btn {
        margin: 0;
    }
</style>

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
                        <h3 class="mb-0"><?php echo number_format($summary['currently_occupied']); ?></h3>
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

    <!-- Centers Table with Expandable Rows -->
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
                                    <?php foreach($allCenters as $index => $center): ?>
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
                                        
                                        // Get full center details
                                        $db = new Connection();
                                        $pdo = $db->connect();
                                        $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
                                        $stmt->bindParam(":center_id", $center['center_id']);
                                        $stmt->execute();
                                        $fullCenter = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <tr class="clickable-row" data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>">
                                        <td class="text-center">
                                            <span class="expand-icon" id="expand-icon-<?php echo htmlspecialchars($center['center_id']); ?>">▶</span>
                                        </td>
                                        <td><?php echo htmlspecialchars($center['center_name']); ?></td>
                                        <td><?php echo htmlspecialchars($center['category']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($center['barangay'] . ', ' . $center['city'] . ', ' . $center['province'])); ?></td>
                                        <td><?php echo number_format($center['capacity']); ?></td>
                                        <td><?php echo number_format($center['current_occupants']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-secondary print-report-btn-inline" title="Print Report"
                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>">
                                                <i class="fa fa-print"></i> Print Report
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="details-row" id="details-<?php echo htmlspecialchars($center['center_id']); ?>" style="display: none;">
                                        <td colspan="8" class="p-0">
                                            <div class="center-details-panel p-3">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul class="nav nav-tabs" id="tab-<?php echo htmlspecialchars($center['center_id']); ?>" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab" href="#info-<?php echo htmlspecialchars($center['center_id']); ?>" role="tab">Center Information</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#evacuees-<?php echo htmlspecialchars($center['center_id']); ?>" role="tab">Evacuees List</a>
                                                            </li>
                                                        </ul>
                                                        
                                                        <div class="tab-content mt-3">
                                                            <!-- Center Information Tab -->
                                                            <div class="tab-pane fade show active" id="info-<?php echo htmlspecialchars($center['center_id']); ?>" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <table class="table table-sm table-borderless info-table">
                                                                            <tr><td width="40%"><strong>Center Name:</strong></td>
                                                                            <td><?php echo htmlspecialchars($center['center_name']); ?></td>
                                                                        </tr>
                                                                        <tr><td><strong>Category:</strong></td>
                                                                            <td><?php echo htmlspecialchars($center['category']); ?></td>
                                                                        </tr>
                                                                        <tr><td><strong>Status:</strong></td>
                                                                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span></td>
                                                                        </tr>
                                                                        <tr><td><strong>Capacity:</strong></td>
                                                                            <td><?php echo number_format($center['capacity']); ?> persons</td>
                                                                        </tr>
                                                                        <tr><td><strong>Current Occupants:</strong></td>
                                                                            <td><?php echo number_format($center['current_occupants']); ?> persons</td>
                                                                        </tr>
                                                                        <tr><td><strong>Available Slots:</strong></td>
                                                                            <td><?php echo number_format($center['capacity'] - $center['current_occupants']); ?> slots</td>
                                                                        </tr>
                                                                    </table>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <table class="table table-sm table-borderless info-table">
                                                                            <tr><td width="40%"><strong>Address:</strong></td>
                                                                            <td><?php echo htmlspecialchars($fullCenter['address'] ?: $center['barangay'] . ', ' . $center['city'] . ', ' . $center['province']); ?></td>
                                                                        </tr>
                                                                        <tr><td><strong>Contact Person:</strong></td>
                                                                            <td><?php echo htmlspecialchars($fullCenter['contact_person'] ?: 'N/A'); ?></td>
                                                                        </tr>
                                                                        <tr><td><strong>Contact Number:</strong></td>
                                                                            <td><?php echo htmlspecialchars($fullCenter['contact_number'] ?: 'N/A'); ?></td>
                                                                        </tr>
                                                                        <tr><td><strong>Assigned LGU:</strong></td>
                                                                            <td><span id="assigned-lgu-<?php echo htmlspecialchars($center['center_id']); ?>">Loading...</span></td>
                                                                        </tr>
                                                                     </table>
                                                                    </div>
                                                                </div>
                                                                <?php if($fullCenter['remarks']): ?>
                                                                <div class="mt-2">
                                                                    <strong>Remarks:</strong>
                                                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($fullCenter['remarks'])); ?></p>
                                                                </div>
                                                                <?php endif; ?>
                                                                
                                                                <!-- Action Buttons moved inside dropdown -->
                                                                <div class="action-buttons-panel">
                                                                    <button type="button" class="btn btn-sm btn-success add-evacuee" 
                                                                            data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                            data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>"
                                                                            data-current-occupants="<?php echo $center['current_occupants']; ?>"
                                                                            data-capacity="<?php echo $center['capacity']; ?>">
                                                                        <i class="fa fa-user-plus"></i> Add Evacuee
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-primary edit-center" 
                                                                            data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                            data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>"
                                                                            data-category="<?php echo htmlspecialchars($center['category']); ?>"
                                                                            data-status="<?php echo htmlspecialchars($statusText); ?>"
                                                                            data-barangay="<?php echo htmlspecialchars($center['barangay']); ?>"
                                                                            data-city="<?php echo htmlspecialchars($center['city']); ?>"
                                                                            data-province="<?php echo htmlspecialchars($center['province']); ?>"
                                                                            data-address="<?php echo htmlspecialchars($fullCenter['address']); ?>"
                                                                            data-capacity="<?php echo $center['capacity']; ?>"
                                                                            data-current-occupants="<?php echo $center['current_occupants']; ?>"
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
                                                                    <button type="button" class="btn btn-sm btn-info assign-lgu" 
                                                                            data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                                            data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>">
                                                                        <i class="fa fa-user-md"></i> Assign LGU
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Evacuees List Tab -->
                                                            <div class="tab-pane fade" id="evacuees-<?php echo htmlspecialchars($center['center_id']); ?>" role="tabpanel">
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Evacuee ID</th>
                                                                                <th>Full Name</th>
                                                                                <th>Age</th>
                                                                                <th>Sex</th>
                                                                                <th>Status</th>
                                                                                <th>Arrival Date</th>
                                                                                <th>Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="evacuees-tbody-<?php echo htmlspecialchars($center['center_id']); ?>">
                                                                            <tr><td colspan="7" class="text-center">Click "Load Evacuees" to view</td>
                                                                        </tbody>
                                                                    </table>
                                                                    <button class="btn btn-sm btn-primary load-evacuees-btn mt-2" data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>">Load Evacuees</button>
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
                <h5 class="modal-title"><i class="fa fa-user-plus"></i> Register New Evacuee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addEvacueeForm">
                    <input type="hidden" id="evac_center_id" name="evacuation_center_id">
                    <input type="hidden" id="evac_encodedby" name="encodedby" value="<?php echo $_SESSION['userid']; ?>">
                    
                    <div class="alert alert-info mb-3">
                        <strong>Evacuation Center:</strong> <span id="selectedCenterName"></span><br>
                        <strong>Current Occupancy:</strong> <span id="selectedCenterOccupancy"></span> / <span id="selectedCenterCapacity"></span>
                    </div>
                    
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
                            <label class="form-label">Sex <span class="text-danger">*</span></label>
                            <select class="form-control" id="evac_sex" name="sex" required>
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" id="evac_age" name="age">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="evac_contact_number" name="contact_number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Arrival Date</label>
                            <input type="date" class="form-control" id="evac_arrival_date" name="arrival_date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <input type="hidden" id="evac_evacuee_status" name="evacuee_status" value="Active">
                    <input type="hidden" id="evac_registration_date" name="registration_date" value="<?php echo date('Y-m-d'); ?>">
                    <input type="hidden" id="evac_departure_date" name="departure_date" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddEvacuee"><i class="fa fa-save"></i> Register Evacuee</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Center Modal -->
<div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Evacuation Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                            <label class="form-label">Category</label>
                            <select class="form-control" id="edit_category" name="category">
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Tertiary">Tertiary</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Full">Full</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="edit_barangay" name="barangay">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="edit_city" name="city">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Province</label>
                            <input type="text" class="form-control" id="edit_province" name="province" value="Negros Occidental">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="edit_capacity" name="capacity">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Current Occupants</label>
                            <input type="number" class="form-control" id="edit_current_occupants" name="current_occupants">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="edit_contact_number" name="contact_number">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="edit_contact_person" name="contact_person">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateCenter"><i class="fa fa-save"></i> Update Center</button>
            </div>
        </div>
    </div>
</div>

<!-- Assign LGU Modal -->
<div class="modal fade" id="assignLGMModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-user-md"></i> Assign LGU User to Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignLGUForm">
                    <input type="hidden" id="assign_center_id" name="center_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Evacuation Center:</label>
                        <input type="text" class="form-control" id="assign_center_name" readonly style="background-color: #e9ecef;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Select LGU User <span class="text-danger">*</span></label>
                        <select class="form-control" id="lgu_user_id" name="lgu_user_id" required>
                            <option value="">-- Select LGU User --</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Assigned LGU users will be able to manage this evacuation center.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAssignLGU"><i class="fa fa-check"></i> Assign LGU User</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Evacuee Status Modal -->
<div class="modal fade" id="updateEvacueeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-exchange-alt"></i> Update Evacuee Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateEvacueeStatusForm">
                    <input type="hidden" id="update_evacuee_id" name="evacuee_id">
                    <input type="hidden" id="update_center_id" name="center_id">
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fa fa-user"></i> Evacuee Name:</label>
                        <input type="text" class="form-control" id="update_evacuee_name" readonly style="background-color: #e9ecef;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fa fa-tag"></i> New Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="update_status" name="evacuee_status" required>
                            <option value="Active">Active in Center</option>
                            <option value="Departed">Departed/Returned Home</option>
                            <option value="Transferred">Transferred to Another Center</option>
                            <option value="Missing">Missing</option>
                            <option value="Deceased">Deceased</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="transfer_center_div" style="display: none;">
                        <label class="form-label"><i class="fa fa-building"></i> Transfer to Center</label>
                        <select class="form-control" id="transfer_center_id" name="transfer_center_id">
                            <option value="">-- Select Center --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fa fa-sticky-note"></i> Remarks (Optional)</label>
                        <textarea class="form-control" id="status_remarks" name="remarks" rows="2" placeholder="Add any notes about this status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateStatus"><i class="fa fa-save"></i> Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="views/js/active.js"></script>