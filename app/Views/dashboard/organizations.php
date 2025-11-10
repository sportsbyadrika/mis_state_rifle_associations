<?php
$title = 'Organizations - KSRA MIS';
$heading = ucfirst($type) . ' Management';
ob_start();
?>
<?php if (!empty($flash)): ?>
    <div class="alert <?= $flash['type'] === 'error' ? 'alert-error' : 'alert-success'; ?> mb-6">
        <?= htmlspecialchars($flash['message'] ?? ''); ?>
    </div>
<?php endif; ?>
<div class="card overflow-hidden">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
        <div>
            <h2 class="text-lg font-semibold section-heading"><?= htmlspecialchars(strtoupper($type)); ?> Directory</h2>
            <p class="text-sm text-muted">Manage organizational details and administrators.</p>
        </div>
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="button-primary">Add New</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table divide-y divide-slate-200">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($organizations as $organization): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-800"><?= htmlspecialchars($organization['name']); ?></td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($organization['email'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($organization['phone'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($organization['address'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= ($organization['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                                <?= htmlspecialchars(ucfirst($organization['status'] ?? 'active')); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted">
                            <a href="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']))); ?>" class="button-secondary inline-flex items-center justify-center text-sm font-semibold">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="create-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden px-4">
    <div class="card w-full max-w-lg p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold section-heading">Create <?= htmlspecialchars(strtoupper($type)); ?></h3>
            <button onclick="document.getElementById('create-modal').classList.add('hidden')" class="text-muted text-xl leading-none">&times;</button>
        </div>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type))); ?>" method="POST" class="mt-4 space-y-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div class="space-y-2">
                <label class="form-label text-sm">Name</label>
                <input type="text" name="name" class="input-control" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="form-label text-sm">Email</label>
                    <input type="email" name="email" class="input-control">
                </div>
                <div class="space-y-2">
                    <label class="form-label text-sm">Phone</label>
                    <input type="tel" name="phone" class="input-control">
                </div>
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Address</label>
                <textarea name="address" class="input-control" rows="3"></textarea>
            </div>
            <div class="flex flex-wrap justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')" class="button-secondary">Cancel</button>
                <button type="submit" class="button-primary">Create</button>
            </div>
        </form>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
