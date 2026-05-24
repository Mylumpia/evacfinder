$(function () {

    newAnnouncement();

    // ── NEW ──────────────────────────────────────────────────────────────────
    function newAnnouncement() {
        $("#ann_type").val('');
        $("#ann_desc").val('');
        $("#trans_type").val('New');
        $("#announcement_id").val('');
        $("#announcementError").hide().text('');
        $("#ann_type").focus();
    }

    // ── SAVE (form submit) ───────────────────────────────────────────────────
    $("#btn-save").click(function (e) {
        e.preventDefault();

        // --- Client-side validation ---
        let requiredFields = [
            { id: "#ann_type", label: "Type of Announcement" },
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
                      '<p>The following fields are required:</p>' +
                      '<ul>' +
                      emptyFields.map(f => `<li>${f}</li>`).join('') +
                      '</ul></div>',
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-primary' },
                buttonsStyling: false
            });
            return;
        }

        // --- Confirm before saving ---
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

    // ── AJAX SAVE ────────────────────────────────────────────────────────────
    function saveAnnouncement() {
        let formData = new FormData();
        formData.append("trans_type",       $("#trans_type").val());
        formData.append("encodedby",        $("#encodedby").val());
        formData.append("ann_type",         $("#ann_type").val());
        formData.append("ann_desc",         $("#ann_desc").val());

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
                            window.location = '?route=announcements';
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

});