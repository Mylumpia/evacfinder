$(function(){
    const form = document.querySelector('.registration-form');
    
    const accountTypeSelect = document.getElementById('accountType');
    const btnRegister = document.getElementById('btn-register');
    const btnNext = document.getElementById('btn-next');
    

    function hideError() {
        const errorContainer = document.getElementById('registrationError');
        if (errorContainer) {
            errorContainer.style.display = 'none';
        }
    }
    
    $("#btn-back").click(function (e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to leave the page?')) {
            if (form) form.reset();
            
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            hideError();
            window.location.href = '?route=login';
        }
    });

    // Initial setup
    if (accountTypeSelect) {
        if (accountTypeSelect.value === 'lgu') {
            btnRegister.hidden = true;
            btnNext.hidden = false;
        } else {
            btnRegister.hidden = false;
            btnNext.hidden = true;
        }
        
        accountTypeSelect.addEventListener('change', function () {
            if (this.value === 'lgu') {
                btnRegister.hidden = true;
                btnNext.hidden = false;
            } else {
                btnRegister.hidden = false;
                btnNext.hidden = true;
            }
        });
    }

    $(".togglePassword").click(function(e){
        e.preventDefault(); // prevents accidental form submit
        const targetId = $(this).data('target');
        const input = $("#" + targetId);
        
        if(input.attr("type") === "password"){
            input.attr("type", "text");
            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
            input.attr("type", "password");
            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });

    // Next button handler for LGU registration
    $("#btn-next").click(function(e) {
        e.preventDefault();
        
        // Validate required fields
        // const requiredFields = ['firstName', 'lastName', 'dateOfBirth', 'sex', 'emailAddress', 'phoneNumber', 'region'];
        // let isValid = true;
        
        // requiredFields.forEach(fieldId => {
        //     const field = document.getElementById(fieldId);
        //     if (field && !field.value) {
        //         field.classList.add('is-invalid');
        //         isValid = false;
        //     } else if (field) {
        //         field.classList.remove('is-invalid');
        //     }
        // });
        
        // if (!isValid) {
        //     showError('Please fill in all required fields');
        //     return false;
        // }
        
        // Simple redirect - no form submission
        window.location.href = '?route=registration_lgu';
    });



});