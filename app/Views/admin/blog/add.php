<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/blog" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-800"><?php echo lang('title_add_post'); ?></h1>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/blog_add" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    
                    <div class="grid grid-cols-1 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Title (EN) *</label>
                            <input type="text" name="title" id="title_en" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary font-bold text-lg">
                        </div>
                        <div class="relative">
                            <label class="block text-xs font-bold text-indigo-600 mb-2 uppercase flex justify-between">
                                Titre (FR)
                                <button type="button" onclick="autoTranslate('title_en', 'title_fr')" class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded hover:bg-indigo-100 transition"><i class="fas fa-magic"></i> Auto</button>
                            </label>
                            <input type="text" name="title_fr" id="title_fr" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 font-bold text-lg">
                            <div id="loader_title_fr" class="hidden absolute right-3 top-9 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Content (EN)</label>
                            <textarea name="content" id="content_en" rows="10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary"></textarea>
                        </div>
                        <div class="relative">
                            <label class="block text-xs font-bold text-indigo-600 mb-2 uppercase flex justify-between">
                                Contenu (FR)
                                <button type="button" onclick="autoTranslate('content_en', 'content_fr')" class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded hover:bg-indigo-100 transition"><i class="fas fa-magic"></i> Auto</button>
                            </label>
                            <textarea name="content_fr" id="content_fr" rows="10" class="w-full bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500"></textarea>
                            <div id="loader_content_fr" class="hidden absolute right-3 top-9 text-indigo-400"><i class="fas fa-circle-notch fa-spin"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Status</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Featured Image</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-primary transition relative">
                            <input type="file" name="image" class="hidden" id="blogImgInput" onchange="previewImage(this, 'blogPreview')">
                            <label for="blogImgInput" class="cursor-pointer block">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-300 mb-2"></i>
                                <span class="block text-xs text-slate-400">Upload Image</span>
                            </label>
                            <img id="blogPreview" class="hidden w-full h-32 object-cover rounded mt-2">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                        Publish Post
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    async function autoTranslate(sourceId, targetId) {
        const text = document.getElementById(sourceId).value.trim();
        const target = document.getElementById(targetId);
        const loader = document.getElementById('loader_' + targetId);
        
        if(!text) { alert("Please enter text first."); return; }
        if(loader) loader.classList.remove('hidden');

        try {
            const res = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|fr`);
            const data = await res.json();
            if(data.responseData.translatedText) {
                target.value = data.responseData.translatedText;
                target.classList.add('bg-green-50');
                setTimeout(() => target.classList.remove('bg-green-50'), 1000);
            }
        } catch(e) { target.value = text; }
        finally { if(loader) loader.classList.add('hidden'); }
    }

    function previewImage(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(id);
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>