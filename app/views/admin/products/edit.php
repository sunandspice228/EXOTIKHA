<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <a href="<?= url('/admin/products') ?>" class="btn btn-light border me-3"><i class="fa-solid fa-arrow-left"></i></a>
        <h3 class="fw-bold m-0">Modifier : <?= htmlspecialchars($product['name']) ?></h3>
    </div>
    <span class="badge bg-light text-dark border">#<?= $product['id'] ?></span>
</div>

<form action="<?= url('/admin/products/update') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3">Infos</h5>
                <div class="mb-3"><label class="fw-bold">Nom</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required></div>
                <div class="mb-3"><label class="fw-bold">Description</label><textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description']) ?></textarea></div>
            </div>

            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4">Variantes</h5>
                <?php 
                    $currSizes = !empty($product['sizes'])?explode(',',$product['sizes']):[];
                    $currColors = !empty($product['colors'])?explode(',',$product['colors']):[];
                ?>
                <div class="mb-4">
                    <label class="d-block mb-2 text-muted small text-uppercase fw-bold">Tailles</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach(($attributes??[]) as $a): if($a['type']!='size') continue; ?>
                        <input type="checkbox" class="btn-check" name="sizes[]" id="s_<?= $a['id'] ?>" value="<?= $a['name'] ?>" <?= in_array($a['name'], $currSizes)?'checked':'' ?>>
                        <label class="btn btn-outline-dark rounded-0 px-3" for="s_<?= $a['id'] ?>"><?= $a['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="d-block mb-2 text-muted small text-uppercase fw-bold">Couleurs</label>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach(($attributes??[]) as $a): if($a['type']!='color') continue; ?>
                        <input type="checkbox" class="btn-check" name="colors[]" id="c_<?= $a['id'] ?>" value="<?= $a['name'] ?>" <?= in_array($a['name'], $currColors)?'checked':'' ?>>
                        <label class="btn btn-outline-secondary rounded-0 px-3" for="c_<?= $a['id'] ?>"><?= $a['name'] ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold mb-3">Galerie</h5>
                <div class="mb-4 p-4 border border-dashed rounded bg-light text-center">
                    <input type="file" name="gallery[]" class="form-control" multiple>
                </div>
                <?php if(!empty($gallery)): ?>
                    <div class="row g-2">
                        <?php foreach($gallery as $img): ?>
                            <div class="col-3 col-md-2 position-relative">
                                <img src="<?= url('/uploads/' . $img['image']) ?>" class="img-thumbnail w-100 object-fit-cover" style="height: 100px;">
                                <a href="<?= url('/admin/gallery/delete/' . $img['id']) ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" style="width:20px;height:20px;padding:0;line-height:20px;" onclick="return confirm('X ?')">&times;</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-3">Organisation & Prix</h5>
                
                <div class="mb-3">
                    <label class="fw-bold">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="">Choisir...</option>
                        <?php foreach(($categories??[]) as $c): ?>
                            <option value="<?= $c['name'] ?>" <?= $product['category']==$c['name']?'selected':'' ?>><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Aucun</option>
                        <?php foreach(($types??[]) as $t): ?>
                            <option value="<?= $t['name'] ?>" <?= $product['type']==$t['name']?'selected':'' ?>><?= $t['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr>

                <div class="mb-3"><label class="fw-bold">Prix Normal</label><input type="number" name="price" id="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required></div>

                <div class="card bg-light border p-3 mb-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_promo" id="isPromo" onchange="togglePromo()" <?= $product['is_promo']?'checked':'' ?>>
                        <label class="form-check-label fw-bold" for="isPromo">Activer Promotion</label>
                    </div>
                    <div id="promoFields" style="display:none;">
                        <div class="mb-2">
                            <label class="small fw-bold text-danger">Prix Promo</label>
                            <input type="number" name="promo_price" id="promo_price" class="form-control border-danger text-danger fw-bold" step="0.01" value="<?= $product['promo_price'] ?>">
                            <div id="priceError" class="text-danger x-small mt-1 fw-bold" style="display:none;">Prix promo doit être inférieur !</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6"><label class="small text-muted">Début</label><input type="datetime-local" name="promo_start_date" class="form-control form-control-sm" value="<?= $product['promo_start_date'] ? date('Y-m-d\TH:i', strtotime($product['promo_start_date'])) : '' ?>"></div>
                            <div class="col-6"><label class="small text-muted">Fin</label><input type="datetime-local" name="promo_end_date" class="form-control form-control-sm" value="<?= $product['promo_end_date'] ? date('Y-m-d\TH:i', strtotime($product['promo_end_date'])) : '' ?>"></div>
                        </div>
                    </div>
                </div>

                <div class="mb-3"><label class="fw-bold">Stock</label><input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>"></div>
            </div>

            <div class="card-custom p-4">
                <h5 class="fw-bold mb-3">Image Principale</h5>
                <div class="text-center bg-light p-3 rounded mb-3"><img src="<?= url('/uploads/' . $product['image']) ?>" class="img-fluid rounded" style="max-height: 150px;"></div>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 mt-4 fw-bold text-uppercase shadow">Mettre à jour</button>
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
    document.addEventListener("DOMContentLoaded", togglePromo);
</script>

<style>.border-dashed { border-style: dashed !important; }</style>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>