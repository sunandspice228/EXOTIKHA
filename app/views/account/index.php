<?php ob_start(); ?>

<div class="container py-5">
    <div class="row">
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fs-3 fw-bold"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                    </div>
                    <h5 class="fw-bold"><?= e($user['name']) ?></h5>
                    <p class="text-muted small"><?= e($user['email']) ?></p>
                </div>
                <div class="list-group list-group-flush">
                    <a href="?tab=dashboard" class="list-group-item list-group-item-action <?= $activeTab === 'dashboard' ? 'active bg-dark border-dark' : '' ?>">
                        <i class="fa-solid fa-gauge me-2"></i> Tableau de bord
                    </a>
                    <a href="?tab=orders" class="list-group-item list-group-item-action <?= $activeTab === 'orders' ? 'active bg-dark border-dark' : '' ?>">
                        <i class="fa-solid fa-box-open me-2"></i> Mes Commandes
                    </a>
                    <a href="<?= url('/logout') ?>" class="list-group-item list-group-item-action text-danger">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            
            <?php if($activeTab === 'dashboard'): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Bonjour, <?= e($user['name']) ?> !</h4>
                        <p>Bienvenue sur votre espace personnel. Ici, vous pouvez gérer vos commandes et vos informations.</p>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border d-flex align-items-center">
                                    <i class="fa-solid fa-cart-shopping fa-2x text-primary me-3"></i>
                                    <div>
                                        <h3 class="m-0 fw-bold"><?= count($orders) ?></h3>
                                        <small class="text-muted">Commandes passées</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border d-flex align-items-center">
                                    <i class="fa-solid fa-wallet fa-2x text-success me-3"></i>
                                    <div>
                                        <h3 class="m-0 fw-bold">Active</h3>
                                        <small class="text-muted">Statut du compte</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($activeTab === 'orders'): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold">Historique des commandes</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="fa-solid fa-basket-shopping fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                                <a href="<?= url('/') ?>" class="btn btn-dark btn-sm rounded-pill">Commencer mes achats</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Référence</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($orders as $o): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold font-monospace">#<?= e($o['reference']) ?></td>
                                            <td class="small text-muted"><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                                            <td class="fw-bold"><?= number_format($o['amount'], 2) ?> GHS</td>
                                            <td>
                                                <?php if($o['status'] === 'paid'): ?>
                                                    <span class="badge bg-success">Payé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark"><?= e($o['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>