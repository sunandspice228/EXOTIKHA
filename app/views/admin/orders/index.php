<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Commandes</h3>
</div>

<div class="card-custom p-0 overflow-hidden">
    <table class="table table-hover mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4">#ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Statut</th>
                <th class="text-end pe-4">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $o): ?>
            <tr>
                <td class="ps-4 fw-bold">#<?= $o['id'] ?></td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold"><?= $o['user_name'] ?? 'Invité' ?></span>
                        <span class="text-muted small"><?= $o['email'] ?? '' ?></span>
                    </div>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                <td class="fw-bold"><?= format_price($o['total_amount']) ?></td>
                <td>
                    <?php 
                        $badges = ['pending' => 'bg-warning', 'completed' => 'bg-success', 'cancelled' => 'bg-danger', 'shipped' => 'bg-primary'];
                        $labels = ['pending' => 'En attente', 'completed' => 'Livré', 'cancelled' => 'Annulé', 'shipped' => 'Expédié'];
                    ?>
                    <span class="badge <?= $badges[$o['status']] ?? 'bg-secondary' ?>">
                        <?= $labels[$o['status']] ?? $o['status'] ?>
                    </span>
                </td>
                <td class="text-end pe-4">
                    <a href="<?= url('/admin/orders/show/' . $o['id']) ?>" class="btn btn-sm btn-light border">
                        <i class="fa-solid fa-eye"></i> Détails
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>