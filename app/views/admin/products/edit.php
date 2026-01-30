<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Product</h1>
            <p class="text-sm text-slate-500">Update product information, inventory and media.</p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $data['product']->id; ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrfField(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">Basic Information</h2>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required value="<?php echo $data['product']->name; ?>"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">SKU</label>
                            <input type="text" name="sku" value="<?php echo $data['product']->sku; ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                                <option value="active" <?php echo ($data['product']->status == 'active') ? 'selected' : ''; ?>>Active (Visible)</option>
                                <option value="draft" <?php echo ($data['product']->status == 'draft') ? 'selected' : ''; ?>>Draft (Hidden)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="price" step="0.01" required value="<?php echo $data['product']->price; ?>"
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Sale Price</label>
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

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">Media</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Main Image</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-primary transition relative">
                                <?php if(!empty($data['product']->image)): ?>
                                    <img src="<?php echo URLROOT . '/public/img/' . $data['product']->image; ?>" class="w-32 h-32 object-cover rounded-lg mx-auto mb-3 shadow-sm" id="currentMainImg">
                                <?php endif; ?>
                                
                                <label class="cursor-pointer block">
                                    <span class="text-xs text-primary font-bold hover:underline">Change Image</span>
                                    <input type="file" name="image" class="hidden" onchange="previewImage(this, 'mainPreview')">
                                </label>
                                <img id="mainPreview" src="" class="hidden w-32 h-32 object-cover rounded-lg mx-auto mt-2 shadow-sm border border-green-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Gallery</label>
                            
                            <?php if(!empty($data['gallery'])): ?>
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <?php foreach($data['gallery'] as $img): ?>
                                        <div class="relative group">
                                            <img src="<?php echo URLROOT . '/public/img/' . $img->image; ?>" class="w-full h-16 object-cover rounded border border-slate-100">
                                            <a href="<?php echo URLROOT; ?>/admin/gallery_delete/<?php echo $img->id; ?>" 
                                               onclick="return confirm('Delete this image?');"
                                               class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-bl-lg text-[10px] hover:bg-red-600 transition">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-primary transition relative">
                                <label class="cursor-pointer block">
                                    <i class="fas fa-plus-circle text-2xl text-slate-300 mb-1"></i>
                                    <span class="block text-xs text-slate-400">Add Photos</span>
                                    <input type="file" name="gallery[]" multiple class="hidden" onchange="previewGallery(this)">
                                </label>
                                <div id="galleryPreview" class="grid grid-cols-3 gap-2 mt-2 hidden"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h2 class="text-sm font-bold uppercase text-slate-500 tracking-wide">Inventory</h2>
                        
                        <input type="hidden" name="has_variants" value="<?php echo !empty($data['variants']) ? '1' : '0'; ?>">
                    </div>

                    <?php if(empty($data['variants'])): ?>
                        <div id="simpleProductStock">
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Stock Quantity</label>
                            <input type="number" name="simple_stock" value="<?php echo $data['product']->stock; ?>" class="w-32 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary font-bold text-slate-800">
                            <p class="text-xs text-slate-400 mt-2 italic"><i class="fas fa-info-circle"></i> To add variants (Size/Color), please switch this product type in the database or delete and re-create.</p>
                        </div>
                    <?php else: ?>
                        <div>
                            <div class="overflow-hidden rounded-xl border border-slate-200 mb-6">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase font-bold">
                                        <tr>
                                            <th class="p-3">Size</th>
                                            <th class="p-3">Color</th>
                                            <th class="p-3 text-center">Stock</th>
                                            <th class="p-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php foreach($data['variants'] as $v): ?>
                                        <tr>
                                            <td class="p-3 font-bold text-slate-700"><?php echo $v->size; ?></td>
                                            <td class="p-3 text-slate-600"><?php echo $v->color; ?></td>
                                            <td class="p-3 text-center">
                                                <input type="number" name="existing_variants[<?php echo $v->id; ?>]" value="<?php echo $v->stock; ?>" class="w-16 text-center border border-slate-200 rounded p-1 text-xs font-bold">
                                            </td>
                                            <td class="p-3 text-right">
                                                <a href="<?php echo URLROOT; ?>/admin/variant_delete/<?php echo $v->id; ?>" onclick="return confirm('Delete variant?');" class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        
                                        <tbody id="variantsContainer"></tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-2">Add New Variant</p>
                                <div class="flex flex-wrap gap-3 items-end">
                                    <div class="flex-1">
                                        <select id="variantSize" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm">
                                            <option value="">Size</option>
                                            <option value="S">S</option><option value="M">M</option><option value="L">L</option><option value="XL">XL</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" id="variantColor" placeholder="Color" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm">
                                    </div>
                                    <div class="w-20">
                                        <input type="number" id="variantStock" value="1" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm text-center">
                                    </div>
                                    <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-9 h-[38px] rounded-lg hover:bg-primary transition shadow-md">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                    <option value="<?php echo $cat->id; ?>" <?php echo ($data['product']->category_id == $cat->id) ? 'selected' : ''; ?>>
                                        <?php echo $cat->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Product Type</label>
                        <div class="relative">
                            <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 appearance-none focus:outline-none focus:border-primary cursor-pointer text-sm font-medium text-slate-700">
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>" <?php echo ($data['product']->type_id == $type->id) ? 'selected' : ''; ?>>
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
                                <option value="Femme" <?php echo ($data['product']->gender == 'Femme') ? 'selected' : ''; ?>>Women</option>
                                <option value="Homme" <?php echo ($data['product']->gender == 'Homme') ? 'selected' : ''; ?>>Men</option>
                                <option value="Enfant" <?php echo ($data['product']->gender == 'Enfant') ? 'selected' : ''; ?>>Kids</option>
                                <option value="Unisexe" <?php echo ($data['product']->gender == 'Unisexe') ? 'selected' : ''; ?>>Unisex</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-xl shadow-primary/30 hover:bg-indigo-600 transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    <i class="fas fa-save"></i> Update Product
                </button>

            </div>
        </div>
    </form>
</div>

<script>
    // JS for Adding NEW Variants (Same logic as add.php)
    let variantCount = 0;
    function addVariantRow() {
        const size = document.getElementById('variantSize').value;
        const color = document.getElementById('variantColor').value;
        const stock = document.getElementById('variantStock').value;

        if(size === '' && color === '') {
            alert("Select size or color.");
            return;
        }

        const rowId = variantCount++;
        const html = `
            <tr id="new-row-${rowId}" class="bg-blue-50/50">
                <td class="p-3 font-bold text-blue-800">${size} <span class="text-[10px] uppercase bg-blue-100 px-1 rounded">New</span></td>
                <td class="p-3 text-blue-800">${color}</td>
                <td class="p-3 text-center">
                    <span class="font-bold">${stock}</span>
                    <input type="hidden" name="new_variants[${rowId}][size]" value="${size}">
                    <input type="hidden" name="new_variants[${rowId}][color]" value="${color}">
                    <input type="hidden" name="new_variants[${rowId}][stock]" value="${stock}">
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="document.getElementById('new-row-${rowId}').remove()" class="text-red-400"><i class="fas fa-times"></i></button>
                </td>
            </tr>
        `;
        document.getElementById('variantsContainer').insertAdjacentHTML('beforeend', html);
        
        // Reset inputs
        document.getElementById('variantColor').value = '';
        document.getElementById('variantSize').value = '';
    }

    // Image Preview
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGallery(input) {
        const previewContainer = document.getElementById('galleryPreview');
        previewContainer.innerHTML = '';
        if (input.files) {
            previewContainer.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                var reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-16 object-cover rounded shadow-sm';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>