<?php ob_start(); ?>

<div class="container py-5">
    <h2 class="fw-bold mb-4"><?= lang('cart') ?></h2>

    <?php if(empty($cartItems)): ?>
        <div class="alert alert-info py-5 text-center">
            <h4><?= lang('empty_cart') ?></h4>
            <a href="<?= url('/') ?>" class="btn btn-dark mt-3">Retour Boutique</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Qté</th>
                            <th class="text-end">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cartItems as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= $item['image'] ? url('/uploads/'.$item['image']) : '' ?>" width="50" class="rounded me-3">
                                    <div>
                                        <strong><?= get_tr($item, 'name') ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><?= format_price($item['real_price']) ?></td>
                            <td>
                                <div class="input-group input-group-sm w-auto">
                                    <a href="<?= url('/cart/update/dec/'.$item['id']) ?>" class="btn btn-outline-secondary">-</a>
                                    <span class="input-group-text bg-white px-3"><?= $item['qty'] ?></span>
                                    <a href="<?= url('/cart/update/inc/'.$item['id']) ?>" class="btn btn-outline-secondary">+</a>
                                </div>
                            </td>
                            <td class="text-end fw-bold"><?= format_price($item['real_price'] * $item['qty']) ?></td>
                            <td class="text-end"><a href="<?= url('/cart/remove/'.$item['id']) ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 bg-light border-0">
                    <h4 class="fw-bold">Résumé</h4>
                    <hr>
                    <div class="d-flex justify-content-between fs-5 fw-bold mb-4">
                        <span>Total</span>
                        <span><?= format_price($total) ?></span>
                    </div>
                    <a href="<?= url('/checkout') ?>" class="btn btn-success w-100 py-3 fw-bold text-uppercase">
                        <?= lang('checkout') ?> <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>