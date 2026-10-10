<?php
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database {

  // ---------- BRANDS ----------

public function addBrand($name) {
    $sql = $this->conn->prepare('INSERT INTO brands (brand_name) VALUES (?)');
    $sql->bind_param('s', $name);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function getAllBrands() {
    $sql = $this->conn->prepare('SELECT * FROM brands ORDER BY brand_name ASC');
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getBrandById($id) {
    $sql = $this->conn->prepare('SELECT * FROM brands WHERE brand_id = ?');
    $sql->bind_param('i', $id);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    $sql->close();
    return $row ?: false;
}

public function updateBrand($id, $name) {
    $sql = $this->conn->prepare('UPDATE brands SET brand_name = ? WHERE brand_id = ?');
    $sql->bind_param('si', $name, $id);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

  // ---------- CATEGORIES ----------

public function addCategory($name) {
    $sql = $this->conn->prepare('INSERT INTO categories (cat_name) VALUES (?)');
    $sql->bind_param('s', $name);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function getAllCategories() {
    $sql = $this->conn->prepare('SELECT * FROM categories ORDER BY cat_name ASC');
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getCategoryById($id) {
    $sql = $this->conn->prepare('SELECT * FROM categories WHERE cat_id = ?');
    $sql->bind_param('i', $id);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    $sql->close();
    return $row ?: false;
}

public function updateCategory($id, $name) {
    $sql = $this->conn->prepare('UPDATE categories SET cat_name = ? WHERE cat_id = ?');
    $sql->bind_param('si', $name, $id);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}


  // ---------- PRODUCTS (Task 9) ----------

public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
    $sql = $this->conn->prepare(
    'INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $sql->bind_param('iisdsss', $cat, $brand, $title, $price, $desc, $image_filename, $keywords);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
    $sql = $this->conn->prepare(
    'UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?,
     product_desc = ?, product_image = ?, product_keywords = ? WHERE product_id = ?'
    );
    $sql->bind_param('iisdsssi', $cat, $brand, $title, $price, $desc, $image_filename, $keywords, $id);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function getProductById($id) {
    $sql = $this->conn->prepare(
    'SELECT p.*, c.cat_name, b.brand_name
     FROM products p
     JOIN categories c ON p.product_cat = c.cat_id
     JOIN brands b ON p.product_brand = b.brand_id
     WHERE p.product_id = ?'
    );
    $sql->bind_param('i', $id);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    $sql->close();
    return $row ?: false;
}

public function getAllProducts() {
    $sql = $this->conn->prepare(
    'SELECT p.*, c.cat_name, b.brand_name
    FROM products p
    JOIN categories c ON p.product_cat = c.cat_id
    JOIN brands b ON p.product_brand = b.brand_id
    ORDER BY p.product_id DESC'
    );
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getFeaturedProducts($limit = 6) {
    $sql = $this->conn->prepare('SELECT * FROM products ORDER BY RAND() LIMIT ?');
    $sql->bind_param('i', $limit);
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getProductsByCategory($cat_id) {
    $sql = $this->conn->prepare('SELECT * FROM products WHERE product_cat = ? ORDER BY product_title ASC');
    $sql->bind_param('i', $cat_id);
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getProductsByBrand($brand_id) {
    $sql = $this->conn->prepare('SELECT * FROM products WHERE product_brand = ? ORDER BY product_title ASC');
    $sql->bind_param('i', $brand_id);
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function searchProducts($query) {
    $like = '%' . addcslashes($query, '%_\\') . '%';
    $sql = $this->conn->prepare(
    'SELECT * FROM products WHERE product_title LIKE ? OR product_keywords LIKE ? ORDER BY product_title ASC'
    );
    $sql->bind_param('ss', $like, $like);
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}
}