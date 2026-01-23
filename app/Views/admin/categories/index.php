<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-8">Gestion des Catégories</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm sticky top-24">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2">Nouvelle Catégorie</h3>
                <form action="<?php echo URLROOT; ?>/admin/categories" method="POST">
                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Nom</label>
                        <input type="text" name="name" class="w-full rounded-xl border-slate-200 focus:ring-primary" placeholder="Ex: Accessoires" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 focus:ring-primary"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-800 text-white py-3 rounded-xl font-bold hover:bg-primary transition shadow-lg">Ajouter</button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-xs font-black uppercase text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Nom</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        <?php foreach($data['categories'] as $cat): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-bold text-slate-800"><?php echo $cat->name; ?></td>
                                <td class="px-6 py-4 text-slate-500 italic truncate max-w-xs"><?php echo $cat->description; ?></td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?php echo URLROOT; ?>/admin/categories_delete/<?php echo $cat->id; ?>" onclick="return confirm('Supprimer cette catégorie ?');" class="text-red-400 hover:text-red-600 transition">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if(empty($data['categories'])): ?>
                    <div class="p-8 text-center text-slate-400 italic">Aucune catégorie pour le moment.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>