<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

$ordersStmt = $conn->prepare("
    SELECT * FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");

$ordersStmt->bind_param("i", $user_id);
$ordersStmt->execute();
$orders = $ordersStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase History</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">
        📚 <span>BookStore</span>
    </div>
    <a href="../../index.php">🏠 Home</a>
    <a href="books.php">📖 Books</a>
    <a href="cart.php">🛒 Cart </a>
    <a href="profile.php">👤 Profile</a>
    <a href="order_history.php">📦 Purchase History</a>
    <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>
</div>

<div class="container">
    <h1>My Purchase History</h1>

    <?php if(isset($_SESSION['success'])): ?>
        <p style="color:green;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </p>
    <?php endif; ?>

    <?php if($orders->num_rows == 0): ?>
        <p>No purchase history found.</p>
    <?php endif; ?>

    <?php while($order = $orders->fetch_assoc()): ?>

        <div class="card">
            <h2>Order #<?php echo $order['id']; ?></h2>

            <p><strong>Total:</strong> ৳ <?php echo $order['total_amount']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
            <p><strong>Payment:</strong> <?php echo $order['payment_method']; ?></p>
            <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>

            <h3>Items</h3>

            <?php
            $order_id = $order['id'];

            $itemsStmt = $conn->prepare("
                SELECT order_items.*, books.title
                FROM order_items
                JOIN books ON order_items.book_id = books.id
                WHERE order_items.order_id = ?
            ");

            $itemsStmt->bind_param("i", $order_id);
            $itemsStmt->execute();
            $items = $itemsStmt->get_result();
            ?>

            <ul>
                <?php while($item = $items->fetch_assoc()): ?>
                    <li>
                        <?php echo $item['title']; ?>
                        × <?php echo $item['quantity']; ?>
                        — ৳ <?php echo $item['unit_price']; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

    <?php endwhile; ?>
</div>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>