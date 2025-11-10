<?php
$title = 'District Admin Dashboard - KSRA MIS';
$heading = 'District Operations';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">District Clubs</h2>
        <p class="text-sm text-slate-500 mt-2">Create and oversee clubs across the district, assign club admins, and manage fee heads.</p>
        <a href="<?= htmlspecialchars(url_to('organizations/club')); ?>" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage Clubs &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Financial Year Setup</h2>
        <p class="text-sm text-slate-500 mt-2">Maintain district financial years and track income & expenses.</p>
        <a href="<?= htmlspecialchars(url_to('finance')); ?>" class="inline-block mt-4 text-sm font-medium text-slate-900">Finance Module &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
