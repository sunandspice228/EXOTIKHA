<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Add Staff Member</h1>
            <p class="text-sm text-slate-500">Create a new administrator account with dashboard access.</p>
        </div>
    </div>

    <?php flash('admin_msg'); ?>

    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
        <form action="<?php echo URLROOT; ?>/admin/users_add" method="POST" class="space-y-6">
            <?php echo csrfField(); ?>
            <?php if(!empty($data['error'])): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl flex items-center gap-3 text-sm font-bold animate-pulse">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Full Name</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="name" value="<?php echo $data['name']; ?>" required
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800" 
                           placeholder="e.g. Jane Doe">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Work Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" value="<?php echo $data['email']; ?>" required
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800" 
                           placeholder="admin@exotikha.com">
                </div>
            </div>

            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                <h3 class="text-xs font-bold uppercase text-slate-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-lock"></i> Security Credentials
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                               placeholder="Min 6 chars">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-500 mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                               placeholder="Repeat password">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2 transform active:scale-95 duration-200">
                    <i class="fas fa-user-shield"></i> Create Administrator
                </button>
            </div>

        </form>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>