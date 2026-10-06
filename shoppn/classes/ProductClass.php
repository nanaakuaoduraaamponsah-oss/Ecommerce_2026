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
}