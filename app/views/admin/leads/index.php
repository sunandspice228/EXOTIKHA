<?php ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">💎 Inscrits Ventes Privées</h3>
    <span class="badge bg-warning text-dark fs-6"><?= count($leads) ?> leads</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nom Complet</th>
                        <th>Numéro WhatsApp</th>
                        <th>Date d'inscription</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($leads)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Aucun inscrit pour le moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($leads as $lead): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $lead['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($lead['full_name']) ?></td>
                            <td class="text-success fw-bold">
                                <i class="fa-brands fa-whatsapp me-1"></i> <?= htmlspecialchars($lead['whatsapp_number']) ?>
                            </td>
                            <td><?= date('d/m/Y à H:i', strtotime($lead['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead['whatsapp_number']) ?>" target="_blank" class="btn btn-sm btn-success text-white fw-bold">
                                    <i class="fa-regular fa-paper-plane"></i> Contacter
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/admin.php'; ?>