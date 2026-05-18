<<<<<<< HEAD
=======
<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';
 
if (!isset($_GET['id'])) {
    redirect('books.php');
}
 
$id = (int) $_GET['id'];
 
$stmt = $conn->prepare("
    SELECT books.*, categories.name AS category_name
    FROM books
    JOIN categories ON books.category_id = categories.id
    WHERE books.id = ?
");
 
$stmt->bind_param("i", $id);
$stmt->execute();
 
$book = $stmt->get_result()->fetch_assoc();
 
if (!$book) {
    redirect('books.php');
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $book['title']; ?></title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
 
<div class="navbar">
 
    <a href="../../index.php">🏠 Home</a>
    <a href="books.php">📖 Books</a>
    <a href="cart.php">🛒 Cart</a>
    <a href="order_history.php">📦 Purchase History</a>
 
    <?php if(isLoggedIn()): ?>
        <a href="profile.php">👤 Profile</a>
        <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>
    <?php else: ?>
        <a href="../auth/login.php">🔑 Login </a>
        <a href="../auth/register.php">📝 Register</a>
    <?php endif; ?>
</div>
 
<div class="container">
    <div class="card">
        <h1><?php echo $book['title']; ?></h1>
 
        <img
            src="<?php echo $book['image_path'] ? '../../public/uploads/books/'.$book['image_path'] : 'https://via.placeholder.com/200x250'; ?>"
            style="width:220px;height:300px;object-fit:cover;border-radius:8px;"
        >
 
        <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
        <p><strong>Category:</strong> <?php echo $book['category_name']; ?></p>
        <p><strong>Description:</strong> <?php echo $book['description']; ?></p>
        <h3>Price: ৳ <?php echo $book['price']; ?></h3>
        <p><strong>Stock:</strong> <?php echo $book['stock']; ?></p>
 
        <?php if(isLoggedIn()): ?>
            <label>Quantity</label>
            <input type="number" id="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>">
 
            <button onclick="addToCart(<?php echo $book['id']; ?>)">
                Add to Cart
            </button>
 
            <p id="cartMessage"></p>
        <?php else: ?>
            <p>
                Please <a href="../auth/login.php">login</a> to add this book to cart.
            </p>
        <?php endif; ?>
    </div>
</div>
 
<script>
function addToCart(bookId) {
    let quantity = document.getElementById("quantity").value;
 
    if (quantity < 1) {
        alert("Quantity must be at least 1");
        return;
    }
 
    fetch("../../api/add_to_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "book_id=" + bookId + "&quantity=" + quantity
    })
    .then(response => response.json())
    .then(data => {
        let msg = document.getElementById("cartMessage");
 
        if (data.success) {
            msg.style.color = "green";
            msg.innerHTML = data.message + " | Cart Items: " + data.cart_count;
        } else {
            msg.style.color = "red";
            msg.innerHTML = data.message;
        }
    });
}
</script>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>
>>>>>>> feature1
