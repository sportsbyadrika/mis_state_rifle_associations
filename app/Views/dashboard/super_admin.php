<?php
$title = 'Super Admin Dashboard - KSRA MIS';
$heading = 'Super Admin Overview';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">System Configuration</h2>
        <p class="text-sm text-slate-500 mt-2">Manage KSRA settings, governance documents, and organization hierarchies.</p>
        <a href="/organizations/dra" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage DRAs &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Financial Oversight</h2>
        <p class="text-sm text-slate-500 mt-2">Review KSRA financial performance across financial years.</p>
        <a href="/finance" class="inline-block mt-4 text-sm font-medium text-slate-900">View Finance &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Election Management</h2>
        <p class="text-sm text-slate-500 mt-2">Record and publish state-level election outcomes securely.</p>
        <a href="/elections" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage Elections &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
