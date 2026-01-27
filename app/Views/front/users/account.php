<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-10 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 text-center md:text-left">
        <h1 class="text-3xl font-serif font-bold text-slate-900">My Account</h1>
        <p class="text-slate-500 text-sm">Manage your orders, personal details, and preferences.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg uppercase shadow-sm">
                        <?php echo substr($data['user']->full_name, 0, 1); ?>
                    </div>
                    <div class="truncate overflow-hidden">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Welcome,</p>
                        <p class="font-bold text-slate-900 truncate text-sm"><?php echo explode(' ', $data['user']->full_name)[0]; ?></p>
                    </div>
                </div>

                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
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
                        <i class="fas fa-shopping-bag w-5 text-center"></i> My Orders
                    </a>
                    <a href="?tab=track" class="nav-link <?php echo ($currentTab == 'track') ? 'active' : ''; ?>">
                        <i class="fas fa-search-location w-5 text-center"></i> Track Order
                    </a>
                    
                    <?php if(isset($data['wishlist'])): ?>
                    <a href="?tab=wishlist" class="nav-link <?php echo ($currentTab == 'wishlist') ? 'active' : ''; ?> hover:text-pink-500">
                        <i class="fas fa-heart w-5 text-center"></i> WishlistModel
                    </a>
                    <?php endif; ?>

                    <a href="?tab=addresses" class="nav-link <?php echo ($currentTab == 'addresses') ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt w-5 text-center"></i> Addresses
                    </a>
                    <a href="?tab=details" class="nav-link <?php echo ($currentTab == 'details') ? 'active' : ''; ?>">
                        <i class="fas fa-user-cog w-5 text-center"></i> Account Details
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
                    <h2 class="text-2xl font-serif font-bold text-slate-900 mb-4 italic">Hello, <?php echo explode(' ', $data['user']->full_name)[0]; ?> 👋</h2>
                    <p class="text-slate-600 leading-relaxed text-sm">From your account dashboard you can view your <a href="?tab=orders" class="text-accent font-bold hover:underline">recent orders</a>, manage your <a href="?tab=addresses" class="text-accent font-bold hover:underline">shipping and billing addresses</a>, and edit your <a href="?tab=details" class="text-accent font-bold hover:underline">password and account details</a>.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="?tab=orders" class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center gap-4 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-white text-indigo-600 rounded-full flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-slate-800"><?php echo count($data['orders']); ?></span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Total Orders</span>
                        </div>
                    </a>

                    <a href="?tab=wishlist" class="p-6 bg-pink-50 rounded-2xl border border-pink-100 flex items-center gap-4 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-white text-pink-500 rounded-full flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-slate-800"><?php echo isset($data['wishlist']) ? count($data['wishlist']) : 0; ?></span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">WishlistModel</span>
                        </div>
                    </a>

                    <a href="?tab=review" class="p-6 bg-amber-50 rounded-2xl border border-amber-100 flex items-center gap-4 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-white text-amber-500 rounded-full flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-slate-800"><?php echo isset($data['my_reviews']) ? count($data['my_reviews']) : 0; ?></span>
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Reviews</span>
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
                        <a href="<?php echo URLROOT; ?>/shop" class="inline-block bg-slate-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-accent transition text-sm uppercase tracking-wide">
                            Start Shopping
                        </a>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">Order ID</th>
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                    <?php foreach($data['orders'] as $order): ?>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                #<?php echo isset($order->order_number) ? $order->order_number : str_pad($order->id, 5, '0', STR_PAD_LEFT); ?>
                                            </td>
                                            
                                            <td class="px-6 py-4 text-slate-500">
                                                <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                            </td>
                                            
                                            <td class="px-6 py-4">
                                                <?php 
                                                    $statusClass = 'bg-slate-100 text-slate-500';
                                                    $statusLabel = ucfirst($order->status);
                                                    
                                                    switch($order->status){
                                                        case 'pending': $statusClass = 'bg-yellow-50 text-yellow-600 border border-yellow-100'; break;
                                                        case 'processing': $statusClass = 'bg-blue-50 text-blue-600 border border-blue-100'; break;
                                                        case 'shipped': $statusClass = 'bg-purple-50 text-purple-600 border border-purple-100'; break;
                                                        case 'delivered': $statusClass = 'bg-green-50 text-green-600 border border-green-100'; break;
                                                        case 'cancelled': $statusClass = 'bg-red-50 text-red-600 border border-red-100'; break;
                                                    }
                                                ?>
                                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?php echo $statusClass; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                            
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                <?php echo CURRENCY_SYMBOL . number_format($order->total_amount, 2); ?>
                                            </td>
                                            
                                            <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                                <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" class="text-slate-400 hover:text-accent transition" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if($order->status == 'shipped' || $order->status == 'processing'): ?>
                                                    <a href="?tab=track&oid=<?php echo $order->id; ?>" class="text-accent hover:underline font-black text-[10px] uppercase">Track</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            <?php elseif($currentTab == 'track'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif text-slate-900">Track Order</h2>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm max-w-lg">
                    <p class="text-sm text-slate-500 mb-6">To track your order please enter your Order ID in the box below and press the "Track" button.</p>
                    <form action="<?php echo URLROOT; ?>/pages/track" method="POST" class="space-y-4">
                    <?php echo csrfField(); ?>    
                    <div>
                            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Order ID</label>
                            <input type="text" name="order_number" value="<?php echo isset($_GET['oid']) ? $_GET['oid'] : ''; ?>" class="w-full rounded-xl border-slate-200 focus:ring-accent transition" placeholder="e.g. 10243" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Billing Email</label>
                            <input type="email" name="email" value="<?php echo $data['user']->email; ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed" readonly>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-accent transition shadow-lg uppercase tracking-widest text-sm">
                            Track Order
                        </button>
                    </form>
                </div>

            <?php elseif($currentTab == 'wishlist'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif text-pink-600">My WishlistModel</h2>
                <?php if(empty($data['wishlist'])): ?>
                    <div class="bg-white p-10 rounded-2xl border border-slate-100 text-center text-slate-400 italic">
                        Your wishlist is currently empty. <a href="<?php echo URLROOT; ?>/shop" class="text-accent font-bold not-italic ml-2 underline">Go Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach($data['wishlist'] as $product): ?>
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition">
                                <div class="relative h-48 overflow-hidden bg-slate-100">
                                    <?php if(!empty($product->image)): ?>
                                        <img src="<?php echo URLROOT . '/public/img/' . $product->image; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    <?php endif; ?>
                                    <a href="<?php echo URLROOT; ?>/wishlist/remove/<?php echo $product->id; ?>" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-white transition shadow-sm" title="Remove">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </a>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-slate-900 truncate mb-1"><?php echo $product->name; ?></h4>
                                    <p class="text-accent font-bold text-sm mb-4"><?php echo CURRENCY_SYMBOL . $product->price; ?></p>
                                    <a href="<?php echo URLROOT; ?>/shop/product/<?php echo $product->id; ?>" class="block w-full bg-slate-900 text-white text-center py-2 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-accent transition">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php elseif($currentTab == 'addresses'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">My Addresses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 class="font-bold border-b pb-2 mb-4 text-slate-500 uppercase text-xs tracking-wider">Billing Address</h3>
                        <form action="<?php echo URLROOT; ?>/users/update_address" method="POST" class="space-y-4">
                        <?php echo csrfField(); ?>    
                        <input type="hidden" name="address_type" value="billing">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Phone Number</label>
                                <input type="text" name="billing_phone" value="<?php echo $data['user']->billing_phone ?? ''; ?>" placeholder="+233..." class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Full Address</label>
                                <input type="text" name="billing_address" value="<?php echo $data['user']->billing_address ?? ''; ?>" placeholder="Street name, House No." class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">City</label>
                                    <input type="text" name="billing_city" value="<?php echo $data['user']->billing_city ?? ''; ?>" placeholder="City" class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Region</label>
                                    <input type="text" name="billing_region" value="<?php echo $data['user']->billing_region ?? ''; ?>" placeholder="Region" class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-slate-800 text-white py-3 rounded-lg font-bold hover:bg-black transition text-xs uppercase tracking-wide mt-2">Save Billing</button>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-accent opacity-5 rounded-bl-full"></div>
                        <h3 class="font-bold border-b pb-2 mb-4 text-accent uppercase text-xs tracking-wider">Shipping Address</h3>
                        <form action="<?php echo URLROOT; ?>/users/update_address" method="POST" class="space-y-4">
                        <?php echo csrfField(); ?>   
                        <input type="hidden" name="address_type" value="shipping">
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Phone Number</label>
                                <input type="text" name="shipping_phone" value="<?php echo $data['user']->shipping_phone ?? ''; ?>" placeholder="+233..." class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Full Address</label>
                                <input type="text" name="shipping_address" value="<?php echo $data['user']->shipping_address ?? ''; ?>" placeholder="GPS Address / Street" class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">City</label>
                                    <input type="text" name="shipping_city" value="<?php echo $data['user']->shipping_city ?? ''; ?>" placeholder="City" class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-slate-400 mb-1 block">Region</label>
                                    <input type="text" name="shipping_region" value="<?php echo $data['user']->shipping_region ?? ''; ?>" placeholder="Region" class="w-full rounded-lg border-slate-200 focus:ring-accent text-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-accent text-white py-3 rounded-lg font-bold hover:bg-indigo-600 transition text-xs uppercase tracking-wide mt-2">Save Shipping</button>
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
                                <input type="text" name="name" value="<?php echo $data['user']->full_name; ?>" class="w-full rounded-xl border-slate-200 focus:ring-accent transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Email Address</label>
                                <input type="email" name="email" value="<?php echo $data['user']->email; ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6 mt-2">
                            <p class="font-bold text-slate-900 mb-4 text-sm uppercase flex items-center gap-2"><i class="fas fa-lock"></i> Password Change</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Current Password (leave blank to keep unchanged)</label>
                                    <input type="password" name="current_password" class="w-full rounded-xl border-slate-200 focus:ring-accent transition">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">New Password</label>
                                        <input type="password" name="new_password" class="w-full rounded-xl border-slate-200 focus:ring-accent transition">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="w-full rounded-xl border-slate-200 focus:ring-accent transition">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg uppercase tracking-widest text-sm mt-4">
                            Save Changes
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; color: #64748b; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; }
    .nav-link:hover { background: #f1f5f9; color: #4f46e5; }
    .nav-link.active { background: #4f46e5 !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
</style>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>