<?php ob_start(); ?>
<div class="container py-5 text-center">
    <div class="py-5">
        <i class="fa-solid fa-circle-check text-success fa-5x mb-4"></i>
        <h1 class="fw-bold">Paiement Réussi !</h1>
        <p class="lead text-muted">Merci pour votre commande. Vous pouvez la suivre dans votre compte.</p>
        <a href="<?= url('/account?tab=orders') ?>" class="btn btn-dark mt-3 px-5">Voir ma commande</a>
    </div>
</div>
<?php $content = ob_get_clean(); require_once ROOT_PATH . '/app/views/layouts/public.php'; ?>