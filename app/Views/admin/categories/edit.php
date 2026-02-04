<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/categories" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo lang('title_edit_category'); ?></h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form action="<?php echo URLROOT; ?>/admin/categories_edit/<?php echo $data['category']->id; ?>" method="POST">
            <div class="space-y-6">
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Name (English) *</label>
                    <input type="text" name="name" id="name_en" required value="<?php echo $data['category']->name; ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-indigo-600 uppercase">Nom (Français)</label>
                        <button type="button" onclick="autoTranslate('name_en', 'name_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1">
                            <i class="fas fa-magic"></i> Auto
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" name="name_fr" id="name_fr" value="<?php echo $data['category']->name_fr; ?>" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500">
                        <div id="loader_name_fr" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Description (English)</label>
                    <textarea name="description" id="desc_en" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary"><?php echo $data['category']->description; ?></textarea>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-indigo-600 uppercase">Description (Français)</label>
                        <button type="button" onclick="autoTranslate('desc_en', 'desc_fr')" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-1 rounded hover:bg-indigo-200 transition font-bold flex items-center gap-1">
                            <i class="fas fa-magic"></i> Auto
                        </button>
                    </div>
                    <div class="relative">
                        <textarea name="description_fr" id="desc_fr" rows="3" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500"><?php echo $data['category']->description_fr; ?></textarea>
                        <div id="loader_desc_fr" class="hidden absolute right-3 top-3 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>