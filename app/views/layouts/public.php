<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Exotikha - Élégance & Intimité' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body>

    <div class="top-bar text-center text-white bg-black py-2 small fw-bold ls-1">
        LIVRAISON GRATUITE À ACCRA DÈS 1,000 GHS 🇬🇭
    </div>

    <header class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="<?= url('/') ?>">
                <img src="https://i0.wp.com/exotikha.com/wp-content/uploads/2025/09/exotikha-logo.png" alt="Exotikha" height="40">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-uppercase fw-bold small ls-1">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/') ?>">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/?category=Femme') ?>">Femme</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/?category=Homme') ?>">Homme</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/?category=Intimité') ?>">Intimité</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="<?= url('/?is_promo=1') ?>">Promos</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3 icon-group">
                    <a href="#" class="text-dark"><i class="fa-solid fa-magnifying-glass"></i></a>
                    <a href="<?= url('/login') ?>" class="text-dark"><i class="fa-regular fa-user"></i></a>
                    <a href="<?= url('/cart') ?>" class="text-dark position-relative">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-gold text-dark" style="font-size:0.6rem;">
                            <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer class="bg-black text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="playfair mb-3">À propos</h5>
                    <p class="small text-white-50">Exotikha – Sensuel, Élégant, Confiant. Une nouvelle expérience boutique africaine pour le style et le bien-être.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-3"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-white"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5 class="playfair mb-3">Liens Utiles</h5>
                    <ul class="list-unstyled small text-white-50">
                        <li><a href="#" class="text-white-50 text-decoration-none">Mon Compte</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Suivre ma commande</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Politique de retour</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="playfair mb-3">Newsletter</h5>
                    <p class="small text-white-50">Rejoignez nos ventes privées exclusives.</p>
                    <form action="#" class="d-flex">
                        <input type="email" class="form-control rounded-0" placeholder="Email...">
                        <button class="btn btn-gold rounded-0">OK</button>
                    </form>
                </div>
            </div>
            <hr class="border-secondary mt-5">
            <div class="text-center small text-white-50">
                &copy; <?= date('Y') ?> Exotikha. Designed by Etroteck.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>