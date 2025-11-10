<?php
$title = ucfirst($type) . ' Details - KSRA MIS';
$heading = ucfirst($type) . ' Profile';
ob_start();
?>
<?php if (!empty($flash)): ?>
    <div class="alert <?= $flash['type'] === 'error' ? 'alert-error' : 'alert-success'; ?> mb-6">
        <?= htmlspecialchars($flash['message'] ?? ''); ?>
    </div>
<?php endif; ?>
<div class="space-y-6">
    <div class="card p-6 space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="space-y-2">
                <h2 class="text-xl font-semibold section-heading"><?= htmlspecialchars($organization['name']); ?></h2>
                <p class="text-sm text-muted">Primary contact and status details for this organization.</p>
            </div>
            <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/toggle')); ?>" method="POST" class="flex-shrink-0">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                <button type="submit" class="<?= ($organization['status'] ?? 'active') === 'active' ? 'button-danger' : 'button-primary'; ?>">
                    <?= ($organization['status'] ?? 'active') === 'active' ? 'Deactivate Organization' : 'Activate Organization'; ?>
                </button>
            </form>
        </div>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-2">
                <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Status</dt>
                <dd>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= ($organization['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                        <?= htmlspecialchars(ucfirst($organization['status'] ?? 'active')); ?>
                    </span>
                </dd>
            </div>
            <div class="space-y-2">
                <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Type</dt>
                <dd class="uppercase font-semibold text-slate-800"><?= htmlspecialchars($type); ?></dd>
            </div>
            <div class="space-y-2">
                <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Email</dt>
                <dd class="text-slate-800"><?= htmlspecialchars($organization['email'] ?? '—'); ?></dd>
            </div>
            <div class="space-y-2">
                <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Phone</dt>
                <dd class="text-slate-800"><?= htmlspecialchars($organization['phone'] ?? '—'); ?></dd>
            </div>
            <div class="md:col-span-2 space-y-2">
                <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Address</dt>
                <dd class="text-slate-800 whitespace-pre-line"><?= nl2br(htmlspecialchars($organization['address'] ?? '—')); ?></dd>
            </div>
            <?php if (!empty($parent)): ?>
                <div class="md:col-span-2 space-y-2">
                    <dt class="text-xs font-semibold text-muted uppercase tracking-wide">Parent Organization</dt>
                    <dd class="text-slate-800"><?= htmlspecialchars($parent['name'] ?? ''); ?></dd>
                </div>
            <?php endif; ?>
        </dl>
    </div>

    <div class="card p-6">
        <h3 class="text-lg font-semibold section-heading">Edit Organization Details</h3>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/update')); ?>" method="POST" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div class="space-y-2">
                <label class="form-label text-sm">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($organization['name']); ?>" required class="input-control">
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($organization['email'] ?? ''); ?>" class="input-control">
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($organization['phone'] ?? ''); ?>" class="input-control">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="form-label text-sm">Address</label>
                <textarea name="address" rows="3" class="input-control"><?= htmlspecialchars($organization['address'] ?? ''); ?></textarea>
            </div>
            <div class="md:col-span-2 flex flex-wrap justify-end gap-3">
                <a href="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type))); ?>" class="button-secondary">Back to List</a>
                <button type="submit" class="button-primary">Update Organization</button>
            </div>
        </form>
    </div>

    <div class="card p-6">
        <h3 class="text-lg font-semibold section-heading">Add <?= htmlspecialchars($adminRoleLabel); ?></h3>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins')); ?>" method="POST" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div class="space-y-2">
                <label class="form-label text-sm">Name</label>
                <input type="text" name="name" required class="input-control">
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Email</label>
                <input type="email" name="email" required class="input-control">
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Phone</label>
                <input type="text" name="phone" class="input-control">
            </div>
            <div class="space-y-2">
                <label class="form-label text-sm">Password</label>
                <input type="password" name="password" required minlength="8" class="input-control">
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="button-primary">Add <?= htmlspecialchars($adminRoleLabel); ?></button>
            </div>
        </form>
    </div>

    <div class="card p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold section-heading">Current <?= htmlspecialchars($adminRoleLabel); ?>s</h3>
            <span class="text-sm text-muted"><?= count($admins); ?> total</span>
        </div>
        <?php if (empty($admins)): ?>
            <p class="mt-4 text-sm text-muted">No administrators assigned yet.</p>
        <?php else: ?>
            <div class="mt-6 space-y-4">
                <?php foreach ($admins as $admin): ?>
                    <div class="card p-4 space-y-4">
                        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins/' . rawurlencode($admin['hash_id']) . '/update')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                            <div class="space-y-2">
                                <label class="form-label text-xs uppercase tracking-wide">Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($admin['name'] ?? ''); ?>" required class="input-control">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label text-xs uppercase tracking-wide">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? ''); ?>" required class="input-control">
                            </div>
                            <div class="space-y-2">
                                <label class="form-label text-xs uppercase tracking-wide">Phone</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? ''); ?>" class="input-control">
                            </div>
                            <div class="flex items-center md:justify-end">
                                <button type="submit" class="button-secondary">Update</button>
                            </div>
                        </form>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between text-sm">
                            <div class="text-xs text-muted uppercase tracking-wide flex items-center gap-2">
                                <span>Status:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold <?= ($admin['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                                    <?= htmlspecialchars(ucfirst($admin['status'] ?? 'active')); ?>
                                </span>
                            </div>
                            <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins/' . rawurlencode($admin['hash_id']) . '/toggle')); ?>" method="POST" class="flex justify-end">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                                <button type="submit" class="<?= ($admin['status'] ?? 'active') === 'active' ? 'button-danger' : 'button-primary'; ?>">
                                    <?= ($admin['status'] ?? 'active') === 'active' ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
