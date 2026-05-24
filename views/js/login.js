$(document).ready(function(){
    $("#togglePassword").click(function(){
        const passwordInput = $("#loginPass");
        
        if(passwordInput.attr("type") === "password"){
            passwordInput.attr("type", "text");
            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        } else {
            passwordInput.attr("type", "password");
            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        }
    });
});