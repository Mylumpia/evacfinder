<!-- Nav Header (Logo) -->
<div class="nav-header">
  <a href="?route=map" class="brand-logo">
    <img class="logo-centered" src="views/assets/images/evaclogo.png" alt="EvacFinder Logo">
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
                <a href="?route=profile" class="dropdown-item ai-icon">
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