<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="min-h-[70vh] flex items-center justify-center py-20 bg-slate-50 relative overflow-hidden">
    
    <div class="absolute top-10 left-10 w-4 h-4 bg-red-400 rounded-full opacity-20 animate-bounce"></div>
    <div class="absolute bottom-20 right-20 w-6 h-6 bg-primary rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute top-1/2 right-10 w-3 h-3 bg-blue-400 rounded-full opacity-20"></div>

    <div class="max-w-xl w-full mx-auto px-6 text-center relative z-10">
        
        <div class="mb-8 relative inline-block">
            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl shadow-sm mx-auto relative z-10 animate-bounce-subtle">
                <i class="fas fa-check"></i>
            </div>
            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-20"></div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-4"><?php echo lang('success_title'); ?></h1>
        <p class="text-lg text-slate-500 mb-8 leading-relaxed">
            <?php echo lang('success_text_1'); ?> <span class="font-bold text-slate-900">#<?php echo $data['order']->order_number; ?></span> <?php echo lang('success_text_2'); ?>
        </p>
        
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 mb-10">
            <p class="text-slate-500 text-sm mb-4">
                <i class="far fa-envelope mr-2 text-slate-400"></i> <?php echo lang('success_email_msg'); ?>
            </p>
            <div class="flex justify-center items-center gap-3 pt-4 border-t border-slate-50">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest"><?php echo lang('success_order_status'); ?></span>
                <span class="text-xs font-bold text-green-700 bg-green-50 border border-green-100 px-3 py-1 rounded-full uppercase tracking-wider">
                    <?php echo lang('status_processing'); // Réutilisation de la clé existante ?>
                </span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo URLROOT; ?>/users/account?tab=orders" class="w-full sm:w-auto bg-slate-900 text-white px-8 py-4 rounded-xl font-bold shadow-lg hover:bg-primary transition transform hover:-translate-y-1">
                <?php echo lang('btn_track_order'); ?>
            </a>
            <a href="<?php echo URLROOT; ?>/shop" class="w-full sm:w-auto bg-white text-slate-900 border border-slate-200 px-8 py-4 rounded-xl font-bold hover:bg-slate-50 transition">
                <?php echo lang('btn_continue_shopping'); ?>
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 3s infinite ease-in-out; }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>