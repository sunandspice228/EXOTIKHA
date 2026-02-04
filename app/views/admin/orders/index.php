<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_orders_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_orders_subtitle'); ?></p>
        </div>
        
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> <?php echo lang('btn_print_list'); ?>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider"><?php echo lang('col_order_id'); ?></th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider"><?php echo lang('col_customer'); ?></th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider"><?php echo lang('col_date'); ?></th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider"><?php echo lang('col_status'); ?></th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider"><?php echo lang('col_total'); ?></th>
                        <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider text-right"><?php echo lang('col_action'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['orders'])): ?>
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-shopping-basket text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-bold text-slate-600"><?php echo lang('adm_no_orders'); ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['orders'] as $order): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            
                            <td class="p-5">
                                <span class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded">
                                    #<?php echo $order->order_number; ?>
                                </span>
                            </td>

                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase border border-indigo-100">
                                        <?php echo substr($order->full_name ?? 'U', 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo $order->full_name; ?></p>
                                        <div class="text-[10px] text-slate-400 flex flex-col">
                                            <span><?php echo $order->shipping_phone ?? 'No phone'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-600">
                                        <?php echo date('M d, Y', strtotime($order->created_at)); ?>
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        <?php echo date('H:i', strtotime($order->created_at)); ?>
                                    </span>
                                </div>
                            </td>

                            <td class="p-5">
                                <?php 
                                    $statusClass = match($order->status) {
                                        'completed', 'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'shipped' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                        default => 'bg-amber-100 text-amber-700 border-amber-200'
                                    };
                                ?>
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex w-fit items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase border <?php echo $statusClass; ?>">
                                        <?php echo lang('status_' . $order->status); ?>
                                    </span>
                                    
                                    <span class="text-[10px] font-bold <?php echo ($order->payment_status == 'paid') ? 'text-emerald-600' : 'text-amber-600'; ?>">
                                        <?php echo lang('status_' . $order->payment_status); ?>
                                    </span>
                                </div>
                            </td>

                            <td class="p-5">
                                <span class="font-black text-slate-800">
                                    <?php echo number_format($order->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?>
                                </span>
                            </td>

                            <td class="p-5 text-right">
                                <a href="<?php echo URLROOT; ?>/admin/orders/details/<?php echo $order->id; ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition shadow-sm"
                                   title="<?php echo lang('btn_view_details'); ?>">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center text-xs text-slate-500 font-medium">
            <span><?php echo lang('adm_showing_last'); ?> <strong><?php echo isset($data['orders']) ? count($data['orders']) : 0; ?></strong> <?php echo lang('adm_orders_count'); ?></span>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>