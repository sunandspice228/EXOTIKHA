<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-8">Configuration Produits</h1>
    <?php flash('admin_msg'); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2 flex items-center gap-2">
                    <i class="fas fa-ruler-combined text-primary"></i> Attributs (Variantes)
                </h3>
                <form action="<?php echo URLROOT; ?>/admin/attributes" method="POST" class="flex gap-2 mb-6">
                    <input type="text" name="name" placeholder="Nom (ex: Taille)" class="flex-1 rounded-lg border-slate-200 text-sm focus:ring-primary" required>
                    <input type="text" name="value" placeholder="Valeurs (Optionnel)" class="flex-1 rounded-lg border-slate-200 text-sm focus:ring-primary">
                    <button type="submit" name="add_attribute" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold hover:bg-primary transition"><i class="fas fa-plus"></i></button>
                </form>

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400">
                            <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3 text-right">Action</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            <?php foreach($data['attributes'] as $attr): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold text-slate-700"><?php echo $attr->name; ?></td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="<?php echo URLROOT; ?>/admin/attributes_delete/<?php echo $attr->id; ?>" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-4 border-b border-slate-50 pb-2 flex items-center gap-2">
                    <i class="fas fa-tags text-secondary"></i> Types de Produits
                </h3>
                <form action="<?php echo URLROOT; ?>/admin/attributes" method="POST" class="flex gap-2 mb-6">
                    <input type="text" name="type_name" placeholder="Nom (ex: Robe de Soirée)" class="flex-1 rounded-lg border-slate-200 text-sm focus:ring-secondary" required>
                    <button type="submit" name="add_type" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold hover:bg-secondary transition"><i class="fas fa-plus"></i></button>
                </form>

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400">
                            <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3 text-right">Action</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            <?php foreach($data['types'] as $type): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold text-slate-700"><?php echo $type->name; ?></td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="<?php echo URLROOT; ?>/admin/types_delete/<?php echo $type->id; ?>" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>