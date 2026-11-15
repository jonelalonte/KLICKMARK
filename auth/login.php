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

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f9f6;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .login-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2ebe5;
            padding: 16px 28px;
        }

        .login-navbar .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }

        .home-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .home-link:hover {
            color: #198754;
        }

        .login-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            max-width: 1180px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(20, 108, 67, 0.15);
            border: 1px solid #e2ebe5;
        }

        /* LEFT BANNER */
        .login-banner {
            background: linear-gradient(145deg, #198754 0%, #115c37 100%);
            color: #ffffff;
            padding: 50px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .banner-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.18);
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 24px;
        }

        .banner-content h1 {
            font-size: 38px;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .banner-content h1 span {
            color: #bbf7d0;
        }

        .banner-content p {
            font-size: 16px;
            line-height: 1.65;
            opacity: 0.94;
            margin-bottom: 32px;
        }

        .banner-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .banner-features div {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 15px;
            font-weight: 600;
        }

        .banner-features i {
            font-size: 20px;
            background: rgba(255, 255, 255, 0.18);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .banner-decoration {
            position: absolute;
            bottom: -30px;
            right: -30px;
            font-size: 160px;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        /* RIGHT LOGIN CARD */
        .login-card {
            padding: 50px 46px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 26px;
        }

        .login-icon {
            width: 50px;
            height: 50px;
            background: #e6f4ea;
            color: #198754;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 14px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: #123d27;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            border: 1.8px solid #cbd5e1;
            border-radius: 12px;
            padding: 0 16px;
            background: #f8fafc;
            transition: 0.2s;
        }

        .input-wrapper:focus-within {
            border-color: #198754;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.12);
        }

        .input-wrapper > i {
            color: #64748b;
            font-size: 18px;
            margin-right: 12px;
        }

        .input-wrapper input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 13px 0;
            font-size: 15px;
            color: #1e293b;
            width: 100%;
        }

        .password-toggle {
            border: none;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            font-size: 16px;
        }

        .login-button {
            width: 100%;
            background: #198754;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }

        .login-button:hover {
            background: #146c43;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
        }

        .register-link a {
            color: #198754;
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 14px;
        }

        .back-home a {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-home a:hover {
            color: #198754;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }

        .copyright {
            text-align: center;
            padding: 16px 28px;
            font-size: 13px;
            color: #64748b;
            background: #ffffff;
            border-top: 1px solid #e2ebe5;
            margin-top: auto;
        }

        /* RESPONSIVE BREAKPOINT */
        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 540px;
            }

            .login-banner {
                padding: 36px 28px;
            }

            .banner-content h1 {
                font-size: 30px;
            }

            .login-card {
                padding: 36px 28px;
            }
        }
    </style>
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
