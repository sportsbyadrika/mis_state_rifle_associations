<?php
$title = 'Club Admin Dashboard - KSRA MIS';
$heading = 'Club Management';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Club Members</h2>
        <p class="text-sm text-slate-500 mt-2">Approve and renew club memberships with audit trails.</p>
        <a href="/memberships" class="inline-block mt-4 text-sm font-medium text-slate-900">Manage Members &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Finance</h2>
        <p class="text-sm text-slate-500 mt-2">Record club-level income and expenses per financial year.</p>
        <a href="/finance" class="inline-block mt-4 text-sm font-medium text-slate-900">Finance Ledger &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
