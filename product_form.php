<?php
declare(strict_types=1);
require_once __DIR__ . '/auth/check_auth.php'; require_login(); require_once __DIR__ . '/controllers/ProductController.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); $product = $id ? Product::find($id) : null; if ($id && !$product) { http_response_code(404); exit('Produk tidak ditemukan.'); }
$pageTitle = $product ? 'Edit Produk' : 'Tambah Produk'; $pageHeading = $pageTitle; $error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        ProductController::save($id ? (int)$id : null, $_POST, $_FILES['image'] ?? null, $product['image'] ?? null);
        flash('success', $product ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');
        redirect('products.php');
    } catch (InvalidArgumentException | RuntimeException $exception) {
        $error = $exception->getMessage();
    }
}
require __DIR__ . '/includes/admin_layout_start.php';
?>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post" enctype="multipart/form-data" class="form-card"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)($id ?? 0) ?>"><label>Nama produk<input name="name" required value="<?= e($product['name'] ?? $_POST['name'] ?? '') ?>"></label><label>Deskripsi<textarea name="description" rows="4"><?= e($product['description'] ?? $_POST['description'] ?? '') ?></textarea></label><div class="form-grid"><label>Harga (Rp)<input type="number" name="price" min="0" step="100" required value="<?= e((string)($product['price'] ?? $_POST['price'] ?? '')) ?>"></label><label>Stok<input type="number" name="stock" min="0" required value="<?= e((string)($product['stock'] ?? $_POST['stock'] ?? '')) ?>"></label></div><label>Gambar produk (opsional)<input type="file" name="image" accept="image/jpeg,image/png,image/webp"><small class="muted">JPG, PNG, atau WEBP. Maksimal 5 MB.</small><?php if (!empty($product['image'])): ?><img class="current-image" src="<?= e($product['image']) ?>" alt="Gambar <?= e($product['name']) ?>"><?php endif; ?></label><div class="form-actions"><a class="button" href="products.php">Batal</a><button class="button primary" type="submit"><?= $product ? 'Simpan perubahan' : 'Simpan produk' ?></button></div></form>
<?php require __DIR__ . '/includes/admin_layout_end.php'; ?>
