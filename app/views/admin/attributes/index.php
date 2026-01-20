<?php ob_start(); ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Configuration des Attributs</h1>
    <p class="text-slate-500 text-sm">Gérez ici les options de vos produits (Tailles, Couleurs, etc.).</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <?php 
    // Configuration des blocs
    $sections = [
        ['key' => 'categories', 'label' => 'Catégories', 'icon' => 'fa-tags', 'placeholder' => 'Ex: Robes'],
        ['key' => 'types',      'label' => 'Types',      'icon' => 'fa-layer-group', 'placeholder' => 'Ex: Longue'],
        ['key' => 'sizes',      'label' => 'Tailles',    'icon' => 'fa-ruler', 'placeholder' => 'Ex: XL'],
        ['key' => 'colors',     'label' => 'Couleurs',   'icon' => 'fa-palette', 'placeholder' => 'Ex: Rouge Bordeau']
    ];
    ?>

    <?php foreach($sections as $s): $key = $s['key']; ?>
    
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 flex flex-col h-full">
        <div class="p-4 border-b bg-slate-50 flex items-center justify-between">
            <h3 class="font-bold text-slate-700">
                <i class="fa-solid <?= $s['icon'] ?> mr-2 text-indigo-500"></i><?= $s['label'] ?>
            </h3>
            <span class="text-xs font-mono bg-slate-200 px-2 py-0.5 rounded text-slate-600">
                <?= count($$key) ?>
            </span>
        </div>
        
        <div class="p-4 bg-slate-50 border-b">
            <form action="<?= url('/admin/attributes/store') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="type" value="<?= $key ?>">
                
                <div class="mb-2">
                    <input type="text" name="name" id="fr_<?= $key ?>" 
                           placeholder="<?= $s['placeholder'] ?> (FR)" 
                           class="w-full text-sm border p-2 rounded focus:ring-2 focus:ring-indigo-500 outline-none" required>
                </div>

                <div class="flex gap-1 mb-2">
                    <input type="text" name="name_en" id="en_<?= $key ?>" 
                           placeholder="Anglais..." 
                           class="w-full text-sm border p-2 rounded bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
                    
                    <button type="button" onclick="translateAttr('fr_<?= $key ?>', 'en_<?= $key ?>')" 
                            class="bg-indigo-100 text-indigo-600 px-2 rounded hover:bg-indigo-200 transition" 
                            title="Traduire automatiquement">
                        <i class="fa-solid fa-language"></i>
                    </button>
                </div>

                <button class="w-full bg-slate-800 text-white text-xs font-bold py-2 rounded hover:bg-slate-700 transition">
                    AJOUTER
                </button>
            </form>
        </div>

        <div class="flex-1 overflow-y-auto max-h-60 p-2 space-y-1 custom-scrollbar">
            <?php if(empty($$key)): ?>
                <div class="text-center py-6 text-slate-400 text-xs">Aucun élément</div>
            <?php else: ?>
                <?php foreach($$key as $item): ?>
                <div class="group flex justify-between items-center p-2 hover:bg-slate-50 rounded border border-transparent hover:border-slate-100 transition">
                    <div>
                        <div class="text-sm font-bold text-slate-700"><?= e($item['name']) ?></div>
                        <div class="text-xs text-slate-400 italic"><?= e($item['name_en']) ?></div>
                    </div>
                    <a href="<?= url("/admin/attributes/delete/$key/" . $item['id']) ?>" 
                       onclick="return confirm('Supprimer ?')"
                       class="text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition px-2">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php endforeach; ?>
</div>

<script>
async function translateAttr(sourceId, targetId) {
    const source = document.getElementById(sourceId);
    const target = document.getElementById(targetId);
    
    if(!source.value) {
        source.focus();
        return;
    }

    target.placeholder = "...";
    target.classList.add('bg-indigo-50');

    try {
        const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(source.value)}&langpair=fr|en`);
        const data = await res.json();
        if(data.responseData.translatedText) {
            target.value = data.responseData.translatedText;
        }
    } catch(e) {
        alert('Erreur traduction');
    } finally {
        target.classList.remove('bg-indigo-50');
    }
}
</script>

<style>
/* Scrollbar fine pour les listes */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>