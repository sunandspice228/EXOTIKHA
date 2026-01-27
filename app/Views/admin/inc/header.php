<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Exotikha</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        sidebar: '#0f172a', // Slate 900
                        primary: '#6366f1', // Indigo 500
                        accent: '#818cf8',  // Indigo 400
                        background: '#f1f5f9', // Slate 100
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f1f5f9; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Nav Item Styles */
        .nav-item { transition: all 0.2s ease-in-out; }
        .nav-item:hover { background-color: rgba(255,255,255,0.05); color: white; transform: translateX(4px); }
        .nav-item.active { 
            background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%); 
            color: white; 
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="text-slate-600 antialiased font-sans bg-background h-screen overflow-hidden flex">

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden backdrop-blur-sm"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar text-slate-400 transition-transform duration-300 lg:static lg:translate-x-0 shadow-2xl flex flex-col h-full">
        
        <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-900">
            <a href="<?php echo URLROOT; ?>/admin" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary/30 group-hover:scale-110 transition">E</div>
                <span class="text-xl font-bold text-white tracking-wide">EXOTIKHA<span class="text-primary">.</span></span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
            
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3 mt-1">Analytics</p>
            
            <a href="<?php echo URLROOT; ?>/admin" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (!isset($_GET['url']) || $_GET['url'] == 'admin') ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie w-5 text-center"></i> Dashboard
            </a>
            
            <a href="<?php echo URLROOT; ?>/admin/orders" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'orders') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag w-5 text-center"></i> Orders
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3 mt-8">Store Management</p>

            <a href="<?php echo URLROOT; ?>/admin/products" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'products') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-tshirt w-5 text-center"></i> Products
            </a>
            <a href="<?php echo URLROOT; ?>/admin/categories" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'categories') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-layer-group w-5 text-center"></i> Categories
            </a>
            <a href="<?php echo URLROOT; ?>/admin/attributes" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'attributes') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-ruler-combined w-5 text-center"></i> Attributes
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3 mt-8">Content & Marketing</p>

            <a href="<?php echo URLROOT; ?>/admin/blog" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'blog') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-pen-nib w-5 text-center"></i> Blog Posts
            </a>
            <a href="<?php echo URLROOT; ?>/admin/reviews" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'reviews') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-star w-5 text-center"></i> Reviews
            </a>

            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3 mt-8">User Management</p>

            <a href="<?php echo URLROOT; ?>/admin/customers" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'customers') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-users w-5 text-center"></i> Customers
            </a>
            <a href="<?php echo URLROOT; ?>/admin/users" class="nav-item flex items-center gap-3 px-4 py-3.5 rounded-xl font-medium text-sm <?php echo (strpos($_GET['url'] ?? '', 'admin/users') !== false) ? 'active' : ''; ?>">
                <i class="fas fa-user-shield w-5 text-center"></i> Admin Staff
            </a>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-900">
            <a href="<?php echo URLROOT; ?>" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-lg text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition mb-2">
                <i class="fas fa-external-link-alt"></i> Live Store
            </a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition font-bold text-sm">
                <i class="fas fa-power-off"></i> Logout
            </a>
        </div>
    </aside>

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200">
            <div class="flex items-center justify-between px-6 py-4">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-800 p-2 rounded-lg hover:bg-slate-100 transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="hidden md:block">
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Admin Console</h1>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    
                    <button class="relative text-slate-400 hover:text-slate-600 transition">
                        <i class="far fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden md:block leading-tight">
                                <p class="text-sm font-bold text-slate-800"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrator'; ?></p>
                                <p class="text-[10px] uppercase font-bold text-primary tracking-wider">Super Admin</p>
                            </div>
                            <div class="h-10 w-10 rounded-full bg-slate-100 border-2 border-white shadow-sm flex items-center justify-center overflow-hidden">
                                <?php if(isset($_SESSION['user_image']) && !empty($_SESSION['user_image'])): ?>
                                    <img src="<?php echo URLROOT . '/' . $_SESSION['user_image']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="font-bold text-slate-600 text-lg"><?php echo substr($_SESSION['user_name'] ?? 'A', 0, 1); ?></span>
                                <?php endif; ?>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:text-slate-600 transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in-up origin-top-right">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-xs text-slate-400 font-bold uppercase">Signed in as</p>
                                <p class="text-sm font-bold text-slate-900 truncate"><?php echo $_SESSION['user_email'] ?? 'admin@exotikha.com'; ?></p>
                            </div>
                            <a href="<?php echo URLROOT; ?>/users/account" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary font-medium transition">
                                <i class="fas fa-user-circle w-5"></i> My Profile
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="<?php echo URLROOT; ?>/users/logout" class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 font-bold transition">
                                <i class="fas fa-sign-out-alt w-5"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-8 bg-slate-50/50">
            <div class="mb-6 space-y-4">
                <?php flash('product_message'); ?>
                <?php flash('admin_msg'); ?>
            </div>