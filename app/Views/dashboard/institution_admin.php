<?php
$title = 'Institution Admin Dashboard - KSRA MIS';
$heading = 'Affiliated Institution Management';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Institution Members</h2>
        <p class="text-sm text-muted">Approve incoming member applications and manage renewals.</p>
        <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Review Members &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Fee Heads</h2>
        <p class="text-sm text-muted">Configure membership fee heads and durations specific to this institution.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Configure Fees &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
