<?php

require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../../index.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_books.php');
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$book = $stmt->get_result()->fetch_assoc();

$categories = $conn->query("SELECT * FROM categories");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
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

        <h2>Edit Book</h2>

        <form
            method="POST"
            action="../../controllers/updateBookController.php"
            enctype="multipart/form-data"
        >

            <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

            <label>Title</label>
            <input type="text" name="title" value="<?php echo $book['title']; ?>">

            <label>Author</label>
            <input type="text" name="author" value="<?php echo $book['author']; ?>">

            <label>Description</label>
            <textarea name="description"><?php echo $book['description']; ?></textarea>

            <label>Price</label>
            <input type="number" step="0.01" name="price" value="<?php echo $book['price']; ?>">

            <label>Stock</label>
            <input type="number" name="stock" value="<?php echo $book['stock']; ?>">

            <label>Category</label>

            <select name="category_id">

                <?php while($category = $categories->fetch_assoc()): ?>

                    <option
                        value="<?php echo $category['id']; ?>"
                        <?php if($category['id'] == $book['category_id']) echo 'selected'; ?>
                    >
                        <?php echo $category['name']; ?>
                    </option>

                <?php endwhile; ?>

            </select>

            <label>New Image</label>
            <input type="file" name="image">

            <button type="submit">Update Book</button>

        </form>

    </div>

</div>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>