<?php
include_once 'includes/header.php';
?>

<main class="register-page">
    <div class="container">
        <div class="register-container">
            <h1>Register</h1>
            <form id="registerForm">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="btn-primary">Register</button>
            </form>
            <p><a href="login.php">Already have an account? Login here</a></p>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
