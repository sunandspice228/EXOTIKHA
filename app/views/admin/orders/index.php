<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Orders</h1>
        <p class="text-slate-500 text-sm">Manage and track customer orders</p>
    </div>
    
    <div class="flex gap-4">
        <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
            <span class="block text-xs text-slate-400 font-bold uppercase">Total Orders</span>
            <span class="block text-xl font-bold text-slate-800"><?php echo count($data['orders']); ?></span>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500">
                    <th class="p-4 pl-6">Order ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Payment</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right pr-6">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($data['orders'])): ?>
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400">No orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['orders'] as $order): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="p-4 pl-6 font-mono font-bold text-primary">
                                #<?php echo $order->order_number; ?>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800 text-sm"><?php echo $order->full_name; ?></p>
                                <p class="text-xs text-slate-400"><?php echo $order->email; ?></p>
                            </td>
                            <td class="p-4 text-sm text-slate-600">
                                <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                <span class="block text-xs text-slate-400"><?php echo date('H:i', strtotime($order->created_at)); ?></span>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                <?php echo number_format($order->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?>
                            </td>
                            <td class="p-4">
                                <?php if($order->payment_status == 'paid'): ?>
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2 py-1 rounded text-[10px] font-bold uppercase border border-green-100">
                                        <i class="fas fa-check-circle"></i> Paid
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 px-2 py-1 rounded text-[10px] font-bold uppercase border border-yellow-100">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php endif; ?>
                                <span class="block text-[10px] text-slate-400 mt-1 uppercase"><?php echo $order->payment_method; ?></span>
                            </td>
                            <td class="p-4">
                                <?php 
                                    $statusColors = [
                                        'pending' => 'bg-gray-100 text-gray-600',
                                        'processing' => 'bg-blue-50 text-blue-600',
                                        'shipped' => 'bg-purple-50 text-purple-600',
                                        'delivered' => 'bg-green-50 text-green-600',
                                        'cancelled' => 'bg-red-50 text-red-600'
                                    ];
                                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?php echo $color; ?>">
                                    <?php echo $order->status; ?>
                                </span>
                            </td>
                            <td class="p-4 text-right pr-6">
                                <a href="<?php echo URLROOT; ?>/admin/orders_details/<?php echo $order->id; ?>" class="bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-center">
        <span class="text-xs text-slate-400 font-bold">Showing all orders</span>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>