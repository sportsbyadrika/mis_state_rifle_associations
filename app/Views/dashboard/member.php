<?php
$title = 'Member Dashboard - KSRA MIS';
$heading = 'Member Portal';
ob_start();
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Your Memberships</h2>
        <p class="text-sm text-slate-500 mt-2">Track status across KSRA, DRAs, AIs, and Clubs.</p>
        <a href="/memberships" class="inline-block mt-4 text-sm font-medium text-slate-900">View Memberships &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Apply for New Membership</h2>
        <p class="text-sm text-slate-500 mt-2">Submit membership requests securely with admin approval workflow.</p>
        <a href="/memberships/apply" class="inline-block mt-4 text-sm font-medium text-slate-900">Apply Now &rarr;</a>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-slate-700">Elections & News</h2>
        <p class="text-sm text-slate-500 mt-2">Stay updated with the latest representatives and governance updates.</p>
        <a href="/public" class="inline-block mt-4 text-sm font-medium text-slate-900">View Public Updates &rarr;</a>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
