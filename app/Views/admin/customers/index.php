<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Customer Database</h1>
            <p class="text-slate-500 mt-1">View and manage your registered users.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-file-export"></i> Export CSV
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Customer</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Contact</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Location</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Joined Date</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['customers'])): ?>
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-400 italic">
                                No customers registered yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['customers'] as $customer): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm uppercase border border-indigo-100">
                                        <?php echo substr($customer->full_name, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm"><?php echo $customer->full_name; ?></p>
                                        <p class="text-xs text-slate-400">ID: #<?php echo $customer->id; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <div class="flex flex-col">
                                    <a href="mailto:<?php echo $customer->email; ?>" class="text-sm text-slate-600 hover:text-primary transition flex items-center gap-2">
                                        <i class="far fa-envelope text-xs text-slate-400"></i> <?php echo $customer->email; ?>
                                    </a>
                                    <?php if(!empty($customer->billing_phone)): ?>
                                        <span class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                            <i class="fas fa-phone text-[10px] text-slate-400"></i> <?php echo $customer->billing_phone; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-400 italic mt-1">No phone</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-5">
                                <?php if(!empty($customer->billing_city)): ?>
                                    <span class="text-sm text-slate-700 font-medium"><?php echo $customer->billing_city; ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs italic">Unknown</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-5">
                                <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-1 rounded border border-slate-200 uppercase tracking-wide">
                                    <?php echo date('M d, Y', strtotime($customer->created_at)); ?>
                                </span>
                            </td>
                            <td class="p-5 text-right">
                                <button class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary transition shadow-sm">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center text-xs text-slate-500">
            <span>Showing all <?php echo count($data['customers']); ?> customers</span>
            <div class="flex gap-1">
                <button class="px-3 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100" disabled>Previous</button>
                <button class="px-3 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>