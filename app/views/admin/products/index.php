<?php ob_start(); ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestion des Produits</h1>
        <p class="text-sm text-slate-500">Gérez votre catalogue, vos stocks et vos promotions.</p>
    </div>
    <a href="<?= url('/admin/products/create') ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nouveau Produit
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden border border-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                <?php if(empty($products)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            <i class="fa-regular fa-folder-open fa-2x mb-2 block"></i>
                            Aucun produit dans la base de données.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($products as $p): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 bg-slate-100 rounded overflow-hidden border border-slate-200">
                                    <?php if(!empty($p['image'])): ?>
                                        <img class="h-12 w-12 object-cover" src="<?= url('/uploads/'.$p['image']) ?>" alt="">
                                    <?php else: ?>
                                        <div class="h-full w-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900"><?= e($p['name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= e($p['name_en']) ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800">
                                <?= e($p['category']) ?>
                            </span>
                            <?php if(!empty($p['type'])): ?>
                                <span class="text-xs text-slate-400 ml-1"><?= e($p['type']) ?></span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($p['is_promo']): ?>
                                <div class="text-sm font-bold text-red-600"><?= number_format($p['promo_price'], 2) ?> GHS</div>
                                <div class="text-xs text-slate-400 line-through"><?= number_format($p['price'], 2) ?> GHS</div>
                            <?php else: ?>
                                <div class="text-sm font-bold text-slate-900"><?= number_format($p['price'], 2) ?> GHS</div>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($p['stock'] <= 5): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Critique : <?= $p['stock'] ?>
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    En stock : <?= $p['stock'] ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="<?= url('/admin/products/edit/'.$p['id']) ?>" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded transition mr-2">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="<?= url('/admin/products/delete/'.$p['id']) ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')" 
                               class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>