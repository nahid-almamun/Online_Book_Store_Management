<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? (int) $_GET['category'] : 0;

if ($category > 0) {
    $sql = "
        SELECT books.*, categories.name AS category_name
        FROM books
        JOIN categories ON books.category_id = categories.id
        WHERE (books.title LIKE ? OR books.author LIKE ?)
        AND books.category_id = ?
        ORDER BY books.id DESC
    ";

    $search = "%$q%";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $search, $search, $category);
} else {
    $sql = "
        SELECT books.*, categories.name AS category_name
        FROM books
        JOIN categories ON books.category_id = categories.id
        WHERE books.title LIKE ? OR books.author LIKE ?
        ORDER BY books.id DESC
    ";

    $search = "%$q%";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
}

$stmt->execute();

$result = $stmt->get_result();

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>