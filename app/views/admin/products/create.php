<?php ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Ajouter un Produit</h1>
        <a href="<?= url('/admin/products') ?>" class="text-slate-500 hover:text-slate-700 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="<?= url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-8 border border-slate-200">
        <?= csrf_field() ?>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Informations Générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nom du produit (FR) *</label>
                    <input type="text" name="name" required class="w-full border border-slate-300 p-2.5 rounded focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Ex: Robe en Soie">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Product Name (EN)</label>
                    <input type="text" name="name_en" class="w-full border border-slate-300 p-2.5 rounded bg-slate-50 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Ex: Silk Dress">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description (FR)</label>
                    <textarea name="description" rows="4" class="w-full border border-slate-300 p-2.5 rounded focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description (EN)</label>
                    <textarea name="description_en" rows="4" class="w-full border border-slate-300 p-2.5 rounded bg-slate-50 focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 p-6 rounded-lg border border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Prix & Inventaire</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Prix (GHS) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400">GH₵</span>
                        <input type="number" step="0.01" name="price" required class="w-full pl-10 border border-slate-300 p-2.5 rounded font-bold text-slate-800">
                    </div>
                </div>

                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_promo" id="togglePromo" value="1" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        <span class="ms-3 text-sm font-medium text-slate-700">En Promotion ?</span>
                    </label>
                </div>

                <div id="promoField" class="hidden">
                    <label class="block text-sm font-bold text-red-600 mb-1">Prix Promo (GHS)</label>
                    <input type="number" step="0.01" name="promo_price" class="w-full border-2 border-red-300 p-2.5 rounded text-red-600 font-bold bg-white">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-bold text-slate-700 mb-1">Stock Initial *</label>
                <input type="number" name="stock" required value="10" class="w-full md:w-1/3 border border-slate-300 p-2.5 rounded">
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Images du Produit</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Image Principale (Couverture) *</label>
                    <input type="file" name="image" required accept="image/*" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100
                    "/>
                    <p class="text-xs text-slate-500 mt-1">Image qui s'affichera en premier.</p>
                </div>

                <div class="bg-indigo-50 p-4 rounded border border-indigo-100 border-dashed">
                    <label class="block text-sm font-bold text-indigo-700 mb-2">
                        <i class="fa-regular fa-images me-1"></i> Galerie (Plusieurs photos)
                    </label>
                    
                    <input type="file" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-white file:text-indigo-700
                        hover:file:bg-slate-100
                    "/>
                    
                    <p class="text-xs text-indigo-500 mt-2">
                        <strong>Astuce :</strong> Maintenez la touche <kbd class="bg-white px-1 rounded border">CTRL</kbd> enfoncée pour sélectionner plusieurs images d'un coup.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Catégorie</label>
                <select name="category" class="w-full border border-slate-300 p-2.5 rounded bg-white">
                    <?php foreach($categories as $c): ?>
                        <option value="<?= e($c['name']) ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Type</label>
                <select name="type" class="w-full border border-slate-300 p-2.5 rounded bg-white">
                    <option value="">-- Aucun --</option>
                    <?php foreach($types as $t): ?>
                        <option value="<?= e($t['name']) ?>"><?= e($t['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Tailles disponibles</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach($sizes as $s): ?>
                    <label class="inline-flex items-center bg-slate-50 px-3 py-1.5 rounded border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <input type="checkbox" name="sizes[]" value="<?= e($s['name']) ?>" class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="ml-2 text-sm text-slate-700"><?= e($s['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Couleurs disponibles</label>
            <div class="flex flex-wrap gap-2">
                <?php foreach($colors as $c): ?>
                    <label class="inline-flex items-center bg-slate-50 px-3 py-1.5 rounded border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <input type="checkbox" name="colors[]" value="<?= e($c['name']) ?>" class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="ml-2 text-sm text-slate-700"><?= e($c['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3 px-4 rounded-lg hover:bg-slate-800 shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                <i class="fa-solid fa-save me-2"></i> Enregistrer le Produit
            </button>
        </div>

    </form>
</div>

<script>
    // Script promo
    const toggle = document.getElementById('togglePromo');
    const field = document.getElementById('promoField');
    toggle.addEventListener('change', function() {
        if(this.checked) field.classList.remove('hidden');
        else field.classList.add('hidden');
    });
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>