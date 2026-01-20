<?php 
// On transforme les strings "S, M" en array pour cocher les cases
$currentSizes = array_map('trim', explode(',', $product['sizes'] ?? ''));
$currentColors = array_map('trim', explode(',', $product['colors'] ?? ''));
ob_start(); 
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Modifier : <?= e($product['name']) ?></h1>
        <a href="<?= url('/admin/products') ?>" class="text-slate-500 hover:text-slate-700">Annuler</a>
    </div>

    <form action="<?= url('/admin/products/update/'.$product['id']) ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nom (FR)</label>
                <input type="text" name="name" value="<?= e($product['name']) ?>" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name (EN)</label>
                <input type="text" name="name_en" value="<?= e($product['name_en']) ?>" class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Description (FR)</label>
                <textarea name="description" rows="3" class="w-full border p-2 rounded"><?= e($product['description']) ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Description (EN)</label>
                <textarea name="description_en" rows="3" class="w-full border p-2 rounded"><?= e($product['description_en']) ?></textarea>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix</label>
                <input type="number" name="price" value="<?= $product['price'] ?>" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Stock</label>
                <input type="number" name="stock" value="<?= $product['stock'] ?>" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Image</label>
                <input type="file" name="image" class="w-full border p-2 rounded text-sm">
            </div>
        </div>

        <hr>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Catégorie</label>
                <select name="category" class="w-full border p-2 rounded">
                    <?php foreach($categories as $c): ?>
                        <option value="<?= e($c['name']) ?>" <?= $product['category'] == $c['name'] ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Genre</label>
                <select name="gender" class="w-full border p-2 rounded">
                    <?php foreach(['Unisexe', 'Homme', 'Femme', 'Enfant'] as $g): ?>
                        <option value="<?= $g ?>" <?= $product['gender'] == $g ? 'selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Tailles</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach($sizes as $s): ?>
                    <label class="flex items-center space-x-2 bg-slate-50 px-3 py-1 rounded border">
                        <input type="checkbox" name="sizes[]" value="<?= e($s['name']) ?>" class="rounded text-indigo-600"
                        <?= in_array($s['name'], $currentSizes) ? 'checked' : '' ?>>
                        <span class="text-sm"><?= e($s['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Couleurs</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach($colors as $c): ?>
                    <label class="flex items-center space-x-2 bg-slate-50 px-3 py-1 rounded border">
                        <input type="checkbox" name="colors[]" value="<?= e($c['name']) ?>" class="rounded text-indigo-600"
                        <?= in_array($c['name'], $currentColors) ? 'checked' : '' ?>>
                        <span class="text-sm"><?= e($c['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="w-full bg-indigo-600 text-white font-bold py-3 rounded hover:bg-indigo-700">Mettre à jour</button>

    </form>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>