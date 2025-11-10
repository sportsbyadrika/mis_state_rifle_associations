<?php
$title = 'Super Admin Dashboard - KSRA MIS';
$heading = 'Super Admin Overview';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">System Configuration</h2>
        <p class="text-sm text-muted">Manage KSRA settings, governance documents, and organization hierarchies.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/dra')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage DRAs &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Financial Oversight</h2>
        <p class="text-sm text-muted">Review KSRA financial performance across financial years.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">View Finance &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Election Management</h2>
        <p class="text-sm text-muted">Record and publish state-level election outcomes securely.</p>
        <a href="<?= htmlspecialchars(url_to('elections')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage Elections &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
