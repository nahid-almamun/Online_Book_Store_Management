<?php require_once '../layouts/header.php'; ?>

<div class="card">
    <h2>Login</h2>

    <?php if(isset($_SESSION['success'])): ?>
        <p style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if(isset($_SESSION['errors'])): ?>
        <div style="color:red;">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../../controllers/loginController.php" onsubmit="return validateLoginForm()">
        <label>Email</label>
        <input 
            type="email" 
            name="email" 
            id="email"
            value="<?php echo isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : ''; ?>"
        >

        <label>Password</label>
        <input type="password" name="password" id="password">

        <label>
            <input type="checkbox" name="remember" style="width:auto;"> Remember Me
        </label>

        <br><br>

        <button type="submit">Login</button>
    </form>
</div>

<script>
function validateLoginForm() {
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();

    if (email === "") {
        alert("Email is required");
        return false;
    }

    if (password === "") {
        alert("Password is required");
        return false;
    }

    return true;
}
</script>

<?php require_once '../layouts/footer.php'; ?>