<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================================
   KLICK MO - ONLINE MARKETPLACE
   DEMO PRODUCTS
   Display only when Firebase is empty
========================================= */

if (empty($products)) {

    $products = [

        "demo_1" => [
            "name" => "Wireless Bluetooth Headphones",
            "category" => "Electronics",
            "price" => 599,
            "stock" => 35,
            "image" => "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500"
        ],

        "demo_2" => [
            "name" => "Smart Watch Series 9",
            "category" => "Gadgets",
            "price" => 1299,
            "stock" => 20,
            "image" => "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500"
        ],

        "demo_3" => [
            "name" => "Classic Men's Casual Sneakers",
            "category" => "Fashion",
            "price" => 899,
            "stock" => 28,
            "image" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500"
        ],

        "demo_4" => [
            "name" => "Women's Minimalist Handbag",
            "category" => "Fashion",
            "price" => 750,
            "stock" => 18,
            "image" => "https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500"
        ],

        "demo_5" => [
            "name" => "Premium Wireless Gaming Mouse",
            "category" => "Computer Accessories",
            "price" => 450,
            "stock" => 40,
            "image" => "https://images.unsplash.com/photo-1527814050087-3793815479db?w=500"
        ],

        "demo_6" => [
            "name" => "Mechanical Gaming Keyboard",
            "category" => "Computer Accessories",
            "price" => 1150,
            "stock" => 15,
            "image" => "https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500"
        ],

        "demo_7" => [
            "name" => "Portable Bluetooth Speaker",
            "category" => "Electronics",
            "price" => 699,
            "stock" => 25,
            "image" => "https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=500"
        ],

        "demo_8" => [
            "name" => "UV Protection Sunglasses",
            "category" => "Fashion Accessories",
            "price" => 299,
            "stock" => 50,
            "image" => "https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=500"
        ],

        "demo_9" => [
            "name" => "Skincare Facial Care Set",
            "category" => "Beauty & Personal Care",
            "price" => 550,
            "stock" => 22,
            "image" => "https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=500"
        ],

        "demo_10" => [
            "name" => "Premium Perfume for Men",
            "category" => "Beauty & Personal Care",
            "price" => 899,
            "stock" => 17,
            "image" => "https://images.unsplash.com/photo-1541643600914-78b084683601?w=500"
        ],

        "demo_11" => [
            "name" => "Modern Table Lamp",
            "category" => "Home & Living",
            "price" => 420,
            "stock" => 30,
            "image" => "https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500"
        ],

        "demo_12" => [
            "name" => "Minimalist Ceramic Coffee Mug",
            "category" => "Home & Living",
            "price" => 199,
            "stock" => 45,
            "image" => "https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=500"
        ],

        "demo_13" => [
            "name" => "Premium Travel Backpack",
            "category" => "Bags & Travel",
            "price" => 799,
            "stock" => 26,
            "image" => "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500"
        ],

        "demo_14" => [
            "name" => "Stainless Steel Water Bottle",
            "category" => "Sports & Outdoors",
            "price" => 250,
            "stock" => 38,
            "image" => "https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500"
        ],

        "demo_15" => [
            "name" => "Professional Basketball",
            "category" => "Sports & Outdoors",
            "price" => 650,
            "stock" => 12,
            "image" => "https://images.unsplash.com/photo-1519861531473-9200262188bf?w=500"
        ],

        "demo_16" => [
            "name" => "Premium Analog Wrist Watch",
            "category" => "Fashion Accessories",
            "price" => 950,
            "stock" => 19,
            "image" => "https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=500"
        ]
    ];
}

/* =========================================
   SEARCH PRODUCTS
========================================= */

$search = trim($_GET["search"] ?? "");

if ($search !== "") {

    $products = array_filter($products, function ($product) use ($search) {

        $name = $product["name"] ?? "";
        $category = $product["category"] ?? "";

        return stripos($name, $search) !== false ||
               stripos($category, $search) !== false;
    });
}

/* =========================================
   HEADER
========================================= */

include __DIR__ . "/includes/header.php";
?>

<!-- =====================================
     HERO SECTION
===================================== -->

