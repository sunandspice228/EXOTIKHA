<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/users" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo lang('title_edit_staff'); ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?php echo URLROOT; ?>/admin/users_edit/<?php echo $data['admin']->id; ?>" method="POST" enctype="multipart/form-data">
            <div class="space-y-6">
                
                <div class="flex items-center justify-center mb-6">
                    <div class="relative">
                        <?php if(!empty($data['admin']->image)): ?>
                            <img src="<?php echo URLROOT . '/uploads/' . $data['admin']->image; ?>" class="w-24 h-24 rounded-full object-cover border-4 border-slate-100">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold text-3xl">
                                <?php echo strtoupper(substr($data['admin']->name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Nom Complet *</label>
                        <input type="text" name="name" required value="<?php echo $data['admin']->name; ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Email *</label>
                        <input type="email" name="email" required value="<?php echo $data['admin']->email; ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Nouveau Mot de passe</label>
                        <input type="password" name="password" placeholder="Laisser vide pour ne pas changer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Rôle</label>
                        <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="admin" <?php echo ($data['admin']->role == 'admin') ? 'selected' : ''; ?>>Administrateur</option>
                            <option value="editor" <?php echo ($data['admin']->role == 'editor') ? 'selected' : ''; ?>>Éditeur</option>
                            <option value="super_admin" <?php echo ($data['admin']->role == 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Changer Avatar</label>
                    <input type="file" name="image" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>