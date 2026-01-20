<?php ob_start(); ?>

<h1 class="text-2xl font-bold text-slate-800 mb-6">Gestion des Commandes</h1>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Référence</th>
                <th class="p-4">Client</th>
                <th class="p-4">Total</th>
                <th class="p-4">Date</th>
                <th class="p-4">Statut</th>
                <th class="p-4">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            <?php foreach($orders as $o): ?>
            <tr class="hover:bg-slate-50">
                <td class="p-4 font-bold text-indigo-600">#<?= e($o['reference']) ?></td>
                <td class="p-4">
                    <div class="font-bold"><?= e($o['user_name'] ?? 'Invité') ?></div>
                    <div class="text-xs text-slate-400"><?= e($o['email']) ?></div>
                </td>
                <td class="p-4 font-bold"><?= format_price($o['total']) ?></td>
                <td class="p-4 text-slate-500"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                
                <td class="p-4">
                    <form action="<?= url('/admin/orders/update/'.$o['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <select name="status" onchange="this.form.submit()" class="border rounded p-1 text-xs font-bold 
                            <?= $o['status'] == 'Livré' ? 'text-green-600 bg-green-50' : 'text-amber-600 bg-amber-50' ?>">
                            <?php foreach(['En attente', 'Payé', 'Expédié', 'Livré', 'Annulé'] as $st): ?>
                                <option value="<?= $st ?>" <?= $o['status'] == $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </td>
                
                <td class="p-4 text-slate-400">
                    <i class="fa-solid fa-eye cursor-pointer hover:text-indigo-500"></i>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>