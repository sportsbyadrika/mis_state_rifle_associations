<?php
$title = 'State Admin Dashboard - KSRA MIS';
$heading = 'KSRA State Administration';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">District Rifle Associations</h2>
        <p class="text-sm text-muted">Create district admins, configure annual fee heads, and review membership performance.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/dra')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage DRAs &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Affiliated Institutions</h2>
        <p class="text-sm text-muted">Onboard new affiliated institutions and set membership fee structures.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/ai')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage AIs &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Clubs</h2>
        <p class="text-sm text-muted">Create club profiles, assign club admins, and monitor member onboarding.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/club')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Manage Clubs &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
