<?php ob_start(); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-user fa-xl text-secondary"></i>
                    </div>
                    <h5 class="fw-bold"><?= e($user['name']) ?></h5>
                    <p class="text-muted small"><?= e($user['email']) ?></p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= url('/account?tab=dashboard') ?>" class="list-group-item list-group-item-action <?= $activeTab == 'dashboard' ? 'active bg-dark border-dark' : '' ?>">
                        <i class="fa-solid fa-gauge me-2"></i> <?= lang('dashboard') ?>
                    </a>
                    <a href="<?= url('/account?tab=orders') ?>" class="list-group-item list-group-item-action <?= $activeTab == 'orders' ? 'active bg-dark border-dark' : '' ?>">
                        <i class="fa-solid fa-box-open me-2"></i> <?= lang('orders') ?>
                    </a>
                    <a href="<?= url('/account?tab=settings') ?>" class="list-group-item list-group-item-action <?= $activeTab == 'settings' ? 'active bg-dark border-dark' : '' ?>">
                        <i class="fa-solid fa-gear me-2"></i> <?= lang('settings') ?>
                    </a>
                    <a href="<?= url('/logout') ?>" class="list-group-item list-group-item-action text-danger">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> <?= lang('logout') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            
            <?php if($activeTab == 'dashboard'): ?>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white p-4 border-0 shadow-sm">
                            <h3><?= $total_orders ?? 0 ?></h3>
                            <p class="mb-0">Commandes Totales</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white p-4 border-0 shadow-sm">
                            <h3>0</h3>
                            <p class="mb-0">En cours de livraison</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Dernière commande</h5>
                    <?php if(!empty($last_order)): ?>
                        <div class="alert alert-light border d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#<?= e($last_order['reference']) ?></strong><br>
                                <small class="text-muted"><?= e($last_order['created_at']) ?></small>
                            </div>
                            <span class="badge bg-warning text-dark"><?= e($last_order['status']) ?></span>
                            <span class="fw-bold"><?= format_price($last_order['total']) ?></span>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune commande récente.</p>
                        <a href="<?= url('/') ?>" class="btn btn-outline-dark btn-sm">Commencer le shopping</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if($activeTab == 'orders'): ?>
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="fw-bold mb-4"><?= lang('orders') ?></h4>
                    <?php if(empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                            <p>Vous n'avez pas encore commandé.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Référence</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $o): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= e($o['reference']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                                        <td><span class="badge bg-secondary"><?= e($o['status']) ?></span></td>
                                        <td class="fw-bold"><?= format_price($o['total']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if($activeTab == 'settings'): ?>
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="fw-bold mb-4"><?= lang('settings') ?></h4>
                    <form action="<?= url('/account/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom complet</label>
                                <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email (Lecture seule)</label>
                                <input type="email" value="<?= e($user['email']) ?>" class="form-control bg-light" readonly>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Téléphone</label>
                                <input type="text" name="phone" class="form-control" value="<?= e($user['phone']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Adresse</label>
                                <input type="text" name="address" class="form-control" value="<?= e($user['address']) ?>">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ville</label>
                                <input type="text" name="city" class="form-control" value="<?= e($user['city']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Région</label>
                                <input type="text" name="region" class="form-control" value="<?= e($user['region']) ?>">
                            </div>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-danger mt-3">Changer le mot de passe</h6>
                        <small class="text-muted d-block mb-3">Laissez vide si vous ne voulez pas le changer.</small>

                        <div class="mb-4">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>

                        <button class="btn btn-dark fw-bold px-4"><?= lang('save') ?></button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>