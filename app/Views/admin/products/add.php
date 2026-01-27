<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Add New Product</h1>
            <p class="text-sm text-slate-500">Create a new item for your store inventory.</p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_add" method="POST" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">Basic Information</h2>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. African Print Dress" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="price" step="0.01" required placeholder="0.00"
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Promo Price <span class="text-slate-400 font-normal normal-case">(Optional)</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-red-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="promo_price" step="0.01" placeholder="0.00"
                                       class="w-full pl-10 pr-4 py-3 bg-red-50 border border-red-100 rounded-xl focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-400/20 transition font-bold text-red-600">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition text-slate-600 leading-relaxed" placeholder="Product details, materials, care instructions..."></textarea>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100" id="stock-section">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h2 class="text-sm font-bold uppercase text-slate-500 tracking-wide">Inventory & Variants</h2>
                        
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="has_variants" id="hasVariantsToggle" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-primary transition">Has Variants (Size/Color)</span>
                        </label>
                    </div>

                    <div id="simpleProductStock">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Stock Quantity</label>
                        <input type="number" name="simple_stock" value="10" class="w-32 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-bold text-slate-800">
                    </div>

                    <div id="variableProductStock" class="hidden">
                        
                        <div class="bg-slate-50 p-5 rounded-xl mb-6 border border-slate-200">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3 flex items-center gap-2"><i class="fas fa-plus-circle text-primary"></i> Add Variant</p>
                            
                            <div class="flex flex-wrap gap-4 items-end">
                                <div class="flex-1 min-w-[120px]">
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Size</label>
                                    <select id="variantSize" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
                                        <option value="">-- None --</option>
                                        <?php 
                                            // Dynamic Attribute Logic (Simplified)
                                            // In a real app, filter by Attribute ID
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
                                        <optgroup label="Other Options">
                                            <?php foreach($data['attributes'] as $attr): ?>
                                                <?php foreach(explode(',', $attr->values_list) as $val): ?>
                                                    <option value="<?php echo trim($val); ?>"><?php echo $attr->name . ': ' . trim($val); ?></option>
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
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Qty</label>
                                    <input type="number" id="variantStock" value="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-slate-800 text-center">
                                </div>

                                <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-10 h-[38px] rounded-lg flex items-center justify-center hover:bg-primary transition shadow-md">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase font-bold">
                                    <tr>
                                        <th class="p-3">Size</th>
                                        <th class="p-3">Color</th>
                                        <th class="p-3 text-center">Stock</th>
                                        <th class="p-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="variantsContainer" class="divide-y divide-slate-100 bg-white">
                                    </tbody>
                            </table>
                            <div id="emptyVariantsMsg" class="p-6 text-center text-slate-400 text-xs italic">
                                No variants added yet. Use the form above.
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
                                    <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Type / Collection</label>
                        <div class="relative">
                            <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Gender</label>
                        <div class="relative">
                            <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <option value="women">Women</option>
                                <option value="men">Men</option>
                                <option value="kids">Kids</option>
                                <option value="unisex">Unisex</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-4 tracking-wide border-b border-slate-50 pb-2">Main Image</h2>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-primary hover:bg-primary/5 transition cursor-pointer relative group">
                        <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center transition-transform group-hover:scale-105">
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-3 group-hover:bg-white group-hover:text-primary transition">
                                <i class="fas fa-cloud-upload-alt text-xl text-slate-400 group-hover:text-primary"></i>
                            </div>
                            <p class="text-sm text-slate-600 font-bold">Click to Upload</p>
                            <p class="text-[10px] text-slate-400 mt-1">JPG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-xl shadow-primary/30 hover:bg-indigo-600 transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <i class="fas fa-check-circle"></i> Save Product
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
    const emptyMsg = document.getElementById('emptyVariantsMsg');
    let variantCount = 0;

    // Toggle Simple/Variable
    toggle.addEventListener('change', function() {
        if(this.checked){
            simpleStock.classList.add('hidden');
            variableStock.classList.remove('hidden');
        } else {
            simpleStock.classList.remove('hidden');
            variableStock.classList.add('hidden');
        }
    });

    // Add Variant Row
    function addVariantRow() {
        const size = document.getElementById('variantSize').value;
        const color = document.getElementById('variantColor').value;
        const stock = document.getElementById('variantStock').value;

        if(size === '' && color === '') {
            alert("Please select at least a Size or a Color.");
            return;
        }

        // Hide empty message
        emptyMsg.style.display = 'none';

        const rowId = variantCount++;
        
        const html = `
            <tr id="row-${rowId}" class="hover:bg-slate-50 transition animate-fade-in-up">
                <td class="p-3">
                    <span class="font-bold text-slate-700">${size || '-'}</span>
                    <input type="hidden" name="variants[${rowId}][size]" value="${size}">
                </td>
                <td class="p-3">
                    <span class="text-slate-600">${color || '-'}</span>
                    <input type="hidden" name="variants[${rowId}][color]" value="${color}">
                </td>
                <td class="p-3 text-center">
                    <span class="bg-slate-100 px-2 py-1 rounded text-xs font-bold text-slate-800">${stock}</span>
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