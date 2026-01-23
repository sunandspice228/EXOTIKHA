<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Catalogue Produits</h1>
            <p class="text-sm text-slate-500">Gérez votre inventaire et vos prix.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/products_add" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-800 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter un Produit
        </a>
    </div>

    <?php flash('product_message'); ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs font-black uppercase text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Produit</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Prix</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    <?php foreach($data['products'] as $product): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200">
                                        <img src="<?php echo URLROOT . '/public/' . $product->image; ?>" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800"><?php echo $product->name; ?></p>
                                        <p class="text-xs text-slate-400">ID: <?php echo $product->id; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    <?php echo $product->category_name; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                <?php echo CURRENCY_SYMBOL . number_format($product->price, 2); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($product->stock > 5): ?>
                                    <span class="text-green-600 font-bold flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span> <?php echo $product->stock; ?></span>
                                <?php elseif($product->stock > 0): ?>
                                    <span class="text-orange-500 font-bold flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span> <?php echo $product->stock; ?></span>
                                <?php else: ?>
                                    <span class="text-red-500 font-bold flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Rupture</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $product->id; ?>" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/admin/products_delete/<?php echo $product->id; ?>" onclick="return confirm('Supprimer ce produit ?');" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>