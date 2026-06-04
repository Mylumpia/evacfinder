<!-- modules/home.php -->
<div class="home-dashboard">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Dashboard</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Welcome Card -->
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card dashboard-card dashboard-card-primary text-white">
                  <div class="card-body">
                    <?php
                      $welcomeName = $_SESSION['username'] ?? '';
                      if ($welcomeName === '' && isset($_SESSION['firstname'])) {
                          $welcomeName = trim(($_SESSION['firstname'] ?? '') . ' ' . ($_SESSION['lastname'] ?? ''));
                      }
                    ?>
                    <h5 class="card-title">Welcome, <?php echo htmlspecialchars($welcomeName ?: 'User'); ?>!</h5>
                    <p class="card-text">Welcome to your personal EvacFinder dashboard.</p>
                  </div>
                </div>
              </div>
              
              <!-- Statistics Card -->
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card dashboard-card dashboard-card-success text-white">
                  <div class="card-body">
                    <h5 class="card-title">Evacuation Centers</h5>
                    <p class="card-text">View and manage evacuation centers in your area.</p>
                    <a href="?route=centers" class="btn btn-light btn-sm">View Centers</a>
                  </div>
                </div>
              </div>
              
              <!-- Alerts Card -->
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card dashboard-card dashboard-card-info text-white">
                  <div class="card-body">
                    <h5 class="card-title">Emergency Alerts</h5>
                    <p class="card-text">Configure your alert preferences.</p>
                    <button class="btn btn-light btn-sm" onclick="showAlert()">Configure</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Activity -->
            <!-- <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Recent Activity</h5>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Activity</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><?php echo date('Y-m-d H:i'); ?></td>
                            <td>Logged in to EvacFinder</td>
                            <td><span class="badge badge-success">Success</span></td>
                          </tr>
                          <tr>
                            <td><?php echo date('Y-m-d', strtotime('-1 day')); ?></td>
                            <td>Viewed evacuation centers</td>
                            <td><span class="badge badge-info">Completed</span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function showAlert() {
  Swal.fire({
    title: 'Coming Soon',
    text: 'Alert configuration will be available soon!',
    icon: 'info',
    confirmButtonText: 'OK'
  });
}
</script>