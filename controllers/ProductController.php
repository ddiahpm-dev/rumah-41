<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Product.php';

final class ProductController
{
    public static function index(): array
    {
        return Product::all();
    }

    public static function save(?int $id, array $input, ?array $uploadedFile, ?string $currentImage = null): void
    {
        $name = trim((string)($input['name'] ?? ''));
        $description = trim((string)($input['description'] ?? ''));
        $price = (float)($input['price'] ?? 0);
        $stock = filter_var($input['stock'] ?? null, FILTER_VALIDATE_INT);

        if ($name === '' || $price < 0 || $stock === false || $stock === null || $stock < 0) {
            throw new InvalidArgumentException('Nama, harga, dan stok harus diisi dengan nilai yang valid.');
        }

        $image = $currentImage ?? '';
        if ($uploadedFile && ($uploadedFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $image = self::storeImage($uploadedFile);
        }

        if ($id !== null) {
            Product::update($id, $name, $description, $price, $stock, $image);
        } else {
            Product::create($name, $description, $price, $stock, $image);
        }
    }

    private static function storeImage(array $file): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('Upload gambar gagal. Silakan pilih file lain.');
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new InvalidArgumentException('Ukuran gambar maksimal 5 MB.');
        }

        $temporaryPath = (string)($file['tmp_name'] ?? '');
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($extensions[$mime])) {
            throw new InvalidArgumentException('Format gambar harus JPG, PNG, atau WEBP.');
        }

        $directory = __DIR__ . '/../assets/uploads';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Folder upload gambar tidak dapat dibuat.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        $destination = $directory . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($temporaryPath, $destination)) {
            throw new RuntimeException('Gambar tidak dapat disimpan.');
        }
        return 'assets/uploads/' . $filename;
    }
}
