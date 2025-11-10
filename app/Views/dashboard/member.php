<?php
$title = 'Member Dashboard - KSRA MIS';
$heading = 'Member Portal';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Your Memberships</h2>
        <p class="text-sm text-muted">Track status across KSRA, DRAs, AIs, and Clubs.</p>
        <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">View Memberships &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Apply for New Membership</h2>
        <p class="text-sm text-muted">Submit membership requests securely with admin approval workflow.</p>
        <a href="<?= htmlspecialchars(url_to('memberships/apply')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">Apply Now &rarr;</a>
    </div>
    <div class="card p-6 space-y-4">
        <h2 class="text-lg font-semibold section-heading">Elections &amp; News</h2>
        <p class="text-sm text-muted">Stay updated with the latest representatives and governance updates.</p>
        <a href="<?= htmlspecialchars(url_to('public')); ?>" class="auth-link inline-flex items-center gap-2 text-sm font-semibold">View Public Updates &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
