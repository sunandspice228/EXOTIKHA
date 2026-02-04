<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/customers" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo lang('title_customer_details'); ?></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-3xl mx-auto mb-4 shadow-lg shadow-indigo-200">
                    <?php echo strtoupper(substr($data['customer']->name, 0, 1)); ?>
                </div>
                <h2 class="text-xl font-bold text-slate-800"><?php echo $data['customer']->name; ?></h2>
                <p class="text-slate-500 text-sm font-medium mb-6">Customer</p>
                
                <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-slate-800"><?php echo count($data['orders']); ?></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Orders</span>
                    </div>
                    <div class="text-center">
                        <?php 
                            $total = 0;
                            foreach($data['orders'] as $o) $total += $o->total_amount;
                        ?>
                        <span class="block text-2xl font-bold text-emerald-600"><?php echo number_format($total, 2); ?></span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Spent</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold uppercase text-slate-400 tracking-wider mb-4">Contact Info</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope mt-1 text-slate-400"></i>
                        <span class="text-slate-600 font-medium break-all"><?php echo $data['customer']->email; ?></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone mt-1 text-slate-400"></i>
                        <span class="text-slate-600 font-medium"><?php echo $data['customer']->phone ?? 'N/A'; ?></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-slate-400"></i>
                        <span class="text-slate-600 font-medium"><?php echo $data['customer']->address ?? 'No address provided'; ?></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-calendar mt-1 text-slate-400"></i>
                        <span class="text-slate-600">Joined: <?php echo date('d M Y', strtotime($data['customer']->created_at)); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Order History</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                            <tr>
                                <th class="p-4">Order ID</th>
                                <th class="p-4">Date</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Total</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(empty($data['orders'])): ?>
                                <tr><td colspan="5" class="p-8 text-center text-slate-400">No orders yet.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['orders'] as $order): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 font-mono font-bold text-slate-700">#<?php echo str_pad($order->id, 6, '0', STR_PAD_LEFT); ?></td>
                                    <td class="p-4 text-sm text-slate-500"><?php echo date('d M Y', strtotime($order->created_at)); ?></td>
                                    <td class="p-4">
                                        <?php 
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800'
                                            ];
                                            $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase <?php echo $color; ?>">
                                            <?php echo $order->status; ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-right font-bold text-slate-800">
                                        <?php echo number_format($order->total_amount, 2) . ' ' . CURRENCY_SYMBOL; ?>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="<?php echo URLROOT; ?>/admin/orders_details/<?php echo $order->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>