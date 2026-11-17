<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../config.php");
require_once("../firebaseRDB.php");

$error = "";
$success = "";

if (isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Lahat ng field ay kinakailangang punan.";
    } elseif ($password !== $confirm_password) {
        $error = "Hindi magkatugma ang password at confirm password.";
    } elseif (strlen($password) < 8) {
        $error = "Ang password ay dapat hindi bumaba sa 8 characters.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check existing registered users
        $checkUrl = FIREBASE_URL . "/users.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $checkUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $users = json_decode($response, true);
        $emailExists = false;

        if ($users) {
            foreach ($users as $user) {
                if (isset($user['email']) && $user['email'] === $email) {
                    $emailExists = true;
                    break;
                }
            }
        }

        if ($emailExists) {
            $error = "Ang email na ito ay nakarehistro na.";
        } else {
            // Save new user to Firebase
            $userData = [
                "name" => $name,
                "email" => $email,
                "password" => $hashed_password,
                "created_at" => date("Y-m-d H:i:s")
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $checkUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $insertResponse = curl_exec($ch);
            curl_close($ch);

            if ($insertResponse) {
                $_SESSION["success"] = "Matagumpay na nakagawa ng account! Mangyaring mag-sign in.";
                header("Location: login.php");
                exit();
            } else {
                $error = "May naganap na error sa pagrehistro sa Firebase.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | KLICK MO</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #7652a8;
            --primary-dark: #51458f;
            --primary-light: #f1edfa;
            --accent: #4fc3df;

            --text: #29283d;
            --muted: #7e8095;
            --border: #e8e6f0;
            --background: #f7f7fc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =====================================
           NAVBAR
        ===================================== */

        .register-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
        }

        .register-navbar .container {
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

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            color: var(--muted);
        }

        .nav-right a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            transition: color .2s ease;
        }

        .nav-right a:hover {
            color: var(--primary-dark);
        }

        /* =====================================
           REGISTER SECTION
        ===================================== */

        .register-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .register-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            max-width: 1180px;
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(73, 66, 130, 0.12);
            border: 1px solid var(--border);
        }

        /* =====================================
           LEFT WELCOME PANEL
        ===================================== */

        .welcome-panel {
            position: relative;
            overflow: hidden;

            background:
                radial-gradient(
                    circle at 10% 35%,
                    rgba(255, 190, 210, 0.95) 0%,
                    transparent 38%
                ),
                radial-gradient(
                    circle at 85% 45%,
                    rgba(35, 195, 225, 0.95) 0%,
                    transparent 42%
                ),
                linear-gradient(
                    145deg,
                    #96539f 0%,
                    #c18cbd 25%,
                    #a9c9df 52%,
                    #498fc4 75%,
                    #414c9e 100%
                );

            color: #ffffff;
            padding: 50px 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .welcome-panel::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            right: -130px;
            top: -150px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50%;
            box-shadow:
                0 0 0 30px rgba(255,255,255,.04),
                0 0 0 60px rgba(255,255,255,.03);
            pointer-events: none;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.18);
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 24px;
        }

        .live-dot {
            width: 9px;
            height: 9px;
            background: #b8f4ff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(184,244,255,.8);
        }

        .welcome-content h1 {
            font-size: 38px;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .welcome-content h1 span {
            color: #f8e8ff;
        }

        .welcome-content p {
            font-size: 16px;
            line-height: 1.65;
            opacity: 0.94;
            margin-bottom: 32px;
        }

        .welcome-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 32px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .feature-icon {
            width: 46px;
            height: 46px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .feature strong {
            display: block;
            font-size: 15px;
        }

        .feature small {
            font-size: 13px;
            opacity: 0.88;
        }

        .panel-bottom {
            position: relative;
            z-index: 1;

            display: flex;
            align-items: center;
            justify-content: space-between;

            border-top: 1px solid rgba(255, 255, 255, 0.25);
            padding-top: 20px;

            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* =====================================
           RIGHT FORM PANEL
        ===================================== */

        .form-panel {
            padding: 50px 46px;
            background: #ffffff;
        }

        .form-heading {
            margin-bottom: 26px;
        }

        .form-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 14px;
        }

        .form-label {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: var(--primary);
            display: block;
            margin-bottom: 6px;
        }

        .form-heading h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-heading p {
            font-size: 15px;
            color: var(--muted);
            margin: 0;
        }

        /* =====================================
           INPUT FIELDS
        ===================================== */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #414157;
            margin-bottom: 8px;
        }

        .custom-input {
            display: flex;
            align-items: center;
            border: 1.8px solid #d7d7e4;
            border-radius: 12px;
            padding: 0 16px;
            background: #faf9fd;
            transition: .2s ease;
        }

        .custom-input:focus-within {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(118, 82, 168, 0.12);
        }

        .custom-input > i {
            color: #85869a;
            font-size: 18px;
            margin-right: 12px;
        }

        .custom-input input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 13px 0;
            font-size: 15px;
            width: 100%;
            color: var(--text);
        }

        .custom-input input::placeholder {
            color: #a3a3b3;
        }

        .password-toggle {
            border: none;
            background: transparent;
            color: #85869a;
            cursor: pointer;
            padding: 6px;
            font-size: 16px;
            transition: color .2s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .input-hint {
            display: block;
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        .input-hint i {
            color: var(--primary);
            margin-right: 4px;
        }

        /* =====================================
           REGISTER BUTTON
        ===================================== */

        .register-button {
            width: 100%;
            background: linear-gradient(135deg, #96539f, #514c9e);
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
            margin-top: 12px;
            transition: .2s ease;
            box-shadow: 0 6px 16px rgba(81, 76, 158, .17);
        }

        .register-button:hover {
            background: linear-gradient(135deg, #81458f, #41428e);
            box-shadow: 0 9px 20px rgba(81, 76, 158, .23);
            transform: translateY(-1px);
        }

        .register-button:active {
            transform: translateY(0);
        }

        /* =====================================
           LOGIN DIVIDER
        ===================================== */

        .login-divider {
            text-align: center;
            margin: 22px 0 16px;
            position: relative;
        }

        .login-divider span {
            background: #ffffff;
            padding: 0 12px;
            font-size: 11px;
            font-weight: 700;
            color: #9898aa;
            position: relative;
            z-index: 1;
        }

        .login-divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1.5px;
            background: var(--border);
        }

        /* =====================================
           LOGIN BUTTON
        ===================================== */

        .login-button {
            display: block;
            width: 100%;
            text-align: center;
            padding: 13px;
            border: 1.8px solid var(--primary);
            color: var(--primary);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .login-button:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* =====================================
           BACK HOME
        ===================================== */

        .back-home {
            text-align: center;
            margin-top: 18px;
        }

        .back-home a {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s ease;
        }

        .back-home a:hover {
            color: var(--primary);
        }

        /* =====================================
           FOOTER
        ===================================== */

        .register-footer {
            background: #ffffff;
            border-top: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--muted);
        }

        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 992px) {
            .register-wrapper {
                grid-template-columns: 1fr;
                max-width: 540px;
            }

            .welcome-panel {
                padding: 36px 28px;
            }

            .welcome-content h1 {
                font-size: 30px;
            }

            .form-panel {
                padding: 36px 28px;
            }

            .register-footer {
                flex-direction: column;
                text-align: center;
                gap: 6px;
            }
        }

        @media (max-width: 480px) {
            .register-navbar {
                padding: 13px 16px;
            }

            .brand-logo-img {
                height: 43px;
            }

            .nav-right {
                gap: 6px;
                font-size: 11px;
            }

            .welcome-panel {
                padding: 30px 22px;
            }

            .welcome-content h1 {
                font-size: 27px;
            }

            .welcome-content p {
                font-size: 14px;
            }

            .feature {
                gap: 12px;
            }

            .feature-icon {
                width: 40px;
                height: 40px;
            }

            .form-panel {
                padding: 30px 22px;
            }

            .form-heading h2 {
                font-size: 24px;
            }

            .form-heading p {
                font-size: 13px;
            }

            .register-footer {
                padding: 14px 16px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="register-navbar">
    <div class="container">

        <a href="/KLICK%20MARK/index.php" class="brand">
            <img src="../assets/images/logo.png" alt="KLICK MO Logo" class="brand-logo-img">
        </a>

        <div class="nav-right">
            <span>Already a member?</span>
            <a href="login.php">Sign In <i class="bi bi-arrow-up-right"></i></a>
        </div>

    </div>
</nav>

<!-- MAIN -->
<main class="register-section">
    <div class="register-wrapper">

        <!-- LEFT PANEL -->
        <section class="welcome-panel">
            <div class="welcome-content">

                <span class="eyebrow">
                    <span class="live-dot"></span>
                    YOUR EVERYDAY GROCERY PARTNER
                </span>

                <h1>
                    Fresh choices.<br>
                    <span>Better living.</span>
                </h1>

                <p>
                    Everything you need for everyday living, delivered with convenience and care.
                    Start your shopping journey with KLICK MO.
                </p>

                <div class="welcome-features">

                    <div class="feature">
                        <span class="feature-icon">
                            <i class="bi bi-bag-check"></i>
                        </span>
                        <div>
                            <strong>Easy Shopping</strong>
                            <small>Shop your essentials effortlessly.</small>
                        </div>
                    </div>

                    <div class="feature">
                        <span class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </span>
                        <div>
                            <strong>Secure Account</strong>
                            <small>Your account matters to us.</small>
                        </div>
                    </div>

                    <div class="feature">
                        <span class="feature-icon">
                            <i class="bi bi-heart"></i>
                        </span>
                        <div>
                            <strong>Made for Everyday</strong>
                            <small>A smarter way to shop for essentials.</small>
                        </div>
                    </div>

                </div>

                <div class="panel-bottom">
                    <span>SHOP SMARTER. LIVE BETTER.</span>
                    <i class="bi bi-arrow-down-right"></i>
                </div>

            </div>
        </section>

        <!-- RIGHT PANEL -->
        <section class="form-panel">
            <div class="form-inner">

                <div class="form-heading">
                    <div class="form-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>

                    <span class="form-label">GET STARTED TODAY</span>
                    <h2>Create your account</h2>
                    <p>Join KLICK MO and enjoy a better shopping experience.</p>
                </div>

                <!-- ERROR MESSAGE -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger custom-alert" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">

                    <!-- NAME -->
                    <div class="form-group">
                        <label for="name">Full Name</label>

                        <div class="custom-input">
                            <i class="bi bi-person"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Enter your full name"
                                value="<?= htmlspecialchars($_POST["name"] ?? "") ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">
                        <label for="email">Email Address</label>

                        <div class="custom-input">
                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="name@example.com"
                                value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">
                        <label for="password">Password</label>

                        <div class="custom-input">
                            <i class="bi bi-lock"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Create a password"
                                minlength="8"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password', this)"
                                aria-label="Show password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <small class="input-hint">
                            <i class="bi bi-info-circle"></i>
                            Use at least 8 characters.
                        </small>
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>

                        <div class="custom-input">
                            <i class="bi bi-shield-lock"></i>

                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Re-enter your password"
                                minlength="8"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('confirm_password', this)"
                                aria-label="Show password"
                            >
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="register-button">
                        <span>Create My Account</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>

                <div class="login-divider">
                    <span>ALREADY HAVE AN ACCOUNT?</span>
                </div>

                <a href="login.php" class="login-button">
                    Sign in to your account
                </a>

                <div class="back-home">
                    <a href="/KLICK%20MARK/index.php">
                        <i class="bi bi-arrow-left"></i>
                        Back to Home
                    </a>
                </div>

            </div>
        </section>

    </div>
</main>

<!-- FOOTER -->
<footer class="register-footer">
    <span>&copy; <?= date("Y") ?> KLICK MO. All rights reserved.</span>
    <span>FRESHNESS IN EVERY CLICK.</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(id, button) {
    const input = document.getElementById(id);
    const icon = button.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
        button.setAttribute("aria-label", "Hide password");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
        button.setAttribute("aria-label", "Show password");
    }
}
</script>

</body>
</html>
