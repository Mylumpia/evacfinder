$ (function () {
    var date = $('#date_established');
    date.attr('placeholder', '  /  /  ');
    date.flatpickr({
      monthSelectorType: 'static',
      dateFormat: 'm/d/Y',
      static: true
    });

    newCenter();

    $("#btn-new").click(function(){
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
          if (result.value) {
            window.location = 'centers';
          }
        });
    }); 

    $("#btn-save").click(function () {
      let requiredFields = [
        {id: "#center_name", label: "Center Name"},
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
          customClass: {
            confirmButton: 'btn btn-primary'
          },
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
            if (result.value) {
                saveCenter();
            }
        });

    });



    function newCenter() {
        $("#center_name").val('');
        $("#category").val('').trigger('change');
        $("#status").val('').trigger('change');
        $("#barangay").val('');
        $("#city").val('');
        $("#province").val('');
        $("#address").val('');
        $("#capacity").val('');
        $("#max_persons").val('');
        $("#current_occupants").val('');
        $("#contact_number").val('');
        $("#contact_person").val('');
        $("#alternate_contact").val('');
        $("#date_established").val('');
        $("#facilities").val('').trigger('change');
        $("#hazard_type").val('').trigger('change');
        $("#remarks").val('');

        $("#center_name").focus();
    }

    function saveCenter() {
        let trans_type = $("#trans_type").val();
        let encodedby = $("#encodedby").val();
        let center_name = $("#center_name").val();
        let category = $("#category").val();
        let status = $("#status").val();
        let barangay = $("#barangay").val();
        let city = $("#city").val();
        let province = $("#province").val();
        let address = $("#address").val();
        let capacity = $("#capacity").val();
        let max_persons = $("#max_persons").val();
        let current_occupants = $("#current_occupants").val();
        let contact_number = $("#contact_number").val();
        let contact_person = $("#contact_person").val();
        let alternate_contact = $("#alternate_contact").val();

        let raw_date = $("#date_established").val();
        let date_established = '';
        if (raw_date !== "") {
            let parts = raw_date.split("/");
            date_established = parts[2] + "-" + parts [0] + "-" + parts[1];
        }

        let facilities = $("#facilities").val() || [];
        let hazard_type = $("#hazard_type").val() || [];
        let remarks = $("#remarks").val();


        let evacCenter = new FormData();
        evacCenter.append("trans_type", trans_type);
        evacCenter.append("encodedby", encodedby);
        evacCenter.append("center_name", center_name);
        evacCenter.append("category", category);
        evacCenter.append("status", status);
        evacCenter.append("barangay", barangay);
        evacCenter.append("city", city);
        evacCenter.append("province", province);
        evacCenter.append("address", address);
        evacCenter.append("capacity", capacity);
        evacCenter.append("max_persons", max_persons);
        evacCenter.append("current_occupants", current_occupants);
        evacCenter.append("contact_number", contact_number);
        evacCenter.append("contact_person", contact_person);
        evacCenter.append("alternate_contact", alternate_contact);
        evacCenter.append("date_established", date_established);
        
        facilities.forEach(f => evacCenter.append("facilities[]", f));
        hazard_type.forEach(h => evacCenter.append("hazard_type[]", h));
        evacCenter.append("remarks", remarks);

        $.ajax({
          url:"ajax/centers_save.ajax.php",
          method: "POST",
          data: evacCenter,
          cache: false,
          contentType: false,
          processData: false,
          dataType:"text",
          success:function(answer) {
            let center_id = answer;
            if (center_id != 'error' && center_id != 'existing') {
              Swal.fire({
                      title: 'New Evacuation Center Details Successfully Saved!',
                      icon: 'success',
                      confirmButtonText: 'Got it',
                      customClass: {
                        confirmButton: 'btn btn-success waves-effect waves-light'
                      },
                      buttonsStyling: false
                  }).then(function (result) {
                      if (result.value) {
                          window.location = 'centers';
                      }
                  });
            }
          },
          error: function () {
            Swal.fire({
              title: 'Oops. Something went wrong!',
              icon: 'error',
              confirmButtonText: 'Got it',
              customClass: {
              confirmButton: 'btn btn-danger waves-effect waves-light'
              },
              buttonsStyling: false
            });
          }

        });
    }
    
})