<?php
include_once 'includes/header.php';
?>

<main class="login-page">
    <div class="container">
        <div class="login-container">
            <h1>Login</h1>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Login</button>
            </form>
            <p><a href="register.php">Don't have an account? Register here</a></p>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
