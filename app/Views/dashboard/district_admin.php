<?php
$title = 'District Admin Dashboard - KSRA MIS';
$heading = 'District Operations';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">District Clubs</h2>
        <p class="text-sm text-muted">Create and oversee clubs across the district, assign club admins, and manage fee heads.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/club')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage Clubs &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Financial Year Setup</h2>
        <p class="text-sm text-muted">Maintain district financial years and track income &amp; expenses.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Finance Module &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
