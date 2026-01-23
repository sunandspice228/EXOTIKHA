<?php
// --- LOGIQUE MENU DYNAMIQUE (DIRECTEMENT DANS LE HEADER) ---
// On récupère les catégories depuis la BDD pour construire le menu sans toucher aux Contrôleurs
require_once '../app/Models/Category.php';
$menuCatModel = new Category();
$allCategories = $menuCatModel->getCategories();

// On prépare des variables pour les IDs spécifiques (si trouvés)
$idCouple = null;
$idGifts = null;
$otherCategories = [];  

foreach($allCategories as $cat){
    // Détection intelligente (insensible à la casse)
    $name = strtolower($cat->name);
    
    if(strpos($name, 'couple') !== false || strpos($name, 'intimacy') !== false) {
        $idCouple = $cat->id;
    } elseif(strpos($name, 'gift') !== false || strpos($name, 'box') !== false || strpos($name, 'cadeau') !== false) {
        $idGifts = $cat->id;
    } else {
        // C'est une "Autre catégorie"
        $otherCategories[] = $cat;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> | Premium Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
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
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white text-slate-800 antialiased flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <div class="bg-primary text-white text-[10px] md:text-xs text-center py-2 tracking-widest uppercase font-bold px-4">
        Free shipping on all orders over 500 GHS in Accra!
    </div>

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?php echo URLROOT; ?>" class="text-2xl font-serif font-bold tracking-tighter text-primary">
                        EXOTIKHA<span class="text-accent">.</span>
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                    
                    <a href="<?php echo URLROOT; ?>" class="text-sm font-bold text-slate-900 hover:text-accent transition uppercase tracking-wide">
                        Home
                    </a>

                    <a href="<?php echo URLROOT; ?>/shop?gender=women" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">
                        For Women
                    </a>

                    <a href="<?php echo URLROOT; ?>/shop?gender=men" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">
                        For Men
                    </a>

                    <?php if($idCouple): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idCouple; ?>" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide text-red-500 hover:text-red-600">
                            Couple & Intimacy
                        </a>
                    <?php endif; ?>

                    <?php if($idGifts): ?>
                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idGifts; ?>" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">
                            Gift Boxes
                        </a>
                    <?php endif; ?>

                    <?php if(!empty($otherCategories)): ?>
                        <div class="relative group" x-data="{ open: false }">
                            <button @mouseenter="open = true" @mouseleave="open = false" class="flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide py-6">
                                Collections <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                            </button>
                            
                            <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" x-cloak class="absolute top-full left-0 w-56 bg-white shadow-xl border border-slate-100 rounded-b-xl overflow-hidden py-2 animate-fade-in-up">
                                <?php foreach($otherCategories as $cat): ?>
                                    <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="block px-6 py-3 text-sm text-slate-600 hover:bg-slate-50 hover:text-accent transition border-b border-slate-50 last:border-0">
                                        <?php echo $cat->name; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo URLROOT; ?>/pages/contact" class="text-sm font-medium text-slate-600 hover:text-accent transition uppercase tracking-wide">
                        Contact Us
                    </a>
                </div>

                <div class="flex items-center space-x-5">
                    <button class="text-slate-600 hover:text-primary transition hidden sm:block">
                        <i class="fas fa-search text-lg"></i>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-slate-600 hover:text-primary transition flex items-center gap-2">
                            <i class="far fa-user text-lg"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-4 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="<?php echo URLROOT; ?>/users/profile" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">My Account</a>
                                <a href="<?php echo URLROOT; ?>/users/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                            <?php else: ?>
                                <a href="<?php echo URLROOT; ?>/users/login" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Login</a>
                                <a href="<?php echo URLROOT; ?>/users/register" class="block px-4 py-2 text-sm font-bold text-accent">Register</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="<?php echo URLROOT; ?>/cart" class="text-slate-600 hover:text-primary transition relative">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <span class="absolute -top-1 -right-2 bg-accent text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm">
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

        <div x-show="mobileMenuOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div x-show="mobileMenuOpen" x-transition.slide.right class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="text-2xl font-serif font-bold text-primary">EXOTIKHA.</span>
                    </a>
                    <button @click="mobileMenuOpen = false" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <a href="<?php echo URLROOT; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Home</a>
                            <a href="<?php echo URLROOT; ?>/shop?gender=women" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">For Women</a>
                            <a href="<?php echo URLROOT; ?>/shop?gender=men" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">For Men</a>
                            
                            <?php if($idCouple): ?>
                                <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idCouple; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-red-600 hover:bg-red-50">Couple & Intimacy</a>
                            <?php endif; ?>

                            <?php if($idGifts): ?>
                                <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $idGifts; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Gift Boxes</a>
                            <?php endif; ?>

                            <?php if(!empty($otherCategories)): ?>
                                <div class="py-2">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">More Collections</p>
                                    <?php foreach($otherCategories as $cat): ?>
                                        <a href="<?php echo URLROOT; ?>/shop?category=<?php echo $cat->id; ?>" class="-mx-3 block rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 pl-6 border-l-2 border-transparent hover:border-accent">
                                            <?php echo $cat->name; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php echo URLROOT; ?>/pages/contact" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        <?php flash('cart_msg'); ?>
        <?php flash('product_message'); ?>
    </div>