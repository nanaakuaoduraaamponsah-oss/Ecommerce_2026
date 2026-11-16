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

// ===================== TASK 13: PAYMENT =====================

// Creates the order, copies the cart into orderdetails, records the payment and
// empties the cart - all inside ONE transaction. Returns the new order_id, or false.
public function processPayment($customer_id, $ip, $reference, $amount) {
    $this->conn->begin_transaction();
    try {
        // 0. read the cart rows we are about to turn into an order
        $sql = $this->conn->prepare('SELECT p_id, qty FROM cart WHERE ip_add = ?');
        $sql->bind_param('s', $ip);
        $sql->execute();
        $cartRows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->close();
        if (empty($cartRows)) {
            throw new Exception('Cart is empty.');
        }

        // 1. invoice number (retry if that number is already taken)
        do {
            $invoice_no = mt_rand(100000, 999999);
            $chk = $this->conn->prepare('SELECT order_id FROM orders WHERE invoice_no = ?');
            $chk->bind_param('i', $invoice_no);
            $chk->execute();
            $taken = $chk->get_result()->num_rows > 0;
            $chk->close();
        } while ($taken);

        // 2. orders row
        $status = 'paid';
        $sql = $this->conn->prepare(
            'INSERT INTO orders (customer_id, invoice_no, order_date, order_status, created_at)
             VALUES (?, ?, CURDATE(), ?, NOW())'
        );
        $sql->bind_param('iis', $customer_id, $invoice_no, $status);
        if (!$sql->execute()) throw new Exception($sql->error);
        $order_id = $this->conn->insert_id;
        $sql->close();

        // 3. cart rows -> orderdetails
        $sql = $this->conn->prepare('INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)');
        foreach ($cartRows as $row) {
            $pid = (int) $row['p_id'];
            $qty = (int) $row['qty'];
            $sql->bind_param('iii', $order_id, $pid, $qty);
            if (!$sql->execute()) throw new Exception($sql->error);
        }
        $sql->close();

        // 3b. payment record (uses the existing payment table + the new reference column)
        $currency = 'GHS';
        $sql = $this->conn->prepare(
            'INSERT INTO payment (amt, customer_id, order_id, currency, payment_date, reference)
             VALUES (?, ?, ?, ?, CURDATE(), ?)'
        );
        $sql->bind_param('diiss', $amount, $customer_id, $order_id, $currency, $reference);
        if (!$sql->execute()) throw new Exception($sql->error);
        $sql->close();

        // 4. empty the cart
        $sql = $this->conn->prepare('DELETE FROM cart WHERE ip_add = ?');
        $sql->bind_param('s', $ip);
        if (!$sql->execute()) throw new Exception($sql->error);
        $sql->close();

        $this->conn->commit();
        return $order_id;               // 5.
    } catch (Throwable $e) {
        $this->conn->rollback();        // nothing is saved if any step failed
        error_log('processPayment failed: ' . $e->getMessage());
        return false;
    }
}

// One row per product in the order, with the product name and price.
// Passing $customer_id makes sure a customer can only open THEIR OWN order.
public function getOrderById($order_id, $customer_id = null) {
    $query = 'SELECT o.order_id, o.customer_id, o.invoice_no, o.order_date, o.order_status,
                     od.product_id, od.qty, p.product_title, p.product_price
              FROM orders o
              JOIN orderdetails od ON od.order_id = o.order_id
              JOIN products p ON p.product_id = od.product_id
              WHERE o.order_id = ?';
    if ($customer_id !== null) {
        $query .= ' AND o.customer_id = ?';
    }
    $query .= ' ORDER BY p.product_title ASC';

    $sql = $this->conn->prepare($query);
    if ($customer_id !== null) {
        $sql->bind_param('ii', $order_id, $customer_id);
    } else {
        $sql->bind_param('i', $order_id);
    }
    $sql->execute();
    $rows = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    return $rows;
}

// If this Paystack reference was already turned into an order, return that order_id.
public function getOrderIdByReference($reference, $customer_id) {
    $sql = $this->conn->prepare('SELECT order_id FROM payment WHERE reference = ? AND customer_id = ?');
    $sql->bind_param('si', $reference, $customer_id);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    $sql->close();
    return $row ? (int) $row['order_id'] : false;
}

// True if the reference exists at all (any customer)
public function referenceExists($reference) {
    $sql = $this->conn->prepare('SELECT 1 FROM payment WHERE reference = ?');
    $sql->bind_param('s', $reference);
    $sql->execute();
    $found = $sql->get_result()->num_rows > 0;
    $sql->close();
    return $found;
}
}