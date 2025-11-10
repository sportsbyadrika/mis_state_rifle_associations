<?php
$title = 'Institution Admin Dashboard - KSRA MIS';
$heading = 'Affiliated Institution Management';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Institution Members</h2>
        <p class="text-sm text-slate-500 mt-2">Approve incoming member applications and manage renewals.</p>
        <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="inline-block mt-4 text-sm font-medium text-slate-900">Review Members &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Fee Heads</h2>
        <p class="text-sm text-slate-500 mt-2">Configure membership fee heads and durations specific to this institution.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="inline-block mt-4 text-sm font-medium text-slate-900">Configure Fees &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
