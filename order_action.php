<?php
require_once 'includes/db.php';

if (isset($_POST['place_order'])) {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
        header('Location: index.php');
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $kecamatan = $_POST['kecamatan'];
    $payment_method = 'Bank Transfer';

    $base_address = preg_replace('/, Kecamatan.*$/', '', $address);
    
    $full_shipping_address = $base_address . ", Kecamatan " . $kecamatan;
    
    $stmt_update_user = $conn->prepare("UPDATE users SET address = ? WHERE id = ?");
    $stmt_update_user->bind_param("si", $full_shipping_address, $user_id);
    $stmt_update_user->execute();
    $stmt_update_user->close();

    $subtotal = 0;
    
    $product_ids = array_keys($_SESSION['cart']);
    if (!empty($product_ids)) {
        $ids_string = implode(',', $product_ids);
        $sql = "SELECT id, price FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {
                $quantity = $_SESSION['cart'][$product['id']];
                $subtotal += $product['price'] * $quantity;
            }
        }
    }

    $shipping_costs = [
        "Cakung" => 15000, "Duren Sawit" => 15000, "Pulo Gadung" => 15000,
        "Jatinegara" => 12000, "Kramat Jati" => 12000, "Matraman" => 12000,
        "Cipayung" => 18000, "Ciracas" => 18000, "Makasar" => 18000, "Pasar Rebo" => 18000
    ];
    $shipping_cost = $shipping_costs[$kecamatan] ?? 0;

    $total_amount = $subtotal + $shipping_cost;

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_cost, shipping_address, status, payment_method) VALUES (?, ?, ?, ?, 'waiting_payment', ?)");
    $stmt->bind_param("idsss", $user_id, $total_amount, $shipping_cost, $full_shipping_address, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    $sql_items = "SELECT id, price FROM products WHERE id IN ($ids_string)";
    $products_result = $conn->query($sql_items);
    $products_data = [];
    if($products_result && $products_result->num_rows > 0) {
        while($row = $products_result->fetch_assoc()){
            $products_data[$row['id']] = $row['price'];
        }
    }

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if(isset($products_data[$product_id])) {
            $price = $products_data[$product_id];
            $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt_item->execute();
        }
    }
    $stmt_item->close();

    unset($_SESSION['cart']);
    
    header("Location: " . BASE_URL . "payment.php?order_id=$order_id");
    exit();
}
?>