<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[85vh] flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-6">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative z-10">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2">Join Exotikha</h2>
            <p class="text-sm text-slate-500">
                Already have an account? <a href="<?php echo URLROOT; ?>/users/login" class="font-bold text-accent hover:text-slate-900 transition underline">Sign in</a>
            </p>
        </div>

        <form class="space-y-5" action="<?php echo URLROOT; ?>/users/register" method="POST">
            <?php echo csrfField(); ?>
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Full Name</label>
                <div class="relative">
                    <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="name" type="text" value="<?php echo $data['name']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['name_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" placeholder="John Doe" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['name_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Email Address</label>
                <div class="relative">
                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="email" type="email" value="<?php echo $data['email']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['email_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" placeholder="you@example.com" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['email_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="password" type="password" value="<?php echo $data['password']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" placeholder="Min 6 characters" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['password_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1">Confirm Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="confirm_password" type="password" value="<?php echo $data['confirm_password']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-accent focus:border-transparent transition placeholder-slate-400 text-sm font-medium" placeholder="Repeat password" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 hover:bg-accent transition transform hover:-translate-y-1 uppercase tracking-widest text-xs">
                    Create Account
                </button>
            </div>
            
            <p class="text-[10px] text-center text-slate-400 leading-relaxed mt-4">
                By registering, you agree to our <a href="#" class="underline hover:text-slate-600">Terms of Service</a> and <a href="#" class="underline hover:text-slate-600">Privacy Policy</a>.
            </p>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>