<section class="hero-section">

    <div class="hero-bg-shape shape-one"></div>
    <div class="hero-bg-shape shape-two"></div>
    <div class="hero-bg-shape shape-three"></div>

    <div class="container-fluid px-4">

        <div class="hero-content">

            <div class="hero-text">

                <span class="hero-label">
                    <i class="bi bi-bag-heart-fill"></i>
                    WELCOME TO KLICK MO
                </span>

                <h1>
                    Discover More,
                    <span>Shop What You Love.</span>
                </h1>

                <p>
                    Explore amazing finds across fashion, gadgets,
                    electronics, beauty, and everyday essentials.
                    Discover great products and enjoy a better
                    online shopping experience.
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

            <!-- HERO IMAGE -->

            <div class="hero-image">

                <div class="hero-image-circle"></div>
                <div class="hero-image-ring"></div>

                <img
                    src="<?= htmlspecialchars($baseUrl) ?>/assets/images/mascot.png"
                    alt="KLICK MO Mascot"
                    class="hero-mascot-img"
                >

                <div class="hero-floating-card card-one">

                    <div class="floating-icon green-icon">
                        <i class="bi bi-bag-heart-fill"></i>
                    </div>

                    <div>
                        <strong>Shop Anytime</strong>
                        <small>Find your favorite items</small>
                    </div>

                </div>

                <div class="hero-floating-card card-two">

                    <div class="floating-icon yellow-icon">
                        <i class="bi bi-tags-fill"></i>
                    </div>

                    <div>
                        <strong>Great Deals</strong>
                        <small>Discover amazing finds</small>
                    </div>

                </div>

                <div class="hero-decoration decoration-one">
                    <i class="bi bi-bag-heart-fill"></i>
                </div>

                <div class="hero-decoration decoration-two">
                    <i class="bi bi-stars"></i>
                </div>

                <div class="hero-decoration decoration-three">
                    <i class="bi bi-gift-fill"></i>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =====================================
     CATEGORIES SECTION
===================================== -->

<section class="container-fluid px-4 category-section">

    <div class="section-heading">

        <h3>Shop by Category</h3>

        <span>
            Explore products from different collections
        </span>

    </div>

    <div class="row g-3">

        <?php

        $categories = [

            ["Electronics", "bi-laptop"],

            ["Fashion", "bi-handbag"],

            ["Beauty & Personal Care", "bi-stars"],

            ["Home & Living", "bi-house-heart"],

            ["Gadgets", "bi-smartwatch"],

            ["Computer Accessories", "bi-mouse"],

            ["Fashion Accessories", "bi-eyeglasses"],

            ["Bags & Travel", "bi-backpack"],

            ["Sports & Outdoors", "bi-bicycle"]

        ];

        foreach ($categories as $category):

        ?>

        <div class="col-6 col-md-4 col-lg-2">

            <a
                class="category-card"
                href="index.php?search=<?= urlencode($category[0]) ?>"
            >

                <i class="bi <?= htmlspecialchars($category[1]) ?>"></i>

                <span>
                    <?= htmlspecialchars($category[0]) ?>
                </span>

            </a>

        </div>

        <?php endforeach; ?>

    </div>

</section>


<!-- =====================================
     FEATURED PRODUCTS
===================================== -->

<section
    class="container-fluid px-4 product-section"
    id="products"
>

    <div class="section-heading">

        <div>

            <h3>Featured Products</h3>

            <span>
                Discover popular finds and amazing deals
            </span>

        </div>

        <span class="product-count">

            <?= count($products) ?> Products

        </span>

    </div>


    <div class="row g-3">

        <?php if (!empty($products)): ?>

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

                    <!-- PRODUCT IMAGE -->

                    <div class="product-image">

                        <img
                            src="<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($name) ?>"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://placehold.co/500x500/f5f5f5/999999?text=No+Image';"
                        >

                        <span class="product-category">

                            <?= htmlspecialchars($category) ?>

                        </span>

                    </div>


                    <!-- PRODUCT INFORMATION -->

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

                                <i class="bi bi-star-fill"></i>
                                New

                            </small>

                        </div>


                        <button
                            class="add-cart-btn"
                            type="button"
                            disabled
                        >

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
                        Walang products na tumugma sa iyong search.
                        Try searching for another product or category.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>

</section>


<!-- =====================================
     FOOTER
===================================== -->

<?php include __DIR__ . "/includes/footer.php"; ?>
