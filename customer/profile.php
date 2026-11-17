<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Siguraduhing dito na nakalagay ang session checks o pag-redirect bago magkaroon ng kahit anong HTML output
// Siguraduhing naka-login ang user
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Default values mula sa PHP session
$user = [
    "name"    => $_SESSION["user_name"] ?? $_SESSION["name"] ?? "Valued Customer",
    "email"   => $_SESSION["user_email"] ?? $_SESSION["email"] ?? "",
    "phone"   => "",
    "address" => ""
];

// Kunin ang latest profile information mula Firebase
if (
    defined("FIREBASE_URL") &&
    !empty($_SESSION["id_token"]) &&
    function_exists("curl_init")
) {
    $firebaseUrl = rtrim(FIREBASE_URL, "/")
        . "/users/" . rawurlencode($userId)
        . ".json?auth=" . urlencode($_SESSION["id_token"]);

    $ch = curl_init($firebaseUrl);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
        $firebaseUser = json_decode($response, true);

        if (is_array($firebaseUser)) {
            if (!empty($firebaseUser["name"])) {
                $user["name"] = $firebaseUser["name"];
            }

            if (!empty($firebaseUser["email"])) {
                $user["email"] = $firebaseUser["email"];
            }

            $user["phone"] = $firebaseUser["phone"] ?? "";
            $user["address"] = $firebaseUser["address"] ?? "";
        }
    }
}

// Update session values
$_SESSION["name"] = $user["name"];
$_SESSION["email"] = $user["email"];

// Initials para sa profile avatar
$nameParts = preg_split('/\s+/', trim($user["name"]));
$initials = "";

foreach (array_slice($nameParts, 0, 2) as $part) {
    $initials .= strtoupper(substr($part, 0, 1));
}

if ($initials === "") {
    $initials = "C";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | KLICK MO</title>

    <link rel="stylesheet" href="../assets/css/profile.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<!-- TOP NAVIGATION -->
<header class="profile-navbar">
    <div class="navbar-inner">

        <a href="../index.php" class="brand">
            <img src="../assets/images/logo.png" alt="KLICK MO">
            <span>KLICK <strong>MO</strong></span>
        </a>

        <a href="../index.php" class="back-shop">
            <i class="bi bi-shop"></i>
            <span>Continue Shopping</span>
        </a>

    </div>
</header>

<main class="profile-container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb">
        <a href="../index.php">Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>My Profile</span>
    </div>

    <!-- PROFILE HEADER -->
    <section class="profile-banner">

        <div class="profile-identity">

            <div class="avatar">
                <?= htmlspecialchars($initials) ?>
                <span class="online-indicator"></span>
            </div>

            <div class="profile-info">
                <span class="welcome-label">WELCOME BACK</span>

                <h1>
                    <?= htmlspecialchars($user["name"]) ?>
                </h1>

                <p>
                    <i class="bi bi-envelope"></i>
                    <?= htmlspecialchars($user["email"]) ?>
                </p>
            </div>

        </div>

        <a href="edit-profile.php" class="edit-profile-btn">
            <i class="bi bi-pencil-square"></i>
            Edit Profile
        </a>

    </section>

    <!-- PURCHASE STATUS -->
    <section class="purchase-section">

        <div class="section-heading">

            <div>
                <span class="section-eyebrow">YOUR SHOPPING ACTIVITY</span>
                <h2>My Purchases</h2>
                <p>Track and manage your grocery orders.</p>
            </div>

            <a href="orders.php" class="view-all">
                View Order History
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

        <div class="purchase-grid">

            <a href="orders.php?status=to-pay" class="purchase-card">
                <div class="purchase-icon orange">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h3>To Pay</h3>
                    <p>Pending payment</p>
                </div>
                <i class="bi bi-chevron-right card-arrow"></i>
            </a>

            <a href="orders.php?status=to-ship" class="purchase-card">
                <div class="purchase-icon blue">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <h3>To Ship</h3>
                    <p>Preparing orders</p>
                </div>
                <i class="bi bi-chevron-right card-arrow"></i>
            </a>

            <a href="orders.php?status=to-receive" class="purchase-card">
                <div class="purchase-icon purple">
                    <i class="bi bi-truck"></i>
                </div>
                <div>
                    <h3>To Receive</h3>
                    <p>On the way</p>
                </div>
                <i class="bi bi-chevron-right card-arrow"></i>
            </a>

            <a href="orders.php?status=completed" class="purchase-card">
                <div class="purchase-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h3>Completed</h3>
                    <p>Delivered orders</p>
                </div>
                <i class="bi bi-chevron-right card-arrow"></i>
            </a>

        </div>
    </section>

    <!-- ACCOUNT CONTENT -->
    <div class="profile-content">

        <section class="account-card">

            <div class="section-heading compact">
                <div>
                    <span class="section-eyebrow">PERSONAL INFORMATION</span>
                    <h2>My Account</h2>
                </div>
            </div>

            <a href="edit-profile.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-person"></i>
                </div>
                <div class="menu-text">
                    <h3>My Profile</h3>
                    <p>Manage your personal information</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="addresses.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="menu-text">
                    <h3>My Addresses</h3>
                    <p>Manage your delivery addresses</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="cart.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-cart3"></i>
                </div>
                <div class="menu-text">
                    <h3>My Shopping Cart</h3>
                    <p>View your selected grocery items</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="orders.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="menu-text">
                    <h3>My Orders</h3>
                    <p>View all your previous purchases</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

        </section>

        <section class="account-card">

            <div class="section-heading compact">
                <div>
                    <span class="section-eyebrow">SUPPORT & INFORMATION</span>
                    <h2>Help & Settings</h2>
                </div>
            </div>

            <a href="../index.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="menu-text">
                    <h3>Continue Shopping</h3>
                    <p>Explore our grocery products</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="help.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <div class="menu-text">
                    <h3>Help Center</h3>
                    <p>Get assistance with your orders</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="settings.php" class="menu-item">
                <div class="menu-icon">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="menu-text">
                    <h3>Account Settings</h3>
                    <p>Manage your account preferences</p>
                </div>
                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

            <a href="../auth/logout.php" class="menu-item logout-item"
               onclick="return confirm('Are you sure you want to log out?');">

                <div class="menu-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </div>

                <div class="menu-text">
                    <h3>Logout</h3>
                    <p>Sign out of your KLICK MO account</p>
                </div>

                <i class="bi bi-chevron-right menu-arrow"></i>
            </a>

        </section>

    </div>

    <!-- FOOTER -->
    <footer class="profile-footer">
        <p>&copy; <?= date("Y") ?> KLICK MO. Fresh choices, better living.</p>
    </footer>

</main>

</body>
</html>
