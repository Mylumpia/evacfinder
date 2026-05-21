$(function () {
    const defaultLat = 10.4167;
    const defaultLng = 123.3833;
    const defaultZoom = 10;

    const map = L.map('centerMap').setView([defaultLat, defaultLng], defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    setTimeout(function () {
        map.invalidateSize();
    }, 200);

    let marker = null;

    function placeMarker(latlng) {
        if (marker) {
            marker.setLatLng(latlng);
        } else {
            marker = L.marker(latlng, { draggable: true }).addTo(map);
            marker.on('dragend', function (e) {
                updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng);
            });
        }
        updateCoords(latlng.lat, latlng.lng);
    }

    function updateCoords(lat, lng) {
        $("#latitude").val(lat.toFixed(6));
        $("#longitude").val(lng.toFixed(6));
        $("#mapOverlay").css('opacity', '0');
    }

    map.on('click', function (e) {
        placeMarker(e.latlng);
        map.setView(e.latlng, Math.max(map.getZoom(), 15));
    });

    newCenter();

    $("#btn-new").click(function () {
        Swal.fire({
            title: 'Enlist New Evacuation Center?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location = 'centers';
            }
        });
    });

    $("#btn-save").click(function () {
        let requiredFields = [
            { id: "#center_name", label: "Center Name" },
            { id: "#category",    label: "Evacuation Category" },
            { id: "#status",      label: "Status" },
            { id: "#province",    label: "Province" },
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

        Swal.fire({
            title: 'Save New Evacuation Center?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.isConfirmed) {
                saveCenter();
            }
        });
    });

    function newCenter() {
        $("#center_name").val('');
        $("#category").val('').trigger('change');
        $("#status").val('').trigger('change');
        $("#address").val('');
        $("#barangay").val('');
        $("#city").val('');
        $("#province").val('');
        $("#latitude").val('');
        $("#longitude").val('');
        $("#estimated_capacity").val('');
        $("#contact_number").val('');
        $("#contact_person").val('');
        $("#accessibility").val('');
        $("#available_facilities").val('');
        $("#remarks").val('');

        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
        map.setView([defaultLat, defaultLng], defaultZoom);
        $("#mapOverlay").css('opacity', '1');

        $("#center_name").focus();
    }

    function saveCenter() {
        let evacCenter = new FormData();
        evacCenter.append("trans_type",          $("#trans_type").val());
        evacCenter.append("encodedby",           $("#encodedby").val());
        evacCenter.append("center_name",         $("#center_name").val());
        evacCenter.append("category",            $("#category").val());
        evacCenter.append("status",              $("#status").val());
        evacCenter.append("address",             $("#address").val());
        evacCenter.append("barangay",            $("#barangay").val());
        evacCenter.append("city",                $("#city").val());
        evacCenter.append("province",            $("#province").val());
        evacCenter.append("latitude",            $("#latitude").val());
        evacCenter.append("longitude",           $("#longitude").val());
        evacCenter.append("estimated_capacity",  $("#estimated_capacity").val());
        evacCenter.append("contact_number",      $("#contact_number").val());
        evacCenter.append("contact_person",      $("#contact_person").val());
        evacCenter.append("available_facilities",$("#available_facilities").val());
        evacCenter.append("remarks",             $("#remarks").val());
        
        evacCenter.append("capacity",            $("#estimated_capacity").val() || 0);
        evacCenter.append("max_persons",         $("#estimated_capacity").val() || 0);
        evacCenter.append("current_occupants",   0);
        evacCenter.append("alternate_contact",   "");
        evacCenter.append("facilities",          $("#available_facilities").val() || "");
        evacCenter.append("hazard_type",         "");
        evacCenter.append("date_established",    "");
        evacCenter.append("accessibility",       $("#accessibility").val() || "");

        $.ajax({
            url: "ajax/centers_save.ajax.php",
            method: "POST",
            data: evacCenter,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "text",
            success: function (answer) {
                if (answer != 'error' && answer != 'existing') {
                    Swal.fire({
                        title: 'New Evacuation Center Details Successfully Saved!',
                        icon: 'success',
                        confirmButtonText: 'Got it',
                        customClass: { confirmButton: 'btn btn-success waves-effect waves-light' },
                        buttonsStyling: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            window.location = '?route=active';
                        }
                    });
                } else if (answer == 'existing') {
                    Swal.fire({
                        title: 'Center already exists!',
                        icon: 'warning',
                        confirmButtonText: 'Got it',
                        customClass: { confirmButton: 'btn btn-warning' },
                        buttonsStyling: false
                    });
                } else {
                    Swal.fire({
                        title: 'Error saving center!',
                        icon: 'error',
                        confirmButtonText: 'Got it',
                        customClass: { confirmButton: 'btn btn-danger' },
                        buttonsStyling: false
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Oops. Something went wrong!',
                    icon: 'error',
                    confirmButtonText: 'Got it',
                    customClass: { confirmButton: 'btn btn-danger waves-effect waves-light' },
                    buttonsStyling: false
                });
            }
        });
    }
});