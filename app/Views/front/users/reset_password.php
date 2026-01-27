<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[75vh] flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-6">
    
    <div class="absolute top-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative z-10">
        
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 border border-slate-100 shadow-inner">
            <i class="fas fa-key text-2xl"></i>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2">Secure Your Account</h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                Please create a new, strong password to regain access to your account.
            </p>
        </div>

        <form action="<?php echo URLROOT; ?>/users/reset_password/<?php echo $data['token']; ?>" method="POST" class="space-y-5">
            <?php echo csrfField(); ?>
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">New Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="password" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" required placeholder="Min 6 characters">
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['password_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Confirm Password</label>
                <div class="relative">
                    <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="confirm_password" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" required placeholder="Repeat password">
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 hover:bg-accent transition transform hover:-translate-y-1 uppercase tracking-widest text-xs mt-2">
                Reset Password
            </button>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>