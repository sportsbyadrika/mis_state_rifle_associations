<?php
$title = 'Manage Profile - KSRA MIS';
$heading = 'Manage Your Profile';
ob_start();
?>
<div class="max-w-3xl space-y-6">
    <?php if (!empty($status ?? '')): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            <?= htmlspecialchars($status); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors ?? [])): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars(url_to('profile')); ?>" class="card p-6 space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf ?? ''); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-muted">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="<?= htmlspecialchars($user['name'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-muted">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($user['email'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-muted">Phone Number</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($user['phone'] ?? ''); ?>"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="text-lg font-semibold section-heading">Update Password</h2>
            <p class="text-sm text-muted">Leave the password fields blank to keep your current password.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-muted">Current Password</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="current-password"
                >
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-muted">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="new-password"
                >
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-muted">Confirm New Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    autocomplete="new-password"
                >
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="button-primary inline-flex items-center justify-center gap-2 px-6 py-2">
                Save Changes
            </button>
        </div>
    </form>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
