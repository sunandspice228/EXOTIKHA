<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/users" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo lang('title_add_staff'); ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?php echo URLROOT; ?>/admin/users_add" method="POST" enctype="multipart/form-data">
            <div class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Nom Complet *</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Email *</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Mot de passe *</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Rôle</label>
                        <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                            <option value="admin">Administrateur</option>
                            <option value="editor">Éditeur (Blog/Produits)</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Avatar</label>
                    <input type="file" name="image" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                        Créer le compte
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>