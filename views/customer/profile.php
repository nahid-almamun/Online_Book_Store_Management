<?php

require_once '../../config/database.php';
require_once '../../config/helpers.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="navbar">

    <div class="logo">
        📚 <span>BookStore</span>
    </div>

    <a href="../../index.php">🏠 Home</a>

    <?php if(isAdmin()): ?>
        <a href="../admin/dashboard.php">⚙️ Admin Dashboard</a>
    <?php endif; ?>

    <a href="/online_book_store/controllers/logoutController.php">🔓 Logout</a>

</div>

<div class="container">

    <div class="card">

        <h2>My Profile</h2>

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

        <?php if($user['profile_picture']): ?>

            <img
                src="../../public/uploads/profiles/<?php echo $user['profile_picture']; ?>"
                width="120"
                height="120"
                style="border-radius:50%; object-fit:cover;"
            >

        <?php endif; ?>

        <form
            method="POST"
            action="../../controllers/profileController.php"
            enctype="multipart/form-data"
        >

            <label>Name</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>">

            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>">

            <label>Address</label>
            <textarea name="address"><?php echo $user['address']; ?></textarea>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>">

            <label>Profile Picture</label>
            <input type="file" name="profile_picture">

            <button type="submit">Update Profile</button>

        </form>

    </div>

</div>
    <?php require_once '../layouts/footer.php'; ?>
</body>
</html>