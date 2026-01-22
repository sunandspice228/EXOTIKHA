<?php $title = "My Account"; require_once ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-uppercase">
                        <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-muted text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">My account</li>
                    </ol>
                </nav>
                <h1 class="display-6 fw-bold" style="font-family: 'Playfair Display', serif;">My account</h1>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white text-center py-4">
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold fs-3" style="width: 60px; height: 60px;">
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    </div>
                    <h5 class="m-0 fw-bold"><?= $user['full_name'] ?></h5>
                    <small class="opacity-75"><?= $user['email'] ?></small>
                </div>
                
                <div class="list-group list-group-flush">
                    <a href="#dashboard" class="list-group-item list-group-item-action py-3 border-0 border-bottom fw-bold active-link" data-bs-toggle="tab">
                        Dashboard
                    </a>
                    <a href="#orders" class="list-group-item list-group-item-action py-3 border-0 border-bottom text-muted" data-bs-toggle="tab">
                        Orders
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 border-0 border-bottom text-muted">
                        Downloads
                    </a>
                    <a href="#addresses" class="list-group-item list-group-item-action py-3 border-0 border-bottom text-muted" data-bs-toggle="tab">
                        Addresses
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 border-0 border-bottom text-muted">
                        Payment methods
                    </a>
                    <a href="#details" class="list-group-item list-group-item-action py-3 border-0 border-bottom text-muted" data-bs-toggle="tab">
                        Account details
                    </a>
                    <a href="<?= url('/logout') ?>" class="list-group-item list-group-item-action py-3 border-0 text-danger fw-bold">
                        Log out
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="dashboard">
                    <div class="alert alert-light border shadow-sm p-4 mb-4">
                        <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Welcome to your account page</h4>
                        <p class="text-muted">
                            Hi <strong class="text-dark"><?= $user['full_name'] ?></strong>, today is a great day to check your account page. You can check also:
                        </p>
                        <div class="d-flex gap-3 mt-3 flex-wrap">
                            <a href="#" onclick="switchTab('orders')" class="btn btn-outline-dark rounded-0 px-4">Recent orders</a>
                            <a href="#" onclick="switchTab('addresses')" class="btn btn-outline-dark rounded-0 px-4">Addresses</a>
                            <a href="#" onclick="switchTab('details')" class="btn btn-outline-dark rounded-0 px-4">Account details</a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="orders">
                    <h4 class="fw-bold mb-4">Your Orders</h4>
                    <?php if(empty($orders)): ?>
                        <div class="text-center py-5 bg-light border">
                            <i class="fa-solid fa-box-open fs-1 text-muted mb-3"></i>
                            <p>No order has been made yet.</p>
                            <a href="<?= url('/') ?>" class="btn btn-dark rounded-0 text-uppercase fw-bold">Browse Products</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="bg-light">
                                    <tr><th>Order</th><th>Date</th><th>Status</th><th>Total</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $o): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= $o['id'] ?></td>
                                        <td><?= date('F j, Y', strtotime($o['created_at'])) ?></td>
                                        <td><span class="badge bg-warning text-dark"><?= ucfirst($o['status']) ?></span></td>
                                        <td class="fw-bold">₵<?= number_format($o['total_price'], 2) ?></td>
                                        <td><a href="#" class="btn btn-sm btn-dark rounded-0">View</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="addresses">
                    <h4 class="fw-bold mb-4">Addresses</h4>
                    <p class="text-muted small">The following addresses will be used on the checkout page by default.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white fw-bold border-bottom">Billing address</div>
                                <div class="card-body">
                                    <p class="fst-italic text-muted">
                                        <?= $user['full_name'] ?><br>
                                        <?= $user['address'] ?? 'No address set' ?><br>
                                        <?= $user['city'] ?? '' ?><br>
                                        <?= $user['phone'] ?? '' ?>
                                    </p>
                                    <a href="#" onclick="switchTab('details')" class="text-warning fw-bold text-decoration-none">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="details">
                    <h4 class="fw-bold mb-4">Account details</h4>
                    <form action="<?= url('/profile/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-uppercase">Full Name *</label>
                                <input type="text" name="full_name" class="form-control rounded-0 bg-light border-0 p-3" value="<?= $user['full_name'] ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-uppercase">Address</label>
                                <input type="text" name="address" class="form-control rounded-0 bg-light border-0 p-3" value="<?= $user['address'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Town / City</label>
                                <input type="text" name="city" class="form-control rounded-0 bg-light border-0 p-3" value="<?= $user['city'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Phone</label>
                                <input type="text" name="phone" class="form-control rounded-0 bg-light border-0 p-3" value="<?= $user['phone'] ?>">
                            </div>
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold">Password change</h5>
                                <p class="text-muted small">Leave blank to keep current password.</p>
                                <div class="mb-3">
                                    <input type="password" name="current_password" class="form-control rounded-0 bg-light border-0 p-3" placeholder="Current password">
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="new_password" class="form-control rounded-0 bg-light border-0 p-3" placeholder="New password">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark rounded-0 px-5 py-3 fw-bold text-uppercase">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 border-top">
        <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif;">You may also like...</h3>
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-4">
            <?php foreach($suggestedProducts as $p): ?>
            <div class="col">
                <div class="product-card h-100 position-relative border-0">
                    <div class="position-relative overflow-hidden bg-light" style="height: 250px;">
                        <a href="<?= url('/product/' . $p['id']) ?>">
                            <img src="<?= url('/uploads/' . $p['image']) ?>" class="w-100 h-100 object-fit-cover hover-zoom">
                        </a>
                        <?php if($p['is_promo']): ?>
                            <span class="badge bg-danger rounded-0 position-absolute top-0 start-0 m-2">Sale</span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3 text-center">
                        <div class="text-muted x-small text-uppercase mb-1"><?= $p['category'] ?></div>
                        <h6 class="fw-bold mb-1 text-truncate">
                            <a href="<?= url('/product/' . $p['id']) ?>" class="text-dark text-decoration-none"><?= $p['name'] ?></a>
                        </h6>
                        <div class="mb-2">
                            <?php if($p['is_promo']): ?>
                                <small class="text-muted text-decoration-line-through">₵<?= number_format($p['price'], 2) ?></small>
                                <span class="fw-bold text-dark ms-1">₵<?= number_format($p['promo_price'], 2) ?></span>
                            <?php else: ?>
                                <span class="fw-bold text-dark">₵<?= number_format($p['price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= url('/product/' . $p['id']) ?>" class="btn btn-outline-dark btn-sm rounded-0 w-100 text-uppercase fw-bold">View</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    // Petit script pour gérer les onglets du sidebar et les liens rapides
    function switchTab(tabId) {
        var triggerEl = document.querySelector('a[href="#' + tabId + '"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }
    
    // Active la classe "fw-bold active-link" sur l'onglet cliqué
    const links = document.querySelectorAll('.list-group-item[data-bs-toggle="tab"]');
    links.forEach(link => {
        link.addEventListener('shown.bs.tab', event => {
            links.forEach(l => {
                l.classList.remove('fw-bold', 'active-link'); 
                l.classList.add('text-muted');
            });
            event.target.classList.add('fw-bold', 'active-link');
            event.target.classList.remove('text-muted');
        });
    });
</script>

<style>
    .active-link { border-left: 4px solid var(--accent) !important; background-color: #f8f9fa; }
    .hover-zoom { transition: transform 0.5s ease; }
    .product-card:hover .hover-zoom { transform: scale(1.05); }
</style>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>