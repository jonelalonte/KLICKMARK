<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Dito na susunod ang iba mong PHP code o include files

require_once("../config.php");
require_once("../firebaseRDB.php");

$error = "";
$success = isset($_SESSION["success"]) ? $_SESSION["success"] : "";
unset($_SESSION["success"]);

// Kung naka-login na ang user, i-redirect agad sa index.php
if (isset($_SESSION["user_email"])) {
    header("Location: ../index.php");
    exit();
}

// Proseso kapag na-submit na ang Login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {
        $error = "Mangyaring ilagay ang iyong email at password.";
    } else {
        $db = new firebaseRDB(FIREBASE_URL);
        $users = $db->retrieve("users");

        if ($users) {
            foreach ($users as $id => $user) {
                if (isset($user['email']) && $user['email'] === $email) {
                    if (isset($user['password']) && password_verify($password, $user['password'])) {
                        $_SESSION["user_id"] = $id;
                        $_SESSION["user_email"] = $user['email'];
                        $_SESSION["user_name"] = $user['name'] ?? "User";

                        header("Location: ../index.php");
                        exit();
                    }
                }
            }
        }

        $error = "Maling email o password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KLICK MO</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/login.css">

</head>
<body>

<!-- NAVBAR -->
<nav class="login-navbar">
    <div class="nav-content">

        <!-- IMAGE LOGO -->
        <a href="../index.php" class="brand">
            <img src="../assets/images/logo.png" alt="KLICK MO Logo" class="brand-logo-img">
        </a>

        <a href="../index.php" class="home-link">
            <i class="bi bi-house"></i>
            Home
        </a>

    </div>
</nav>

<!-- LOGIN SECTION -->
<main class="login-section">
    <div class="login-wrapper">

        <!-- LEFT BANNER -->
        <div class="login-banner">
            <div class="banner-content">

                <span class="banner-tag">
                    <i class="bi bi-stars"></i>
                    WELCOME BACK
                </span>

                <h1>
                    Great to see<br>
                    <span>you again.</span>
                </h1>

                <p>
                    Your favorite products are just a few clicks away.
                    Sign in to explore, shop, and enjoy everything KLICK MO has to offer.
                </p>

                <div class="banner-features">
                    <div>
                        <i class="bi bi-bag-check"></i>
                        <span>Discover Amazing Products</span>
                    </div>

                    <div>
                        <i class="bi bi-shield-check"></i>
                        <span>Secure Shopping Experience</span>
                    </div>

                    <div>
                        <i class="bi bi-heart"></i>
                        <span>Your Shopping Journey Starts Here</span>
                    </div>
                </div>

            </div>

            <div class="banner-decoration">
                <i class="bi bi-bag-heart-fill"></i>
            </div>
        </div>

        <!-- RIGHT LOGIN FORM -->
        <div class="login-card">

            <div class="login-header">
                <div class="login-icon">
                    <i class="bi bi-person-circle"></i>
                </div>

                <h2>Welcome Back!</h2>
                <p>Enter your account details to continue.</p>
            </div>

            <!-- SUCCESS MESSAGE -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ERROR MESSAGE -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- LOGIN FORM -->
            <form method="POST" action="">

                <!-- EMAIL -->
                <div class="form-field">
                    <label for="email">Email Address</label>

                    <div class="input-wrapper">
                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Enter your email address"
                            value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, 'UTF-8') ?>"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="form-field">
                    <label for="password">Password</label>

                    <div class="input-wrapper">
                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                            aria-label="Show password"
                        >
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- LOGIN BUTTON -->
                <button type="submit" class="login-button">
                    <span>Login to Account</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <!-- REGISTER LINK -->
            <div class="register-link">
                <span>Don't have an account?</span>
                <a href="register.php">Create Account</a>
            </div>

            <!-- BACK TO HOME -->
            <div class="back-home">
                <a href="../index.php">
                    <i class="bi bi-arrow-left"></i>
                    Back to Home
                </a>
            </div>

            <!-- SECURITY FOOTER -->
            <div class="login-footer">
                <i class="bi bi-shield-lock"></i>
                Secure login powered by KLICK MO.
            </div>

        </div>
    </div>

    <!-- COPYRIGHT -->
    <div class="copyright">
        &copy; <?= date("Y") ?> KLICK MO. All rights reserved.
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("passwordIcon");
    const button = document.querySelector(".password-toggle");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
        button.setAttribute("aria-label", "Hide password");
    } else {
        password.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
        button.setAttribute("aria-label", "Show password");
    }
}
</script>

</body>
</html>
