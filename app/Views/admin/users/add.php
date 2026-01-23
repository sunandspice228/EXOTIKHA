<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-2">Ajouter un Membre Staff</h1>
    <p class="text-slate-500 mb-8">Créer un nouveau compte administrateur.</p>

    <?php flash('admin_msg'); ?>

    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
        <form action="<?php echo URLROOT; ?>/admin/users_add" method="POST" class="space-y-6">
            
            <?php if(!empty($data['error'])): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Nom Complet</label>
                <input type="text" name="name" value="<?php echo $data['name']; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" placeholder="Ex: Jean Admin">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Email Professionnel</label>
                <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" placeholder="admin@exotikha.com">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Mot de passe</label>
                    <input type="password" name="password" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Confirmer</label>
                    <input type="password" name="confirm_password" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-lg">
                    <i class="fas fa-user-shield mr-2"></i> Créer l'Administrateur
                </button>
            </div>

        </form>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>