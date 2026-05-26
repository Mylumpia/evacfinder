$(function () {

    newAnnouncement();
    loadAnnouncementList();

    function newAnnouncement() {
        $("#ann_type").val('');
        $("#title").val('');
        $("#ann_desc").val('');
        $("#trans_type").val('New');
        $("#announcement_id").val('');
        $("#announcementError").hide().text('');
        $("#ann_type").focus();
    }

    function loadAnnouncementList() {
        $.ajax({
            url: "ajax/get_announcements.ajax.php",
            method: "GET",
            dataType: "json",
            success: function(data) {
                let tbody = $(".table tbody");
                tbody.empty();
                
                if (data && data.length > 0) {
                    $.each(data, function(index, announcement) {
                        let row = `
                            <tr>
                                <td>${escapeHtml(announcement.announcement_id)}</td>
                                <td>${escapeHtml(announcement.ann_title)}</td>
                                <td><span class="badge bg-label-primary">${escapeHtml(announcement.ann_type)}</span></td>
                                <td>${escapeHtml(announcement.ann_desc.substring(0, 100))}${announcement.ann_desc.length > 100 ? '...' : ''}</td>
                                <td>${escapeHtml(announcement.encodedby)}</td>
                                <td>${escapeHtml(announcement.date_created)}</td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary btn-edit" 
                                            data-id="${escapeHtml(announcement.announcement_id)}"
                                            data-title="${escapeHtml(announcement.ann_title)}"
                                            data-type="${escapeHtml(announcement.ann_type)}"
                                            data-desc="${escapeHtml(announcement.ann_desc)}">
                                        <i class="ti tabler-edit"></i> Edit
                                    </button>
                                 </td>
                             </tr>
                        `;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="7" class="text-center">No announcements found</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading announcements:", error);
                $(".table tbody").html('<tr><td colspan="7" class="text-center text-danger">Error loading announcements</td></tr>');
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function saveAnnouncement() {
        let formData = new FormData();
        formData.append("trans_type", $("#trans_type").val());
        formData.append("encodedby",  $("#encodedby").val());
        formData.append("ann_title",  $("#title").val());
        formData.append("ann_type",   $("#ann_type").val());
        formData.append("ann_desc",   $("#ann_desc").val());
        
        // IMPORTANT: Add announcement_id for edit operation
        if ($("#trans_type").val() === "Edit") {
            formData.append("announcement_id", $("#announcement_id").val());
        }

        $.ajax({
            url:         "ajax/announcement_save.ajax.php",
            method:      "POST",
            data:        formData,
            cache:       false,
            contentType: false,
            processData: false,
            dataType:    "text",
            success: function (answer) {
                // Handle different responses
                if (answer === 'updated') {
                    Swal.fire({
                        title:             'Announcement Successfully Updated!',
                        icon:              'success',
                        confirmButtonText: 'Got it',
                        customClass:       { confirmButton: 'btn btn-success waves-effect waves-light' },
                        buttonsStyling:    false
                    }).then(function (result) {
                        if (result.value) {
                            newAnnouncement();           // Reset the form
                            loadAnnouncementList();      // Reload the table
                        }
                    });
                } else if (answer !== 'error' && answer !== 'existing') {
                    Swal.fire({
                        title:             'Announcement Successfully Saved!',
                        icon:              'success',
                        confirmButtonText: 'Got it',
                        customClass:       { confirmButton: 'btn btn-success waves-effect waves-light' },
                        buttonsStyling:    false
                    }).then(function (result) {
                        if (result.value) {
                            newAnnouncement();           // Reset the form
                            loadAnnouncementList();      // Reload the table
                        }
                    });
                } else if (answer === 'existing') {
                    Swal.fire({
                        title:             'Announcement already exists!',
                        icon:              'warning',
                        confirmButtonText: 'Got it',
                        customClass:       { confirmButton: 'btn btn-warning' },
                        buttonsStyling:    false
                    });
                } else {
                    Swal.fire({
                        title:             'Error saving announcement!',
                        icon:              'error',
                        confirmButtonText: 'Got it',
                        customClass:       { confirmButton: 'btn btn-danger' },
                        buttonsStyling:    false
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title:             'Oops. Something went wrong!',
                    icon:              'error',
                    confirmButtonText: 'Got it',
                    customClass:       { confirmButton: 'btn btn-danger waves-effect waves-light' },
                    buttonsStyling:    false
                });
            }
        });
    }

    $("#btn-save").click(function (e) {
        e.preventDefault();

        let requiredFields = [
            { id: "#ann_type", label: "Type of Announcement" },
            { id: "#title",    label: "Title"                },
            { id: "#ann_desc", label: "Description"          },
        ];

        let emptyFields = [];
        requiredFields.forEach(function (field) {
            let value = $(field.id).val();
            if (!value || value.trim() === '') {
                emptyFields.push(field.label);
            }
        });

        if (emptyFields.length > 0) {
            Swal.fire({
                title: 'Required Fields Missing',
                icon: 'warning',
                html: '<div style="text-align:left;margin-left:20px;">' +
                      '<p>The following fields are required:</p><ul>' +
                      emptyFields.map(f => `<li>${f}</li>`).join('') +
                      '</ul></div>',
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false
            });
            return;
        }

        let confirmTitle = ($("#trans_type").val() === "Edit") ? 'Update Announcement?' : 'Save Announcement?';
        let confirmText = ($("#trans_type").val() === "Edit") ? 'Yes, update it' : 'Yes';
        
        Swal.fire({
            title: confirmTitle,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: confirmText,
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton:  'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                saveAnnouncement();
            }
        });
    });

    $(document).on('click', '.btn-edit', function () {
        $("#announcement_id").val($(this).data('id'));
        $("#title").val($(this).data('title'));
        $("#ann_type").val($(this).data('type'));
        $("#ann_desc").val($(this).data('desc'));
        $("#trans_type").val('Edit');
        $('html, body').animate({ scrollTop: 0 }, 400);
        
        // Optional: Show a visual indicator that you're in edit mode
        $("#ann_type").focus();
    });

});