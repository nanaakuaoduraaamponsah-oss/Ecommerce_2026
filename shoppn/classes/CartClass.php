<?php
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database {

public function isInCart($product_id, $ip) {
    $sql = $this->conn->prepare('SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?');
    $sql->bind_param('is', $product_id, $ip);
    $sql->execute();
    $found = $sql->get_result()->num_rows > 0;
    $sql->close();
    return $found;
}

public function addToCart($product_id, $qty, $ip, $customer_id = null) {
    $sql = $this->conn->prepare('INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)');
    $sql->bind_param('isii', $product_id, $ip, $customer_id, $qty);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function getCartItems($ip) {
    $sql = $this->conn->prepare(
    'SELECT cart.*, products.product_title, products.product_price, products.product_image
    FROM cart
    JOIN products ON cart.p_id = products.product_id
    WHERE cart.ip_add = ?
    ORDER BY products.product_title ASC'
    );
    $sql->bind_param('s', $ip);
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

public function getCartCount($ip) {
    $sql = $this->conn->prepare('SELECT COUNT(*) FROM cart WHERE ip_add = ?');
    $sql->bind_param('s', $ip);
    $sql->execute();
    $count = (int) $sql->get_result()->fetch_row()[0];
    $sql->close();
    return $count;
}

public function getCartTotal($ip) {
    $sql = $this->conn->prepare(
    'SELECT COALESCE(SUM(cart.qty * products.product_price), 0)
    FROM cart
    JOIN products ON cart.p_id = products.product_id
    WHERE cart.ip_add = ?'
    );
    $sql->bind_param('s', $ip);
    $sql->execute();
    $total = (float) $sql->get_result()->fetch_row()[0];
    $sql->close();
    return $total;
}

public function removeFromCart($product_id, $ip) {
    $sql = $this->conn->prepare('DELETE FROM cart WHERE p_id = ? AND ip_add = ?');
    $sql->bind_param('is', $product_id, $ip);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}

public function updateQty($product_id, $new_qty, $ip) {
    if ($new_qty < 1) {
    return false;
    }
    $sql = $this->conn->prepare('UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?');
    $sql->bind_param('iis', $new_qty, $product_id, $ip);
    $ok = $sql->execute();
    $sql->close();
    return $ok;
}
}