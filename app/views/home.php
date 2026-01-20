<?php ob_start(); ?>

<div class="container py-5">
    <div class="row">
        
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm p-3 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-filter me-2"></i><?= lang('filters') ?></h5>
                <form action="<?= url('/') ?>" method="GET">
                    
                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-1"><?= lang('categories') ?></label>
                        <select name="category" class="form-select border-0 bg-light">
                            <option value="all">Tout</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= e($cat['name']) ?>" <?= ($activeFilters['category'] == $cat['name']) ? 'selected' : '' ?>>
                                    <?= e(get_tr($cat, 'name')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-1">Genre</label>
                        <?php foreach(['Homme', 'Femme', 'Enfant', 'Unisexe'] as $g): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="<?= $g ?>" id="g_<?= $g ?>" <?= ($activeFilters['gender'] == $g) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="g_<?= $g ?>"><?= $g ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase mb-1"><?= lang('color') ?></label>
                        <select name="color" class="form-select border-0 bg-light">
                            <option value="">Tout</option>
                            <?php foreach($colors as $c): ?>
                                <option value="<?= e($c['name']) ?>" <?= ($activeFilters['color'] == $c['name']) ? 'selected' : '' ?>>
                                    <?= e(get_tr($c, 'name')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-dark w-100 fw-bold"><?= lang('apply') ?></button>
                    <?php if(!empty($_GET)): ?>
                        <a href="<?= url('/') ?>" class="btn btn-link w-100 btn-sm text-decoration-none text-muted mt-1"><?= lang('reset') ?></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="col-lg-9">
            <?php if(empty($products)): ?>
                <div class="text-center py-5 text-muted">
                    <h3>Aucun produit trouvé</h3>
                    <p>Essayez de modifier vos filtres.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach($products as $p): ?>
                    <div class="col-6 col-md-4">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <a href="<?= url('/product/'.$p['id']) ?>">
                                <img src="<?= $p['image'] ? url('/uploads/'.$p['image']) : 'https://dummyimage.com/600x600/eee/aaa' ?>" class="card-img-top" alt="<?= e($p['name']) ?>">
                            </a>
                            <div class="card-body text-center">
                                <h6 class="fw-bold text-truncate mb-1"><?= get_tr($p, 'name') ?></h6>
                                <p class="text-muted mb-2"><?= format_product_price_html($p) ?></p>
                                <a href="<?= url('/product/'.$p['id']) ?>" class="btn btn-outline-dark btn-sm rounded-pill px-4"><?= lang('shop') ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>