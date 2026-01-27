<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Product</h1>
            <p class="text-sm text-slate-500 font-medium">SKU: <span class="font-mono text-slate-600"><?php echo $data['product']->sku; ?></span></p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $data['product']->id; ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">Product Information</h2>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Product Name</label>
                        <input type="text" name="name" value="<?php echo $data['product']->name; ?>" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="price" step="0.01" value="<?php echo $data['product']->price; ?>" required 
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Promo Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-red-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="promo_price" step="0.01" value="<?php echo $data['product']->promo_price; ?>" 
                                       class="w-full pl-10 pr-4 py-3 bg-red-50 border border-red-100 rounded-xl focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/20 transition font-bold text-red-600">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-slate-600 leading-relaxed"><?php echo $data['product']->description; ?></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100" id="stock-section">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h2 class="text-sm font-bold uppercase text-slate-500 tracking-wide">Inventory Management</h2>
                        
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="has_variants" id="hasVariantsToggle" value="1" 
                                       class="sr-only peer" <?php echo (count($data['variants']) > 0) ? 'checked' : ''; ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-primary transition">Has Variants</span>
                        </label>
                    </div>

                    <div id="simpleProductStock" class="<?php echo (count($data['variants']) > 0) ? 'hidden' : ''; ?>">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Global Stock</label>
                        <input type="number" name="simple_stock" value="<?php echo $data['product']->stock; ?>" class="w-32 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-bold text-slate-800">
                        <p class="text-[10px] text-slate-400 mt-2"><i class="fas fa-info-circle"></i> Used only if "Has Variants" is disabled.</p>
                    </div>

                    <div id="variableProductStock" class="<?php echo (count($data['variants']) > 0) ? '' : 'hidden'; ?>">
                        
                        <?php if(count($data['variants']) > 0): ?>
                            <div class="mb-8">
                                <h3 class="text-xs font-bold uppercase text-slate-400 mb-3 tracking-widest">Existing Variants</h3>
                                <div class="border border-slate-200 rounded-xl overflow-hidden">
                                    <table class="w-full text-left text-sm">
                                        <thead class="bg-slate-50 text-slate-500 font-bold text-[10px] uppercase">
                                            <tr>
                                                <th class="p-3">Size</th>
                                                <th class="p-3">Color</th>
                                                <th class="p-3 text-center">Current Stock</th>
                                                <th class="p-3 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <?php foreach($data['variants'] as $v): ?>
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="p-3 font-bold text-slate-700"><?php echo $v->size; ?></td>
                                                <td class="p-3 text-slate-600">
                                                    <span class="inline-block w-2 h-2 rounded-full mr-1 bg-slate-400"></span>
                                                    <?php echo $v->color; ?>
                                                </td>
                                                <td class="p-3 text-center font-bold text-slate-800"><?php echo $v->stock; ?></td>
                                                <td class="p-3 text-right">
                                                    <a href="<?php echo URLROOT; ?>/admin/products_delete_variant/<?php echo $v->id; ?>/<?php echo $data['product']->id; ?>" 
                                                       onclick="return confirm('Permanently delete this variant?')"
                                                       class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase tracking-wide border border-red-200 hover:border-red-400 px-2 py-1 rounded transition">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                            <p class="text-xs font-bold text-slate-600 uppercase mb-4 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-primary"></i> Add New Variants
                            </p>
                            
                            <div class="flex flex-wrap gap-4 items-end mb-4">
                                <div class="flex-1 min-w-[120px]">
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Size</label>
                                    <select id="variantSize" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
                                        <option value="">-- None --</option>
                                        <?php 
                                            foreach($data['attributes'] as $attr){
                                                if(stripos($attr->name, 'taille') !== false || stripos($attr->name, 'size') !== false){
                                                    echo '<optgroup label="'.$attr->name.'">';
                                                    foreach(explode(',', $attr->values_list) as $val){
                                                        echo '<option value="'.trim($val).'">'.trim($val).'</option>';
                                                    }
                                                    echo '</optgroup>';
                                                }
                                            }
                                        ?>
                                        <optgroup label="Other">
                                            <?php foreach($data['attributes'] as $attr): ?>
                                                <?php foreach(explode(',', $attr->values_list) as $val): ?>
                                                    <option value="<?php echo trim($val); ?>"><?php echo trim($val); ?></option>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="flex-1 min-w-[120px]">
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Color</label>
                                    <select id="variantColor" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
                                        <option value="">-- None --</option>
                                        <?php 
                                            foreach($data['attributes'] as $attr){
                                                if(stripos($attr->name, 'couleur') !== false || stripos($attr->name, 'color') !== false){
                                                    echo '<optgroup label="'.$attr->name.'">';
                                                    foreach(explode(',', $attr->values_list) as $val){
                                                        echo '<option value="'.trim($val).'">'.trim($val).'</option>';
                                                    }
                                                    echo '</optgroup>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="w-24">
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Stock</label>
                                    <input type="number" id="variantStock" value="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-slate-800 text-center">
                                </div>

                                <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-10 h-[38px] rounded-lg flex items-center justify-center hover:bg-primary transition shadow-md">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <div class="border border-slate-200 rounded-lg overflow-hidden bg-white" id="newVariantsTable" style="display:none;">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-green-50 text-green-700 text-[10px] uppercase font-bold">
                                        <tr>
                                            <th class="p-3">New Size</th>
                                            <th class="p-3">New Color</th>
                                            <th class="p-3 text-center">Stock</th>
                                            <th class="p-3 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantsContainer" class="divide-y divide-slate-100">
                                        </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="space-y-8">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-4 tracking-wide border-b border-slate-50 pb-2">Organization</h2>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Category</label>
                        <div class="relative">
                            <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>" <?php echo ($cat->id == $data['product']->category_id) ? 'selected' : ''; ?>>
                                        <?php echo $cat->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Type</label>
                        <div class="relative">
                            <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>" <?php echo ($type->id == $data['product']->type_id) ? 'selected' : ''; ?>>
                                        <?php echo $type->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Gender</label>
                        <div class="relative">
                            <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <option value="women" <?php echo ($data['product']->gender == 'women') ? 'selected' : ''; ?>>Women</option>
                                <option value="men" <?php echo ($data['product']->gender == 'men') ? 'selected' : ''; ?>>Men</option>
                                <option value="kids" <?php echo ($data['product']->gender == 'kids') ? 'selected' : ''; ?>>Kids</option>
                                <option value="unisex" <?php echo ($data['product']->gender == 'unisex') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-4 tracking-wide border-b border-slate-50 pb-2">Product Image</h2>
                    
                    <?php if(!empty($data['product']->image)): ?>
                        <div class="mb-4 rounded-xl overflow-hidden border border-slate-200 relative group">
                            <img src="<?php echo URLROOT . '/public/img/' . $data['product']->image; ?>" class="w-full h-auto">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>
                        </div>
                    <?php endif; ?>

                    <div class="relative">
                        <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:uppercase file:bg-slate-100 file:text-slate-600 hover:file:bg-primary hover:file:text-white transition cursor-pointer border border-slate-200 rounded-xl">
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-xl shadow-primary/30 hover:bg-indigo-600 transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>

            </div>
        </div>
    </form>
</div>

<script>
    const toggle = document.getElementById('hasVariantsToggle');
    const simpleStock = document.getElementById('simpleProductStock');
    const variableStock = document.getElementById('variableProductStock');
    const container = document.getElementById('variantsContainer');
    const newTable = document.getElementById('newVariantsTable');
    let variantCount = 0;

    toggle.addEventListener('change', function() {
        if(this.checked){
            simpleStock.classList.add('hidden');
            variableStock.classList.remove('hidden');
        } else {
            simpleStock.classList.remove('hidden');
            variableStock.classList.add('hidden');
        }
    });

    function addVariantRow() {
        const size = document.getElementById('variantSize').value;
        const color = document.getElementById('variantColor').value;
        const stock = document.getElementById('variantStock').value;

        if(size === '' && color === '') {
            alert("Please select at least a Size or a Color.");
            return;
        }

        newTable.style.display = 'block';

        const rowId = variantCount++;
        
        const html = `
            <tr id="row-${rowId}" class="hover:bg-slate-50 transition">
                <td class="p-3">
                    <span class="font-bold text-slate-700">${size || '-'}</span>
                    <input type="hidden" name="variants[${rowId}][size]" value="${size}">
                </td>
                <td class="p-3">
                    <span class="text-slate-600">${color || '-'}</span>
                    <input type="hidden" name="variants[${rowId}][color]" value="${color}">
                </td>
                <td class="p-3 text-center">
                    <span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-bold">${stock}</span>
                    <input type="hidden" name="variants[${rowId}][stock]" value="${stock}">
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="document.getElementById('row-${rowId}').remove()" class="w-6 h-6 rounded-full bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm">
                        <i class="fas fa-times text-[10px]"></i>
                    </button>
                </td>
            </tr>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
    }
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>