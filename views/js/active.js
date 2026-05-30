$(document).ready(function() {
    // Set default dates
    var today = new Date().toISOString().split('T')[0];
    $('#evac_registration_date').val(today);
    $('#evac_arrival_date').val(today);
    
    // Auto-calculate age from birth date
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
    
    // Close modal function
    function closeModal(modalId) {
        $('#' + modalId).modal('hide');
    }
    
    // Handle all close buttons (X icon and Cancel buttons)
    $('.close-modal-btn').on('click', function() {
        var modalId = $(this).data('modal');
        if (modalId) {
            closeModal(modalId);
        }
    });
    
    // Add Evacuee button click
    $('.add-evacuee').on('click', function() {
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        var currentOccupants = $(this).data('current-occupants');
        var capacity = $(this).data('capacity');
        
        $('#evac_center_id').val(centerId);
        $('#selectedCenterName').text(centerName);
        $('#selectedCenterOccupancy').text(currentOccupants);
        $('#selectedCenterCapacity').text(capacity);
        
        // Reset form fields
        $('#addEvacueeForm')[0].reset();
        $('#evac_registration_date').val(today);
        $('#evac_arrival_date').val(today);
        $('#evac_center_id').val(centerId);
        $('#evac_evacuee_status').val('Active');
        
        $('#addEvacueeModal').modal('show');
    });
    
    // Confirm Add Evacuee
    $('#confirmAddEvacuee').on('click', function() {
        // Validate required fields
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
        
        // Check if center has capacity
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
                
                // Collect form data
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
                
                // Send AJAX request
                $.ajax({
                    url: "ajax/evacuees_save.ajax.php",
                    method: "POST",
                    data: formData,
                    dataType: "text",
                    success: function(response) {
                        console.log("Response:", response);
                        
                        if (response && response.trim() !== 'error' && response.trim() !== '') {
                            // Now update the center's occupancy count
                            var newOccupants = currentOccupants + 1;
                            $.ajax({
                                url: "ajax/update_center_occupancy.ajax.php",
                                method: "POST",
                                data: {
                                    center_id: $('#evac_center_id').val(),
                                    current_occupants: newOccupants
                                },
                                dataType: "json",
                                success: function(updateResponse) {
                                    if (updateResponse.success) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Evacuee registered successfully and center occupancy updated!',
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Partial Success',
                                            text: 'Evacuee registered but occupancy update failed. Please refresh.',
                                            icon: 'warning',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("Update error:", error);
                                    Swal.fire({
                                        title: 'Partial Success',
                                        text: 'Evacuee registered but occupancy update may have failed. Please refresh.',
                                        icon: 'warning',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            });
                        } else {
                            Swal.fire('Error', 'Failed to register evacuee. Response: ' + response, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Response text:', xhr.responseText);
                        Swal.fire('Error', 'An error occurred: ' + error, 'error');
                    }
                });
            }
        });
    });
    
    // Edit button click - Populate modal with center data
    $('.edit-center').on('click', function() {
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
    
    // Update center button click
    $('#confirmUpdateCenter').on('click', function() {
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
    
    // Assign LGU button click
    $('.assign-lgu').on('click', function() {
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        
        $('#assign_center_id').val(centerId);
        $('#assign_center_name').val(centerName);
        $('#lgu_user_id').val('');
        
        $('#assignLGMModal').modal('show');
    });
    
    // Confirm Assign LGU
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
    
    // Print Report button click
    $('.print-report').on('click', function() {
        var centerId = $(this).data('center-id');
        window.open('reports/center_report.php?center_id=' + centerId, '_blank');
    });
});