<div class="col-6 col-md-4">
    <div class="card product-card h-100 position-relative">
        
        <?php if(is_on_promotion($p)): ?>
            <?php $percent = round((($p['price'] - $p['promo_price']) / $p['price']) * 100); ?>
            <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold shadow" style="z-index:10;">
                -<?= $percent ?>%
            </span>
        <?php elseif(strtotime($p['created_at']) > strtotime('-7 days')): ?>
            <span class="position-absolute top-0 start-0 bg-success text-white px-2 py-1 m-2 rounded small fw-bold shadow" style="z-index:10;">
                NEW
            </span>
        <?php endif; ?>

        <a href="<?= url('/product/'.$p['id']) ?>" class="product-img-wrap">
            <img src="<?= $p['image'] ? url('/uploads/'.$p['image']) : 'https://dummyimage.com/600x600/f0f0f0/ccc' ?>" alt="<?= e($p['name']) ?>">
        </a>
        
        <div class="card-body d-flex flex-column text-center">
            <span class="text-muted text-uppercase text-xs fw-bold mb-1"><?= e($p['category']) ?></span>
            <h6 class="fw-bold text-dark mb-2 text-truncate">
                <a href="<?= url('/product/'.$p['id']) ?>" class="text-dark text-decoration-none">
                    <?= get_tr($p, 'name') ?>
                </a>
            </h6>
            <div class="mt-auto">
                <div class="mb-2 fs-5">
                    <?= format_product_price_html($p) ?>
                </div>
                <a href="<?= url('/product/'.$p['id']) ?>" class="btn btn-outline-dark btn-sm rounded-pill px-4">Voir</a>
            </div>
        </div>
    </div>
</div>