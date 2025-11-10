<?php
$title = 'Memberships - KSRA MIS';
$heading = 'Membership Management';
ob_start();
?>
<div class="card overflow-hidden">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
        <div>
            <h2 class="text-lg font-semibold section-heading">Membership Overview</h2>
            <p class="text-sm text-muted">Track and manage membership applications across organizations.</p>
        </div>
        <a href="<?= htmlspecialchars(url_to('memberships/apply')); ?>" class="button-primary">Apply for Membership</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full table divide-y divide-slate-200">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Organization</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Requested</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Approved</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($memberships as $membership): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-800"><?= htmlspecialchars($membership['organization_name']); ?></td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($membership['membership_type']); ?></td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?php echo match($membership['status']) {
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-amber-100 text-amber-700'
                            }; ?>"><?= htmlspecialchars(ucfirst($membership['status'])); ?></span>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($membership['requested_on']); ?></td>
                        <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($membership['approved_on'] ?? '--'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
