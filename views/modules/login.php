<?php
// Start session to display error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = null;

// Handle login when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $login = new ControllerUserRights();
    $error = $login->ctrUserLogin();
}
?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<style>
/* Enhanced Login Page UI */
.authincation-content {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    transform: scale(1.1);
    margin: 0 auto;
}

.auth-form {
    padding: 2rem 2rem;
}

.auth-form h4 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem !important;
}

/* Form group styling */
.auth-form .form-group {
    margin-bottom: 1.25rem;
}

.auth-form .form-group label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    display: block;
}

/* Input wrapper for icons */
.input-icon-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon-wrapper i:first-child {
    position: absolute;
    left: 14px;
    color: #999;
    font-size: 1rem;
    z-index: 1;
}

.input-icon-wrapper input {
    width: 100%;
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    background-color: #fff;
}

.input-icon-wrapper input:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    outline: none;
    background-color: #ffffff;
}

/* Password toggle button - moved to the right */
#togglePassword {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 2;
    color: #999;
    font-size: 1rem;
}

#togglePassword:hover {
    color: #333;
}

/* Checkbox and Forgot Password row */
.form-row {
    margin: 1.25rem 0;
}

.custom-control-label {
    color: #555;
    font-size: 0.875rem;
    cursor: pointer;
}

.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #007bff;
    border-color: #007bff;
}

.auth-form a {
    color: #007bff;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.auth-form a:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Sign In button */
.auth-form .btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    font-weight: 500;
    font-size: 0.95rem;
    width: 100%;
    transition: all 0.2s ease;
    cursor: pointer;
}

.auth-form .btn-primary:hover {
    background-color: #0069d9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.auth-form .btn-primary:active {
    transform: translateY(0);
}

/* Sign Up link */
.new-account {
    text-align: center;
    padding-top: 1.25rem;
    margin-top: 0.5rem;
    border-top: 1px solid #e5e7eb;
}

.new-account p {
    color: #666;
    font-size: 0.875rem;
    margin: 0;
}

.new-account a {
    font-weight: 600;
    color: #007bff;
}

/* Alert styling */
.alert-danger {
    border-radius: 10px;
    border-left: 4px solid #dc3545;
    background-color: #fef2f2;
    color: #dc2626;
    max-width: 500px;
    margin: 1rem auto;
    text-align: center;
    padding: 0.75rem;
}
</style>

<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Sign In to EvacFinder</h4>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label><strong>Email</strong></label>
                                        <div class="input-icon-wrapper">
                                            <i class="fa fa-envelope"></i>
                                            <input type="email" name="loginEmail" class="form-control" placeholder="Enter your email" required>
                                        </div>
                                    </div>
                                    <div class="form-group" style="position: relative;">
                                        <label><strong>Password</strong></label>
                                        <div class="input-icon-wrapper">
                                            <i class="fa fa-lock"></i>
                                            <input type="password" name="loginPass" id="loginPass" class="form-control" placeholder="Enter your password" required style="padding-right: 40px;">
                                            <i class="fa fa-eye-slash" id="togglePassword"></i>
                                        </div>
                                    </div>

                                    <div class="form-row d-flex justify-content-between align-items-center">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_1">
                                                <label class="custom-control-label" for="basic_checkbox_1">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <a href="?route=forgot-password">Forgot Password?</a>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-primary">Sign In</button>
                                    </div>
                                </form>
                                
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a href="?route=registration">Sign up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>