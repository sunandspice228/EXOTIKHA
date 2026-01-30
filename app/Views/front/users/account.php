<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php 
    // --- 1. RÉCUPÉRATION DES DONNÉES UTILISATEUR ---
    $user = $data['user'];
    
    // Identité
    $firstName = $user->first_name ?? 'Customer';
    $lastName = $user->last_name ?? '';
    $email = $user->email ?? '';
    
    // --- 2. RÉCUPÉRATION INTELLIGENTE DE L'ADRESSE ---
    // On vérifie si le modèle renvoie 'shipping_phone' (mapping) ou 'phone' (nom colonne BDD)
    $shippingPhone = $user->shipping_phone ?? $user->phone ?? '';
    $shippingAddress = $user->shipping_address ?? $user->address ?? '';
    
    // Formatage Nom
    $fullName = trim($firstName . ' ' . $lastName);
    if(empty($fullName)) $fullName = 'Exotikha Customer';
    
    // Initiale Avatar
    $initial = strtoupper(substr($firstName, 0, 1));

    // --- 3. LISTE DES QUARTIERS (Identique au Checkout) ---
    $accraLocations = [
        'Adabraka', 'Airport Residential', 'Achimota', 'Cantonments', 
        'Dansoman', 'Dzorwulu', 'East Legon', 'Labadi', 'Labone', 
        'Lapaz', 'Madina', 'Osu', 'Ridge', 'Spintex', 'Tema (Port)', 
        'Teshie', 'University of Ghana (Legon)', 'Weija', 'West Legon', 
        'Other location (Accra)'
    ];
    sort($accraLocations); 
?>

