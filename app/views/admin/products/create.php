<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Nouveau Produit</h3>
    <a href="<?= url('/admin/products') ?>" class="btn btn-light border">Retour</a>
</div>

<form action="<?= url('/admin/products/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3">Infos</h5>
                <div class="mb-3"><label class="fw-bold">Nom</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="fw-bold">Description</label><textarea name="description" class="form-control" rows="5"></textarea></div>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">Variantes</h5>
                <div class="mb-4">
                    <label class="d-block mb-2 text-muted small text-uppercase fw-bold">Tailles</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach(($attributes ?? []) as $a): if($a['type']!='size') continue; ?>
                        <input type="checkbox" class="btn-check" name="sizes[]" id="s_<?= $a['id'] ?>" value="<?= $a['name'] ?>">
                        <label class="btn btn-outline-dark rounded-0 px-3" for="s_<?= $a['id'] ?>"><?= $a['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="d-block mb-2 text-muted small text-uppercase fw-bold">Couleurs</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach(($attributes ?? []) as $a): if($a['type']!='color') continue; ?>
                        <input type="checkbox" class="btn-check" name="colors[]" id="c_<?= $a['id'] ?>" value="<?= $a['name'] ?>">
                        <label class="btn btn-outline-secondary rounded-0 px-3" for="c_<?= $a['id'] ?>"><?= $a['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3">Organisation & Prix</h5>
                
                <div class="mb-3">
                    <label class="fw-bold">Catégorie</label>
                    <select name="category" class="form-select" required>
                        <option value="">Choisir...</option>
                        <?php foreach(($categories ?? []) as $c): ?>
                            <option value="<?= $c['name'] ?>"><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Aucun</option>
                        <?php foreach(($types ?? []) as $t): ?>
                            <option value="<?= $t['name'] ?>"><?= $t['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="fw-bold">Prix Normal</label>
                    <div class="input-group"><span class="input-group-text">₵</span><input type="number" name="price" id="price" class="form-control" step="0.01" required></div>
                </div>

                

                <div class="mb-3"><label class="fw-bold">Stock</label><input type="number" name="stock" class="form-control" value="..."></div>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold mb-3">Image</h5>
                <input type="file" name="image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark w-100 py-3 mt-4 fw-bold text-uppercase shadow">Enregistrer</button>
        </div>
    </div>
</form>

<script>
    function togglePromo() {
        document.getElementById('promoFields').style.display = document.getElementById('isPromo').checked ? 'block' : 'none';
        validate();
    }
    function validate() {
        const p = parseFloat(document.getElementById('price').value)||0;
        const pp = parseFloat(document.getElementById('promo_price').value)||0;
        const isP = document.getElementById('isPromo').checked;
        const err = document.getElementById('priceError');
        const btn = document.querySelector('button[type=submit]');
        
        if(isP && pp >= p && p > 0) { err.style.display='block'; btn.disabled=true; }
        else { err.style.display='none'; btn.disabled=false; }
    }
    ['price','promo_price'].forEach(id => document.getElementById(id).addEventListener('input', validate));
</script>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>