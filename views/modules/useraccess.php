<?php
// User Access management UI
$users = ModelUserRights::mdlListUsers();
$panels = ['home' => 'Dashboard', 'map' => 'Map', 'centers' => 'Centers', 'evacuees' => 'Evacuees', 'active' => 'Active Centers', 'announcement' => 'Announcement', 'useraccess' => 'User Access'];
// permission levels
$levels = ['full' => 'Full', 'view' => 'View', 'restricted' => 'Restricted'];
?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">User Access</h4>
      </div>
      <div class="card-body">
        <p>Assign what each user can access. Changes are saved immediately.</p>

        <div class="table-responsive">
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>User</th>
                <th>Email</th>
                <?php foreach ($panels as $key => $label): ?>
                  <th><?php echo htmlspecialchars($label); ?></th>
                <?php endforeach; ?>
                <th>Save</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
                <?php $perms = ModelUserRights::mdlGetPermissions($u['userid']); ?>
                <tr data-userid="<?php echo htmlspecialchars($u['userid']); ?>">
                  <td><?php echo htmlspecialchars(($u['first_name'] ? $u['first_name'] . ' ' . $u['last_name'] : $u['userid'])); ?></td>
                  <td><?php echo htmlspecialchars($u['email']); ?></td>
                  <?php foreach ($panels as $key => $label): ?>
                    <td style="min-width:140px;">
                      <select class="form-select form-select-sm perm-select" data-panel="<?php echo htmlspecialchars($key); ?>">
                        <?php foreach ($levels as $lvKey => $lvLabel):
                          $sel = (isset($perms[$key]) && $perms[$key] === $lvKey) ? 'selected' : '';
                        ?>
                          <option value="<?php echo $lvKey; ?>" <?php echo $sel; ?>><?php echo $lvLabel; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                  <?php endforeach; ?>
                  <td>
                    <button class="btn btn-sm btn-primary save-perms">Save</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.save-perms').forEach(function(btn){
    btn.addEventListener('click', function(e){
      var row = e.currentTarget.closest('tr');
      var userid = row.getAttribute('data-userid');
      var selects = row.querySelectorAll('.perm-select');
      var perms = {};
      selects.forEach(function(s){ perms[s.getAttribute('data-panel')] = s.value; });

      var fd = new FormData();
      fd.append('userid', userid);
      fd.append('permissions', JSON.stringify(perms));

      fetch('ajax/save_user_permissions.ajax.php', { method: 'POST', credentials: 'same-origin', body: fd })
        .then(function(res){ return res.json(); })
        .then(function(json){
          if (json && json.success) {
            Swal.fire({ icon: 'success', title: 'Saved', text: json.message, timer: 1200, showConfirmButton: false });
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: json.message || 'Failed to save' });
          }
        }).catch(function(){
          Swal.fire({ icon: 'error', title: 'Error', text: 'Server error' });
        });
    });
  });
});
</script>
