<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isset($_GET['category_id'])) {
    redirect('../../index.php');
}

$category_id = (int) $_GET['category_id'];

$categoryQuery = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$categoryQuery->bind_param("i", $category_id);
$categoryQuery->execute();

$category = $categoryQuery->get_result()->fetch_assoc();

$booksQuery = $conn->prepare("
    SELECT books.*, categories.name AS category_name
    FROM books
    JOIN categories ON books.category_id = categories.id
    WHERE category_id = ?
");

$booksQuery->bind_param("i", $category_id);
$booksQuery->execute();

$books = $booksQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $category['name']; ?> Books</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">

    <a href="../../index.php">🏠 Home</a>

    <?php if(isLoggedIn()): ?>

        <a href="profile.php">👤 Profile</a>

        <?php if(isAdmin()): ?>
            <a href="../admin/dashboard.php">⚙️ Admin Dashboard</a>
        <?php endif; ?>

        <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>

    <?php else: ?>

        <a href="../auth/login.php">🔑 Login</a>
        <a href="../auth/register.php">📝 Register</a>

    <?php endif; ?>

</div>

<div class="container">

    <h1><?php echo $category['name']; ?> Books</h1>

    <div class="book-grid">

        <?php while($book = $books->fetch_assoc()): ?>

            <div class="book-card">

                <img 
                    src="<?php echo $book['image_path'] ? '../../public/uploads/books/'.$book['image_path'] : 'https://via.placeholder.com/200x250'; ?>" 
                    alt="Book Image"
                >

                <h3><?php echo $book['title']; ?></h3>

                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>

                <p>
                    <?php echo substr($book['description'], 0, 80); ?>...
                </p>

                <h4>৳ <?php echo $book['price']; ?></h4>

                <p><strong>Stock:</strong> <?php echo $book['stock']; ?></p>

            </div>

        <?php endwhile; ?>

    </div>

</div>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>