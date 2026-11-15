<?php

require_once("config.php");
require_once("firebaseRDB.php");

$db = new firebaseRDB(FIREBASE_URL);

$products = $db->retrieve("products");

if (!is_array($products)) {
    $products = [];
}

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $products = array_filter($products, function($product) use ($search) {
        $name = $product["name"] ?? "";
        $category = $product["category"] ?? "";

        return stripos($name, $search) !== false ||
               stripos($category, $search) !== false;
    });
}

include("includes/header.php");

?>


<!-- =========================================
     KLICK MARK - ONLINE GROCERY HERO
========================================= -->

<section class="hero-section">
    <div class="hero-bg-shape shape-one"></div>
    <div class="hero-bg-shape shape-two"></div>
    <div class="hero-bg-shape shape-three"></div>

    <div class="container-fluid px-4">
        <div class="hero-content">

            <!-- LEFT CONTENT -->
            <div class="hero-text">

                <span class="hero-label">
                    <i class="bi bi-basket2-fill"></i>
                    WELCOME TO KLICK MARK
                </span>

                <h1>
                    Everything You Need,
                    <span>All in One Place.</span>
                </h1>

                <p>
                    Discover amazing products and enjoy shopping
                    from the comfort of your home. Fresh finds,
                    great deals, and everyday essentials await!
                </p>

                <div class="hero-buttons">
                    <a href="#products" class="btn hero-btn">
                        Shop Now
                        <i class="bi bi-arrow-right"></i>
                    </a>

                    <span class="hero-note">
                        <i class="bi bi-shield-check"></i>
                        Easy and convenient shopping
                    </span>
                </div>

                <div class="hero-features">
                    <div>
                        <i class="bi bi-truck"></i>
                        <span>Easy Shopping</span>
                    </div>

                    <div>
                        <i class="bi bi-bag-check"></i>
                        <span>Quality Products</span>
                    </div>

                    <div>
                        <i class="bi bi-tags"></i>
                        <span>Great Deals</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="hero-image">

                <div class="hero-image-circle"></div>

                <div class="hero-image-ring"></div>

                <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="KLICK MARK">

                <!-- FLOATING CARD 1 -->
                <div class="hero-floating-card card-one">
                    <div class="floating-icon green-icon">
                        <i class="bi bi-basket2-fill"></i>
                    </div>
                    <div>
                        <strong>Shop Anytime</strong>
                        <small>Your daily essentials</small>
                    </div>
                </div>

                <!-- FLOATING CARD 2 -->
                <div class="hero-floating-card card-two">
                    <div class="floating-icon yellow-icon">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <div>
                        <strong>Great Deals</strong>
                        <small>Discover more products</small>
                    </div>
                </div>

                <!-- DECORATIVE ICONS -->
                <div class="hero-decoration decoration-one">
                    <i class="bi bi-bag-heart-fill"></i>
                </div>

                <div class="hero-decoration decoration-two">
                    <i class="bi bi-flower1"></i>
                </div>

                <div class="hero-decoration decoration-three">
                    <i class="bi bi-stars"></i>
                </div>

            </div>

        </div>
    </div>
</section>


<!-- CATEGORIES (Full-width din para sagad sa gilid) -->
<section class="container-fluid px-4 category-section">

    <div class="section-heading">
        <h3>Categories</h3>
        <span>Explore our collections</span>
    </div>

    <div class="row g-3">

        <?php
        $categories = [
            ["Electronics", "bi-phone"],
            ["Fashion", "bi-handbag"],
            ["Home & Living", "bi-house"],
            ["Beauty", "bi-stars"],
            ["Sports", "bi-dribbble"],
            ["Accessories", "bi-watch"]
        ];

        foreach ($categories as $category):
        ?>

        <div class="col-6 col-md-4 col-lg-2">
            <a class="category-card"
               href="index.php?search=<?= urlencode($category[0]) ?>">

                <i class="bi <?= htmlspecialchars($category[1]) ?>"></i>

                <span>
                    <?= htmlspecialchars($category[0]) ?>
                </span>

            </a>
        </div>

        <?php endforeach; ?>

    </div>
</section>

<!-- PRODUCTS (Full-width din para malawak ang lagayan ng items) -->
<section class="container-fluid px-4 product-section" id="products">

    <div class="section-heading">
        <div>
            <h3>Featured Products</h3>
            <span>Find something you will love</span>
        </div>

        <span class="product-count">
            <?= count($products) ?> Products
        </span>
    </div>

    <div class="row g-3">

        <?php if (count($products) > 0): ?>

            <?php foreach ($products as $id => $product):

                $name = $product["name"] ?? "Unnamed Product";
                $price = (float)($product["price"] ?? 0);
                $image = $product["image"] ?? "";
                $category = $product["category"] ?? "General";
                $stock = (int)($product["stock"] ?? 0);

                if ($image === "") {
                    $image = "https://placehold.co/500x500/f5f5f5/999999?text=No+Image";
                }

            ?>

            <div class="col-6 col-md-4 col-lg-3 col-xl-2">

                <div class="product-card">

                    <div class="product-image">
                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($name) ?>"
                            loading="lazy"
                            onerror="this.src='https://placehold.co/500x500/f5f5f5/999999?text=No+Image'">

                        <span class="product-category">
                            <?= htmlspecialchars($category) ?>
                        </span>
                    </div>

                    <div class="product-info">

                        <h6>
                            <?= htmlspecialchars($name) ?>
                        </h6>

                        <div class="product-price">
                            ₱<?= number_format($price, 2) ?>
                        </div>

                        <div class="product-bottom">
                            <small>
                                Stock: <?= $stock ?>
                            </small>

                            <small>
                                <i class="bi bi-star-fill"></i> New
                            </small>
                        </div>

                        <button class="add-cart-btn" type="button" disabled>
                            <i class="bi bi-cart-plus"></i>
                            Add to Cart
                        </button>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="empty-products">
                    <i class="bi bi-bag-x"></i>
                    <h5>No products found</h5>
                    <p>
                        Wala pang products sa database o walang
                        tumugmang search.
                    </p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php include("includes/footer.php"); ?>