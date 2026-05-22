<?php
$summary = ModelCenters::mdlGetCenterSummary();
$allCenters = ModelCenters::mdlGetAllCenters();
?>

<div class="home-dashboard">
<div class="container-fluid flex-grow-1 container-p-y">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-primary fw-bold">Total Centers</p>
                        <h3 class="mb-0"><?php echo number_format($summary['total_centers']); ?></h3>
                    </div>
                    <div class="bg-primary text-white rounded-3 p-3">
                        <i class="fa fa-home"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-success fw-bold">Total Capacity</p>
                        <h3 class="mb-0"><?php echo number_format($summary['total_capacity']); ?></h3>
                    </div>
                    <div class="bg-success text-white rounded-3 p-3">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-warning fw-bold">Currently Occupied</p>
                        <h3 class="mb-0"><?php echo number_format($summary['currently_occupied']); ?></h3>
                    </div>
                    <div class="bg-warning text-dark rounded-3 p-3">
                        <i class="fa fa-bed"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-uppercase text-info fw-bold">Active Centers</p>
                        <h3 class="mb-0"><?php echo number_format($summary['active_centers']); ?></h3>
                    </div>
                    <div class="bg-info text-white rounded-3 p-3">
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
                    <a href="?route=active" class="btn btn-primary btn-sm">Refresh</a>
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
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No evacuation centers found.</td>
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

<!-- Edit Center Modal -->
<div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Evacuation Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    
                    <!-- IMPORTANT: Current Occupancy and Capacity fields -->
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateCenter">Update Center</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Edit button click - Populate modal with center data
    $('.edit-center').on('click', function() {
        // Get all data from button attributes
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
        
        // Populate modal fields
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
        
        // Show modal
        $('#editCenterModal').modal('show');
    });
    
    // Update center button click
    $('#confirmUpdateCenter').on('click', function() {
        // Collect form data
        var formData = {
            center_id: $('#edit_center_id').val(),
            center_name: $('#edit_center_name').val(),
            category: $('#edit_category').val(),
            status: $('#edit_status').val(),
            address: $('#edit_address').val(),
            barangay: $('#edit_barangay').val(),
            city: $('#edit_city').val(),
            province: $('#edit_province').val(),
            capacity: $('#edit_capacity').val(),
            estimated_capacity: $('#edit_estimated_capacity').val(),
            current_occupants: $('#edit_current_occupants').val(),
            contact_number: $('#edit_contact_number').val(),
            contact_person: $('#edit_contact_person').val(),
            latitude: $('#edit_latitude').val(),
            longitude: $('#edit_longitude').val(),
            accessibility: $('#edit_accessibility').val(),
            available_facilities: $('#edit_available_facilities').val(),
            remarks: $('#edit_remarks').val()
        };
        
        // Validate
        var errors = [];
        if (!formData.center_name) errors.push('Center Name is required');
        if (!formData.category) errors.push('Category is required');
        if (!formData.status) errors.push('Status is required');
        if (!formData.province) errors.push('Province is required');
        if (!formData.capacity || formData.capacity < 0) errors.push('Valid Capacity is required');
        if (!formData.current_occupants || formData.current_occupants < 0) errors.push('Valid Current Occupants is required');
        if (parseInt(formData.current_occupants) > parseInt(formData.capacity)) {
            errors.push('Current Occupants cannot exceed Maximum Capacity');
        }
        
        if (errors.length > 0) {
            Swal.fire({
                title: 'Validation Error',
                html: errors.join('<br>'),
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Confirm update
        Swal.fire({
            title: 'Update Center?',
            text: 'Are you sure you want to update this evacuation center?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Send update request
                $.ajax({
                    url: "ajax/update_center.ajax.php",
                    method: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Center updated successfully',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to update center', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update error:', error);
                        Swal.fire('Error', 'An error occurred while updating. Please try again.', 'error');
                    }
                });
            }
        });
    });
});
</script>