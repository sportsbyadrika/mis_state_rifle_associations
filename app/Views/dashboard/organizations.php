<?php
$title = 'Organizations - KSRA MIS';
$heading = ucfirst($type) . ' Management';
ob_start();
?>
<div class="bg-white rounded-xl shadow">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <div>
            <h2 class="text-lg font-semibold text-slate-700"><?= htmlspecialchars(strtoupper($type)); ?> Directory</h2>
            <p class="text-sm text-slate-500">Manage organizational details and administrators.</p>
        </div>
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="px-4 py-2 bg-slate-900 text-white rounded-md">Add New</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($organizations as $organization): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700 font-medium"><?= htmlspecialchars($organization['name']); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($organization['email'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($organization['phone'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($organization['address'] ?? ''); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            <a href="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($organization['hash_id']))); ?>" class="text-slate-900 font-medium">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="create-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-700">Create <?= htmlspecialchars(strtoupper($type)); ?></h3>
            <button onclick="document.getElementById('create-modal').classList.add('hidden')" class="text-slate-500">&times;</button>
        </div>
        <form action="<?= htmlspecialchars(url_to('organizations/' . rawurlencode($type))); ?>" method="POST" class="mt-4 space-y-4">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf); ?>">
            <div>
                <label class="block text-sm font-medium text-slate-600">Name</label>
                <input type="text" name="name" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600">Email</label>
                    <input type="email" name="email" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600">Phone</label>
                    <input type="tel" name="phone" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600">Address</label>
                <textarea name="address" class="mt-1 w-full rounded border-slate-300 focus:border-slate-500 focus:ring-slate-500" rows="3"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')" class="px-4 py-2 rounded-md border border-slate-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-md">Create</button>
            </div>
        </form>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
