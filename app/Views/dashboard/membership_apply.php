<?php
$title = 'Apply for Membership - KSRA MIS';
$heading = 'New Membership Application';
ob_start();
?>
<div class="card p-6 max-w-3xl">
    <h2 class="text-lg font-semibold section-heading">Submit Membership Request</h2>
    <p class="text-sm text-muted mt-1">Link to an existing DRA/Club/Institution or request a new membership below. Administrators will review each submission.</p>
    <form action="<?= htmlspecialchars(url_to('memberships')); ?>" method="POST" class="mt-6 space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="form-label text-sm">Organization (Hashed ID)</label>
                <input type="text" name="organization_id" class="input-control" placeholder="Provide hashed organization id" required>
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Membership Type (Hashed ID)</label>
                <input type="text" name="membership_type_id" class="input-control" placeholder="Provide hashed membership type id" required>
            </div>
        </div>
        <p class="text-xs text-muted">* Replace this form with organization selectors for production. Hash decoding should happen server-side using secure lookups.</p>
        <div class="flex flex-wrap justify-end gap-3">
            <a href="<?= htmlspecialchars(url_to('memberships')); ?>" class="button-secondary">Cancel</a>
            <button type="submit" class="button-primary">Submit Application</button>
        </div>
    </form>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
