// ── VIEW HISTORY ─────────────────────────────────────────────────────────────
$(document).on('click', '.view-history', function () {
    const centerId   = $(this).data('center-id');
    const centerName = $(this).data('center-name');

    $('#history_center_name').text(centerName);
    $('#history-tbody').html('<td><td colspan="5" class="text-center">Loading...</td></tr>');
    $('#historyModal').modal('show');

    $.ajax({
        url:      'ajax/get_history.ajax.php',
        method:   'POST',
        data:     { center_id: centerId },
        dataType: 'json',
        success: function (response) {
            if (response.success && response.data.length > 0) {
                let rows = '';
                $.each(response.data, function (i, row) {
                    let changesList = '';
                    if (row.changes && row.changes.length > 0) {
                        changesList = '<ul class="mb-0 ps-3">';
                        $.each(row.changes, function (j, change) {
                            changesList += `<li>${change}</li>`;
                        });
                        changesList += '</ul>';
                    }

                    rows += `<tr>
                        <td>${row.history_date}</td>
                        <td><span class="badge ${
                            row.action_made === 'Created' ? 'bg-success' :
                            row.action_made === 'Updated' ? 'bg-primary' :
                            row.action_made === 'Occupancy Updated' ? 'bg-warning' :
                            'bg-secondary'
                        }">${row.action_made}</span></td>
                        <td>${changesList}</td>
                        <td>${row.remarks || '-'}</td>
                        <td>${row.changed_by_name}</td>
                    </tr>`;
                });
                $('#history-tbody').html(rows);
            } else {
                $('#history-tbody').html('<tr><td colspan="5" class="text-center">No history records found.</td></tr>');
            }
        },
        error: function () {
            $('#history-tbody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load history.</td></tr>');
        }
    });
});