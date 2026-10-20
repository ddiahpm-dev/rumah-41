<?php
declare(strict_types=1);
require_once __DIR__ . '/auth/check_auth.php'; require_login(); require_once __DIR__ . '/models/Product.php';
$pageTitle = 'Dashboard'; $pageHeading = 'Dashboard'; $productCount = Product::count(); require __DIR__ . '/includes/admin_layout_start.php';
?>
<section class="welcome"><div><p class="eyebrow">RINGKASAN USAHA</p><h2>Halo, <?= e($_SESSION['user']['name']) ?> 👋</h2><p class="muted">Pantau dan kelola produk Penglipuran Rumah41 dari satu tempat.</p></div><a class="button primary" href="product_form.php">+ Tambah produk</a></section><section class="stat-grid"><article class="stat-card"><span>Total produk</span><strong><?= $productCount ?></strong><a href="products.php">Lihat data produk →</a></article><article class="stat-card accent"><span>Peran akun</span><strong><?= e(ucfirst($_SESSION['user']['role'])) ?></strong><small>Akses pengelolaan aktif</small></article></section>
<?php require __DIR__ . '/includes/admin_layout_end.php'; ?>
