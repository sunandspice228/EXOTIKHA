<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-5xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight"><?php echo lang('adm_attributes_title'); ?></h1>
            <p class="text-slate-500 mt-1"><?php echo lang('adm_attributes_subtitle'); ?></p>
        </div>
        <a href="<?php echo URLROOT; ?>/admin/attributes_add" class="bg-slate-900 hover:bg-primary text-white px-6 py-3 rounded-xl font-bold transition shadow-lg flex items-center gap-2 transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> <span><?php echo lang('btn_add_attribute'); ?></span>
        </a>
    </div>

    <?php flash('attr_msg'); ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider"><?php echo lang('col_name'); ?></th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Valeurs (EN)</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider">Valeurs (FR)</th>
                        <th class="p-5 text-[10px] font-black uppercase text-slate-400 tracking-wider text-right"><?php echo lang('col_action'); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['attributes'])): ?>
                        <tr><td colspan="4" class="p-8 text-center text-slate-400"><?php echo lang('adm_no_attributes'); ?></td></tr>
                    <?php else: ?>
                        <?php foreach($data['attributes'] as $attr): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-5">
                                <span class="font-bold text-slate-800 block text-base"><?php echo $attr->name; ?></span>
                                <?php if(!empty($attr->name_fr)): ?>
                                    <span class="text-xs text-indigo-500 font-medium"><?php echo $attr->name_fr; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="p-5">
                                <div class="flex flex-wrap gap-1">
                                    <?php 
                                    $vals = explode(',', $attr->values_en);
                                    foreach($vals as $v): 
                                    ?>
                                        <span class="px-2 py-1 bg-slate-100 rounded text-xs font-mono text-slate-600"><?php echo trim($v); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="p-5">
                                <div class="flex flex-wrap gap-1">
                                    <?php 
                                    $valsFr = explode(',', $attr->values_fr);
                                    foreach($valsFr as $v): 
                                    ?>
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-xs font-mono"><?php echo trim($v); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo URLROOT; ?>/admin/attributes_edit/<?php echo $attr->id; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-blue-500 hover:text-blue-700 hover:border-blue-300 transition shadow-sm"><i class="fas fa-pen text-xs"></i></a>
                                    <a href="<?php echo URLROOT; ?>/admin/attributes_delete/<?php echo $attr->id; ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-red-400 hover:text-red-600 hover:border-red-300 transition shadow-sm"><i class="fas fa-trash-alt text-xs"></i></a>
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