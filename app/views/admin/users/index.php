<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Clients Inscrits</h3>
</div>

<div class="card-custom p-0">
    <table class="table table-hover mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4">Nom</th>
                <th>Email</th>
                <th>Inscrit le</th>
                <th class="text-end pe-4">Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($customers as $c): ?>
            <tr>
                <td class="ps-4 fw-bold">
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:35px; height:35px;">
                            <?= strtoupper(substr($c['name'], 0, 1)) ?>
                        </div>
                        <?= $c['name'] ?>
                    </div>
                </td>
                <td><?= $c['email'] ?></td>
                <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                <td class="text-end pe-4">
                    <span class="badge bg-light text-dark border">Client</span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>