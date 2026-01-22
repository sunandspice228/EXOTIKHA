<?php if(empty($orders)): ?>
    <div class="text-center py-5 border bg-white">
        <i class="fa-solid fa-bag-shopping text-secondary opacity-25 mb-3" style="font-size: 3rem;"></i>
        <p class="text-muted mb-4"><?= __('Aucune commande trouvée.', 'No orders found.') ?></p>
        <a href="<?= url('/') ?>" class="btn btn-dark text-uppercase btn-sm px-4 py-2 rounded-0">
            <?= __('Shopping', 'Start Shopping') ?>
        </a>
    </div>
<?php else: ?>
    <div class="table-responsive border">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-uppercase text-muted">
                <tr>
                    <th class="py-3 ps-4">Ref</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td class="ps-4 fw-bold">#<?= e($order['id']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td>
                            <?php 
                                $statusClass = 'bg-secondary';
                                if($order['status'] == 'completed') $statusClass = 'bg-success';
                                if($order['status'] == 'pending') $statusClass = 'bg-warning text-dark';
                                if($order['status'] == 'cancelled') $statusClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $statusClass ?> rounded-0 fw-normal"><?= e($order['status']) ?></span>
                        </td>
                        <td class="fw-bold"><?= format_price($order['total_amount']) ?></td>
                        <td class="text-end pe-4">
                            <a href="#" class="btn btn-sm btn-outline-dark rounded-0"><?= __('Voir', 'View') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>