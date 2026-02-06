<?php
// ⛔ SÉCURITÉ
if (!defined('APPROOT')) {
    die('Accès interdit');
}

// =========================================================================
// 1. LOGIQUE PHP : RÉCUPÉRATION CATÉGORIES
// =========================================================================

// On initialise les variables par défaut pour éviter les erreurs "Undefined variable"
$giftCat = null;
$coupleCat = null;
$otherCategories = []; 
$allCategories = [];

// On charge le modèle Category s'il existe
if(file_exists(APPROOT . '/Models/Category.php')){
    require_once APPROOT . '/Models/Category.php';
    // On instancie la classe uniquement si elle n'existe pas déjà
    if(class_exists('Category')){
        $menuCatModel = new Category();
        // S'assure que getAllCategories existe
        if(method_exists($menuCatModel, 'getAllCategories')){
            $allCategories = $menuCatModel->getAllCategories();
        }
    }
}

// Langue actuelle (avec sécurité)
$curLang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Tri des catégories pour le menu
if(!empty($allCategories) && is_array($allCategories)){
    foreach($allCategories as $cat){
        // Sécurité : On s'assure que c'est un objet
        if(is_array($cat)) $cat = (object) $cat;

        $techName = strtolower($cat->name);
        
        if(strpos($techName, 'gift') !== false || strpos($techName, 'box') !== false || strpos($techName, 'cadeau') !== false) {
            $giftCat = $cat; 
        } elseif(strpos($techName, 'couple') !== false || strpos($techName, 'intimacy') !== false) {
            $coupleCat = $cat; 
        } else {
            $otherCategories[] = $cat;
        }
    }
}

