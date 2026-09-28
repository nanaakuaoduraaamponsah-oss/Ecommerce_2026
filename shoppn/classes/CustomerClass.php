<?php
require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database {

  public function emailExists($email) {
    $sql = $this->conn->prepare('SELECT customer_email FROM customer WHERE customer_email = ?');
    $sql->bind_param('s', $email);
    $sql->execute();
    $res = $sql->get_result();
    $found = ($res->num_rows > 0);
    $sql->close();
    return $found;
  }

  public function addCustomer($name, $email, $pass, $country, $city, $contact) {
    $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

    $sql = $this->conn->prepare(
      'INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role)
      VALUES (?, ?, ?, ?, ?, ?, 2)'
    );
    $sql->bind_param('ssssss', $name, $email, $hashedPass, $country, $city, $contact);
    $ok = $sql->execute();
    $newId = $this->conn->insert_id;
    $sql->close();

    return $ok ? $newId : false;
  }

  public function getCustomerByEmail($email) {
    $sql = $this->conn->prepare('SELECT * FROM customer WHERE customer_email = ?');
    $sql->bind_param('s', $email);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    $sql->close();
    return $row ?: false;
  }

  public function login($email, $pass) {
    $account = $this->getCustomerByEmail($email);
    if ($account && password_verify($pass, $account['customer_pass'])) {
      return $account;
    }
    return false;
  }
}