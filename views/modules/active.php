<?php
$summary = ModelCenters::mdlGetCenterSummary();
$allCenters = ModelCenters::mdlGetAllCenters();
?>

<div class="home-dashboard">
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white overflow-hidden">
        <div class="card-body">
            <div class="d-flex flex-column justify-content-center align-items-center text-center gap-3">
            <img src="views/assets/images/evaclogo.png" alt="EvacFinder Logo" style="max-width: 320px; width: 100%; height: auto; max-height: 180px; margin: 0 auto;" />
            </div>
        </div>
        </div>
    </div>
    </div>

    <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
            <p class="mb-1 text-uppercase text-primary fw-bold">Total Centers</p>
            <h3 class="mb-0"><?php echo number_format($summary['total_centers']); ?></h3>
            </div>
            <div class="bg-primary text-white rounded-3 p-3">
            <i class="fa fa-home"></i>
            </div>
        </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
            <p class="mb-1 text-uppercase text-success fw-bold">Total Capacity</p>
            <h3 class="mb-0"><?php echo number_format($summary['total_capacity']); ?></h3>
            </div>
            <div class="bg-success text-white rounded-3 p-3">
            <i class="fa fa-users"></i>
            </div>
        </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
            <p class="mb-1 text-uppercase text-warning fw-bold">Currently Occupied</p>
            <h3 class="mb-0"><?php echo number_format($summary['currently_occupied']); ?></h3>
            </div>
            <div class="bg-warning text-dark rounded-3 p-3">
            <i class="fa fa-bed"></i>
            </div>
        </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm h-100">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
            <p class="mb-1 text-uppercase text-info fw-bold">Active Centers</p>
            <h3 class="mb-0"><?php echo number_format($summary['active_centers']); ?></h3>
            </div>
            <div class="bg-info text-white rounded-3 p-3">
            <i class="fa fa-check-circle"></i>
            </div>
        </div>
        </div>
    </div>
    </div>

    <div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Assigned Evacuation Centers</h5>
            <a href="?route=active" class="btn btn-primary btn-sm">Refresh</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Center Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Capacity</th>
                    <th>Current Occupancy</th>
                    <th>Assigned Staff</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if(count($allCenters) > 0): ?>
                    <?php foreach($allCenters as $center): ?>
                    <?php
                        $statusText = htmlspecialchars($center['status']);
                        $badgeClass = 'bg-secondary';
                        if ($statusText === 'Active') {
                            $badgeClass = 'bg-success';
                        } elseif ($statusText === 'Inactive') {
                            $badgeClass = 'bg-secondary';
                        } elseif ($statusText === 'Full') {
                            $badgeClass = 'bg-warning text-dark';
                        } elseif ($statusText === 'Under Maintenance') {
                            $badgeClass = 'bg-danger';
                        }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($center['center_name']); ?></td>
                        <td><?php echo htmlspecialchars($center['category']); ?></td>
                        <td><?php echo htmlspecialchars(trim($center['barangay'] . ', ' . $center['city'] . ', ' . $center['province'])); ?></td>
                        <td><?php echo number_format($center['capacity']); ?> people</td>
                        <td><?php echo number_format($center['current_occupants']); ?></td>
                        <td><span class="text-muted">No staff assigned</span></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                    <td colspan="7" class="text-center py-4">No active centers found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</div>
