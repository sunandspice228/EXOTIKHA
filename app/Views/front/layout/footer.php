<?php
// ⛔ SÉCURITÉ
if (!defined('APPROOT')) {
    die('Accès interdit');
}

// =========================================================================
// 1. LOGIQUE PHP : RÉCUPÉRATION CATÉGORIES (POUR LIENS FOOTER)
// =========================================================================

// On initialise les variables par défaut
$f_allCategories = [];
$f_idGifts = null;
$f_idAccessories = null;

// Chargement sécurisé du modèle Category
if(file_exists(APPROOT . '/Models/Category.php')){
    require_once APPROOT . '/Models/Category.php';
    if(class_exists('Category')){
        $footerCatModel = new Category();
        if(method_exists($footerCatModel, 'getAllCategories')){
            $f_allCategories = $footerCatModel->getAllCategories();
        }
    }
}

if(!empty($f_allCategories) && is_array($f_allCategories)){
    foreach($f_allCategories as $cat){
        if(is_array($cat)) $cat = (object) $cat; // Conversion tableau -> objet si nécessaire
        
        $name = strtolower($cat->name);
        
        // On cherche l'ID "Cadeaux"
        if(strpos($name, 'gift') !== false || strpos($name, 'box') !== false || strpos($name, 'cadeau') !== false) {
            $f_idGifts = $cat->id;
        } 
        // On cherche l'ID "Accessoires"
        elseif(strpos($name, 'accessoire') !== false || strpos($name, 'accessories') !== false) {
            $f_idAccessories = $cat->id;
        }
    }
}

// Langue courante
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<footer class="bg-slate-900 text-slate-300 pt-20 pb-10 mt-auto border-t border-slate-800 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-slate-900 via-primary to-slate-900"></div>

    <div class="max-w-7xl mx-auto px-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            
            <div class="space-y-6">
                <a href="<?php echo URLROOT; ?>" class="block">
                    <img src="<?php echo URLROOT; ?>/img/logo.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="<?php echo SITENAME; ?>" class="h-12 w-auto object-contain brightness-0 invert opacity-80 hover:opacity-100 transition">
                    <span class="text-2xl font-serif font-bold text-white hidden tracking-tighter">EXOTIKHA.</span>
                </a>Streaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcastingStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in yStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything

                <p class="text-sm leading-relaxed text-slate-400">
                    <?php echo lang('footer_about'); ?>
                </p>
                
                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-3 opacity-80 hover:opacity-100 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/1/19/Flag_of_Ghana.svg" class="w-5 h-auto mt-1 shadow-sm rounded-sm">
                        <div class="text-sm">
                            <p class="font-bold text-white"><?php echo lang('footer_loc_ghana'); ?></p>
                            <div class="flex flex-col">
                                <a href="tel:+233539382808" class="hover:text-primary transition flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-phone-alt text-xs"></i> +233 53 938 2808
                                </a>
                                <a href="https://wa.me/233539382808" target="_blank" class="text-green-500 hover:text-green-400 transition flex items-center gap-2 mt-1 font-medium">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 opacity-80 hover:opacity-100 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/Flag_of_Togo.svg" class="w-5 h-auto mt-1 shadow-sm rounded-sm">
                        <div class="text-sm">
                            <p class="font-bold text-white"><?php echo lang('footer_loc_togo'); ?></p>
                            <div class="flex flex-col">
                                <a href="tel:+22891081688" class="hover:text-primary transition flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-phone-alt text-xs"></i> +228 91 08 16 88
                                </a>
                                <a href="https://wa.me/22891081688" target="_blank" class="text-green-500 hover:text-green-400 transition flex items-center gap-2 mt-1 font-medium">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6 border-b border-slate-800 pb-2 inline-block"><?php echo lang('footer_col_title'); ?></h4>
                <ul class="space-y-4 text-sm">
                    <li>
                        <a href="<?php echo URLROOT; ?>/shop/promotions" class="text-red-500 hover:text-red-400 font-bold flex items-center gap-2 transition group">
                            <i class="fas fa-bolt text-xs animate-pulse"></i> <?php echo lang('sec_flash_sales'); ?>
                        </a>
                    </li>
                    
                    <?php if($f_idGifts): ?>
                        <li><a href="<?php echo URLROOT; ?>/shop?category=<?php echo $f_idGifts; ?>" class="text-primary hover:text-white font-bold transition inline-block"><?php echo lang('nav_gifts'); ?></a></li>
                    <?php endif; ?>

                    <li><a href="<?php echo URLROOT; ?>/shop?gender=women" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('nav_women'); ?></a></li>
                    <li><a href="<?php echo URLROOT; ?>/shop?gender=men" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('nav_men'); ?></a></li>
                    
                    <li>
                        <a href="<?php echo URLROOT; ?>/shop?<?php echo $f_idAccessories ? 'category='.$f_idAccessories : 'search=accessoires'; ?>" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400">
                            <?php echo lang('nav_accessories'); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6 border-b border-slate-800 pb-2 inline-block"><?php echo lang('footer_support_title'); ?></h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="<?php echo URLROOT; ?>/users/account?tab=orders" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('link_track_order'); ?></a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/about" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('nav_about'); ?></a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/shipping" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('link_shipping'); ?></a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/contact" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('link_contact'); ?></a></li>
                    <li><a href="<?php echo URLROOT; ?>/pages/terms" class="hover:text-white hover:translate-x-1 transition inline-block text-slate-400"><?php echo lang('link_terms'); ?></a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6 border-b border-slate-800 pb-2 inline-block"><?php echo lang('footer_stay_connected'); ?></h4>
                <p class="text-sm text-slate-400 mb-4"><?php echo lang('footer_sub_text'); ?></p>
                
                <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col gap-3 mb-8">
                    <?php if(function_exists('csrfField')) echo csrfField(); ?>    
                    
                    <input type="email" name="email" placeholder="<?php echo lang('club_placeholder'); ?>" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition" required>
                    <button type="submit" class="bg-white text-slate-900 hover:bg-primary hover:text-white px-4 py-3 rounded-lg text-sm font-bold transition uppercase tracking-wide"><?php echo lang('footer_subscribe'); ?></button>
                </form>
                
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition duration-300 transform hover:scale-110"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition duration-300 transform hover:scale-110"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition duration-300 border border-slate-700 transform hover:scale-110"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6 text-xs text-slate-500">
            <div class="text-center md:text-left">
                <p>&copy; <?php echo date('Y'); ?> <span class="text-white font-bold tracking-widest">EXOTIKHA</span>. <?php echo lang('footer_rights'); ?></p>
                <p class="mt-2 text-[10px]">
                    <?php echo lang('footer_designed'); ?> <i class="fas fa-heart text-red-500 mx-1"></i> <?php echo lang('footer_by'); ?> <span class="text-slate-300 font-bold hover:text-primary transition cursor-pointer">Lilmoussis</span>
                </p>
            </div>
            
            <div class="flex items-center gap-4 text-2xl opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition duration-500 cursor-help">
                <i class="fab fa-cc-visa text-white"></i>
                <i class="fab fa-cc-mastercard text-white"></i>
                <i class="fas fa-mobile-alt text-white" title="Mobile Money"></i>
                <div class="flex items-center gap-1 ml-2 border-l border-slate-700 pl-4">
                    <i class="fas fa-lock text-primary text-sm"></i> 
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400"><?php echo lang('footer_payment_secure'); ?></span>
                </div>
            </div>
        </div>
    </div>
</footer>