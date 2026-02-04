<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_customers_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_customers_subtitle'); ?></p>
        </div>
        </div>

    <?php flash('customer_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Client</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Email / Tel</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Commandes</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Dépensé</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Inscrit le</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['customers'])): ?>
                        <tr><td colspan="6" class="p-8 text-center text-slate-400"><?php echo lang('adm_no_customers'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach($data['customers'] as $c): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        <?php echo strtoupper(substr($c->name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block"><?php echo $c->name; ?></span>
                                        <span class="text-xs text-slate-400">ID: #<?php echo $c->id; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <div class="flex flex-col">
                                    <a href="mailto:<?php echo $c->email; ?>" class="text-sm text-slate-600 hover:text-primary transition font-medium"><?php echo $c->email; ?></a>
                                    <span class="text-xs text-slate-400"><?php echo $c->phone ?? '-'; ?></span>
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    <?php echo $c->order_count; ?>
                                </span>
                            </td>
                            <td class="p-5 font-bold text-emerald-600">
                                <?php echo number_format($c->total_spent, 2) . ' ' . CURRENCY_SYMBOL; ?>
                            </td>
                            <td class="p-5 text-xs text-slate-500">
                                <?php echo date('d M Y', strtotime($c->created_at)); ?>
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo URLROOT; ?>/admin/customers_details/<?php echo $c->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-blue-500 hover:text-blue-700 hover:border-blue-300 transition shadow-sm" title="<?php echo lang('btn_view_details'); ?>">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="<?php echo URLROOT; ?>/admin/customers_delete/<?php echo $c->id; ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-400 hover:text-red-600 hover:border-red-300 transition shadow-sm" title="<?php echo lang('btn_delete'); ?>">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>