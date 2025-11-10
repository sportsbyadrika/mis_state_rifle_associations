<?php
$title = 'Memberships - KSRA MIS';
$heading = 'Membership Management';
ob_start();
?>
<div class="bg-white rounded-xl shadow">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
        <div>
            <h2 class="text-lg font-semibold text-slate-700">Membership Overview</h2>
            <p class="text-sm text-slate-500">Track and manage membership applications across organizations.</p>
        </div>
        <a href="/memberships/apply" class="px-4 py-2 bg-slate-900 text-white rounded-md">Apply for Membership</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Organization</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Requested</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Approved</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($memberships as $membership): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-700 font-medium"><?= htmlspecialchars($membership['organization_name']); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($membership['membership_type']); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?php echo match($membership['status']) {
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-amber-100 text-amber-700'
                            }; ?>"><?= htmlspecialchars(ucfirst($membership['status'])); ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($membership['requested_on']); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?= htmlspecialchars($membership['approved_on'] ?? '--'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
