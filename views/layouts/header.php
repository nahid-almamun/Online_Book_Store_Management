<?php require_once '../../config/helpers.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Book Store</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">

    <!-- LOGO LEFT -->

    <div class="logo">
        <a href="../../index.php">
            📚 <span>BookStore</span>
        </a>
    </div>

    <!-- CENTER LINKS -->

    <div class="nav-links">

        <a href="../../index.php">
            🏠 Home
        </a>

        <a href="../customer/books.php">
            📖 Books
        </a>

        <?php if(isLoggedIn()): ?>

            <a href="../customer/profile.php">
                👤 Profile
            </a>

            <?php if(isAdmin()): ?>

                <a href="../admin/dashboard.php">
                    ⚙️ Admin Dashboard
                </a>

            <?php endif; ?>

            <a href="../../controllers/logoutController.php"
                class="logout-btn">
                🔓 Logout
            </a>

            <?php else: ?>
                <a href="../auth/login.php">🔑 Login </a>
                <a href="../auth/register.php">📝 Register</a>

        <?php endif; ?>

    </div>

</div>

<div class="container">