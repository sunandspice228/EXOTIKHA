<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="min-h-[80vh] flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-6">
    
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative z-10">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2"><?php echo lang('login_title'); ?></h2>
            <p class="text-sm text-slate-500"><?php echo lang('login_subtitle'); ?></p>
        </div>
        
        <?php if(function_exists('flash')): ?>
            <?php flash('register_success'); ?>
            <?php flash('product_message'); ?>
            <?php flash('login_msg'); ?> 
        <?php endif; ?>

        <form class="space-y-6" action="<?php echo URLROOT; ?>/users/login" method="POST">
            <?php if(function_exists('csrfField')) echo csrfField(); ?>
            
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_email'); ?></label>
                <div class="relative">
                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="email" type="email" 
                           value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" 
                           class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['email_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" 
                           placeholder="name@example.com" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['email_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_password'); ?></label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="password" type="password" 
                           value="<?php echo isset($data['password']) ? htmlspecialchars($data['password']) : ''; ?>" 
                           class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" 
                           placeholder="••••••••" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember-me" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                    <span class="ml-2 text-xs font-bold text-slate-600"><?php echo lang('login_remember'); ?></span>
                </label>

                <a href="<?php echo URLROOT; ?>/users/forgot_password" class="text-xs font-bold text-primary hover:text-slate-900 transition">
                    <?php echo lang('login_forgot'); ?>
                </a>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 hover:bg-primary transition transform hover:-translate-y-1 uppercase tracking-widest text-xs group">
                <?php echo lang('btn_login'); ?> <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <div class="mt-10 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-500 text-xs mb-3"><?php echo lang('login_no_account'); ?></p>
            <a href="<?php echo URLROOT; ?>/users/register" class="inline-block border-2 border-slate-100 text-slate-900 px-6 py-2 rounded-lg font-bold text-xs uppercase tracking-wide hover:border-primary hover:text-primary transition">
                <?php echo lang('btn_create_account'); ?>
            </a>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>