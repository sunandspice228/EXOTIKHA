<?php
// --- LOGIQUE MENU DYNAMIQUE (DIRECTEMENT DANS LE HEADER) ---
require_once '../app/Models/Category.php';
$menuCatModel = new Category();
$allCategories = $menuCatModel->getCategories();

$idCouple = null;
$idGifts = null;
$otherCategories = [];  

foreach($allCategories as $cat){
    $name = strtolower($cat->name);
    if(strpos($name, 'couple') !== false || strpos($name, 'intimacy') !== false) {
        $idCouple = $cat->id;
    } elseif(strpos($name, 'gift') !== false || strpos($name, 'box') !== false || strpos($name, 'cadeau') !== false) {
        $idGifts = $cat->id;
    } else {
        $otherCategories[] = $cat;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('SITENAME') ? SITENAME : 'Exotikha'; ?> | Premium Store</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
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
                        primary: '#1a1a1a',
                        accent: '#4f46e5',
                        gold: '#d4af37',
                    },
                    keyframes: {
                        'fade-in-down': {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        'fade-in-down': 'fade-in-down 0.2s ease-out',
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white text-slate-800 antialiased flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false, searchOpen: false }">

    <div class="bg-primary text-white text-[10px] md:text-xs text-center py-2 tracking-widest uppercase font-bold px-4">
        Free shipping on all orders over 500 GHS in Accra!
    </div>

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-300 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?php echo URLROOT; ?>" class="text-2xl font-serif font-bold tracking-tighter text-primary group">
                        EXOTIKHA<span class="text-accent group-hover:text-gold transition">.</span>
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                    <a href="<?php echo URLROOT; ?>" class="text-sm font-bold text-slate-900 hover:text-accent transition uppercase tracking-wide">Home</a>
                    <a href="<?php echo URLROOT; ?>/shop?gender=women" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">For Women</a>
                    <a href="<?php echo URLROOT; ?>/shop?gender=men" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">For Men</a>

                    <?php if($idCouple): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idCouple; ?>" class="text-sm font-medium text-slate-600 hover:text-red-500 transition uppercase tracking-wide">Couple & Intimacy</a>
                    <?php endif; ?>

                    <?php if($idGifts): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idGifts; ?>" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">Gift Boxes</a>
                    <?php endif; ?>

                    <?php if(!empty($otherCategories)): ?>
                        <div class="relative group" x-data="{ open: false }">
                            <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide py-6">
                                Collections <i class="fas fa-chevron-down text-[10px] ml-1 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" x-cloak class="absolute top-full left-0 w-56 bg-white shadow-xl border border-slate-100 rounded-b-xl overflow-hidden py-2 animate-fade-in-down z-50">
                                <?php foreach($otherCategories as $cat): ?>
                                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="block px-6 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-accent transition border-b border-slate-50 last:border-0">
                                        <?php echo $cat->name; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo URLROOT; ?>/shop/promotions" class="text-sm font-bold text-red-600 hover:text-red-700 transition uppercase tracking-wide flex items-center gap-1 animate-pulse">
                        <i class="fas fa-bolt text-xs"></i> Sales
                    </a>
                </div>

                <div class="flex items-center space-x-5">
                    
                    <button @click="searchOpen = !searchOpen" class="text-slate-600 hover:text-accent transition">
                        <i class="fas fa-search text-lg"></i>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-slate-600 hover:text-primary transition flex items-center gap-2 relative">
                            <i class="far fa-user text-lg"></i>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full border border-white"></span>
                            <?php endif; ?>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-4 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in-down">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                    <p class="text-xs text-slate-400 font-bold uppercase">Signed in as</p>
                                    <p class="text-sm font-bold text-slate-900 truncate"><?php echo $_SESSION['user_name']; ?></p>
                                </div>
                                <a href="<?php echo URLROOT; ?>/users/account" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><i class="fas fa-tachometer-alt w-5 text-slate-400"></i> Dashboard</a>
                                <a href="<?php echo URLROOT; ?>/users/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5 text-red-400"></i> Logout</a>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/users/login" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Login</a>
                                <a href="<?php echo URLROOT; ?>/users/register" class="block px-4 py-2 text-sm font-bold text-accent">Register</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/cart" class="text-slate-600 hover:text-primary transition relative group">
                        <i class="fas fa-shopping-bag text-lg group-hover:scale-110 transition-transform"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="absolute -top-1.5 -right-2 bg-accent text-white text-[9px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm animate-bounce">
                                <?php echo count($_SESSION['cart']); ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <button @click="mobileMenuOpen = true" class="lg:hidden text-slate-900 ml-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="searchOpen" 
             @click.away="searchOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-cloak
             class="absolute top-full left-0 w-full bg-white border-b border-slate-100 shadow-xl p-6 z-40">
            <form action="<?php echo URLROOT; ?>/shop" method="GET" class="max-w-3xl mx-auto relative">
            <?php echo csrfField(); ?>    
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" placeholder="Search for products, brands and more..." class="w-full bg-slate-50 border-none rounded-xl py-4 pl-12 pr-32 text-slate-900 font-medium focus:ring-2 focus:ring-accent focus:bg-white transition shadow-inner" autofocus>
                <button type="submit" class="absolute right-2 top-2 bottom-2 bg-primary text-white px-6 rounded-lg font-bold text-xs uppercase tracking-widest hover:bg-accent transition">Search</button>
            </form>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full empty:hidden">
        <?php flash('cart_msg'); ?>
        <?php flash('product_message'); ?>
    </div>

    <div x-show="mobileMenuOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm shadow-2xl">
            
            <div class="flex items-center justify-between mb-8">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="text-2xl font-serif font-bold text-primary">EXOTIKHA.</span>
                </a>
                <button @click="mobileMenuOpen = false" type="button" class="-m-2.5 rounded-md p-2.5 text-slate-400 hover:text-slate-900 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-slate-100">
                    <div class="space-y-2 py-6">
                        <a href="<?php echo URLROOT; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-bold leading-7 text-slate-900 hover:bg-slate-50 hover:text-accent">Home</a>
                        <a href="<?php echo URLROOT; ?>/shop?gender=women" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50 hover:text-accent">For Women</a>
                        <a href="<?php echo URLROOT; ?>/shop?gender=men" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50 hover:text-accent">For Men</a>
                        
                        <a href="<?php echo URLROOT; ?>/shop/promotions" class="-mx-3 block rounded-lg px-3 py-2 text-base font-bold leading-7 text-red-600 bg-red-50 border border-red-100 mt-4 mb-4 flex items-center gap-2">
                            <i class="fas fa-bolt"></i> Flash Sales
                        </a>

                        <?php if($idCouple): ?>
                            <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idCouple; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50">Couple & Intimacy</a>
                        <?php endif; ?>

                        <?php if($idGifts): ?>
                            <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idGifts; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50">Gift Boxes</a>
                        <?php endif; ?>

                        <?php if(!empty($otherCategories)): ?>
                            <div class="py-4">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-3">More Collections</p>
                                <?php foreach($otherCategories as $cat): ?>
                                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900 pl-6 border-l-2 border-transparent hover:border-accent ml-3">
                                        <?php echo $cat->name; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <a href="<?php echo URLROOT; ?>/pages/blog" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50">Journal</a>
                        <a href="<?php echo URLROOT; ?>/pages/contact" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-slate-600 hover:bg-slate-50">Contact Us</a>
                    </div>
                    
                    <?php if(!isLoggedIn()): ?>
                    <div class="py-6 space-y-3">
                        <a href="<?php echo URLROOT; ?>/users/login" class="flex w-full items-center justify-center rounded-xl border border-slate-200 px-3 py-3 text-sm font-bold leading-6 text-slate-900 hover:bg-slate-50">Log in</a>
                        <a href="<?php echo URLROOT; ?>/users/register" class="flex w-full items-center justify-center rounded-xl bg-primary px-3 py-3 text-sm font-bold leading-6 text-white hover:bg-accent transition shadow-lg">Register</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>