<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-6xl mx-auto pb-20">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/products" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition shadow-sm group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight"><?php echo lang('title_add_product'); ?></h1>
            <p class="text-sm text-slate-500"><?php echo lang('subtitle_add_product'); ?></p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/products_add" method="POST" enctype="multipart/form-data" id="productForm">
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
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Name (English) <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name_en" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition font-medium">
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-bold text-indigo-600 uppercase">Nom (Français)</label>
                                    <button type="button" onclick="autoTranslate('name_en', 'name_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1" title="Copier/Traduire de l'anglais">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                                <div class="relative">
                                    <input type="text" name="name_fr" id="name_fr" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition font-medium">
                                    <div id="loader_name_fr" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                        <i class="fas fa-circle-notch fa-spin text-indigo-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Description (English)</label>
                                <textarea name="description" id="desc_en" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition text-sm"></textarea>
                            </div>
                            
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-xs font-bold text-indigo-600 uppercase">Description (Français)</label>
                                    <button type="button" onclick="autoTranslate('desc_en', 'desc_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                                <div class="relative">
                                    <textarea name="description_fr" id="desc_fr" rows="5" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition text-sm"></textarea>
                                    <div id="loader_desc_fr" class="hidden absolute right-3 top-3">
                                        <i class="fas fa-circle-notch fa-spin text-indigo-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2">
                        <i class="fas fa-tag mr-2"></i> <?php echo lang('section_pricing'); ?>
                    </h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Price <span class="text-red-500">*</span></label>
                            <input type="number" name="price" step="0.01" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Promo Price</label>
                            <input type="number" name="promo_price" step="0.01" class="w-full bg-red-50 border border-red-100 rounded-xl px-4 py-3 font-bold text-red-600">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                        <h2 class="text-sm font-bold uppercase text-slate-500 tracking-wide">
                            <i class="fas fa-boxes mr-2"></i> <?php echo lang('section_inventory'); ?>
                        </h2>
                        <label class="flex items-center gap-3 cursor-pointer group select-none">
                            <div class="relative">
                                <input type="checkbox" id="hasVariantsToggle" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover:text-primary transition"><?php echo lang('has_variants'); ?></span>
                        </label>
                    </div>
                    <div id="simpleProductStock">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Stock Quantity</label>
                        <input type="number" name="stock" value="10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800">
                    </div>
                    <div id="variableProductStock" class="hidden animate-fade-in-up">
                        <div class="bg-slate-50 p-5 rounded-xl mb-6 border border-slate-200">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-3">Add Variant</p>
                            <div class="flex flex-wrap gap-4 items-end">
                                <div class="flex-1 min-w-[100px]"><input type="text" id="variantSize" placeholder="Size" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm uppercase"></div>
                                <div class="flex-1 min-w-[100px]"><input type="text" id="variantColor" placeholder="Color" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm"></div>
                                <div class="w-24"><input type="number" id="variantStock" value="1" placeholder="Qty" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-center"></div>
                                <button type="button" onclick="addVariantRow()" class="bg-slate-900 text-white w-10 h-[38px] rounded-lg flex items-center justify-center hover:bg-primary transition shadow-md"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-100 text-xs text-slate-500 uppercase font-bold"><tr><th class="p-3">Size</th><th class="p-3">Color</th><th class="p-3 text-center">Stock</th><th class="p-3 text-right">Action</th></tr></thead>
                                <tbody id="variantsContainer" class="divide-y divide-slate-100 bg-white"></tbody>
                            </table>
                            <div id="emptyVariantsMsg" class="p-6 text-center text-slate-400 text-xs italic">No variants added yet.</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-6 tracking-wide border-b border-slate-50 pb-2"><i class="fas fa-images mr-2"></i> <?php echo lang('section_media'); ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-primary hover:bg-primary/5 transition relative bg-slate-50">
                            <label class="cursor-pointer block w-full h-full">
                                <span class="block text-xs font-bold uppercase text-slate-500 mb-2">Main Image *</span>
                                <input type="file" name="image" class="hidden" onchange="previewImage(this, 'mainPreview')" required>
                                <img id="mainPreview" src="" class="hidden w-full h-48 object-contain rounded-lg mb-2">
                                <div id="mainPlaceholder" class="py-8"><i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2"></i><p class="text-xs text-slate-400">Click to upload</p></div>
                            </label>
                        </div>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-primary hover:bg-primary/5 transition relative bg-slate-50">
                            <label class="cursor-pointer block w-full h-full">
                                <span class="block text-xs font-bold uppercase text-slate-500 mb-2">Gallery</span>
                                <input type="file" name="gallery[]" multiple class="hidden" onchange="previewGallery(this)">
                                <div id="galleryPlaceholder" class="py-8"><i class="fas fa-layer-group text-3xl text-slate-300 mb-2"></i><p class="text-xs text-slate-400">Ctrl + Click</p></div>
                                <div id="galleryPreview" class="grid grid-cols-3 gap-2 mt-2 hidden"></div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
                    <h2 class="text-sm font-bold uppercase text-slate-500 mb-4 tracking-wide border-b border-slate-50 pb-2"><i class="fas fa-sitemap mr-2"></i> <?php echo lang('section_organization'); ?></h2>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">SKU</label>
                        <div class="relative"><input type="text" name="sku" id="skuField" placeholder="Auto..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary"><button type="button" onclick="generateSKU()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-primary hover:underline uppercase">Gen</button></div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm"><option value="active">Active</option><option value="draft">Draft</option></select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                            <option value="">-- Select --</option>
                            <?php foreach($data['categories'] as $cat): ?><option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Type</label>
                        <select name="type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm"><option value="">-- None --</option><?php foreach($data['types'] as $type): ?><option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option><?php endforeach; ?></select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Gender</label>
                        <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm"><option value="Women">Women</option><option value="Men">Men</option><option value="Unisex">Unisex</option></select>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold shadow-lg hover:bg-primary transition flex justify-center items-center gap-2"><i class="fas fa-save"></i> <?php echo lang('btn_save_product'); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // ---------------------------------------------------------
    // 1. TRADUCTION AUTOMATIQUE (API RÉELLE)
    // ---------------------------------------------------------
    async function autoTranslate(sourceId, targetId) {
        const sourceInput = document.getElementById(sourceId);
        const targetInput = document.getElementById(targetId);
        const loader = document.getElementById('loader_' + targetId); // Assurez-vous d'avoir mis les div loader dans le HTML

        const text = sourceInput.value.trim();

        // Vérification
        if(!text) {
            alert("Veuillez d'abord remplir le champ en Anglais.");
            sourceInput.focus();
            return;
        }

        // Afficher le chargement (si le loader existe dans le HTML)
        if(loader) loader.classList.remove('hidden');
        targetInput.classList.add('opacity-50');

        try {
            // Appel API Gratuite (MyMemory) : Anglais (en) -> Français (fr)
            const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|fr`;
            
            const response = await fetch(url);
            const data = await response.json();

            if(data.responseData.translatedText) {
                // Succès : On met le texte traduit
                targetInput.value = data.responseData.translatedText;
                
                // Petit effet visuel vert
                targetInput.classList.add('ring-2', 'ring-green-400', 'bg-green-50');
                setTimeout(() => targetInput.classList.remove('ring-2', 'ring-green-400', 'bg-green-50'), 1000);
            } else {
                // Échec API : On copie le texte original (Fallback)
                console.warn("Erreur API, copie simple effectuée.");
                targetInput.value = text;
            }

        } catch (error) {
            console.error("Erreur Traduction:", error);
            // En cas d'erreur réseau, on copie simplement le texte
            targetInput.value = text; 
        } finally {
            // Cacher le chargement
            if(loader) loader.classList.add('hidden');
            targetInput.classList.remove('opacity-50');
        }
    }

    // ---------------------------------------------------------
    // 2. TOGGLE VARIANTS
    // ---------------------------------------------------------
    const toggle = document.getElementById('hasVariantsToggle');
    const simpleStock = document.getElementById('simpleProductStock');
    const variableStock = document.getElementById('variableProductStock');
    
    if(toggle) {
        toggle.addEventListener('change', function() {
            if(this.checked){
                simpleStock.classList.add('hidden');
                variableStock.classList.remove('hidden');
                simpleStock.querySelector('input').disabled = true;
            } else {
                simpleStock.classList.remove('hidden');
                variableStock.classList.add('hidden');
                simpleStock.querySelector('input').disabled = false;
            }
        });
    }

    // ---------------------------------------------------------
    // 3. ADD VARIANTS ROW
    // ---------------------------------------------------------
    let variantCount = 0;
    function addVariantRow() {
        const size = document.getElementById('variantSize').value.trim();
        const color = document.getElementById('variantColor').value.trim();
        const stock = document.getElementById('variantStock').value;

        if(size === '') { alert("Size is required."); return; }

        const emptyMsg = document.getElementById('emptyVariantsMsg');
        if(emptyMsg) emptyMsg.style.display = 'none';
        
        const rowId = variantCount++;
        
        const html = `
            <tr id="row-${rowId}" class="hover:bg-slate-50 transition">
                <td class="p-3">
                    <span class="font-bold text-slate-700">${size}</span>
                    <input type="hidden" name="variants_size[]" value="${size}">
                </td>
                <td class="p-3">
                    <span class="text-slate-600">${color || '-'}</span>
                    <input type="hidden" name="variants_color[]" value="${color}">
                </td>
                <td class="p-3 text-center">
                    <span class="bg-slate-100 px-2 py-1 rounded text-xs font-bold">${stock}</span>
                    <input type="hidden" name="variants_stock[]" value="${stock}">
                </td>
                <td class="p-3 text-right">
                    <button type="button" onclick="document.getElementById('row-${rowId}').remove()" class="text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
        
        document.getElementById('variantsContainer').insertAdjacentHTML('beforeend', html);
        
        // Reset inputs
        document.getElementById('variantSize').value = '';
        document.getElementById('variantColor').value = '';
        document.getElementById('variantSize').focus();
    }

    // ---------------------------------------------------------
    // 4. IMAGES PREVIEW
    // ---------------------------------------------------------
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById(previewId);
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('mainPlaceholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGallery(input) {
        const container = document.getElementById('galleryPreview');
        const placeholder = document.getElementById('galleryPlaceholder');
        container.innerHTML = ''; 
        
        if (input.files.length > 0) {
            placeholder.classList.add('hidden');
            container.classList.remove('hidden');
            Array.from(input.files).forEach(file => {
                var reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-16 object-cover rounded shadow-sm border border-slate-200';
                    container.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // 5. SKU Generator
    function generateSKU() {
        document.getElementById('skuField').value = 'PROD-' + Math.floor(10000 + Math.random() * 90000);
    }
</script>


<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>