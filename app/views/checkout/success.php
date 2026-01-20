<?php ob_start(); ?>

<div class="container py-5 text-center">
    <div class="card shadow-lg border-0 rounded-lg p-5 mx-auto" style="max-width: 600px;">
        <div class="mb-4 text-success">
            <i class="fa-regular fa-circle-check fa-5x animate-bounce"></i>
        </div>
        
        <h1 class="fw-bold text-dark mb-3">Paiement Réussi !</h1>
        <p class="lead text-muted">Merci pour votre commande.</p>
        
        <div class="bg-light p-3 rounded mb-4">
            <span class="text-muted text-uppercase small fw-bold">Référence de commande</span>
            <div class="fs-4 fw-bold text-dark font-monospace"><?= $reference ?></div>
        </div>

        <p class="mb-4">Un email de confirmation vous sera envoyé prochainement. Vous pouvez suivre votre commande dans votre compte.</p>

        <div class="d-grid gap-2 d-md-block">
            <a href="<?= url('/account?tab=orders') ?>" class="btn btn-dark btn-lg px-4 rounded-pill">
                Voir ma commande
            </a>
            <a href="<?= url('/') ?>" class="btn btn-outline-dark btn-lg px-4 rounded-pill">
                Continuer mes achats
            </a>
        </div>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-20px);}
    60% {transform: translateY(-10px);}
}
.animate-bounce {
    animation: bounce 2s infinite;
}
</style>

<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>