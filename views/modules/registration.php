<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$registrationData = $_SESSION['lgu_registration'] ?? [];
$registrationError = null;
$registrationSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_next'])) {
    $_SESSION['lgu_registration'] = [
        'firstName' => trim($_POST['firstName'] ?? ''),
        'lastName' => trim($_POST['lastName'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'accountType' => $_POST['accountType'] ?? 'lgu'
    ];
    header('Location: ?route=registration_lgu');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_register_submit'])) {
    $registrationResult = ControllerUserRights::ctrUserRegister();
    if ($registrationResult === true) {
        unset($_SESSION['lgu_registration']);
        $registrationSuccess = true;
    } else {
        $registrationError = $registrationResult;
    }
}
?>
<style>
    .content-body {
        min-height: unset !important;
    }
</style>

<div class="container-fluid">
    <form class="registration-form" method="POST" action="" autocomplete="nope">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <!-- Blue Header Banner -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="bg-primary text-white p-4 rounded shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2b4c7c 100%);">
                                <h2 class="mb-1 fw-bold" style="color: #ffffff;">
                                    <i class="ti tabler-user-plus me-2"></i>USER REGISTRATION
                                </h2>                                
                            </div>
                        </div>
                    </div>

                        <input type="hidden" name="trans_type" id="trans_type" value="New">
                        <input type="hidden" name="registrant_id" id="registrant_id" value="">
                        <input type="hidden" name="registrationStep" id="registrationStep" value="step1">
                   

                    <div class="card-body">
                        <?php if ($registrationError): ?>
                            <div class="alert alert-danger"><?php echo $registrationError; ?></div>
                        <?php endif; ?>
                        <?php if ($registrationSuccess): ?>
                            <script>
                                window.location.href = '?route=login';
                            </script>
                        <?php endif; ?>
                        <!-- Row 1: Name Fields -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label" for="firstName">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="firstName" name="firstName" class="form-control" placeholder="First name" required value="<?php echo htmlspecialchars($registrationData['firstName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="lastName">Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last name" required value="<?php echo htmlspecialchars($registrationData['lastName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="middleInitial">Middle Initial</label>
                                <input type="text" id="middleInitial" name="middleInitial" class="form-control" placeholder="M.I." maxlength="1" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="extension">Extension</label>
                                <input type="text" id="extension" name="extension" class="form-control" placeholder="Extension" maxlength="3" />
                            </div>
                        </div>

                        <!-- Row 2: DOB and Sex (better use of space) -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <label class="form-label" for="dateOfBirth">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                                <br>
                                <select id="sex" name="sex" class="form-select" required>
                                    <option value="" disabled selected>Select sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 3: Contact Info -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="emailAddress">Email Address <span class="text-danger">*</span></label>
                                <input type="email" id="emailAddress" name="email" class="form-control" placeholder="example@gmail.com" required value="<?php echo htmlspecialchars($registrationData['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phoneNumber">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="09** *** ****" required value="<?php echo htmlspecialchars($registrationData['phoneNumber'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>

                        <!-- Row 4: Region & Account Type -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="region" class="form-label">Region <span class="text-danger">*</span></label>
                                <br>
                                <select id="region" name="region" class="select2 form-select" required>
                                    <option value="">- select region -</option>
                                    <option value="negros-occidental-region6">Negros Occidental → Region VI</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="accountType" class="form-label">Account Type</label>
                                <br>
                                <select id="accountType" name="accountType" class="select2 form-select">
                                    <option value="public" <?php echo (isset($registrationData['accountType']) && $registrationData['accountType'] === 'public') ? 'selected' : ''; ?>>Public User</option>
                                    <option value="lgu" <?php echo (isset($registrationData['accountType']) && $registrationData['accountType'] === 'lgu') ? 'selected' : ''; ?>>Local Government Unit User</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 5: Password Fields -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6" style="position: relative;">
                                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required style="padding-right: 40px;">
                                <i class="fa fa-eye-slash togglePassword" data-target="password" style="position: absolute; right: 24px; top: 70%; transform: translateY(-50%); cursor: pointer; z-index: 10; color: #6c757d;"></i>
                            </div>
                            <div class="col-md-6" style="position: relative;">
                                <label class="form-label" for="confirmPassword">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm password" required style="padding-right: 40px;">
                                <i class="fa fa-eye-slash togglePassword" data-target="confirmPassword" style="position: absolute; right: 24px; top: 70%; transform: translateY(-50%); cursor: pointer; z-index: 10; color: #6c757d;"></i>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-2">
                            <button type="button" class="btn btn-outline-primary" id="btn-back">
                                <i class="ti tabler-file me-2"></i>Back
                            </button> 
                            <button style="margin-left: auto;" type="submit" name="btn_register_submit" class="btn btn-outline-success" id="btn-register">
                                <i class="ti tabler-search me-2"></i>Register
                            </button>
                            <button style="margin-left: auto;" type="submit" name="btn_next" class="btn btn-outline-primary" id="btn-next" hidden>
                                <i class="ti tabler-search me-2"></i>Next
                            </button>        
                        </div>


                        

                        <!-- Error container -->
                        <div id="registrationError" class="alert alert-danger mt-4" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>