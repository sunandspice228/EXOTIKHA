<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="min-h-[75vh] flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-6">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative z-10">
        
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 border border-slate-100 shadow-inner">
            <i class="fas fa-lock text-2xl text-primary"></i>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-serif font-bold text-slate-900 mb-2"><?php echo lang('auth_forgot_title'); ?></h2>
            <p class="text-sm text-slate-500 leading-relaxed">
                <?php echo lang('auth_forgot_text'); ?>
            </p>
        </div>

        <?php if(function_exists('flash')) flash('register_success'); ?>

        <form action="<?php echo URLROOT; ?>/users/forgot_password" method="POST" class="space-y-6">
            <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>
            
            <?php if(!empty($data['error'])): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2 animate-fade-in">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_email'); ?></label>
                <div class="relative">
                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" value="<?php echo $data['email']; ?>" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" required placeholder="name@example.com">
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 hover:bg-primary transition transform hover:-translate-y-1 uppercase tracking-widest text-xs">
                <?php echo lang('btn_send_reset'); ?>
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-400 text-xs mb-2"><?php echo lang('auth_remember_pass'); ?></p>
            <a href="<?php echo URLROOT; ?>/users/login" class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 hover:text-primary transition">
                <i class="fas fa-arrow-left text-xs"></i> <?php echo lang('auth_back_login'); ?>
            </a>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>