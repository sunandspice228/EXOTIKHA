<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="min-h-[85vh] flex items-center justify-center bg-slate-50 relative overflow-hidden py-12 px-6">
    
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="max-w-md w-full bg-white p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative z-10">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2"><?php echo lang('auth_join_title'); ?></h2>
            <p class="text-sm text-slate-500">
                <?php echo lang('auth_already_account'); ?> 
                <a href="<?php echo URLROOT; ?>/users/login" class="font-bold text-primary hover:text-slate-900 transition underline">
                    <?php echo lang('auth_sign_in'); ?>
                </a>
            </p>
        </div>

        <form class="space-y-5" action="<?php echo URLROOT; ?>/users/register" method="POST">
            <?php if(function_exists('csrfField')) echo csrfField(); ?>
            
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_fullname'); ?></label>
                <div class="relative">
                    <i class="far fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="name" type="text" 
                           value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>" 
                           class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['name_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" 
                           placeholder="<?php echo lang('ph_fullname'); ?>" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['name_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_email'); ?></label>
                <div class="relative">
                    <i class="far fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="email" type="email" 
                           value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" 
                           class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['email_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" 
                           placeholder="<?php echo lang('ph_email'); ?>" required>
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
                           placeholder="<?php echo lang('ph_password'); ?>" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['password_err']; ?></span>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-1"><?php echo lang('form_confirm_password'); ?></label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input name="confirm_password" type="password" 
                           value="<?php echo isset($data['confirm_password']) ? htmlspecialchars($data['confirm_password']) : ''; ?>" 
                           class="w-full pl-10 pr-4 py-3.5 rounded-xl border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500 bg-red-50' : 'border-slate-200 bg-slate-50'; ?> focus:bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-slate-400 text-sm font-medium" 
                           placeholder="<?php echo lang('ph_confirm_password'); ?>" required>
                </div>
                <span class="text-xs text-red-500 font-bold mt-1 block pl-1"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 hover:bg-primary transition transform hover:-translate-y-1 uppercase tracking-widest text-xs">
                    <?php echo lang('btn_register'); ?>
                </button>
            </div>
            
            <p class="text-[10px] text-center text-slate-400 leading-relaxed mt-4">
                <?php echo lang('auth_terms'); ?>
            </p>
        </form>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>