<div class="bg-slate-50 py-10 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 text-center md:text-left">
        <h1 class="text-3xl font-serif font-bold text-slate-900">My Account</h1>
        <p class="text-slate-500 text-sm">Manage your orders and shipping addresses.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg uppercase shadow-sm">
                        <?php echo $initial; ?>
                    </div>
                    <div class="truncate overflow-hidden">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Welcome,</p>
                        <p class="font-bold text-slate-900 truncate text-sm" title="<?php echo $fullName; ?>">
                            <?php echo $firstName; ?>
                        </p>
                    </div>
                </div>

                <?php if(function_exists('isAdmin') && isAdmin()): ?>
                    <div class="p-4 bg-slate-900">
                        <a href="<?php echo URLROOT; ?>/admin" class="block w-full bg-red-600 text-white text-center py-3 rounded-lg font-bold text-xs uppercase tracking-widest shadow-lg hover:bg-red-700 transition transform hover:scale-105">
                            <i class="fas fa-user-shield mr-2"></i> Admin Panel
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard'; ?>
                <nav class="p-2 space-y-1">
                    <a href="?tab=dashboard" class="nav-link <?php echo ($currentTab == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
                    </a>
                    <a href="?tab=orders" class="nav-link <?php echo ($currentTab == 'orders') ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-bag w-5 text-center"></i> Orders
                    </a>
                    <a href="?tab=addresses" class="nav-link <?php echo ($currentTab == 'addresses') ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt w-5 text-center"></i> Addresses
                    </a>
                    <a href="?tab=details" class="nav-link <?php echo ($currentTab == 'details') ? 'active' : ''; ?>">
                        <i class="fas fa-user-cog w-5 text-center"></i> Profile
                    </a>
                    
                    <a href="<?php echo URLROOT; ?>/users/logout" class="nav-link text-red-500 mt-4 border-t border-slate-50 hover:bg-red-50 hover:text-red-600">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                    </a>
                </nav>
            </div>
        </aside>

        <div class="flex-1">
            <?php flash('product_message'); ?>

            <?php if($currentTab == 'dashboard'): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm mb-8">
                    <h2 class="text-2xl font-serif font-bold text-slate-900 mb-4 italic">Hello, <?php echo $firstName; ?> 👋</h2>
                    <p class="text-slate-600 leading-relaxed text-sm">This is your dashboard. You can view your <a href="?tab=orders" class="text-primary font-bold hover:underline">recent orders</a>, manage your <a href="?tab=addresses" class="text-primary font-bold hover:underline">shipping addresses</a> for Accra, and edit your <a href="?tab=details" class="text-primary font-bold hover:underline">personal details</a>.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="?tab=orders" class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center gap-4 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-white text-indigo-600 rounded-full flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-slate-800"><?php echo isset($data['orders']) ? count($data['orders']) : 0; ?></span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Total Orders</span>
                        </div>
                    </a>

                    <a href="?tab=addresses" class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-4 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-white text-emerald-600 rounded-full flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-800 mt-1">Accra Only</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Delivery Zone</span>
                        </div>
                    </a>
                </div>

            <?php elseif($currentTab == 'orders'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif text-slate-900">Order History</h2>
                <?php if(empty($data['orders'])): ?>
                    <div class="bg-white p-12 rounded-2xl border border-slate-100 text-center shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                        <p class="text-slate-500 italic mb-6">You haven't placed any orders yet.</p>
                        <a href="<?php echo URLROOT; ?>/shop" class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-primary transition text-sm uppercase tracking-wide">Start Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">ID</th>
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4 text-right">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                    <?php foreach($data['orders'] as $order): ?>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-bold text-slate-900">#<?php echo isset($order->order_number) ? $order->order_number : $order->id; ?></td>
                                            <td class="px-6 py-4 text-slate-500"><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-500">
                                                    <?php echo ucfirst($order->status); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($order->total_amount, 2); ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" class="text-slate-400 hover:text-primary transition"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            <?php elseif($currentTab == 'addresses'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">My Addresses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm opacity-60">
                        <h3 class="font-bold border-b pb-2 mb-4 text-slate-500 uppercase text-xs tracking-wider">Billing Address</h3>
                        <p class="text-sm text-slate-500 italic">The billing address is automatically linked to your shipping address to simplify the process.</p>
                        <div class="mt-4 text-xs text-slate-400">
                            <i class="fas fa-lock mr-1"></i> Managed automatically
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-visible">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-primary opacity-5 rounded-bl-full pointer-events-none"></div>
                        
                        <h3 class="font-bold border-b pb-2 mb-4 text-primary uppercase text-xs tracking-wider">Shipping Address (Accra)</h3>
                        
                        <form action="<?php echo URLROOT; ?>/users/update_address" method="POST" class="space-y-4">
                        <?php echo csrfField(); ?>   
                        <input type="hidden" name="address_type" value="shipping">
                            
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Phone Number</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fas fa-mobile-alt"></i></span>
                                    <input type="text" name="shipping_phone" 
                                           value="<?php echo htmlspecialchars($shippingPhone); ?>" 
                                           placeholder="024XXXXXXX" 
                                           class="w-full pl-9 rounded-lg border-slate-200 focus:ring-primary text-sm font-semibold text-slate-700 placeholder:font-normal">
                                </div>
                            </div>

                            <div class="relative">
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Neighborhood / Area</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"><i class="fas fa-map-signs"></i></span>
                                    <select name="shipping_address" class="w-full pl-9 rounded-lg border-slate-200 focus:ring-primary text-sm bg-white cursor-pointer appearance-none font-semibold text-slate-700">
                                        <option value="" disabled <?php echo empty($shippingAddress) ? 'selected' : ''; ?>>-- Select Location --</option>
                                        <?php foreach($accraLocations as $loc): ?>
                                            <option value="<?php echo $loc; ?>" <?php echo ($shippingAddress == $loc) ? 'selected' : ''; ?>>
                                                <?php echo $loc; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400"><i class="fas fa-chevron-down text-xs"></i></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">City</label>
                                    <input type="text" name="shipping_city" value="Accra" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Region</label>
                                    <input type="text" name="shipping_region" value="Greater Accra" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-500 font-bold text-sm cursor-not-allowed" readonly>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-slate-800 transition text-xs uppercase tracking-wide mt-2 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Save Address
                            </button>
                        </form>
                    </div>
                </div>

            <?php elseif($currentTab == 'details'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">Account Details</h2>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm max-w-2xl">
                    <form action="<?php echo URLROOT; ?>/users/update_details" method="POST" class="space-y-6">
                    <?php echo csrfField(); ?>    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Full Name</label>
                                <input type="text" name="name" value="<?php echo $fullName; ?>" class="w-full rounded-xl border-slate-200 focus:ring-primary transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Email</label>
                                <input type="email" name="email" value="<?php echo $email; ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6 mt-2">
                            <p class="font-bold text-slate-900 mb-4 text-sm uppercase flex items-center gap-2"><i class="fas fa-lock"></i> Change Password</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Current Password</label>
                                    <input type="password" name="current_password" class="w-full rounded-xl border-slate-200 focus:ring-primary transition">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">New Password</label>
                                        <input type="password" name="new_password" class="w-full rounded-xl border-slate-200 focus:ring-primary transition">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Confirm</label>
                                        <input type="password" name="confirm_password" class="w-full rounded-xl border-slate-200 focus:ring-primary transition">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg uppercase tracking-widest text-sm mt-4">Update Details</button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; color: #64748b; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; }
    .nav-link:hover { background: #f1f5f9; color: #ca8a04; }
    .nav-link.active { background: #ca8a04 !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>