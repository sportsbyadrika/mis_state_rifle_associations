<?php
$title = 'Finance - KSRA MIS';
$heading = 'Financial Overview';
ob_start();
?>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-1 card">
        <div class="px-6 py-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold section-heading">Financial Years</h2>
            <p class="text-sm text-muted">Switch between financial years for reporting.</p>
        </div>
        <ul class="divide-y divide-slate-200">
            <?php foreach ($years as $year): ?>
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-800"><?= htmlspecialchars($year['label']); ?></p>
                        <p class="text-xs text-muted"><?= htmlspecialchars($year['date_from']); ?> - <?= htmlspecialchars($year['date_to']); ?></p>
                    </div>
                    <?php if (!empty($year['is_current'])): ?>
                        <span class="text-xs font-semibold text-green-600">Current</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="xl:col-span-2 card overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold section-heading">Income &amp; Expense Ledger</h2>
            <p class="text-sm text-muted">Detailed entries for the selected financial year.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full table divide-y divide-slate-200">
                <thead class="table-header">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Head</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($entry['recorded_on']); ?></td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-800"><?= htmlspecialchars($entry['head_name']); ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?= $entry['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>"><?= htmlspecialchars(ucfirst($entry['type'])); ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-muted">₹<?= number_format((float) $entry['amount'], 2); ?></td>
                            <td class="px-6 py-4 text-sm text-muted"><?= htmlspecialchars($entry['notes'] ?? '--'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$slot = ob_get_clean();
include __DIR__ . '/../layouts/dashboard.php';
