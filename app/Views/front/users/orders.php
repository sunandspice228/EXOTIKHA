<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-slate-900">My Account</h1>
            <p class="text-slate-500">Welcome back, <?php echo $_SESSION['user_name']; ?></p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                    <nav class="flex flex-col">
                        <a href="<?php echo URLROOT; ?>/users/profile" class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50 text-slate-600 font-bold text-sm transition flex items-center gap-3">
                            <i class="far fa-user w-5 text-center"></i> Personal Info
                        </a>
                        <a href="<?php echo URLROOT; ?>/orders" class="px-6 py-4 border-b border-slate-50 bg-slate-900 text-white font-bold text-sm transition flex items-center gap-3">
                            <i class="fas fa-shopping-bag w-5 text-center"></i> My Orders
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/wishlist" class="px-6 py-4 border-b border-slate-50 hover:bg-slate-50 text-slate-600 font-bold text-sm transition flex items-center gap-3">
                            <i class="far fa-heart w-5 text-center"></i> Wishlist
                        </a>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="px-6 py-4 text-red-500 hover:bg-red-50 font-bold text-sm transition flex items-center gap-3">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="flex-1">
                
                <h2 class="text-xl font-bold text-slate-800 mb-6">Order History</h2>

                <?php if(empty($data['orders'])): ?>
                    
                    <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">No orders yet</h3>
                        <p class="text-slate-500 mb-6 text-sm">You haven't placed any orders yet. Discover our collection!</p>
                        <a href="<?php echo URLROOT; ?>/shop" class="inline-block bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary transition">
                            Start Shopping
                        </a>
                    </div>

                <?php else: ?>

                    <div class="space-y-4">
                        <?php foreach($data['orders'] as $order): ?>
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 transition hover:shadow-md hover:border-slate-300 group">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900">
                                                Order #<?php echo $order->order_number; ?>
                                            </h3>
                                            <p class="text-xs text-slate-500 mt-1">
                                                Placed on <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                <?php echo $order->total_items ?? '1'; ?> item(s)
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-slate-50">
                                        
                                        <?php 
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'shipped' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'delivered' => 'bg-green-100 text-green-700 border-green-200',
                                                'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                                            ];
                                            $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        ?>
                                        <div class="text-center">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border <?php echo $colorClass; ?>">
                                                <?php echo $order->status; ?>
                                            </span>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-[10px] uppercase text-slate-400 font-bold">Total</p>
                                            <p class="font-bold text-slate-900"><?php echo number_format($order->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?></p>
                                        </div>

                                        <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>