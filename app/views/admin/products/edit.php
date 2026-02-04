<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto pb-20">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight"><?php echo lang('title_edit_product'); ?></h1>
            <p class="text-sm text-slate-500"><?php echo lang('subtitle_edit_product'); ?></p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_edit/<?php echo $data['product']->id; ?>" method="POST" enctype="multipart/form-data">
        <?php if(function_exists('csrfField')) echo csrfField(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">
                        <i class="fas fa-info-circle mr-2"></i> <?php echo lang('section_basic_info'); ?>
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Name (EN) <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name_en" required value="<?php echo $data['product']->name; ?>"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium text-slate-800">
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-bold text-indigo-600 uppercase">Nom (FR)</label>
                                    <button type="button" onclick="autoTranslate('name_en', 'name_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                                <div class="relative">
                                    <input type="text" name="name_fr" id="name_fr" value="<?php echo $data['product']->name_fr; ?>"
                                           class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition font-medium text-slate-800">
                                    <div id="loader_name_fr" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Description (EN)</label>
                                <textarea name="description" id="desc_en" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition text-slate-600 leading-relaxed"><?php echo $data['product']->description; ?></textarea>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-bold text-indigo-600 uppercase">Description (FR)</label>
                                    <button type="button" onclick="autoTranslate('desc_en', 'desc_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                                <div class="relative">
                                    <textarea name="description_fr" id="desc_fr" rows="5" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition text-slate-600 leading-relaxed"><?php echo $data['product']->description_fr; ?></textarea>
                                    <div id="loader_desc_fr" class="hidden absolute right-3 top-3 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">
                        <i class="fas fa-tag mr-2"></i> <?php echo lang('section_pricing'); ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Price <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="price" step="0.01" required value="<?php echo $data['product']->price; ?>"
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Promo Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-red-400 font-bold"><?php echo CURRENCY_SYMBOL; ?></span>
                                <input type="number" name="promo_price" step="0.01" value="<?php echo $data['product']->promo_price; ?>"
                                       class="w-full pl-10 pr-4 py-3 bg-red-50 border border-red-100 rounded-xl focus:outline-none focus:border-red-400 font-bold text-red-600">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">SKU</label>
                            <input type="text" name="sku" value="<?php echo $data['product']->sku; ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">
                        <i class="fas fa-images mr-2"></i> <?php echo lang('section_media'); ?>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Main Image</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-primary transition relative">
                                <?php if(!empty($data['product']->image)): ?>
                                    <img src="<?php echo URLROOT . '/public/uploads/' . $data['product']->image; ?>" class="w-32 h-32 object-cover rounded-lg mx-auto mb-3 shadow-sm border border-slate-100" id="currentMainImg">
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
                                            <img src="<?php echo URLROOT . '/public/uploads/' . $img->image; ?>" class="w-full h-16 object-cover rounded border border-slate-100">
                                            <a href="<?php echo URLROOT; ?>/admin/gallery_delete/<?php echo $img->id; ?>" 
                                               onclick="return confirm('Delete this image?');"
                                               class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-bl-lg text-[10px] hover:bg-red-600 transition opacity-0 group-hover:opacity-100">
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
                        <h2 class="text-sm font-bold uppercase text-slate-500 tracking-wide">
                            <i class="fas fa-boxes mr-2"></i> <?php echo lang('section_inventory'); ?>
                        </h2>
                    </div>

                    <?php if(empty($data['variants'])): ?>
                        <div id="simpleProductStock">
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase ml-1">Stock Quantity</label>
                            <input type="number" name="stock" value="<?php echo $data['product']->stock; ?>" class="w-32 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary font-bold text-slate-800">
                            
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-100 rounded-lg text-xs text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i> 
                                Want to add variants (Size/Color)? Adding variants will switch this product to "Variable Product" mode.
                            </div>
                            
                            <button type="button" onclick="switchToVariants()" class="mt-2 text-xs font-bold text-primary hover:underline">
                                + Add Variants Now
                            </button>
                        </div>

                        <div id="variableProductStock" class="hidden mt-6 animate-fade-in-up">
                            <div class="bg-slate-50 p-4 rounded-xl mb-4 border border-slate-200">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-2"><i class="fas fa-plus-circle text-primary"></i> Add Variant</p>
                                <div class="flex flex-wrap gap-3 items-end">
                                    <div class="flex-1"><input type="text" id="variantSize" placeholder="Size" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm uppercase"></div>
                                    <div class="flex-1"><input type="text" id="variantColor" placeholder="Color" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm"></div>
                                    <div class="w-20"><input type="number" id="variantStock" value="1" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm text-center"></div>
                                    <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-9 h-[38px] rounded-lg hover:bg-primary transition shadow-md"><i class="fas fa-plus text-xs"></i></button>
                                </div>
                            </div>
                            
                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase font-bold">
                                        <tr><th class="p-3">Size</th><th class="p-3">Color</th><th class="p-3 text-center">Stock</th><th class="p-3 text-right">Action</th></tr>
                                    </thead>
                                    <tbody id="variantsContainer" class="divide-y divide-slate-100 bg-white"></tbody>
                                </table>
                            </div>
                        </div>

                    <?php else: ?>
                        <div id="variableProductStock">
                            <div class="overflow-hidden rounded-xl border border-slate-200 mb-6">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200 text-xs text-slate-500 uppercase font-bold">
                                        <tr><th class="p-3">Size</th><th class="p-3">Color</th><th class="p-3 text-center">Stock</th><th class="p-3 text-right">Action</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        <?php foreach($data['variants'] as $v): ?>
                                        <tr>
                                            <td class="p-3 font-bold text-slate-700"><?php echo $v->size; ?></td>
                                            <td class="p-3 text-slate-600"><?php echo $v->color ?? '-'; ?></td>
                                            <td class="p-3 text-center">
                                                <input type="number" name="existing_variants[<?php echo $v->id; ?>]" value="<?php echo $v->stock; ?>" class="w-16 text-center border border-slate-200 rounded p-1 text-xs font-bold focus:border-primary outline-none">
                                            </td>
                                            <td class="p-3 text-right">
                                                <a href="<?php echo URLROOT; ?>/admin/variant_delete/<?php echo $v->id; ?>" onclick="return confirm('Delete this variant?');" class="text-red-400 hover:text-red-600 w-6 h-6 flex items-center justify-center rounded-full hover:bg-red-50 ml-auto">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tbody id="variantsContainer"></tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-2"><i class="fas fa-plus-circle text-primary"></i> Add New Variant</p>
                                <div class="flex flex-wrap gap-3 items-end">
                                    <div class="flex-1"><input type="text" id="variantSize" placeholder="Size" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm uppercase"></div>
                                    <div class="flex-1"><input type="text" id="variantColor" placeholder="Color" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm"></div>
                                    <div class="w-20"><input type="number" id="variantStock" value="1" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-sm text-center"></div>
                                    <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-9 h-[38px] rounded-lg hover:bg-primary transition shadow-md"><i class="fas fa-plus text-xs"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="space-y-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-4 tracking-wide border-b border-slate-50 pb-2">
                        <i class="fas fa-sitemap mr-2"></i> <?php echo lang('section_organization'); ?>
                    </h2>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                            <option value="active" <?php echo ($data['product']->status == 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="draft" <?php echo ($data['product']->status == 'draft') ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Category</label>
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                            <?php foreach($data['categories'] as $cat): ?>
                                <option value="<?php echo $cat->id; ?>" <?php echo ($data['product']->category_id == $cat->id) ? 'selected' : ''; ?>>
                                    <?php echo $cat->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Type</label>
                        <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                            <option value="">-- None --</option>
                            <?php foreach($data['types'] as $type): ?>
                                <option value="<?php echo $type->id; ?>" <?php echo ($data['product']->type_id == $type->id) ? 'selected' : ''; ?>>
                                    <?php echo $type->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Gender</label>
                        <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary">
                            <option value="Women" <?php echo ($data['product']->gender == 'Women') ? 'selected' : ''; ?>>Women</option>
                            <option value="Men" <?php echo ($data['product']->gender == 'Men') ? 'selected' : ''; ?>>Men</option>
                            <option value="Unisex" <?php echo ($data['product']->gender == 'Unisex') ? 'selected' : ''; ?>>Unisex</option>
                            <option value="Kids" <?php echo ($data['product']->gender == 'Kids') ? 'selected' : ''; ?>>Kids</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-indigo-600 transition transform hover:-translate-y-1 flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // 1. TRADUCTION AUTO (API MyMemory)
    async function autoTranslate(sourceId, targetId) {
        const sourceInput = document.getElementById(sourceId);
        const targetInput = document.getElementById(targetId);
        const loader = document.getElementById('loader_' + targetId);
        const text = sourceInput.value.trim();

        if(!text) { alert("Please enter English text first."); return; }
        if(loader) loader.classList.remove('hidden');
        
        try {
            const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|fr`);
            const data = await response.json();
            if(data.responseData.translatedText) {
                targetInput.value = data.responseData.translatedText;
                targetInput.classList.add('bg-green-50');
                setTimeout(() => targetInput.classList.remove('bg-green-50'), 1000);
            }
        } catch (e) { targetInput.value = text; } 
        finally { if(loader) loader.classList.add('hidden'); }
    }

    // 2. SWITCH SIMPLE -> VARIANTS (Affichage Formulaire)
    function switchToVariants() {
        document.getElementById('simpleProductStock').classList.add('hidden');
        // On désactive l'input simple stock pour qu'il ne soit pas envoyé
        document.querySelector('#simpleProductStock input').disabled = true;
        document.getElementById('variableProductStock').classList.remove('hidden');
    }

    // 3. AJOUT VARIANTE (JS)
    // IMPORTANT : On utilise des noms de champs spécifiques "new_variants" pour les distinguer des existants
    let newVarCount = 0;
    function addVariantRow() {
        const size = document.getElementById('variantSize').value.trim();
        const color = document.getElementById('variantColor').value.trim();
        const stock = document.getElementById('variantStock').value;

        if(!size) { alert("Size required"); return; }

        const rowId = newVarCount++;
        let container = document.getElementById('variantsContainer');
        
        const html = `
            <tr id="new-row-${rowId}" class="bg-blue-50/50 transition">
                <td class="p-3 font-bold text-blue-800">${size} <span class="text-[10px] bg-blue-100 px-1 rounded uppercase ml-1">New</span>
                    <input type="hidden" name="variants_size[]" value="${size}">
                </td>
                <td class="p-3 text-blue-800">${color || '-'}
                    <input type="hidden" name="variants_color[]" value="${color}">
                </td>
                <td class="p-3 text-center">
                    <span class="font-bold">${stock}</span>
                    <input type="hidden" name="variants_stock[]" value="${stock}">
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="document.getElementById('new-row-${rowId}').remove()" class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', html);
        
        document.getElementById('variantSize').value = '';
        document.getElementById('variantColor').value = '';
    }

    // 4. PREVIEW IMAGES
    function previewImage(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(id).src = e.target.result;
                document.getElementById(id).classList.remove('hidden');
                if(document.getElementById('currentMainImg')) document.getElementById('currentMainImg').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGallery(input) {
        const div = document.getElementById('galleryPreview');
        div.innerHTML = ''; div.classList.remove('hidden');
        Array.from(input.files).forEach(f => {
            let r = new FileReader();
            r.onload = e => {
                let img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-16 object-cover rounded shadow-sm';
                div.appendChild(img);
            };
            r.readAsDataURL(f);
        });
    }
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>