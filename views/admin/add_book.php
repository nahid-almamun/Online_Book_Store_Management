<?php

require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../../index.php');
}

$categories = $conn->query("SELECT * FROM categories");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">

    <a href="../../index.php">🏠 Home</a>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>

</div>

<div class="container">

    <div class="card">

        <h2>Add New Book</h2>

        <?php if(isset($_SESSION['success'])): ?>

            <p style="color:green;">
                <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </p>

        <?php endif; ?>

        <?php if(isset($_SESSION['errors'])): ?>

            <div style="color:red;">

                <?php foreach($_SESSION['errors'] as $error): ?>

                    <p><?php echo $error; ?></p>

                <?php endforeach; ?>

            </div>

            <?php unset($_SESSION['errors']); ?>

        <?php endif; ?>

        <form
            method="POST"
            action="../../controllers/bookController.php"
            enctype="multipart/form-data"
        >

            <label>Book Title</label>
            <input type="text" name="title">

            <label>Author</label>
            <input type="text" name="author">

            <label>Description</label>
            <textarea name="description"></textarea>

            <label>Price</label>
            <input type="number" step="0.01" name="price">

            <label>Stock</label>
            <input type="number" name="stock">

            <label>Category</label>

            <select name="category_id">

                <?php while($category = $categories->fetch_assoc()): ?>

                    <option value="<?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
                    </option>

                <?php endwhile; ?>

            </select>

            <label>Book Image</label>
            <input type="file" name="image">

            <button type="submit">Add Book</button>

        </form>

    </div>

</div>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>