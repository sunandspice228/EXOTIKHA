<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-5xl mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Product Attributes</h1>
            <p class="text-slate-500 mt-1">Manage global options for your products.</p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/attributes_add" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary transition shadow-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> New Attribute
        </a>
    </div>

    <?php flash('attr_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider">Name</th>
                    <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider w-1/2">Values</th>
                    <th class="p-5 font-black text-slate-400 text-[10px] uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($data['attributes'])): ?>
                    <tr>
                        <td colspan="3" class="p-12 text-center text-slate-400 italic">
                            No attributes defined yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['attributes'] as $attr): ?>
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="p-5">
                            <span class="font-bold text-slate-800 text-sm"><?php echo $attr->name; ?></span>
                        </td>
                        <td class="p-5">
                            <div class="flex flex-wrap gap-1">
                                <?php 
                                    $values = explode(',', $attr->values_list);
                                    $limit = 5;
                                    foreach(array_slice($values, 0, $limit) as $val): 
                                ?>
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs border border-slate-200"><?php echo trim($val); ?></span>
                                <?php endforeach; ?>
                                
                                <?php if(count($values) > $limit): ?>
                                    <span class="text-xs text-slate-400 self-center">+<?php echo count($values) - $limit; ?> more</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition">
                                <a href="<?php echo URLROOT; ?>/admin/attributes_edit/<?php echo $attr->id; ?>" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-primary transition bg-white border border-slate-200 shadow-sm">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?php echo URLROOT; ?>/admin/attributes_delete/<?php echo $attr->id; ?>" onclick="return confirm('Delete this attribute?');" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-500 transition bg-white border border-slate-200 shadow-sm">
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

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>