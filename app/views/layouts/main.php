<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Exotikha - Élégance & Intimité' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #000000;
            --secondary: #d4af37; /* Or Exotikha */
            --light: #f8f9fa;
            --font-main: 'Lato', sans-serif;
            --font-serif: 'Playfair Display', serif;
        }
        body { font-family: var(--font-main); color: #333; overflow-x: hidden; }
        h1, h2, h3, h4 { font-family: var(--font-serif); }
        
        /* HEADER */
        .top-bar { background: #000; color: #fff; font-size: 0.8rem; padding: 5px 0; letter-spacing: 1px; }
        .navbar-brand img { height: 50px; transition: transform 0.3s; }
        .navbar-brand:hover img { transform: scale(1.05); }
        .nav-link { text-transform: uppercase; font-size: 0.85rem; font-weight: 700; color: #000 !important; letter-spacing: 1px; margin: 0 10px; position: relative; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background: var(--secondary); transition: width 0.3s; }
        .nav-link:hover::after { width: 100%; }
        
        /* ICONS HEADER */
        .header-icons i { font-size: 1.2rem; margin-left: 15px; cursor: pointer; transition: color 0.3s; }
        .header-icons i:hover { color: var(--secondary); }
        .cart-count { background: var(--secondary); color: #fff; font-size: 0.6rem; padding: 2px 6px; border-radius: 50%; position: relative; top: -10px; right: 5px; }

        /* FOOTER */
        footer { background: #111; color: #aaa; padding: 60px 0 20px; }
        footer h5 { color: #fff; font-family: var(--font-serif); margin-bottom: 20px; }
        footer a { color: #aaa; text-decoration: none; transition: 0.3s; }
        footer a:hover { color: var(--secondary); }
        .social-icons a { margin-right: 15px; font-size: 1.2rem; }
    </style>
</head>
<body>

    <div class="top-bar text-center">
        <span><i class="fa-solid fa-truck-fast me-2"></i> Livraison Gratuite à Accra dès 1000 GHS</span>
    </div>

    <nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm py-3">
        <div class="container">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars"></i>
            </button>

            <a class="navbar-brand mx-auto d-lg-none" href="<?= url('/') ?>">
                <img src="<?= url('/public/assets/img/logo.png') ?>" alt="Exotikha">
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/') ?>">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/shop?category=Femme') ?>">Femme</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/shop?category=Homme') ?>">Homme</a></li>
                </ul>

                <a class="navbar-brand mx-auto d-none d-lg-block" href="<?= url('/') ?>">
                    <img src="https://exotikha.com/wp-content/uploads/2025/09/exotikha-logo.png" alt="Exotikha">
                </a>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="<?= url('/shop?category=Couple') ?>">Couple & Intimité</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/blog') ?>">Blog</a></li>
                </ul>
            </div>

            <div class="header-icons d-flex align-items-center ms-3">
                <a href="#" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa-solid fa-magnifying-glass"></i></a>
                <a href="<?= url('/login') ?>"><i class="fa-regular fa-user"></i></a>
                <a href="<?= url('/cart') ?>" class="position-relative">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="cart-count"><?= array_sum(array_column($_SESSION['cart'], 'quantity')) ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <?= $content ?>

    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="https://exotikha.com/wp-content/uploads/2020/09/EXOTIKHA.png" width="150" class="mb-3 grayscale" style="filter: invert(1);">
                    <p class="small">Exotikha – Sensuel, Élégant, Confiant. Une nouvelle expérience boutique africaine.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h5>Explorer</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Nouveautés</a></li>
                        <li><a href="#">Femme</a></li>
                        <li><a href="#">Homme</a></li>
                        <li><a href="#">Intimité</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Aide</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Suivre ma commande</a></li>
                        <li><a href="#">Livraison & Retours</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Newsletter</h5>
                    <p class="small">Rejoignez nos ventes privées exclusives.</p>
                    <form action="#" class="d-flex">
                        <input type="email" class="form-control form-control-sm me-2" placeholder="Email...">
                        <button class="btn btn-sm btn-light">OK</button>
                    </form>
                </div>
            </div>
            <div class="border-top border-secondary mt-5 pt-3 text-center small">
                &copy; <?= date('Y') ?> Exotikha. Tous droits réservés.
            </div>
        </div>
    </footer>

    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="<?= url('/') ?>" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control" placeholder="Rechercher un produit...">
                        <button class="btn btn-dark ms-2">Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>