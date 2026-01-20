<?php ob_start(); ?>

<h1 class="text-2xl font-bold text-slate-800 mb-6">Gestion des Attributs</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <?php foreach(['Catégories' => 'categories', 'Tailles' => 'sizes', 'Couleurs' => 'colors'] as $label => $type): ?>
    
    <div class="bg-white p-5 rounded-lg shadow h-full flex flex-col">
        <h3 class="font-bold text-lg mb-4 text-slate-700 border-b pb-2"><?= $label ?></h3>
        
        <form action="<?= url('/admin/attributes/store') ?>" method="POST" class="mb-4 bg-slate-50 p-3 rounded">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="<?= $type ?>">
            <input type="text" name="name" placeholder="FR (ex: Rouge)" class="w-full border p-2 mb-2 rounded text-sm" required>
            <input type="text" name="name_en" placeholder="EN (ex: Red)" class="w-full border p-2 mb-2 rounded text-sm">
            <button class="w-full bg-slate-800 text-white py-1 rounded text-sm font-bold">Ajouter</button>
        </form>

        <ul class="space-y-2 overflow-y-auto flex-1 max-h-60">
            <?php foreach($$type as $item): ?>
            <li class="flex justify-between items-center bg-white border p-2 rounded hover:bg-slate-50">
                <div>
                    <span class="block text-sm font-bold text-slate-700"><?= e($item['name']) ?></span>
                    <span class="block text-xs text-slate-400"><?= e($item['name_en']) ?></span>
                </div>
                <a href="<?= url("/admin/attributes/delete/$type/" . $item['id']) ?>" class="text-red-300 hover:text-red-500">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php endforeach; ?>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>