<?php 
// Préparation des tableaux pour les checkboxes
$currentSizes = array_map('trim', explode(',', $product['sizes'] ?? ''));
$currentColors = array_map('trim', explode(',', $product['colors'] ?? ''));
ob_start(); 
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Modifier : <?= e($product['name']) ?></h1>
        <a href="<?= url('/admin/products') ?>" class="text-slate-500 hover:text-slate-700 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="<?= url('/admin/products/update/'.$product['id']) ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-8 border border-slate-200">
        <?= csrf_field() ?>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nom (FR)</label>
                    <input type="text" name="name" value="<?= e($product['name']) ?>" required class="w-full border border-slate-300 p-2.5 rounded">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Name (EN)</label>
                    <input type="text" name="name_en" value="<?= e($product['name_en']) ?>" class="w-full border border-slate-300 p-2.5 rounded bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description (FR)</label>
                    <textarea name="description" rows="4" class="w-full border border-slate-300 p-2.5 rounded"><?= e($product['description']) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description (EN)</label>
                    <textarea name="description_en" rows="4" class="w-full border border-slate-300 p-2.5 rounded bg-slate-50"><?= e($product['description_en']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-lg border border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Tarification</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Prix (GHS)</label>
                    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required class="w-full border border-slate-300 p-2.5 rounded font-bold">
                </div>
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_promo" id="togglePromo" value="1" class="sr-only peer" <?= $product['is_promo'] ? 'checked' : '' ?>>
                        <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        <span class="ms-3 text-sm font-medium text-slate-700">En Promo</span>
                    </label>
                </div>
                <div id="promoField" class="<?= $product['is_promo'] ? '' : 'hidden' ?>">
                    <label class="block text-sm font-bold text-red-600 mb-1">Prix Promo</label>
                    <input type="number" step="0.01" name="promo_price" value="<?= $product['promo_price'] ?>" class="w-full border-2 border-red-300 p-2.5 rounded text-red-600 font-bold bg-white">
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Images & Médias</h2>
            
            <div class="flex items-start gap-6 mb-6 p-4 border rounded bg-slate-50">
                <div class="w-24 h-24 flex-shrink-0 bg-white border rounded flex items-center justify-center overflow-hidden">
                    <?php if(!empty($product['image'])): ?>
                        <img src="<?= url('/uploads/'.$product['image']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-xs text-slate-400">Aucune</span>
                    <?php endif; ?>
                </div>
                <div class="flex-grow">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Changer l'image de couverture</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-indigo-50"/>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-3">Galerie Actuelle (Cliquez sur la croix pour supprimer)</label>
                
                <?php if(!empty($gallery)): ?>
                    <div class="flex flex-wrap gap-4">
                        <?php foreach($gallery as $img): ?>
                            <div class="relative group w-24 h-24">
                                <img src="<?= url('/uploads/'.$img['image']) ?>" class="w-full h-full object-cover rounded-lg border border-slate-200 shadow-sm">
                                
                                <a href="<?= url('/admin/products/image/delete/'.$img['id']) ?>" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette image ?')"
                                   class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md transform scale-0 group-hover:scale-100 transition duration-200 hover:bg-red-600 z-10"
                                   title="Supprimer l'image">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-slate-400 italic bg-slate-50 p-3 rounded border border-dashed border-slate-200">Aucune image supplémentaire dans la galerie.</p>
                <?php endif; ?>
            </div>

            <div class="bg-indigo-50 p-4 rounded border border-indigo-100 border-dashed">
                <label class="block text-sm font-bold text-indigo-700 mb-1">
                    <i class="fa-regular fa-square-plus me-1"></i> Ajouter des photos à la galerie
                </label>
                <input type="file" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-slate-100"/>
                <p class="text-xs text-indigo-500 mt-2">
                    Maintenez <strong>CTRL</strong> (ou CMD) pour sélectionner plusieurs images.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Stock</label>
                <input type="number" name="stock" value="<?= $product['stock'] ?>" class="w-full border border-slate-300 p-2.5 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Catégorie</label>
                <select name="category" class="w-full border border-slate-300 p-2.5 rounded bg-white">
                    <?php foreach($categories as $c): ?>
                        <option value="<?= e($c['name']) ?>" <?= $product['category'] == $c['name'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Type</label>
                <select name="type" class="w-full border border-slate-300 p-2.5 rounded bg-white">
                    <option value="">-- Aucun --</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= e($t['name']) ?>" <?= $product['type'] == $t['name'] ? 'selected' : '' ?>><?= e($t['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-2">Tailles</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach($sizes as $s): ?>
                    <label class="inline-flex items-center bg-slate-50 px-3 py-1.5 rounded border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <input type="checkbox" name="sizes[]" value="<?= e($s['name']) ?>" <?= in_array($s['name'], $currentSizes) ? 'checked' : '' ?> class="rounded text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm"><?= e($s['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-2">Couleurs</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach($colors as $c): ?>
                    <label class="inline-flex items-center bg-slate-50 px-3 py-1.5 rounded border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <input type="checkbox" name="colors[]" value="<?= e($c['name']) ?>" <?= in_array($c['name'], $currentColors) ? 'checked' : '' ?> class="rounded text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm"><?= e($c['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3 px-4 rounded-lg hover:bg-slate-800 shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                Mettre à jour le Produit
            </button>
        </div>

    </form>
</div>

<script>
    const toggle = document.getElementById('togglePromo');
    const field = document.getElementById('promoField');
    toggle.addEventListener('change', function() {
        if(this.checked) field.classList.remove('hidden');
        else field.classList.add('hidden');
    });
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>