<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Exotikha</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        sidebar: '#0f172a', // Bleu nuit profond
                        primary: '#4f46e5', // Indigo vibrant
                        secondary: '#ec4899', // Rose moderne
                        success: '#10b981',
                        warning: '#f59e0b',
                        background: '#f8fafc', // Gris très très clair
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; }
        .nav-item.active { background: rgba(255,255,255,0.1); border-left: 4px solid #4f46e5; color: white; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .glass-effect { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        
        /* Scrollbar fine pour la sidebar */
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-track { background: transparent; }
        aside::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="absolute left-0 top-0 z-50 flex h-screen w-72 flex-col bg-sidebar text-slate-400 transition-transform duration-300 lg:static lg:translate-x-0 shadow-2xl">
            
            <div class="flex items-center justify-center h-20 border-b border-slate-800 bg-slate-900/50">
                <a href="<?php echo URLROOT; ?>/admin" class="text-2xl font-bold text-white tracking-wide flex items-center gap-2">
                    <span class="bg-gradient-to-r from-primary to-secondary text-transparent bg-clip-text">EXOTIKHA</span>
                </a>
            </div>

            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-2 mt-2">Vue d'ensemble</p>
                
                <a href="<?php echo URLROOT; ?>/admin" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (!isset($_GET['url']) || $_GET['url'] == 'admin') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
                </a>
                
                <a href="<?php echo URLROOT; ?>/admin/orders" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'orders') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-bag w-5 text-center"></i> Commandes
                </a>

                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-2 mt-6">Gestion Boutique</p>

                <a href="<?php echo URLROOT; ?>/admin/products" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'products') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-tshirt w-5 text-center"></i> Produits
                </a>
                <a href="<?php echo URLROOT; ?>/admin/categories" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'categories') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-tags w-5 text-center"></i> Catégories
                </a>
                <a href="<?php echo URLROOT; ?>/admin/attributes" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'attributes') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-ruler-combined w-5 text-center"></i> Attributs
                </a>

                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-2 mt-6">Gestion Contenu</p>

                <a href="<?php echo URLROOT; ?>/admin/blog" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'blog') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-pen-nib w-5 text-center"></i> Blog & Articles
                </a>
                <a href="<?php echo URLROOT; ?>/admin/reviews" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'reviews') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-star w-5 text-center"></i> Avis Clients
                </a>

                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-600 mb-2 mt-6">Utilisateurs</p>

                <a href="<?php echo URLROOT; ?>/admin/customers" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'customers') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-users w-5 text-center"></i> Clients
                </a>
                <a href="<?php echo URLROOT; ?>/admin/users_add" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?php echo (strpos($_GET['url'] ?? '', 'users_add') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-user-shield w-5 text-center"></i> Staff Admin
                </a>
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-900/50">
                <a href="<?php echo URLROOT; ?>" target="_blank" class="flex items-center gap-2 text-xs text-slate-500 hover:text-white mb-3 pl-4 transition">
                    <i class="fas fa-external-link-alt"></i> Voir le site public
                </a>
                <a href="<?php echo URLROOT; ?>/users/logout" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all text-sm font-bold">
                    <i class="fas fa-power-off"></i> Déconnexion
                </a>
            </div>
        </aside>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            <header class="sticky top-0 z-40 glass-effect border-b border-slate-200">
                <div class="flex items-center justify-between px-6 py-4">
                    
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-primary transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="hidden md:block">
                        <h2 class="text-lg font-bold text-slate-800">
                            Bonjour, <?php echo isset($_SESSION['user_name']) ? explode(' ', $_SESSION['user_name'])[0] : 'Admin'; ?> 👋
                        </h2>
                        <p class="text-xs text-slate-500">Administration Exotikha</p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                                <div class="text-right hidden md:block">
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-primary transition"><?php echo $_SESSION['user_name']; ?></p>
                                    <p class="text-xs text-slate-500">Super Admin</p>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary to-secondary p-[2px]">
                                    <div class="h-full w-full rounded-full bg-white flex items-center justify-center overflow-hidden">
                                         <span class="font-bold text-slate-700"><?php echo substr($_SESSION['user_name'], 0, 1); ?></span>
                                    </div>
                                </div>
                            </button>

                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50 animate-fade-in-down" style="display: none;">
                                <a href="<?php echo URLROOT; ?>/users/account" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-primary"><i class="fas fa-user-circle mr-2"></i> Mon Profil</a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="<?php echo URLROOT; ?>/users/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt mr-2"></i> Déconnexion</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="w-full h-full p-6 md:p-8 bg-background">
                <?php flash('product_message'); ?>
                <?php flash('admin_msg'); ?>