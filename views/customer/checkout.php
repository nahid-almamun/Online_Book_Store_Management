<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

$userStmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

$cartStmt = $conn->prepare("
    SELECT cart.*, books.title, books.price
    FROM cart
    JOIN books ON cart.book_id = books.id
    WHERE cart.user_id = ?
");

$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartItems = $cartStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">
    <a href="../../index.php">🏠 Home</a>
    <a href="books.php">📖 Books</a>
    <a href="cart.php">🛒 Cart </a>
    <a href="profile.php">👤 Profile</a>
    <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>
</div>

<div class="container">
    <div class="card">
        <h2>Checkout</h2>

        <?php if(isset($_SESSION['errors'])): ?>
            <div style="color:red;">
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <h3>Order Summary</h3>

        <?php
        $total = 0;
        while($item = $cartItems->fetch_assoc()):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>

            <p>
                <?php echo $item['title']; ?>
                × <?php echo $item['quantity']; ?>
                = ৳ <?php echo $subtotal; ?>
            </p>

        <?php endwhile; ?>

        <h3>Total: ৳ <?php echo $total; ?></h3>

        <?php if($total <= 0): ?>
            <p>Your cart is empty.</p>
            <a href="books.php"><button>Browse Books</button></a>
        <?php else: ?>

        <form method="POST" action="../../controllers/placeOrderController.php" onsubmit="return validateCheckout()">

            <label>Confirm Address</label>
            <textarea name="address" id="address"><?php echo $user['address']; ?></textarea>

            <label>Payment Method</label>
            <select name="payment_method" id="payment_method">
                <option value="">Select Payment Method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="bKash">bKash</option>
                <option value="Nagad">Nagad</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>

            <label>Transaction ID</label>
            <input type="text" name="transaction_id" placeholder="Optional for Cash on Delivery">

            <button type="submit">Place Order</button>
        </form>

        <?php endif; ?>
    </div>
</div>

<script>
function validateCheckout() {
    let address = document.getElementById("address").value.trim();
    let payment = document.getElementById("payment_method").value;

    if (address === "") {
        alert("Address is required.");
        return false;
    }

    if (payment === "") {
        alert("Please select a payment method.");
        return false;
    }

    return true;
}
</script>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>