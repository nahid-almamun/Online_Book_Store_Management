<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';
 
if (!isLoggedIn()) {
    redirect('../auth/login.php');
}
 
$user_id = $_SESSION['user_id'];
 
$stmt = $conn->prepare("
    SELECT cart.*, books.title, books.price, books.image_path, books.stock
    FROM cart
    JOIN books ON cart.book_id = books.id
    WHERE cart.user_id = ?
");
 
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cartItems = $stmt->get_result();
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
 
<div class="navbar">
    <div class="logo">
        📚 <span>BookStore</span>
    </div>
    <a href="../../index.php">🏠 Home</a>
    <a href="books.php">📖 Books</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="profile.php">👤 Profile</a>
    <a href="order_history.php">📦 Purchase History</a>
    <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>
</div>
 
<div class="container">
    <h1>My Cart</h1>
 
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <th>Image</th>
            <th>Book</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
 
        <?php
        $total = 0;
        while($item = $cartItems->fetch_assoc()):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
 
        <tr id="row-<?php echo $item['id']; ?>">
            <td>
                <?php if($item['image_path']): ?>
                    <img src="../../public/uploads/books/<?php echo $item['image_path']; ?>" width="70">
                <?php endif; ?>
            </td>
 
            <td><?php echo $item['title']; ?></td>
            <td>৳ <?php echo $item['price']; ?></td>
 
            <td>
                <input
                    type="number"
                    value="<?php echo $item['quantity']; ?>"
                    min="1"
                    max="<?php echo $item['stock']; ?>"
                    id="qty-<?php echo $item['id']; ?>"
                    style="width:70px;"
                >
 
                <button onclick="updateCart(<?php echo $item['id']; ?>)">Update</button>
            </td>
 
            <td>৳ <?php echo $subtotal; ?></td>
 
            <td>
                <button onclick="removeCartItem(<?php echo $item['id']; ?>)">Remove</button>
            </td>
        </tr>
 
        <?php endwhile; ?>
    </table>
 
    <h2>Total: ৳ <?php echo $total; ?></h2>
 
    <?php if($total > 0): ?>
        <a href="checkout.php">
            <button>Proceed to Checkout</button>
        </a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
 
<script>
function updateCart(cartId) {
    let quantity = document.getElementById("qty-" + cartId).value;
 
    fetch("../../api/update_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "cart_id=" + cartId + "&quantity=" + quantity
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
}
 
function removeCartItem(cartId) {
    if (!confirm("Remove this item?")) return;
 
    fetch("../../api/remove_cart_item.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "cart_id=" + cartId
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    });
}
</script>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>
