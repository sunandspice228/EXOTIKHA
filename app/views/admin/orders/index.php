<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Commandes Clients</h1>
        <div class="text-sm text-slate-500">Total: <strong><?php echo count($data['orders']); ?></strong> commandes</div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-xs font-black uppercase text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Commande</th>
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Statut</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                <?php foreach($data['orders'] as $order): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-bold text-primary">#<?php echo $order->order_number; ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo $order->full_name; ?></div>
                            <div class="text-xs text-slate-400"><?php echo $order->email; ?></div>
                        </td>
                        <td class="px-6 py-4 text-slate-500"><?php echo date('d/m/Y', strtotime($order->created_at)); ?></td>
                        <td class="px-6 py-4 font-bold"><?php echo CURRENCY_SYMBOL . number_format($order->total_amount, 2); ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $statusClass = 'bg-slate-100 text-slate-500';
                                if($order->status == 'paid') $statusClass = 'bg-green-100 text-green-700';
                                if($order->status == 'shipped') $statusClass = 'bg-blue-100 text-blue-700';
                                if($order->status == 'cancelled') $statusClass = 'bg-red-100 text-red-700';
                            ?>
                            <span class="px-2 py-1 rounded text-[10px] font-black uppercase <?php echo $statusClass; ?>">
                                <?php echo $order->status; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo URLROOT; ?>/admin/orders_details/<?php echo $order->id; ?>" class="bg-white border border-slate-200 text-slate-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition">
                                Détails
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>