<?php
$title = 'Club Admin Dashboard - KSRA MIS';
$heading = 'Club Management';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Club Members</h2>
        <p class="text-sm text-muted">Approve and renew club memberships with audit trails.</p>
        <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage Members &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Finance</h2>
        <p class="text-sm text-muted">Record club-level income and expenses per financial year.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Finance Ledger &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
