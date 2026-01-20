<?php ob_start(); ?>

<h1 class="text-3xl font-bold text-slate-800 mb-8">Tableau de Bord</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-indigo-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Produits</p>
                <p class="text-2xl font-bold text-slate-800"><?= $products ?></p>
            </div>
            <i class="fa-solid fa-shirt text-3xl text-indigo-200"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-emerald-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Commandes</p>
                <p class="text-2xl font-bold text-slate-800"><?= $orders ?></p>
            </div>
            <i class="fa-solid fa-cart-shopping text-3xl text-emerald-200"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-amber-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-bold uppercase">Revenus (Est.)</p>
                <p class="text-2xl font-bold text-slate-800">-- FCFA</p>
            </div>
            <i class="fa-solid fa-wallet text-3xl text-amber-200"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 font-bold text-slate-700">
        Dernières commandes
    </div>
    <table class="w-full text-left text-sm text-slate-600">
        <thead class="bg-slate-50 uppercase text-xs font-bold text-slate-500">
            <tr>
                <th class="px-6 py-3">Réf</th>
                <th class="px-6 py-3">Client</th>
                <th class="px-6 py-3">Total</th>
                <th class="px-6 py-3">Statut</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach($recent_orders as $o): ?>
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-3 font-medium text-indigo-600">#<?= e($o['reference']) ?></td>
                <td class="px-6 py-3"><?= e($o['user_name'] ?? 'Invité') ?></td>
                <td class="px-6 py-3 font-bold"><?= format_price($o['total']) ?></td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-600">
                        <?= e($o['status']) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>