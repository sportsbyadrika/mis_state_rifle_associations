<?php
$title = 'Apply for Membership - KSRA MIS';
$heading = 'New Membership Application';
ob_start();
?>
<div class="bg-white rounded-xl shadow p-6 max-w-3xl">
    <h2 class="text-lg font-semibold text-slate-700">Submit Membership Request</h2>
    <p class="text-sm text-slate-500 mt-1">Link to an existing DRA/Club/Institution or request a new membership below. Administrators will review each submission.</p>
    <form action="<?= htmlspecialchars(url_to('memberships')); ?>" method="POST" class="mt-6 space-y-4">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600">Organization (Hashed ID)</label>
                <input type="text" name="organization_id" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Provide hashed organization id" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Membership Type (Hashed ID)</label>
                <input type="text" name="membership_type_id" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="Provide hashed membership type id" required>
            </div>
        </div>
        <p class="text-xs text-slate-500">* Replace this form with organization selectors for production. Hash decoding should happen server-side using secure lookups.</p>
        <div class="flex justify-end space-x-3">
            <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="px-4 py-2 border border-slate-300 rounded-md">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-md">Submit Application</button>
        </div>
    </form>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
