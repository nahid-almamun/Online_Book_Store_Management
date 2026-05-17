<?php
require_once 'config/helpers.php';
require_once 'config/database.php';

$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Book Store</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="navbar">

    <div class="logo">
        📚 <span>BookStore</span>
    </div>

    <div class="nav-links">
        <a href="index.php">🏠 Home</a>
        <a href="views/customer/books.php">📖 Books</a>
    

        <?php if(isLoggedIn()): ?>

        <a href="views/customer/profile.php">👤 Profile</a>

        <?php if(isAdmin()): ?>
            <a href="views/admin/dashboard.php">⚙️ Admin Dashboard</a>
        <?php endif; ?>

        <a href="controllers/logoutController.php">🔓 Logout</a>

        <?php else: ?>

        <a href="views/auth/login.php">🔑 Login </a>
        <a href="views/auth/register.php">📝 Register</a>

        <?php endif; ?>
    </div>

</div>

<div class="container">

    <div class="card">
        <h1>Welcome to Online Book Store</h1>
        <p>Select Book Categories</p>

        <div class="book-grid">

            <?php while($category = $result->fetch_assoc()): ?>

                <div class="book-card">

                    <h2><?php echo $category['name']; ?></h2>

                    <p>
                        Explore books from <?php echo $category['name']; ?> category.
                    </p>

                    <a href="views/customer/category_books.php?category_id=<?php echo $category['id']; ?>">
                        <button>Browse Books</button>
                    </a>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</div>
    <?php require_once 'views/layouts/footer.php'; ?>
</body>
</html>