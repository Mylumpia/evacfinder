<!-- Nav Header (Logo) -->
<div class="nav-header">
  <a href="?route=map" class="brand-logo">
    <img class="logo-centered" src="views/assets/images/evaclogo.png" alt="EvacFinder Logo">
    <!-- <img class="logo-abbr"    src="views/assets/images/logo-white.png"      alt="Logo">
    <img class="logo-compact" src="views/assets/images/logo-text-white.png" alt="Logo Text">
    <img class="brand-title"  src="views/assets/images/logo-text-white.png" alt="Brand"> -->
  </a>
  <?php if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok"): ?>
    <div class="nav-control">
      <div class="hamburger">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php
$profileInfo = null;
$lguInfo = null;
$publicInfo = null;
$profileUserName = 'User';
$profileEmail = '';
$profileUserId = $_SESSION['userid'] ?? '';
$profileRole = 'USER';
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok") {
    $userEmail = $_SESSION["email"] ?? null;
    if ($userEmail) {
        $profileInfo = ModelUserRights::mdlGetUserCredentials('userrights', 'email', $userEmail);
        $profileEmail = $userEmail;
        if (!empty($profileInfo)) {
            $profileRole = strtoupper($profileInfo['Type'] ?? 'user');
        }
        if (!empty($profileInfo) && isset($profileInfo['Type']) && $profileInfo['Type'] === 'lgu') {
            $lguInfo = ModelUserRights::mdlGetUserCredentials('lgu_users', 'office_email_address', $userEmail);
        } elseif (!empty($profileInfo) && isset($profileInfo['Type']) && $profileInfo['Type'] === 'public') {
            $publicInfo = ModelUserRights::mdlGetUserCredentials('personal_users', 'email_address', $userEmail);
        }
        if (!empty($lguInfo)) {
            $profileUserName = trim(($lguInfo['first_name'] ?? '') . ' ' . ($lguInfo['last_name'] ?? '')) ?: $profileUserName;
        } elseif (!empty($publicInfo)) {
            $profileUserName = trim(($publicInfo['first_name'] ?? '') . ' ' . ($publicInfo['last_name'] ?? '')) ?: $profileUserName;
        } elseif (!empty($_SESSION['username'])) {
            $profileUserName = $_SESSION['username'];
        }
    }
}

function profileDisplayValue($value, $default = 'N/A') {
    return $value ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $default;
}
?>

<!-- Top Navbar -->
<div class="header">
  <div class="header-content">
    <nav class="navbar navbar-expand">
      <div class="collapse navbar-collapse justify-content-between">

        <!-- Left: Search -->
        <div class="header-left">
          <div class="search_bar dropdown">
            <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
              <i class="mdi mdi-magnify"></i>
            </span>
            <div class="dropdown-menu p-0 m-0">
              <form>
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
              </form>
            </div>
          </div>
        </div>

        <!-- Right: Show Login or Profile based on session -->
        <ul class="navbar-nav header-right">
          <?php 
            $isLoggedIn = isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok";
          ?>
          
          <?php if(!$isLoggedIn): ?>
            <!-- Show Login Button when not logged in -->
            <li class="nav-item">
              <a href="?route=login" class="btn btn-primary btn-sm">
                <i class="mdi mdi-login"></i> Login
              </a>
            </li>
          <?php else: ?>
            <!-- Show Profile Dropdown when logged in -->
            <li class="nav-item dropdown header-profile">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                <i class="mdi mdi-account-circle font-size-h3"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item ai-icon" data-toggle="modal" data-target="#profileModal">
                  <i class="ti-user text-primary"></i> Profile
                </a>
                <a href="?route=logout" class="dropdown-item ai-icon">
                  <i class="ti-close text-danger"></i> Logout
                </a>
              </div>
            </li>
          <?php endif; ?>
        </ul>

      </div>
    </nav>
  </div>
</div>

