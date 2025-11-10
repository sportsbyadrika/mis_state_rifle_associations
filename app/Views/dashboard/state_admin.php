<?php
$title = 'State Admin Dashboard - KSRA MIS';
$heading = 'KSRA State Administration';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">District Rifle Associations</h2>
        <p class="text-sm text-slate-500 mt-2">Create district admins, configure annual fee heads, and review membership performance.</p>
        <a href="/organizations/dra" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage DRAs &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Affiliated Institutions</h2>
        <p class="text-sm text-slate-500 mt-2">Onboard new affiliated institutions and set membership fee structures.</p>
        <a href="/organizations/ai" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage AIs &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
