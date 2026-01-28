<?php
// Session9/user/checkout/transaction_process.php

session_start();
require_once __DIR__ . "/../../config/connection_pdo.php";

// ============================
// VALIDATION
// ============================

if (empty($_SESSION['cart'])) {
    die("Cart is empty.");
}

$name    = trim($_POST['full_name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $address === '') {
    die("All fields are required.");
}

// ============================
// START DATABASE PROCESS
// ============================

try {

    $pdo->beginTransaction();

    // ============================
    // 1. SAVE USER DATA
    // ============================
    $stmtUser = $pdo->prepare("
        INSERT INTO users (name, email, phone, address)
        VALUES (:name, :email, :phone, :address)
    ");
    $stmtUser->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':phone'   => $phone,
        ':address' => $address
    ]);

    $user_id = $pdo->lastInsertId();

    // ============================
    // 2. GET CART PRODUCTS
    // ============================
    $cart = $_SESSION['cart'];
    $productIds = array_keys($cart);

    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    $stmtProduct = $pdo->prepare("
        SELECT id, price FROM products
        WHERE id IN ($placeholders)
    ");
    $stmtProduct->execute($productIds);
    $products = $stmtProduct->fetchAll(PDO::FETCH_ASSOC);

    // ============================
    // 3. CALCULATE TOTAL
    // ============================
    $total = 0;
    $items = [];

    foreach ($products as $product) {
        $qty = (int)$cart[$product['id']];
        $subtotal = $qty * (float)$product['price'];
        $total += $subtotal;

        $items[] = [
            'product_id'  => $product['id'],
            'quantity'    => $qty,
            'total_price' => $subtotal
        ];
    }

    // ============================
    // 4. SAVE TRANSACTION
    // ============================
    $stmtTransaction = $pdo->prepare("
        INSERT INTO transactions (status, total, user_id)
        VALUES ('pending', :total, :user_id)
    ");
    $stmtTransaction->execute([
        ':total'   => $total,
        ':user_id' => $user_id
    ]);

    $transaction_id = $pdo->lastInsertId();

    // ============================
    // 5. SAVE TRANSACTION ITEMS
    // ============================
    $stmtItem = $pdo->prepare("
        INSERT INTO transaction_items
        (transaction_id, product_id, quantity, total_price)
        VALUES (:transaction_id, :product_id, :quantity, :total_price)
    ");

    foreach ($items as $item) {
        $stmtItem->execute([
            ':transaction_id' => $transaction_id,
            ':product_id'     => $item['product_id'],
            ':quantity'       => $item['quantity'],
            ':total_price'    => $item['total_price']
        ]);
    }

    // ============================
    // FINISH
    // ============================
    $pdo->commit();

    unset($_SESSION['cart']);

    header("Location: ../transaction_status.php?transaction_id=" . $transaction_id);
    exit;

} catch (Exception $e) {

    $pdo->rollBack();
    die("Transaction failed: " . $e->getMessage());

}
