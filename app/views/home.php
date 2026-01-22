<?php require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>

<?php if(isset($_SESSION['flash'])): ?>
    <div class="container mt-3">
        <?php foreach($_SESSION['flash'] as $type => $message): ?>
            <div class="alert alert-<?= $type == 'error' ? 'danger' : 'success' ?> alert-dismissible fade show">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>


<?php if($isSearching): ?>
    
    <div class="container my-5">
        <h2 class="fw-bold mb-4"><?= $title ?></h2>
        
        <?php if(empty($products)): ?>
            <div class="bg-light p-5 text-center border">
                <h4 class="fw-bold mb-3">No products were found</h4>
                <p class="text-muted mb-4">Check your spelling or search again with less specific terms.</p>
                <a href="<?= url('/') ?>" class="btn btn-dark rounded-0 text-uppercase fw-bold px-4 py-2">Return To Shop</a>
            </div>
        <?php else: ?>
            <div class="row g-4 row-cols-1 row-cols-md-3">
                <?php foreach($products as $p): ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <a href="<?= url('/product/' . $p['id']) ?>">
                                <img src="<?= url('/uploads/' . $p['image']) ?>" class="card-img-top object-fit-cover" style="height:300px;">
                            </a>
                            <div class="card-body text-center">
                                <h6 class="fw-bold"><a href="<?= url('/product/' . $p['id']) ?>" class="text-dark text-decoration-none"><?= $p['name'] ?></a></h6>
                                <span class="fw-bold">₵<?= number_format($p['price'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php else: ?>

    <div class="container my-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="category-banner position-relative overflow-hidden text-center text-white" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1594631252845-29fc4cc8cde9?q=80&w=800" class="w-100 h-100 object-fit-cover brightness-50">
                    <div class="position-absolute top-50 start-50 translate-middle w-100 px-3">
                        <h3 class="text-uppercase fw-bold mb-3" style="font-family: 'Playfair Display', serif;">For Women</h3>
                        <a href="<?= url('/?category=Women') ?>" class="btn btn-outline-light rounded-0 text-uppercase fw-bold px-4 py-2" style="font-size:12px;">Shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-banner position-relative overflow-hidden text-center text-white" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=800" class="w-100 h-100 object-fit-cover brightness-50">
                    <div class="position-absolute top-50 start-50 translate-middle w-100 px-3">
                        <h3 class="text-uppercase fw-bold mb-3" style="font-family: 'Playfair Display', serif;">For Men</h3>
                        <a href="<?= url('/?category=Men') ?>" class="btn btn-outline-light rounded-0 text-uppercase fw-bold px-4 py-2" style="font-size:12px;">Discover more</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-banner position-relative overflow-hidden text-center text-white" style="height: 300px;">
                    <img src="https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=800" class="w-100 h-100 object-fit-cover brightness-50">
                    <div class="position-absolute top-50 start-50 translate-middle w-100 px-3">
                        <h3 class="text-uppercase fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Gift Boxes</h3>
                        <a href="<?= url('/?category=Gifts') ?>" class="btn btn-outline-light rounded-0 text-uppercase fw-bold px-4 py-2" style="font-size:12px;">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">New Arrivals</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">
                    Stay ahead of trends with our latest collections.
                </p>
            </div>

            <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-4">
                <?php foreach($newArrivals as $p): ?>
                <div class="col">
                    <div class="product-card h-100 position-relative">
                        <div class="position-relative overflow-hidden bg-light" style="height: 300px;">
                            <a href="<?= url('/product/' . $p['id']) ?>">
                                <img src="<?= url('/uploads/' . $p['image']) ?>" class="w-100 h-100 object-fit-cover hover-zoom">
                            </a>
                            <?php if($p['is_promo']): ?>
                                <span class="badge bg-danger rounded-0 position-absolute top-0 start-0 m-2">SALE!</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-center mt-3">
                            <div class="text-muted x-small text-uppercase mb-1"><?= $p['category'] ?></div>
                            <h6 class="fw-bold mb-2 text-truncate">
                                <a href="<?= url('/product/' . $p['id']) ?>" class="text-dark text-decoration-none"><?= $p['name'] ?></a>
                            </h6>
                            <div class="mb-2">
                                <?php if($p['is_promo']): ?>
                                    <span class="text-muted text-decoration-line-through small me-2">₵<?= number_format($p['price'], 2) ?></span>
                                    <span class="fw-bold text-dark">₵<?= number_format($p['promo_price'], 2) ?></span>
                                <?php else: ?>
                                    <span class="fw-bold text-dark">₵<?= number_format($p['price'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-5 bg-dark text-white text-center" style="background: url('https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=2000&auto=format&fit=crop') fixed center/cover;">
        <div class="container position-relative z-2" style="background: rgba(0,0,0,0.7); padding: 50px; border: 1px solid #d4af37;">
            <h2 class="display-5 fw-bold mb-2" style="font-family: 'Playfair Display', serif;">Join our exclusive private sales 💎</h2>
            <p class="lead mb-4 opacity-75">We bring a curated selection of items, and you choose what you love.</p>
            
            <form action="<?= url('/join-private-sale') ?>" method="POST" class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <input type="text" name="full_name" class="form-control rounded-0 p-3 bg-transparent text-white border-light" placeholder="Your full name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="whatsapp_number" class="form-control rounded-0 p-3 bg-transparent text-white border-light" placeholder="Your WhatsApp number" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100 h-100 rounded-0 fw-bold text-uppercase">Join Now</button>
                </div>
            </form>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" style="font-family: 'Playfair Display', serif;">What Our Customer Say</h2>
            
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php $i = 0; foreach($testimonials as $t): ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <p class="lead fst-italic text-muted mb-4">"<?= htmlspecialchars($t['content']) ?>"</p>
                                    <h5 class="fw-bold"><?= htmlspecialchars($t['author']) ?></h5>
                                    <span class="text-warning small text-uppercase fw-bold"><?= htmlspecialchars($t['city']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
                </button>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase small ls-1">Blog</span>
                <h2 class="fw-bold" style="font-family: 'Playfair Display', serif;">Exotikha Lifestyle & Inspirations</h2>
            </div>

            <div class="row g-4">
                <?php foreach($blogPosts as $b): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0">
                        <div class="position-relative">
                            <img src="<?= url('/assets/img/blog/' . $b['image']) ?>" onerror="this.src='https://via.placeholder.com/800x600?text=Blog'" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 bg-white p-2 text-center m-3 shadow-sm">
                                <span class="d-block fw-bold h4 m-0"><?= $b['day'] ?></span>
                                <span class="d-block small text-muted text-uppercase"><?= $b['month'] ?></span>
                            </div>
                        </div>
                        <div class="card-body px-0">
                            <span class="text-warning text-uppercase x-small fw-bold"><?= htmlspecialchars($b['category']) ?></span>
                            <h5 class="fw-bold mt-2"><a href="#" class="text-dark text-decoration-none"><?= htmlspecialchars($b['title']) ?></a></h5>
                            <p class="text-muted small"><?= htmlspecialchars($b['excerpt']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php endif; ?>

<style>
    .brightness-50 { filter: brightness(0.6); transition: 0.3s; }
    .category-banner:hover .brightness-50 { filter: brightness(0.4); }
    .hover-zoom { transition: transform 0.5s ease; }
    .product-card:hover .hover-zoom { transform: scale(1.05); }
</style>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>