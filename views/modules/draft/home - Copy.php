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
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <h5 class="card-title">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</h5>
                    <p class="card-text">Welcome to your personal EvacFinder dashboard.</p>
                  </div>
                </div>
              </div>
              
              <!-- Statistics Card -->
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <h5 class="card-title">Evacuation Centers</h5>
                    <p class="card-text">View and manage evacuation centers in your area.</p>
                    <a href="?route=centers" class="btn btn-light btn-sm">View Centers</a>
                  </div>
                </div>
              </div>
              
              <!-- Alerts Card -->
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <h5 class="card-title">Emergency Alerts</h5>
                    <p class="card-text">Configure your alert preferences.</p>
                    <button class="btn btn-light btn-sm" onclick="showAlert()">Configure</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MAP SECTION - ADDED HERE -->
            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Evacuation Centers Map</h5>
                    <small class="text-muted">View all evacuation centers in your area</small>
                  </div>
                  <div class="card-body" style="padding: 0;">
                    <div id="homeMap" style="height: 450px; width: 100%; border-radius: 0.375rem; overflow: hidden;"></div>
                  </div>
                </div>
              </div>
            </div>
            <!-- END MAP SECTION -->

            <!-- Recent Activity -->
            <div class="row mt-4">
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
            </div>
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

// Ensure map resizes properly when modal or tab changes
$(document).ready(function() {
  // Small delay to ensure map renders
  setTimeout(function() {
    if (typeof map !== 'undefined' && map) {
      map.invalidateSize();
    }
  }, 500);
});
</script>