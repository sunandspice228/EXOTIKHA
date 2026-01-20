<?php 
$s_list = array_map('trim', explode(',', $product['sizes'] ?? ''));
$c_list = array_map('trim', explode(',', $product['colors'] ?? ''));
ob_start(); 
?>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-md-6">
            <img src="<?= $product['image'] ? url('/uploads/'.$product['image']) : 'https://dummyimage.com/800x800/eee/aaa' ?>" class="img-fluid rounded shadow w-100">
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-dark">Accueil</a></li>
                    <li class="breadcrumb-item active"><?= e($product['category']) ?></li>
                </ol>
            </nav>

            <h1 class="fw-bold display-5"><?= get_tr($product, 'name') ?></h1>
            <h3 class="text-danger my-3 fw-bold"><?= format_product_price_html($product) ?></h3>
            <p class="text-muted lead fs-6"><?= nl2br(get_tr($product, 'description')) ?></p>

            <hr>

            <form action="<?= url('/cart/add') ?>" method="POST" class="mt-4">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                
                <div class="row g-3 mb-4">
                    <?php if(!empty($product['sizes'])): ?>
                    <div class="col-6">
                        <label class="fw-bold small mb-1"><?= lang('size') ?></label>
                        <select name="size" class="form-select">
                            <?php foreach($s_list as $s): ?>
                                <option value="<?= e($s) ?>"><?= e($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($product['colors'])): ?>
                    <div class="col-6">
                        <label class="fw-bold small mb-1"><?= lang('color') ?></label>
                        <select name="color" class="form-select">
                            <?php foreach($c_list as $c): ?>
                                <option value="<?= e($c) ?>"><?= e($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-3">
                    <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" class="form-control w-25 text-center">
                    <button class="btn btn-dark flex-grow-1 py-2 fw-bold text-uppercase">
                        <i class="fa-solid fa-cart-plus me-2"></i> <?= lang('add_to_cart') ?>
                    </button>
                </div>
            </form>
            
            <div class="mt-4 small text-muted">
                <i class="fa-solid fa-lock me-1"></i> Paiement sécurisé<br>
                <i class="fa-solid fa-truck me-1"></i> Livraison Togo & Ghana
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>