<?php
require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController {
private $model;

public function __construct() {
    $this->model = new ProductClass();
}

public function addBrand($name) {
    return $this->model->addBrand($name);
}

public function getAllBrands() {
    return $this->model->getAllBrands();
}

public function getBrandById($id) {
    return $this->model->getBrandById($id);
}

public function updateBrand($id, $name) {
    return $this->model->updateBrand($id, $name);
}

public function addCategory($name) {
    return $this->model->addCategory($name);
}

public function getAllCategories() {
    return $this->model->getAllCategories();
}

public function getCategoryById($id) {
    return $this->model->getCategoryById($id);
}

public function updateCategory($id, $name) {
    return $this->model->updateCategory($id, $name);
}


public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
    return $this->model->addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords);
}

public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
    return $this->model->updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords);
}

public function getProductById($id) {
    return $this->model->getProductById($id);
}

public function getAllProducts() {
    return $this->model->getAllProducts();
}

public function getFeaturedProducts($limit = 6) {
    return $this->model->getFeaturedProducts($limit);
}

public function getProductsByCategory($cat_id) {
    return $this->model->getProductsByCategory($cat_id);
}

public function getProductsByBrand($brand_id) {
    return $this->model->getProductsByBrand($brand_id);
}

public function searchProducts($query) {
    return $this->model->searchProducts($query);
}
}