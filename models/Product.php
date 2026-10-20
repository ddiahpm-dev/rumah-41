<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

final class Product
{
    public static function all(): array
    {
        return db()->query('SELECT id, name, description, price, stock, image, created_at FROM products ORDER BY created_at DESC')->fetchAll();
    }
    public static function count(): int
    {
        return (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
    }
    public static function find(int $id): ?array
    {
        $query = db()->prepare('SELECT * FROM products WHERE id = :id');
        $query->execute(['id' => $id]);
        return $query->fetch() ?: null;
    }
    public static function create(string $name, string $description, float $price, int $stock, string $image): void
    {
        $query = db()->prepare('INSERT INTO products (name, description, price, stock, image) VALUES (:name, :description, :price, :stock, :image)');
        $query->execute(compact('name', 'description', 'price', 'stock', 'image'));
    }
    public static function update(int $id, string $name, string $description, float $price, int $stock, string $image): void
    {
        $query = db()->prepare('UPDATE products SET name=:name, description=:description, price=:price, stock=:stock, image=:image WHERE id=:id');
        $query->execute(compact('id', 'name', 'description', 'price', 'stock', 'image'));
    }
}
