<?php require_once '../layouts/header.php'; ?>

<div class="card">
    <h2>Create Account</h2>

    <?php if(isset($_SESSION['errors'])): ?>
        <div style="color:red;">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../../controllers/registerController.php" onsubmit="return validateRegisterForm()">
        <label>Name</label>
        <input type="text" name="name" id="name">

        <label>Email</label>
        <input type="email" name="email" id="email">

        <label>Password</label>
        <input type="password" name="password" id="password">

        <label>Role</label>
        <select name="role" id="role">
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
        </select>

        <label>Address</label>
        <textarea name="address" id="address"></textarea>

        <label>Phone</label>
        <input type="text" name="phone" id="phone">

        <button type="submit">Register</button>
    </form>
</div>

<script>
function validateRegisterForm() {
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();

    if (name === "") {
        alert("Name is required");
        return false;
    }

    if (email === "") {
        alert("Email is required");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }

    return true;
}
</script>

<?php require_once '../layouts/footer.php'; ?>