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

<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Sign in your account</h4>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label><strong>Email</strong></label>
                                        <input type="email" name="loginEmail" class="form-control" placeholder="Enter your email" required>
                                    </div>
                                    <div class="form-group" style="position: relative;">
    <label><strong>Password</strong></label>
    <input type="password" name="loginPass" id="loginPass" class="form-control" placeholder="Enter your password" required style="padding-right: 40px;">
    <i class="fa fa-eye-slash" id="togglePassword" style="position: absolute; right: 12px; top: 70%; transform: translateY(-50%); cursor: pointer; z-index: 10; color: #6c757d;"></i>
</div>

                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox ml-1">
                                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_1">
                                                <label class="custom-control-label" for="basic_checkbox_1">Remember my preference</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="page-forgot-password.html">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Sign me in</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="?route=registration">Sign up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>