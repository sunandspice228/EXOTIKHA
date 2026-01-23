<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Modifier : <?php echo $data['product']->name; ?></h1>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $data['product']->id; ?>" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Informations</h3>
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Nom</label>
                        <input type="text" name="name" value="<?php echo $data['product']->name; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Description</label>
                        <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 focus:ring-primary"><?php echo $data['product']->description; ?></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Prix</label>
                            <input type="number" step="0.01" name="price" value="<?php echo $data['product']->price; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Promo</label>
                            <input type="number" step="0.01" name="promo_price" value="<?php echo $data['product']->promo_price; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary text-red-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Caractéristiques</h3>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Genre</label>
                            <select name="gender" class="w-full rounded-xl border-slate-200 bg-slate-50">
                                <option value="uni" <?php echo $data['product']->gender == 'uni' ? 'selected' : ''; ?>>Unisexe</option>
                                <option value="women" <?php echo $data['product']->gender == 'women' ? 'selected' : ''; ?>>Femme</option>
                                <option value="men" <?php echo $data['product']->gender == 'men' ? 'selected' : ''; ?>>Homme</option>
                                <option value="couple" <?php echo $data['product']->gender == 'couple' ? 'selected' : ''; ?>>Couple</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Type</label>
                            <select name="type_id" class="w-full rounded-xl border-slate-200 bg-slate-50">
                                <option value="">-- Aucun --</option>
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>" <?php echo $data['product']->type_id == $type->id ? 'selected' : ''; ?>>
                                        <?php echo $type->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label class="block text-xs font-bold uppercase text-slate-400 mb-3">Attributs</label>
                    <div class="bg-slate-50 p-4 rounded-xl space-y-4">
                        <?php foreach($data['attributes'] as $attr): ?>
                            <?php 
                                // Chercher si cet attribut a déjà une valeur pour ce produit
                                $val = '';
                                foreach($data['current_attributes'] as $curr){
                                    if($curr->attribute_id == $attr->id) $val = $curr->value;
                                }
                            ?>
                            <div class="flex items-center gap-4">
                                <label class="w-1/3 text-sm font-bold text-slate-700"><?php echo $attr->name; ?> :</label>
                                <input type="text" name="attributes[<?php echo $attr->id; ?>]" value="<?php echo $val; ?>" class="flex-1 rounded-lg border-slate-200 text-sm">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Stock & Catégorie</h3>
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Catégorie</label>
                        <select name="category_id" class="w-full rounded-xl border-slate-200">
                            <?php foreach($data['categories'] as $cat): ?>
                                <option value="<?php echo $cat->id; ?>" <?php echo $data['product']->category_id == $cat->id ? 'selected' : ''; ?>>
                                    <?php echo $cat->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Stock</label>
                        <input type="number" name="stock" value="<?php echo $data['product']->stock; ?>" class="w-full rounded-xl border-slate-200 font-mono">
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Galerie Actuelle</h3>
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        <?php foreach($data['gallery'] as $img): ?>
                            <div class="relative group">
                                <img src="<?php echo URLROOT . '/public/' . $img->image; ?>" class="w-full h-16 object-cover rounded-lg">
                                <a href="<?php echo URLROOT; ?>/admin/gallery_delete/<?php echo $img->id; ?>/<?php echo $data['product']->id; ?>" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 rounded-lg transition" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Ajouter des images</label>
                    <input type="file" name="gallery[]" multiple class="block w-full text-xs text-slate-500 file:bg-primary/10 file:text-primary file:rounded-full file:border-0 file:px-4 file:py-2">
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-slate-800 transition">
                    Sauvegarder les modifications
                </button>
            </div>

        </div>
    </form>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>