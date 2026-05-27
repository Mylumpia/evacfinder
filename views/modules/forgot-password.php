<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Reset Your Password</h4>
                                <p class="text-center text-muted mb-4">Enter your email and we will send you instructions to reset your password.</p>
                                <form method="POST" action="?route=login">
                                    <div class="form-group">
                                        <label><strong>Email</strong></label>
                                        <input type="email" name="resetEmail" class="form-control" placeholder="Enter your email" required>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" name="resetPassword" class="btn btn-outline-secondary btn-block">Send reset link</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3 text-center">
                                    <p>Remembered your password? <a class="text-primary" href="?route=login">Sign in</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
