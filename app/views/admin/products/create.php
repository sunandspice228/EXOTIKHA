<?php ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Ajouter un Produit</h1>
        <a href="<?= url('/admin/products') ?>" class="text-slate-500 hover:text-slate-700">Annuler</a>
    </div>

    <form action="<?= url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nom (FR) *</label>
                <input type="text" name="name" required class="w-full border p-2 rounded focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name (EN)</label>
                <input type="text" name="name_en" class="w-full border p-2 rounded focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Description (FR)</label>
                <textarea name="description" rows="3" class="w-full border p-2 rounded focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Description (EN)</label>
                <textarea name="description_en" rows="3" class="w-full border p-2 rounded focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Prix (FCFA) *</label>
                <input type="number" name="price" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Stock *</label>
                <input type="number" name="stock" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Image</label>
                <input type="file" name="image" class="w-full border p-2 rounded text-sm bg-slate-50">
            </div>
        </div>

        <hr>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Catégorie</label>
                <select name="category" class="w-full border p-2 rounded bg-white">
                    <?php foreach($categories as $c): ?>
                        <option value="<?= e($c['name']) ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Genre</label>
                <select name="gender" class="w-full border p-2 rounded bg-white">
                    <option value="Unisexe">Unisexe</option>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                    <option value="Enfant">Enfant</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Tailles</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach($sizes as $s): ?>
                    <label class="flex items-center space-x-2 bg-slate-50 px-3 py-1 rounded border cursor-pointer">
                        <input type="checkbox" name="sizes[]" value="<?= e($s['name']) ?>" class="rounded text-indigo-600">
                        <span class="text-sm"><?= e($s['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Couleurs</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach($colors as $c): ?>
                    <label class="flex items-center space-x-2 bg-slate-50 px-3 py-1 rounded border cursor-pointer">
                        <input type="checkbox" name="colors[]" value="<?= e($c['name']) ?>" class="rounded text-indigo-600">
                        <span class="text-sm"><?= e($c['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="w-full bg-indigo-600 text-white font-bold py-3 rounded hover:bg-indigo-700 shadow-lg">
            Enregistrer le produit
        </button>

    </form>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>