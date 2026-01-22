<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold m-0">Tableau de Bord</h3>
        <p class="text-muted small mb-0">Bienvenue, voici ce qui se passe sur Exotikha aujourd'hui.</p>
    </div>
    <div class="text-end">
        <span class="badge bg-light text-dark border p-2">
            <i class="fa-regular fa-calendar me-2"></i> <?= date('d F Y') ?>
        </span>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #111 0%, #333 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-white-50 text-uppercase fw-bold">Revenu Total</small>
                        <h3 class="fw-bold mt-2 mb-0">₵<?= number_format($totalRevenue, 2) ?></h3>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded p-2">
                        <i class="fa-solid fa-wallet text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Commandes</small>
                        <h3 class="fw-bold mt-2 mb-0"><?= $countOrders ?></h3>
                    </div>
                    <div class="bg-light rounded p-2">
                        <i class="fa-solid fa-bag-shopping text-primary fs-4"></i>
                    </div>
                </div>
                <a href="<?= url('/admin/orders') ?>" class="small text-decoration-none mt-3 d-inline-block">Voir tout &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Produits</small>
                        <h3 class="fw-bold mt-2 mb-0"><?= $countProducts ?></h3>
                    </div>
                    <div class="bg-light rounded p-2">
                        <i class="fa-solid fa-shirt text-success fs-4"></i>
                    </div>
                </div>
                <a href="<?= url('/admin/products') ?>" class="small text-decoration-none mt-3 d-inline-block">Gérer le stock &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-bold">Leads V.P.</small>
                        <h3 class="fw-bold mt-2 mb-0"><?= $countLeads ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded p-2">
                        <i class="fa-brands fa-whatsapp text-warning fs-4"></i>
                    </div>
                </div>
                <a href="<?= url('/admin/leads') ?>" class="small text-decoration-none mt-3 d-inline-block text-warning">Voir les inscrits &rarr;</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0"><i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i> Commandes Récentes</h6>
                <a href="<?= url('/admin/orders') ?>" class="btn btn-sm btn-light border">Tout voir</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>État</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($recentOrders)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Aucune commande pour l'instant.</td></tr>
                        <?php else: ?>
                            <?php foreach($recentOrders as $o): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?= $o['id'] ?></td>
                                <td><?= htmlspecialchars($o['customer_name'] ?? 'Client') ?></td>
                                <td class="fw-bold">₵<?= number_format($o['total_price'], 2) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = match($o['status'] ?? 'pending') {
                                            'completed' => 'success',
                                            'shipped' => 'info',
                                            'cancelled' => 'danger',
                                            default => 'warning'
                                        };
                                        $statusLabel = match($o['status'] ?? 'pending') {
                                            'completed' => 'Livré',
                                            'shipped' => 'Expédié',
                                            'cancelled' => 'Annulé',
                                            default => 'En attente'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?> bg-opacity-10 text-<?= $statusClass ?> border border-<?= $statusClass ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= url('/admin/orders/show/' . $o['id']) ?>" class="btn btn-sm btn-light border"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-dark text-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold m-0"><i class="fa-solid fa-gem me-2 text-warning"></i> Ventes Privées</h6>
                <a href="<?= url('/admin/leads') ?>" class="btn btn-sm btn-outline-light" style="font-size: 0.7rem;">Gérer</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if(empty($recentLeads)): ?>
                        <li class="list-group-item text-center py-4 text-muted border-0">Aucun inscrit récent.</li>
                    <?php else: ?>
                        <?php foreach($recentLeads as $lead): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width:40px; height:40px;">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h6 class="m-0 fw-bold small"><?= htmlspecialchars($lead['full_name']) ?></h6>
                                    <small class="text-muted x-small"><?= date('d/m H:i', strtotime($lead['created_at'])) ?></small>
                                </div>
                            </div>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_number']) ?>" target="_blank" class="btn btn-sm btn-light border rounded-circle" title="Contacter">
                                <i class="fa-regular fa-paper-plane text-success"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="card-footer bg-white text-center border-0 py-3">
                <a href="<?= url('/admin/leads') ?>" class="small text-muted text-decoration-none">Voir tous les inscrits</a>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>