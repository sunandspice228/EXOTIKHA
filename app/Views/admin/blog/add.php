<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo URLROOT; ?>/admin/blog" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Écrire un Article</h1>
            <p class="text-sm text-slate-500">Partagez vos conseils lifestyle.</p>
        </div>
    </div>

    <form action="<?php echo URLROOT; ?>/admin/add_post" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-6">
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Titre de l'article</label>
                    <input type="text" name="title" placeholder="Ex: Comment choisir sa lingerie..." class="w-full rounded-xl border-slate-200 focus:ring-primary h-12" required>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Contenu</label>
                    <textarea id="blog_editor" name="content" rows="15" class="w-full rounded-xl border-slate-200"></textarea>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-900 mb-4">Image de couverture</h3>
                <div class="relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:border-primary transition text-center" onclick="document.getElementById('imageInput').click()">
                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-500">Cliquez pour ajouter<br>(Recommandé: 1200x800px)</p>
                    <input type="file" id="imageInput" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    <img id="preview" class="hidden mt-4 rounded-lg w-full h-40 object-cover">
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-900 mb-4">Paramètres</h3>
                <div class="mb-4">
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Catégorie</label>
                    <select name="category" class="w-full rounded-xl border-slate-200 focus:ring-primary h-12">
                        <option value="Accessories">Accessoires</option>
                        <option value="Tips for Life">Conseils Lifestyle</option>
                        <option value="Trends">Tendances</option>
                        <option value="Wellness">Bien-être</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-slate-800 transition uppercase tracking-widest">
                    Publier
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#blog_editor',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace vertical align visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat | link image',
        height: 500,
        content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:16px }',
        branding: false
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>