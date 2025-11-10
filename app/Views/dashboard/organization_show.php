<?php
$title = ucfirst($type) . ' Details - KSRA MIS';
$heading = ucfirst($type) . ' Profile';
ob_start();
?>
<?php if (!empty($flash)): ?>
    <div class="mb-6 px-4 py-3 rounded-lg <?= $flash['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'; ?>">
        <?= htmlspecialchars($flash['message'] ?? ''); ?>
    </div>
<?php endif; ?>
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-800"><?= htmlspecialchars($organization['name']); ?></h2>
                <p class="text-sm text-slate-500 mt-1">Primary contact and status details for this organization.</p>
            </div>
            <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/toggle')); ?>" method="POST">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                <button type="submit" class="px-4 py-2 rounded-md <?= ($organization['status'] ?? 'active') === 'active' ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-emerald-600 text-white hover:bg-emerald-700'; ?>">
                    <?= ($organization['status'] ?? 'active') === 'active' ? 'Deactivate Organization' : 'Activate Organization'; ?>
                </button>
            </form>
        </div>
        <dl class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-600">
            <div>
                <dt class="font-medium text-slate-500">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= ($organization['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                        <?= htmlspecialchars(ucfirst($organization['status'] ?? 'active')); ?>
                    </span>
                </dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500">Type</dt>
                <dd class="mt-1 uppercase text-slate-700"><?= htmlspecialchars($type); ?></dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500">Email</dt>
                <dd class="mt-1 text-slate-700"><?= htmlspecialchars($organization['email'] ?? '—'); ?></dd>
            </div>
            <div>
                <dt class="font-medium text-slate-500">Phone</dt>
                <dd class="mt-1 text-slate-700"><?= htmlspecialchars($organization['phone'] ?? '—'); ?></dd>
            </div>
            <div class="md:col-span-2">
                <dt class="font-medium text-slate-500">Address</dt>
                <dd class="mt-1 text-slate-700 whitespace-pre-line"><?= nl2br(htmlspecialchars($organization['address'] ?? '—')); ?></dd>
            </div>
            <?php if (!empty($parent)): ?>
                <div class="md:col-span-2">
                    <dt class="font-medium text-slate-500">Parent Organization</dt>
                    <dd class="mt-1 text-slate-700"><?= htmlspecialchars($parent['name'] ?? ''); ?></dd>
                </div>
            <?php endif; ?>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-slate-700">Edit Organization Details</h3>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/update')); ?>" method="POST" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div>
                <label class="block text-sm font-medium text-slate-600">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($organization['name']); ?>" required class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($organization['email'] ?? ''); ?>" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($organization['phone'] ?? ''); ?>" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-600">Address</label>
                <textarea name="address" rows="3" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500"><?= htmlspecialchars($organization['address'] ?? ''); ?></textarea>
            </div>
            <div class="md:col-span-2 flex justify-end space-x-3">
                <a href="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type))); ?>" class="px-4 py-2 rounded-md border border-slate-300 text-slate-600">Back to List</a>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-md">Update Organization</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-slate-700">Add <?= htmlspecialchars($adminRoleLabel); ?></h3>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins')); ?>" method="POST" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div>
                <label class="block text-sm font-medium text-slate-600">Name</label>
                <input type="text" name="name" required class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Email</label>
                <input type="email" name="email" required class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Phone</label>
                <input type="text" name="phone" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Password</label>
                <input type="password" name="password" required minlength="8" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md">Add <?= htmlspecialchars($adminRoleLabel); ?></button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-700">Current <?= htmlspecialchars($adminRoleLabel); ?>s</h3>
            <span class="text-sm text-slate-500"><?= count($admins); ?> total</span>
        </div>
        <?php if (empty($admins)): ?>
            <p class="mt-4 text-sm text-slate-500">No administrators assigned yet.</p>
        <?php else: ?>
            <div class="mt-6 space-y-4">
                <?php foreach ($admins as $admin): ?>
                    <div class="border border-slate-200 rounded-lg p-4">
                        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins/' . rawurlencode($admin['hash_id']) . '/update')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase">Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($admin['name'] ?? ''); ?>" required class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? ''); ?>" required class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 uppercase">Phone</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? ''); ?>" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                            </div>
                            <div class="flex items-center mt-6 md:mt-8">
                                <button type="submit" class="px-3 py-2 bg-slate-900 text-white rounded-md text-sm">Update</button>
                            </div>
                        </form>
                        <div class="mt-3 flex items-center justify-between text-sm">
                            <div class="text-xs text-slate-500 uppercase">
                                Status:
                                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold <?= ($admin['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'; ?>">
                                    <?= htmlspecialchars(ucfirst($admin['status'] ?? 'active')); ?>
                                </span>
                            </div>
                            <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']) . '/admins/' . rawurlencode($admin['hash_id']) . '/toggle')); ?>" method="POST">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
                                <button type="submit" class="px-3 py-2 rounded-md text-sm <?= ($admin['status'] ?? 'active') === 'active' ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-emerald-600 text-white hover:bg-emerald-700'; ?>">
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
