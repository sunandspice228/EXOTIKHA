<?php ob_start(); ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mes Produits</h1>
    <a href="<?= url('/admin/products/create') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold hover:bg-indigo-700 transition">
        <i class="fa-solid fa-plus mr-2"></i> Ajouter
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold">
            <tr>
                <th class="p-4">Image</th>
                <th class="p-4">Nom</th>
                <th class="p-4">Prix</th>
                <th class="p-4">Stock</th>
                <th class="p-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            <?php foreach($products as $p): ?>
            <tr class="hover:bg-slate-50 group">
                <td class="p-4">
                    <img src="<?= $p['image'] ? url('/uploads/'.$p['image']) : 'https://dummyimage.com/50' ?>" class="w-12 h-12 object-cover rounded border">
                </td>
                <td class="p-4 font-bold text-slate-700"><?= e($p['name']) ?></td>
                <td class="p-4"><?= format_price($p['price']) ?></td>
                <td class="p-4">
                    <span class="<?= $p['stock'] < 5 ? 'text-red-500 font-bold' : 'text-green-600' ?>">
                        <?= $p['stock'] ?>
                    </span>
                </td>
                <td class="p-4 text-right space-x-2">
                    <a href="<?= url('/admin/products/edit/'.$p['id']) ?>" class="text-indigo-500 hover:text-indigo-700 font-bold">Edit</a>
                    <a href="<?= url('/admin/products/delete/'.$p['id']) ?>" 
                       onclick="return confirm('Supprimer ce produit ?')"
                       class="text-red-400 hover:text-red-600">
                       <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>