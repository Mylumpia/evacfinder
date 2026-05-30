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
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Center Name</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Current Occupancy</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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
                                        
                                        // Get full center details for display
                                        $db = new Connection();
                                        $pdo = $db->connect();
                                        $stmt = $pdo->prepare("SELECT * FROM centers WHERE center_id = :center_id");
                                        $stmt->bindParam(":center_id", $center['center_id']);
                                        $stmt->execute();
                                        $fullCenter = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($center['center_name']); ?></td>
                                        <td><?php echo htmlspecialchars($center['category']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($center['barangay'] . ', ' . $center['city'] . ', ' . $center['province'])); ?></td>
                                        <td class="capacity-cell">
                                            <span class="capacity-display-<?php echo $center['center_id']; ?>"><?php echo number_format($center['capacity']); ?></span>
                                        </td>
                                        <td class="occupancy-cell">
                                            <span class="occupancy-display-<?php echo $center['center_id']; ?>"><?php echo number_format($center['current_occupants']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success add-evacuee me-1" 
                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>"
                                                    data-current-occupants="<?php echo $center['current_occupants']; ?>"
                                                    data-capacity="<?php echo $center['capacity']; ?>">
                                                <i class="fa fa-user-plus"></i> Add Evacuee
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary edit-center me-1" 
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
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info assign-lgu me-1" 
                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>"
                                                    data-center-name="<?php echo htmlspecialchars($center['center_name']); ?>">
                                                <i class="fa fa-user-md"></i> Assign LGU
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary print-report" 
                                                    data-center-id="<?php echo htmlspecialchars($center['center_id']); ?>">
                                                <i class="fa fa-print"></i> Print Report
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
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
                <button type="button" class="close-modal-btn" data-modal="addEvacueeModal" aria-label="Close" style="background: none; border: none; padding: 0; margin: 0;">
                    <i class="fa fa-times" style="font-size: 20px; color: #999;"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addEvacueeForm">
                    <input type="hidden" id="evac_center_id" name="evacuation_center_id">
                    <input type="hidden" id="evac_center_name_display" name="center_name_display">
                    <input type="hidden" id="evac_encodedby" name="encodedby" value="<?php echo $_SESSION['userid']; ?>">
                    
                    <!-- Center Info Display -->
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
                <button type="button" class="btn btn-secondary close-modal-btn" data-modal="addEvacueeModal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddEvacuee">Register Evacuee</button>
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
                <button type="button" class="close-modal-btn" data-modal="editCenterModal" aria-label="Close" style="background: none; border: none; padding: 0; margin: 0;">
                    <i class="fa fa-times" style="font-size: 20px; color: #999;"></i>
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
                            <input type="number" class="form-control" id="edit_current_occupants" name="current_occupants" min="0" required>
                            <small class="text-muted">Current number of people in this center</small>
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
                <button type="button" class="btn btn-secondary close-modal-btn" data-modal="editCenterModal">Cancel</button>
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
                <button type="button" class="close-modal-btn" data-modal="assignLGMModal" aria-label="Close" style="background: none; border: none; padding: 0; margin: 0;">
                    <i class="fa fa-times" style="font-size: 20px; color: #999;"></i>
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
                        <i class="fa fa-info-circle"></i> Assigned LGU users will be able to manage this evacuation center and view its reports.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal-btn" data-modal="assignLGMModal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAssignLGU">Assign LGU User</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="views/js/active.js"></script>