<!-- Profile Popup Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header p-0 border-0 bg-primary text-white rounded-top">
          <div class="d-flex align-items-center p-4 w-100" style="position:relative;">
          <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width:64px; height:64px; font-size:24px; font-weight:700;">
            <?php echo strtoupper(substr($profileUserName, 0, 1)); ?>
          </div>
          <div class="ms-3" style="margin-left: 8px;">
            <h5 class="mb-1 text-white"><?php echo htmlspecialchars($profileUserName, ENT_QUOTES, 'UTF-8'); ?></h5>
            <p class="mb-0 text-white-75"><?php echo profileDisplayValue($profileEmail, 'No email'); ?></p>
          </div>
          <span class="badge bg-light text-primary py-2 px-3" style="font-size:.85rem; letter-spacing:.04em; position:absolute; right:24px; top:20px;"><?php echo htmlspecialchars($profileRole, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
      </div>
      <div class="modal-body p-4">
        <div class="row gy-4">
          <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <h6 class="mb-4 text-uppercase text-muted">Account Information</h6>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Full name</span>
                  <strong><?php echo profileDisplayValue($profileUserName); ?></strong>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Email address</span>
                  <strong><?php echo profileDisplayValue($profileEmail); ?></strong>
                </div>
                <?php
                  $contactNum = '';
                  if (is_array($lguInfo)) {
                    $contactNum = $lguInfo['num'] ?? $lguInfo['phone_number'] ?? '';
                  } elseif (is_array($publicInfo)) {
                    $contactNum = $publicInfo['phone_number'] ?? '';
                  }

                  $areaAssigned = '';
                  if (is_array($lguInfo)) {
                    $province = trim($lguInfo['province'] ?? '');
                    $region = trim($lguInfo['region'] ?? '');
                    if ($province === '' && $region === '') {
                      $areaAssigned = '';
                    } else {
                      $areaAssigned = $province . ($region !== '' ? ' / ' . $region : '');
                    }
                  }
                ?>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Contact number</span>
                  <strong><?php echo profileDisplayValue($contactNum, 'N/A'); ?></strong>
                </div>
                <?php if (!empty($lguInfo) && is_array($lguInfo)): ?>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Office / agency</span>
                  <strong><?php echo profileDisplayValue($lguInfo['lgu_office_name'] ?? '', 'N/A'); ?></strong>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Area assigned</span>
                  <strong><?php echo profileDisplayValue($areaAssigned, 'N/A'); ?></strong>
                </div>
                <?php endif; ?>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Role</span>
                  <strong><?php echo profileDisplayValue($lguInfo['position_role'] ?? '', strtoupper($profileRole)); ?></strong>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
              <div class="card-body">
                <h6 class="mb-4 text-uppercase text-muted">Security</h6>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">User ID</span>
                  <strong><?php echo profileDisplayValue($profileUserId, 'N/A'); ?></strong>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Password</span>
                  <strong>••••••••</strong>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                  <span class="text-muted">Last login</span>
                  <?php
                    $lastLoginRaw = $_SESSION['last_login'] ?? $profileInfo['last_login'] ?? null;
                    $lastLoginValue = '';
                    if ($lastLoginRaw) {
                        try {
                            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $lastLoginRaw, new DateTimeZone('UTC'));
                            if ($dt !== false) {
                                $lastLoginValue = $dt->format(DATE_ATOM);
                            }
                        } catch (Exception $e) {
                            $lastLoginValue = '';
                        }
                    }
                  ?>
                  <strong id="lastLoginValue" data-last-login="<?php echo htmlspecialchars($lastLoginValue, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo $lastLoginValue ? 'Loading...' : 'N/A'; ?>
                  </strong>
                </div>
                <!-- Role change notice removed per user request -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var el = document.getElementById('lastLoginValue');
  if (!el) return;
  var raw = el.getAttribute('data-last-login');
  if (!raw) {
    el.textContent = 'N/A';
    return;
  }
  var parsed = new Date(raw);
  if (isNaN(parsed.getTime())) {
    el.textContent = raw;
    return;
  }
  el.textContent = parsed.toLocaleString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  });
});
</script>