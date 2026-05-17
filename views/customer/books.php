<?php
require_once '../../config/database.php';
require_once '../../config/helpers.php';

$books = $conn->query("
    SELECT books.*, categories.name AS category_name
    FROM books
    JOIN categories ON books.category_id = categories.id
    ORDER BY books.id DESC
");

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Books</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="logo">
        📚 <span>BookStore</span>
    </div>

    <div class="nav-links">

    </div>

    <a href="../../index.php">🏠 Home </a>
    <a href="books.php">📖 Books </a>
    <a href="cart.php">🛒 Cart  </a>
    <a href="order_history.php">📦 Purchase History</a>

    <?php if(isLoggedIn()): ?>
        <a href="profile.php">👤 Profile</a>
        <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>
    <?php else: ?>
        <a href="../auth/login.php">🔑 Login </a>
        <a href="../auth/register.php"> 📝 Register</a>
    <?php endif; ?>
</div>

<div class="container">
    <h1>Browse Books</h1>

    <div class="card">
        <input type="text" id="searchInput" placeholder="Search by title or author">

        <select id="categoryFilter">
            <option value="">All Categories</option>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>">
                    <?php echo $cat['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button onclick="searchBooks()">Search</button>
    </div>

    <div class="book-grid" id="bookList">
        <?php while($book = $books->fetch_assoc()): ?>
            <div class="book-card">
                <img src="<?php echo $book['image_path'] ? '../../public/uploads/books/'.$book['image_path'] : 'https://via.placeholder.com/200x250'; ?>">

                <h3><?php echo $book['title']; ?></h3>
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Category:</strong> <?php echo $book['category_name']; ?></p>
                <h4>৳ <?php echo $book['price']; ?></h4>

                <a href="book_details.php?id=<?php echo $book['id']; ?>">
                    <button>View Details</button>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function searchBooks() {
    let q = document.getElementById("searchInput").value;
    let category = document.getElementById("categoryFilter").value;

    fetch("../../api/search_books.php?q=" + encodeURIComponent(q) + "&category=" + encodeURIComponent(category))
        .then(response => response.json())
        .then(data => {
            let output = "";

            if (data.length === 0) {
                output = "<p>No books found.</p>";
            }

            data.forEach(book => {
                let image = book.image_path 
                    ? "../../public/uploads/books/" + book.image_path 
                    : "https://via.placeholder.com/200x250";

                output += `
                    <div class="book-card">
                        <img src="${image}">
                        <h3>${book.title}</h3>
                        <p><strong>Author:</strong> ${book.author}</p>
                        <p><strong>Category:</strong> ${book.category_name}</p>
                        <h4>৳ ${book.price}</h4>
                        <a href="book_details.php?id=${book.id}">
                            <button>View Details</button>
                        </a>
                    </div>
                `;
            });

            document.getElementById("bookList").innerHTML = output;
        });
}
</script>

    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>