// Helper local pour le nom de catégorie
if (!function_exists('getMenuCatName')) {
    function getMenuCatName($cat, $lang) {
        if(is_array($cat)) $cat = (object) $cat;
        
        if ($lang == 'fr' && !empty($cat->name_fr)) {
            return $cat->name_fr;
        }
        return $cat->name;
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $curLang; ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('SITENAME') ? SITENAME : 'Exotikha'; ?> | Premium African Fashion</title>
    
    <link rel="shortcut icon" href="<?php echo URLROOT; ?>/uploads/favicon.ico" type="image/x-icon">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        primary: '#C8AD7F', // Gold Premium
                        secondary: '#FDF8F3', // Cream light
                        dark: '#1A1A1A',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="font-sans antialiased text-slate-800 bg-white flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, searchOpen: false }">

    <div class="bg-dark text-white py-2.5 text-[10px] md:text-xs font-bold uppercase tracking-[0.15em] relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-50 animate-pulse pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 flex justify-center items-center text-center">
            <p><?php echo lang('topbar_promo'); ?></p>
        </div>
    </div>

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                
                <a href="<?php echo URLROOT; ?>" class="flex items-center group">
                    <img src="<?php echo URLROOT; ?>/img/logo.png" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Exotikha" class="h-10 md:h-12 w-auto object-contain">
                    <span class="text-2xl font-serif font-bold text-dark hidden tracking-tighter">EXOTIKHA<span class="text-primary">.</span></span>
                </a>

                <div class="hidden lg:flex items-center gap-8 text-xs font-bold uppercase tracking-widest text-slate-600">
                    <a href="<?php echo URLROOT; ?>" class="hover:text-primary transition relative group">
                        <?php echo lang('nav_home'); ?>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/shop" class="hover:text-primary transition"><?php echo lang('nav_shop'); ?></a>
                    
                    <?php if($giftCat): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $giftCat->id; ?>" class="text-primary hover:text-red-700 transition flex items-center gap-1">
                            <i class="fas fa-gift text-sm animate-bounce"></i> 
                            <?php echo getMenuCatName($giftCat, $curLang); ?>
                        </a>
                    <?php endif; ?>

                    <?php if(!empty($otherCategories) || $coupleCat): ?>
                        <div class="relative group h-20 flex items-center" x-data="{ open: false }">
                            <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center gap-1 hover:text-primary transition h-full focus:outline-none">
                                <?php echo lang('nav_collections'); ?> <i class="fas fa-chevron-down text-[8px] ml-1 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            
                            <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" x-cloak class="absolute top-full left-1/2 -translate-x-1/2 w-64 bg-white shadow-xl border border-slate-100 rounded-b-xl overflow-hidden py-2 animate-fade-in">
                                <?php if($coupleCat): ?>
                                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $coupleCat->id; ?>" class="block px-6 py-3 text-xs text-slate-600 hover:bg-secondary hover:text-primary transition border-b border-slate-50">
                                        <?php echo getMenuCatName($coupleCat, $curLang); ?>
                                    </a>
                                <?php endif; ?>

                                <?php foreach($otherCategories as $cat): ?>
                                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="block px-6 py-3 text-xs text-slate-600 hover:bg-secondary hover:text-primary transition border-b border-slate-50 last:border-0">
                                        <?php echo getMenuCatName($cat, $curLang); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo URLROOT; ?>/pages/blog" class="hover:text-primary transition"><?php echo lang('nav_blog'); ?></a>
                    <a href="<?php echo URLROOT; ?>/pages/contact" class="hover:text-primary transition"><?php echo lang('nav_contact'); ?></a>
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    
                    <div class="relative group hidden md:block" x-data="{ langOpen: false }">
                        <button @click="langOpen = !langOpen" class="flex items-center gap-1 hover:text-primary transition font-bold text-xs uppercase tracking-widest border px-2 py-1 rounded-md border-slate-200">
                            <?php if($curLang == 'en'): ?>
                                <span class="text-lg leading-none">🇺🇸</span> <span class="hidden xl:inline">EN</span>
                            <?php else: ?>
                                <span class="text-lg leading-none">🇫🇷</span> <span class="hidden xl:inline">FR</span>
                            <?php endif; ?>
                            <i class="fas fa-chevron-down text-[8px] ml-1 text-slate-400"></i>
                        </button>

                        <div x-show="langOpen" @click.outside="langOpen = false" x-cloak class="absolute top-full right-0 w-32 bg-white shadow-xl border border-slate-100 rounded-lg overflow-hidden py-1 mt-2 z-50">
                            <a href="<?php echo URLROOT; ?>/users/setLang/en" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition text-xs font-bold text-slate-700">
                                <span class="text-lg">🇺🇸</span> English
                            </a>
                            <a href="<?php echo URLROOT; ?>/users/setLang/fr" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition text-xs font-bold text-slate-700">
                                <span class="text-lg">🇫🇷</span> Français
                            </a>
                        </div>
                    </div>

                    <?php if(isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'super_admin', 'editor'])): ?>
                        <a href="<?php echo URLROOT; ?>/admin" class="text-red-600 hover:text-red-800 transition" title="Admin Panel">
                            <i class="fas fa-user-shield text-lg"></i>
                        </a>
                    <?php endif; ?>

                    <button @click="searchOpen = !searchOpen" class="hover:text-primary transition">
                        <i class="fas fa-search text-lg"></i>
                    </button>
                    
                    <a href="<?php echo URLROOT; ?>/cart" class="relative hover:text-primary transition group">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="absolute -top-1.5 -right-1.5 bg-primary text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center group-hover:scale-110 transition shadow-sm">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?php echo isset($_SESSION['user_id']) ? URLROOT.'/users/account' : URLROOT.'/users/login'; ?>" class="hover:text-primary transition">
                        <i class="far fa-user text-lg"></i>
                    </a>
                    
                    <button @click="mobileMenuOpen = true" class="lg:hidden text-slate-800 text-xl pl-2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="searchOpen" @click.outside="searchOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="absolute top-full left-0 w-full bg-white border-b border-slate-100 shadow-xl p-6 z-40">
            <form action="<?php echo URLROOT; ?>/shop" method="GET" class="max-w-3xl mx-auto relative">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" placeholder="<?php echo lang('search_placeholder'); ?>" class="w-full bg-slate-50 border-none rounded-full py-4 pl-12 pr-32 text-slate-900 font-medium focus:ring-2 focus:ring-primary focus:bg-white transition shadow-inner placeholder-slate-400 outline-none">
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-primary text-white px-8 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-dark transition">GO</button>
            </form>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full empty:hidden">
        <?php if(function_exists('flash')) { flash('cart_msg'); flash('product_message'); flash('newsletter_msg'); } ?>
    </div>

    <div x-show="mobileMenuOpen" class="relative z-[60] lg:hidden" role="dialog" aria-modal="true" x-cloak>
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 z-50 w-[85%] max-w-sm overflow-y-auto bg-white px-6 py-6 shadow-2xl">
            
            <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
                <span class="text-2xl font-serif font-bold text-primary">EXOTIKHA.</span>
                <button @click="mobileMenuOpen = false" type="button" class="-m-2.5 rounded-md p-2.5 text-slate-400 hover:text-slate-900 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="mt-2 flow-root">
                <div class="flex items-center justify-center gap-4 mb-8">
                     <a href="<?php echo URLROOT; ?>/users/setLang/en" class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-lg text-xs font-bold transition <?php echo $curLang == 'en' ? 'border border-primary text-primary bg-secondary' : 'text-slate-500'; ?>">
                        <span class="text-lg">🇺🇸</span> EN
                    </a>
                    <a href="<?php echo URLROOT; ?>/users/setLang/fr" class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-lg text-xs font-bold transition <?php echo $curLang == 'fr' ? 'border border-primary text-primary bg-secondary' : 'text-slate-500'; ?>">
                        <span class="text-lg">🇫🇷</span> FR
                    </a>
                </div>

                <div class="space-y-1">
                    <a href="<?php echo URLROOT; ?>" class="-mx-3 block rounded-lg px-3 py-3 text-base font-bold text-slate-900 hover:bg-secondary hover:text-primary transition"><?php echo lang('nav_home'); ?></a>
                    <a href="<?php echo URLROOT; ?>/shop" class="-mx-3 block rounded-lg px-3 py-3 text-base font-medium text-slate-600 hover:bg-secondary hover:text-primary transition"><?php echo lang('nav_shop'); ?></a>
                    
                    <?php if($giftCat): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $giftCat->id; ?>" class="-mx-3 block rounded-lg px-3 py-3 text-base font-bold text-primary bg-secondary/50">
                            <?php echo getMenuCatName($giftCat, $curLang); ?>
                        </a>
                    <?php endif; ?>

                    <?php if(!empty($otherCategories) || $coupleCat): ?>
                        <div class="py-4 border-t border-b border-slate-50 my-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 px-3"><?php echo lang('nav_collections'); ?></p>
                            
                            <?php if($coupleCat): ?>
                                <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $coupleCat->id; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-secondary hover:text-primary pl-6 border-l-2 border-transparent hover:border-primary ml-3">
                                    <?php echo getMenuCatName($coupleCat, $curLang); ?>
                                </a>
                            <?php endif; ?>

                            <?php foreach($otherCategories as $cat): ?>
                                <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-secondary hover:text-primary pl-6 border-l-2 border-transparent hover:border-primary ml-3">
                                    <?php echo getMenuCatName($cat, $curLang); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo URLROOT; ?>/blog" class="-mx-3 block rounded-lg px-3 py-3 text-base font-medium text-slate-600 hover:bg-secondary hover:text-primary transition"><?php echo lang('nav_blog'); ?></a>
                    <a href="<?php echo URLROOT; ?>/pages/contact" class="-mx-3 block rounded-lg px-3 py-3 text-base font-medium text-slate-600 hover:bg-secondary hover:text-primary transition"><?php echo lang('nav_contact'); ?></a>
                </div>
                
                <div class="mt-8 pt-8 border-t border-slate-100">
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="<?php echo URLROOT; ?>/users/login" class="flex items-center justify-center rounded-xl border border-slate-200 px-3 py-3 text-sm font-bold text-slate-900 hover:bg-slate-50"><?php echo lang('login'); ?></a>
                            <a href="<?php echo URLROOT; ?>/users/register" class="flex items-center justify-center rounded-xl bg-primary px-3 py-3 text-sm font-bold text-white hover:bg-dark transition shadow-lg"><?php echo lang('register'); ?></a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <a href="<?php echo URLROOT; ?>/users/account" class="flex w-full items-center justify-center rounded-xl bg-dark px-3 py-3 text-sm font-bold text-white hover:bg-slate-800"><?php echo lang('my_account'); ?></a>
                            <a href="<?php echo URLROOT; ?>/users/logout" class="block text-center text-sm font-medium text-red-500 hover:text-red-700"><?php echo lang('logout'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>