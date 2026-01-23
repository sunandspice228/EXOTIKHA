<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Ajouter un Produit</h1>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_add" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Détails Principaux</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Nom du produit</label>
                        <input type="text" name="name" class="w-full rounded-xl border-slate-200 focus:ring-primary" placeholder="Ex: Robe en Soie" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Description</label>
                        <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 focus:ring-primary"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Prix (<?php echo CURRENCY_SYMBOL; ?>)</label>
                            <input type="number" step="0.01" name="price" class="w-full rounded-xl border-slate-200 focus:ring-primary font-bold text-lg" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Prix Promo</label>
                            <input type="number" step="0.01" name="promo_price" class="w-full rounded-xl border-slate-200 focus:ring-primary text-red-500 font-bold" placeholder="Optionnel">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Caractéristiques</h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Genre / Cible</label>
                            <select name="gender" class="w-full rounded-xl border-slate-200 focus:ring-primary bg-slate-50">
                                <option value="uni">Unisexe</option>
                                <option value="women">Femme</option>
                                <option value="men">Homme</option>
                                <option value="couple">Couple</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Type de Produit</label>
                            <select name="type_id" class="w-full rounded-xl border-slate-200 focus:ring-primary bg-slate-50">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="block text-xs font-bold uppercase text-slate-400 mb-3">Attributs & Variantes</label>
                    <div class="bg-slate-50 p-4 rounded-xl space-y-4">
                        <?php if(empty($data['attributes'])): ?>
                            <p class="text-xs text-slate-400 italic">Aucun attribut configuré. Allez dans "Attributs" pour en créer.</p>
                        <?php else: ?>
                            <?php foreach($data['attributes'] as $attr): ?>
                                <div class="flex items-center gap-4">
                                    <label class="w-1/3 text-sm font-bold text-slate-700"><?php echo $attr->name; ?> :</label>
                                    <input type="text" name="attributes[<?php echo $attr->id; ?>]" class="flex-1 rounded-lg border-slate-200 text-sm" placeholder="Ex: S, M, L ou Rouge, Bleu...">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Organisation</h3>
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Catégorie</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-200 focus:ring-primary bg-slate-50" required>
                            <?php foreach($data['categories'] as $cat): ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Stock</label>
                        <input type="number" name="stock" class="w-full rounded-xl border-slate-200 focus:ring-primary font-mono" value="10">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Médias</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Image Principale</label>
                        <input type="file" name="image" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Galerie Photos</label>
                        <input type="file" name="gallery[]" multiple class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-slate-800 transition">
                    Enregistrer
                </button>
            </div>

        </div>
    </form>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>