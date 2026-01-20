<div class="col-6 col-md-3">
    <div class="product-card h-100 position-relative">
        
        <?php if(is_on_promotion($p)): ?>
            <?php $pct = round((($p['price'] - $p['promo_price']) / $p['price']) * 100); ?>
            <span class="position-absolute top-0 start-0 m-2 badge bg-danger text-uppercase shadow-sm" style="z-index:5; font-size:10px;">-<?= $pct ?>%</span>
        <?php elseif(strtotime($p['created_at']) > strtotime('-7 days')): ?>
            <span class="position-absolute top-0 start-0 m-2 badge bg-dark text-uppercase shadow-sm" style="z-index:5; font-size:10px;">NEW</span>
        <?php endif; ?>

        <div class="product-img-wrap">
            <a href="<?= url('/product/'.$p['id']) ?>">
                <img src="<?= $p['image'] ? url('/uploads/'.$p['image']) : 'https://dummyimage.com/600x800/f0f0f0/ccc' ?>" alt="<?= e($p['name']) ?>">
            </a>
            
            <div class="product-actions">
                <a href="<?= url('/product/'.$p['id']) ?>" class="action-btn" title="Voir">
                    <i class="fa-regular fa-eye"></i>
                </a>
                <form action="<?= url('/cart/add') ?>" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="qty" value="1">
                    <button class="action-btn" title="Ajouter">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="p-3 text-center">
            <div class="text-uppercase text-muted" style="font-size: 10px; letter-spacing: 1px;"><?= e($p['category']) ?></div>
            <h6 class="fw-bold my-1 text-truncate">
                <a href="<?= url('/product/'.$p['id']) ?>" class="text-dark text-decoration-none">
                    <?= get_tr($p, 'name') ?>
                </a>
            </h6>
            <div class="small">
                <?= format_product_price_html($p) ?>
            </div>
        </div>
    </div>
</div>