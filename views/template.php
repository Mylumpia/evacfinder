<!DOCTYPE html>
<?php
  session_start();
?>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>EvacFinder</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="16x16" href="views/assets/images/favicon.png">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <!-- CSS -->
  <link rel="stylesheet" href="views/assets/vendor/jqvmap/css/jqvmap.min.css">
  <link rel="stylesheet" href="views/assets/vendor/chartist/css/chartist.min.css">
  <link rel="stylesheet" href="views/assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="views/assets/css/perfect-scrollbar.css">
  <link rel="stylesheet" href="views/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

  <!-- Preloader -->
  <div id="preloader">
    <div class="sk-three-bounce">
      <div class="sk-child sk-bounce1"></div>
      <div class="sk-child sk-bounce2"></div>
      <div class="sk-child sk-bounce3"></div>
    </div>
  </div>

  <?php if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == "ok"): ?>

    <!-- Main Wrapper -->
    <div id="main-wrapper">

      <!-- Navbar & Sidebar -->
      <?php include "modules/navbar.php"; ?>
      <?php include "modules/sidebar.php"; ?>

      <!-- Main Content Area -->
      <div class="content-body">
        <div class="container-fluid">

          <?php
            if(isset($_GET["route"])){
              $route = basename($_GET["route"]);
              $allowedRoutes = [
                'home',
                'logout',
                'centers'
              ];

              if(in_array($route, $allowedRoutes)){
                include "modules/" . $route . ".php";
              } else {
                include "modules/404.php";
              }
            } else {
              include "modules/home.php";
            }
          ?>

        </div>
      </div>

    </div>
    <!-- End Main Wrapper -->

  <?php else: ?>

    <?php include "modules/login.php"; ?>

  <?php endif; ?>

  <!-- Scripts -->
  <script src="views/assets/vendor/global/global.min.js"></script>
  <script src="views/assets/js/deznav-init.js"></script>
  <script src="views/assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
  <script src="views/assets/js/custom.min.js"></script>

  <!-- Charts -->
  <script src="views/assets/vendor/chart.js/Chart.bundle.min.js"></script>
  <script src="views/assets/vendor/gaugeJS/dist/gauge.min.js"></script>

  <!-- Counter Up -->
  <script src="views/assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="views/assets/vendor/jquery.counterup/jquery.counterup.min.js"></script>

  <!-- SVG Animation -->
  <script src="views/assets/vendor/svganimation/vivus.min.js"></script>
  <script src="views/assets/vendor/svganimation/svg.animation.js"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- Page-specific JS -->
  <?php if(isset($route)): ?>
    <?php
      $routeScripts = [
        'home'    => ['home.js'],
        'centers' => ['centers.js'],
      ];
      if(array_key_exists($route, $routeScripts)){
        foreach($routeScripts[$route] as $script){
          $scriptPath = "views/js/" . $script;
          if(file_exists($scriptPath)){
            echo '<script src="/EvacFinder/' . $scriptPath . '"></script>';
          }
        }
      }
    ?>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>