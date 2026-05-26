$(function () {

    newAnnouncement();
    loadAnnouncementList(); // Add this to load announcements on page load

    function newAnnouncement() {
        $("#ann_type").val('');
        $("#title").val('');
        $("#ann_desc").val('');
        $("#trans_type").val('New');
        $("#announcement_id").val('');
        $("#announcementError").hide().text('');
        $("#ann_type").focus();
    }

    // Add this new function to load announcements
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
                            </tr>
                        `;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="6" class="text-center">No announcements found</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading announcements:", error);
                $(".table tbody").html('<tr><td colspan="6" class="text-center text-danger">Error loading announcements</td></tr>');
            }
        });
    }

    // Helper function to prevent XSS
    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Update saveAnnouncement to reload the table after saving
    function saveAnnouncement() {
        let formData = new FormData();
        formData.append("trans_type", $("#trans_type").val());
        formData.append("encodedby",  $("#encodedby").val());
        formData.append("ann_title",  $("#title").val());
        formData.append("ann_type",   $("#ann_type").val());
        formData.append("ann_desc",   $("#ann_desc").val());

        $.ajax({
            url:         "ajax/announcement_save.ajax.php",
            method:      "POST",
            data:        formData,
            cache:       false,
            contentType: false,
            processData: false,
            dataType:    "text",
            success: function (answer) {
                if (answer !== 'error' && answer !== 'existing') {
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

    // Keep your existing button click handler
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

        Swal.fire({
            title: 'Save Announcement?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
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

});