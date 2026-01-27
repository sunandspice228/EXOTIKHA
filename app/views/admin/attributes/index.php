<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Product Attributes</h1>
            <p class="text-slate-500 mt-1">Manage variations like Sizes, Colors, and Materials.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/attributes_add" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-primary/30 flex items-center gap-2">
            <i class="fas fa-plus"></i> New Attribute
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Attribute Name</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider">Values Preview</th>
                        <th class="p-5 font-bold text-slate-500 text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['attributes'])): ?>
                        <tr>
                            <td colspan="3" class="p-10 text-center text-slate-400 italic">
                                No attributes defined yet. Start by adding one.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['attributes'] as $attr): ?>
                        <tr class="hover:bg-slate-50 transition group">
                            <td class="p-5 font-bold text-slate-800 text-sm">
                                <?php echo $attr->name; ?>
                            </td>
                            <td class="p-5">
                                <?php if(!empty($attr->values_list)): ?>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach(explode(',', $attr->values_list) as $val): ?>
                                            <span class="inline-block bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded border border-slate-200">
                                                <?php echo trim($val); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs italic">No values assigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-5 text-right space-x-2">
                                <a href="<?php echo URLROOT; ?>/admin/attributes_edit/<?php echo $attr->id; ?>" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-primary hover:text-white transition shadow-sm" title="Edit">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/admin/attributes_delete/<?php echo $attr->id; ?>" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-red-500 hover:text-white transition shadow-sm" onclick="return confirm('Are you sure you want to delete this attribute? This will affect linked products.');" title="Delete">
                                    <i class="fas fa-trash-alt text-xs"></i>
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

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>