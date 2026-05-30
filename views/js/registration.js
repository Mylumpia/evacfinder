$(function(){
    const form = document.querySelector('.registration-form');
    const lguForm = document.querySelector('.registration-lgu-form');
    
    const accountTypeSelect = document.getElementById('accountType');
    const btnRegister = document.getElementById('btn-register');
    const btnNext = document.getElementById('btn-next');

    
    // Clear only on page refresh (not on first load)
    if (performance.navigation && performance.navigation.type === 1) {
        // This is a refresh
        if (form) {
            form.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.type !== 'submit' && field.type !== 'button' && field.type !== 'hidden') {
                    field.value = '';
                }
            });
        }
        if (lguForm) {
            lguForm.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.type !== 'submit' && field.type !== 'button' && field.type !== 'hidden') {
                    field.value = '';
                }
            });
        }
    }

    $("#btn-save").on('click', function () {
        const isLgu = !!lguForm;

        if (isLgu) {
            // Basic LGU form validation
            const lguRequired = ['lguOfficeName', 'lguOfficeEmail', 'lguPhone', 'lguPosition', 'lguDepartment', 'lguProvince', 'lguRegion'];
            let lguValid = true;
            lguRequired.forEach(function (id) {
                const field = document.getElementById(id);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    lguValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });
            if (!lguValid) return;

            // Removed showSuccess call
        } else {
            // Basic user form validation
            const userRequired = ['firstName', 'lastName', 'dateOfBirth', 'emailAddress', 'phoneNumber', 'region', 'password', 'confirmPassword'];
            let userValid = true;
            userRequired.forEach(function (id) {
                const field = document.getElementById(id);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    userValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });

            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                showError('Password and Confirm Password must match.');
                confirmPassword.classList.add('is-invalid');
                userValid = false;
            }

            if (!userValid) return;

            // Removed showSuccess call
        }
    });
    

    function hideError() {
        const errorContainer = document.getElementById('registrationError');
        if (errorContainer) {
            errorContainer.style.display = 'none';
        }
    }

    function showError(message) {
        const errorContainer = document.getElementById('registrationError');
        if (errorContainer) {
            errorContainer.style.display = 'block';
            errorContainer.textContent = message;
        }
    }

    // Removed showSuccess function entirely

    if (form) {
        form.addEventListener('submit', function (e) {
            const requiredFields = ['firstName', 'lastName', 'dateOfBirth', 'sex', 'emailAddress', 'phoneNumber', 'region', 'password', 'confirmPassword'];
            let isValid = true;
            let firstInvalid = null;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        if (!firstInvalid) {
                            firstInvalid = field;
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                }
            });

            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                isValid = false;
                showError('Password and Confirm Password must match.');
                confirmPassword.classList.add('is-invalid');
                if (!firstInvalid) {
                    firstInvalid = confirmPassword;
                }
            }

            if (!isValid) {
                e.preventDefault();
                if (!document.getElementById('registrationError').textContent) {
                    showError('Please fill in all required fields.');
                }
                if (firstInvalid) {
                    firstInvalid.focus();
                }
                return false;
            }
        });
    }
    
    $("#btn-back").click(function (e) {
        e.preventDefault();

        if (confirm('Are you sure you want to leave the page?')) {
            if (form) form.reset();
            
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            hideError();
            history.go(-1);
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
        e.preventDefault();
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
});