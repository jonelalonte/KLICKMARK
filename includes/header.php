<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseUrl = "/KLICK%20MARK";
$isLoggedIn = isset($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KLICK MARK | Online Grocery</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">

    <!-- Navbar CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/navbar.css">

</head>

<body>

<!-- KLICK MARK NAVBAR -->
<nav class="navbar navbar-expand-lg klick-navbar">

    <div class="container-fluid px-lg-4 px-3">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center p-0"
           href="<?= $baseUrl ?>/index.php">

            <img
                src="<?= $baseUrl ?>/assets/images/logo.png"
                alt="KLICK MARK Logo"
                class="navbar-logo"
            >

        </a>

        <!-- MOBILE TOGGLER -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- NAVIGATION CONTENT -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- SEARCH -->
            <form
                class="navbar-search mx-lg-4 my-3 my-lg-0 flex-grow-1"
                action="<?= $baseUrl ?>/index.php"
                method="GET">

                <div class="input-group">

                    <input
                        type="search"
                        class="form-control"
                        name="search"
                        placeholder="Search groceries and products..."
                        value="<?= htmlspecialchars($_GET["search"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        aria-label="Search products"
                    >

                    <button class="btn search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                </div>
            </form>

            <!-- MENU -->
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                <!-- HOME -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>/index.php">
                        <i class="bi bi-house-door"></i>
                        Home
                    </a>
                </li>

                <!-- CATEGORIES -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>/index.php#categories">
                        <i class="bi bi-grid"></i>
                        Categories
                    </a>
                </li>

                <!-- CART -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>/cart.php">
                        <i class="bi bi-cart3"></i>
                        Cart
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>

            <!-- USER ACCOUNT -->
            <li class="nav-item dropdown">

                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <i class="bi bi-person-circle"></i>

                    <?= htmlspecialchars(
                        $_SESSION["name"] ?? $_SESSION["email"] ?? "My Account",
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <h6 class="dropdown-header">My Account</h6>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?= $baseUrl ?>/customer/profile.php">
                            <i class="bi bi-person me-2"></i> My Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="<?= $baseUrl ?>/cart.php">
                            <i class="bi bi-cart3 me-2"></i>
                            My Cart
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger"
                        href="<?= $baseUrl ?>/auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>
            </li>

        <?php else: ?>

                <!-- LOGIN -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl ?>/auth/login.php">
                        Login
                    </a>
                </li>

                <!-- REGISTER -->
                <li class="nav-item">
                    <a class="btn rounded-pill signup-btn"
                       href="<?= $baseUrl ?>/auth/register.php">
                        <i class="bi bi-person-plus me-1"></i>
                        Sign Up
                    </a>
                </li>

            <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>