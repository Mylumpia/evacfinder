$(document).ready(function() {
    // Set default date
    var today = new Date().toISOString().split('T')[0];
    $('#evac_registration_date').val(today);
    $('#evac_arrival_date').val(today);
    
    // Toggle details row when clicking on the entire row
    function toggleCenterDetails(centerId) {
        var $detailsRow = $('#details-' + centerId);
        var $icon = $('#expand-icon-' + centerId);
        
        if ($detailsRow.is(':visible')) {
            $detailsRow.slideUp(200);
            $icon.text('▶');
        } else {
            // Close other open rows
            $('.details-row:visible').each(function() {
                var otherId = $(this).attr('id').replace('details-', '');
                $('#details-' + otherId).slideUp(200);
                $('#expand-icon-' + otherId).text('▶');
            });
            
            $detailsRow.slideDown(200);
            $icon.text('▼');
        }
    }
    
    // Click on the entire row
    $('.clickable-row').off('click').on('click', function(e) {
        var centerId = $(this).data('center-id');
        toggleCenterDetails(centerId);
    });
    
    // Function to close any modal
    function closeModal(modalId) {
        $('#' + modalId).modal('hide');
        // Remove backdrop if stuck
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }
    
    // Handle ALL close buttons (X icon and Cancel buttons) for ALL modals
    $(document).on('click', '.close-modal-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var modalId = $(this).data('modal');
        if (modalId) {
            closeModal(modalId);
        } else {
            // If no data-modal, find the closest modal
            var $modal = $(this).closest('.modal');
            if ($modal.length) {
                closeModal($modal.attr('id'));
            }
        }
    });
    
    // Also handle any element with data-bs-dismiss (Bootstrap 4 standard)
    $(document).on('click', '[data-dismiss="modal"]', function(e) {
        e.preventDefault();
        var $modal = $(this).closest('.modal');
        if ($modal.length) {
            closeModal($modal.attr('id'));
        }
    });
    
    // Load evacuees button click
    $('.load-evacuees-btn').off('click').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        loadEvacueesForCenter(centerId);
    });
    
    // Function to load evacuees
    function loadEvacueesForCenter(centerId) {
        var tbody = $('#evacuees-tbody-' + centerId);
        tbody.html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        $.ajax({
            url: "ajax/get_center_evacuees.ajax.php",
            method: "POST",
            data: { center_id: centerId },
            dataType: "json",
            success: function(response) {
                if (response.success && response.evacuees.length > 0) {
                    var html = '';
                    $.each(response.evacuees, function(index, evacuee) {
                        var statusClass = '';
                        var statusIcon = '';
                        if (evacuee.evacuee_status === 'Active') {
                            statusClass = 'status-active';
                            statusIcon = '🟢';
                        } else if (evacuee.evacuee_status === 'Departed') {
                            statusClass = 'status-departed';
                            statusIcon = '🏠';
                        } else if (evacuee.evacuee_status === 'Transferred') {
                            statusClass = 'status-transferred';
                            statusIcon = '🔄';
                        } else if (evacuee.evacuee_status === 'Missing') {
                            statusClass = 'status-missing';
                            statusIcon = '❓';
                        } else if (evacuee.evacuee_status === 'Deceased') {
                            statusClass = 'status-deceased';
                            statusIcon = '⚰️';
                        }
                        
                        html += '<tr>' +
                            '<td>' + (evacuee.evacuee_id || 'N/A') + '</td>' +
                            '<td>' + evacuee.last_name + ', ' + evacuee.first_name + ' ' + (evacuee.middle_name ? evacuee.middle_name.charAt(0) + '.' : '') + '</td>' +
                            '<td>' + (evacuee.age || 'N/A') + '</td>' +
                            '<td>' + (evacuee.sex || 'N/A') + '</td>' +
                            '<td><span class="evacuee-status-badge ' + statusClass + '">' + statusIcon + ' ' + evacuee.evacuee_status + '</span></td>' +
                            '<td>' + (evacuee.arrival_date ? new Date(evacuee.arrival_date).toLocaleDateString() : 'N/A') + '</td>' +
                            '<td><button class="btn btn-sm btn-warning edit-evacuee-status" data-evacuee-id="' + evacuee.evacuee_id + '" data-evacuee-name="' + evacuee.last_name + ', ' + evacuee.first_name + '" data-current-status="' + evacuee.evacuee_status + '" data-center-id="' + centerId + '"><i class="fa fa-exchange-alt"></i> Update Status</button></td>' +
                            '</tr>';
                    });
                    tbody.html(html);
                } else {
                    tbody.html('<tr><td colspan="7" class="text-center">No evacuees found in this center</td></tr>');
                }
            },
            error: function() {
                tbody.html('<tr><td colspan="7" class="text-center text-danger">Error loading evacuees</td></tr>');
            }
        });
    }
    
    // Update evacuee status button click (delegated)
    $(document).on('click', '.edit-evacuee-status', function(e) {
        e.stopPropagation();
        var evacueeId = $(this).data('evacuee-id');
        var evacueeName = $(this).data('evacuee-name');
        var currentStatus = $(this).data('current-status');
        var centerId = $(this).data('center-id');
        
        $('#update_evacuee_id').val(evacueeId);
        $('#update_evacuee_name').val(evacueeName);
        $('#update_center_id').val(centerId);
        $('#update_status').val(currentStatus);
        $('#transfer_center_div').hide();
        $('#status_remarks').val('');
        
        $('#updateEvacueeStatusModal').modal('show');
    });
    
    // Show/hide transfer center field based on status
    $('#update_status').off('change').on('change', function() {
        if ($(this).val() === 'Transferred') {
            $('#transfer_center_div').show();
            // Load centers for transfer
            $.ajax({
                url: "ajax/get_centers.ajax.php",
                method: "POST",
                data: { action: "get_centers" },
                dataType: "json",
                success: function(centers) {
                    var options = '<option value="">-- Select Center --</option>';
                    var currentCenterId = $('#update_center_id').val();
                    $.each(centers, function(i, center) {
                        if (center.center_id != currentCenterId) {
                            options += '<option value="' + center.center_id + '">' + center.center_name + '</option>';
                        }
                    });
                    $('#transfer_center_id').html(options);
                }
            });
        } else {
            $('#transfer_center_div').hide();
        }
    });
    
    // Confirm update status
    $('#confirmUpdateStatus').off('click').on('click', function() {
        var evacueeId = $('#update_evacuee_id').val();
        var newStatus = $('#update_status').val();
        var transferCenterId = $('#transfer_center_id').val();
        var centerId = $('#update_center_id').val();
        var remarks = $('#status_remarks').val();
        
        if (newStatus === 'Transferred' && !transferCenterId) {
            Swal.fire('Error', 'Please select a center to transfer to', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Update Status?',
            text: 'Are you sure you want to update this evacuee\'s status?',
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
                    url: "ajax/update_evacuee_status.ajax.php",
                    method: "POST",
                    data: {
                        evacuee_id: evacueeId,
                        evacuee_status: newStatus,
                        transfer_center_id: transferCenterId,
                        center_id: centerId,
                        remarks: remarks
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                closeModal('updateEvacueeStatusModal');
                                loadEvacueesForCenter(centerId);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
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
    
    // Add Evacuee button click (inside dropdown)
    $(document).on('click', '.add-evacuee', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        var currentOccupants = $(this).data('current-occupants');
        var capacity = $(this).data('capacity');
        
        $('#evac_center_id').val(centerId);
        $('#selectedCenterName').text(centerName);
        $('#selectedCenterOccupancy').text(currentOccupants);
        $('#selectedCenterCapacity').text(capacity);
        
        $('#addEvacueeForm')[0].reset();
        $('#evac_registration_date').val(today);
        $('#evac_arrival_date').val(today);
        $('#evac_center_id').val(centerId);
        $('#evac_sex').val('');
        
        $('#addEvacueeModal').modal('show');
    });
    
    // Confirm Add Evacuee
    $('#confirmAddEvacuee').off('click').on('click', function() {
        var last_name = $('#evac_last_name').val();
        var first_name = $('#evac_first_name').val();
        var sex = $('#evac_sex').val();
        
        if (!last_name || !first_name || !sex) {
            Swal.fire('Error', 'Please fill in all required fields', 'warning');
            return;
        }
        
        var formData = {
            trans_type: 'New',
            encodedby: $('#evac_encodedby').val(),
            registration_date: $('#evac_registration_date').val(),
            last_name: last_name,
            first_name: first_name,
            middle_name: $('#evac_middle_name').val(),
            sex: sex,
            age: $('#evac_age').val(),
            contact_number: $('#evac_contact_number').val(),
            evacuation_center_id: $('#evac_center_id').val(),
            arrival_date: $('#evac_arrival_date').val(),
            evacuee_status: 'Active'
        };
        
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
                
                $.ajax({
                    url: "ajax/evacuees_save.ajax.php",
                    method: "POST",
                    data: formData,
                    dataType: "text",
                    success: function(response) {
                        if (response && response.trim() !== 'error') {
                            var centerId = $('#evac_center_id').val();
                            var currentOccupants = parseInt($('#selectedCenterOccupancy').text());
                            var newOccupants = currentOccupants + 1;
                            
                            $.ajax({
                                url: "ajax/update_center_occupancy.ajax.php",
                                method: "POST",
                                data: { center_id: centerId, current_occupants: newOccupants },
                                dataType: "json",
                                success: function() {
                                    Swal.fire('Success!', 'Evacuee registered successfully', 'success').then(() => {
                                        closeModal('addEvacueeModal');
                                        location.reload();
                                    });
                                },
                                error: function() {
                                    Swal.fire('Success', 'Evacuee registered but occupancy may need refresh', 'success').then(() => {
                                        closeModal('addEvacueeModal');
                                        location.reload();
                                    });
                                }
                            });
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
    
    // Edit center button click (inside dropdown)
    $(document).on('click', '.edit-center', function(e) {
        e.stopPropagation();
        $('#edit_center_id').val($(this).data('center-id'));
        $('#edit_center_name').val($(this).data('center-name'));
        $('#edit_category').val($(this).data('category'));
        $('#edit_status').val($(this).data('status'));
        $('#edit_barangay').val($(this).data('barangay') || '');
        $('#edit_city').val($(this).data('city') || '');
        $('#edit_province').val($(this).data('province') || 'Negros Occidental');
        $('#edit_capacity').val($(this).data('capacity'));
        $('#edit_current_occupants').val($(this).data('current-occupants'));
        $('#edit_contact_number').val($(this).data('contact-number') || '');
        $('#edit_contact_person').val($(this).data('contact-person') || '');
        
        $('#editCenterModal').modal('show');
    });
    
    // Update center button click
    $('#confirmUpdateCenter').off('click').on('click', function() {
        var formData = {
            center_id: $('#edit_center_id').val(),
            center_name: $('#edit_center_name').val(),
            category: $('#edit_category').val(),
            status: $('#edit_status').val(),
            barangay: $('#edit_barangay').val(),
            city: $('#edit_city').val(),
            province: $('#edit_province').val(),
            capacity: $('#edit_capacity').val(),
            current_occupants: $('#edit_current_occupants').val(),
            contact_number: $('#edit_contact_number').val(),
            contact_person: $('#edit_contact_person').val()
        };
        
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
                                closeModal('editCenterModal');
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Failed to update center', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An error occurred', 'error');
                    }
                });
            }
        });
    });
    
    // Assign LGU button click (inside dropdown)
    $(document).on('click', '.assign-lgu', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        var centerName = $(this).data('center-name');
        
        $('#assign_center_id').val(centerId);
        $('#assign_center_name').val(centerName);
        
        // Load available LGU users
        $.ajax({
            url: "ajax/get_available_lgu.ajax.php",
            method: "GET",
            dataType: "json",
            success: function(users) {
                var options = '<option value="">-- Select LGU User --</option>';
                $.each(users, function(i, user) {
                    options += '<option value="' + user.userid + '">' + user.first_name + ' ' + user.last_name + ' - ' + user.position_role + '</option>';
                });
                $('#lgu_user_id').html(options);
            }
        });
        
        $('#assignLGMModal').modal('show');
    });
    
    // Confirm Assign LGU
    $('#confirmAssignLGU').off('click').on('click', function() {
        var centerId = $('#assign_center_id').val();
        var lguUserId = $('#lgu_user_id').val();
        
        if (!lguUserId) {
            Swal.fire('Error', 'Please select an LGU user', 'warning');
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
                    data: { center_id: centerId, lgu_user_id: lguUserId },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => {
                                closeModal('assignLGMModal');
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
    
    // Print Report button click (inline button)
    $('.print-report-btn-inline').off('click').on('click', function(e) {
        e.stopPropagation();
        var centerId = $(this).data('center-id');
        window.open('reports/center_report.php?center_id=' + centerId, '_blank');
    });
    
    // Load assigned LGU info for each center
    $('.center-row').each(function() {
        var centerId = $(this).data('center-id');
        $.ajax({
            url: "ajax/get_center_details.ajax.php",
            method: "POST",
            data: { center_id: centerId },
            dataType: "json",
            success: function(response) {
                if (response && !response.error) {
                    var assignedLgu = response.assigned_lgu_name || 'Not Assigned';
                    $('#assigned-lgu-' + centerId).text(assignedLgu);
                }
            }
        });
    });
});