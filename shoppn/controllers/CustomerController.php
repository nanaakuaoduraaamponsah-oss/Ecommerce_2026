<?php
require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController {
private $model;

public function __construct() {
    $this->model = new CustomerClass();
}

public function register($data) {
    if ($this->model->emailExists($data['email'])) {
    return ['success' => false, 'error' => 'That email is already registered.'];
    }

    $newId = $this->model->addCustomer(
    $data['name'],
    $data['email'],
    $data['pass'],
    $data['country'],
    $data['city'],
    $data['contact']
    );

    if (!$newId) {
    return ['success' => false, 'error' => 'Something went wrong — please try again.'];
    }

    return ['success' => true, 'customer_id' => $newId];
}

public function login($email, $pass) {
    $account = $this->model->login($email, $pass);
    if (!$account) {
    return ['success' => false, 'error' => 'Incorrect email or password.'];
    }
    return ['success' => true, 'customer' => $account];
}
}