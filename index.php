
<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/firebaseRDB.php";

$db = new firebaseRDB(FIREBASE_URL);

$products = $db->retrieve("products");

if (!is_array($products)) {
    $products = [];
}

/* =========================================
   DEMO PRODUCTS
   Display only when Firebase is empty
========================================= */

if (empty($products)) {
    $products = [

        "demo_1" => [
            "name" => "Premium Jasmine Rice 5kg",
            "category" => "Rice & Grains",
            "price" => 285,
            "stock" => 25,
            "image" => "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500"
        ],

        "demo_2" => [
            "name" => "Fresh Whole Milk 1L",
            "category" => "Dairy & Eggs",
            "price" => 95,
            "stock" => 30,
            "image" => "https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500"
        ],

        "demo_3" => [
            "name" => "Fresh Farm Eggs 12pcs",
            "category" => "Dairy & Eggs",
            "price" => 115,
            "stock" => 40,
            "image" => "https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=500"
        ],

        "demo_4" => [
            "name" => "Gardenia Classic White Bread",
            "category" => "Bakery",
            "price" => 75,
            "stock" => 20,
            "image" => "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500"
        ],

        "demo_5" => [
            "name" => "Fresh Red Apples 1kg",
            "category" => "Fruits",
            "price" => 180,
            "stock" => 15,
            "image" => "https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500"
        ],

        "demo_6" => [
            "name" => "Fresh Bananas 1kg",
            "category" => "Fruits",
            "price" => 85,
            "stock" => 35,
            "image" => "https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=500"
        ],

        "demo_7" => [
            "name" => "Fresh Broccoli",
            "category" => "Vegetables",
            "price" => 90,
            "stock" => 18,
            "image" => "https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?w=500"
        ],

        "demo_8" => [
            "name" => "Fresh Tomatoes 1kg",
            "category" => "Vegetables",
            "price" => 95,
            "stock" => 25,
            "image" => "https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=500"
        ],

        "demo_9" => [
            "name" => "Instant Coffee 10 Sachets",
            "category" => "Beverages",
            "price" => 110,
            "stock" => 50,
            "image" => "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500"
        ],

        "demo_10" => [
            "name" => "Orange Juice 1L",
            "category" => "Beverages",
            "price" => 120,
            "stock" => 20,
            "image" => "https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500"
        ],

        "demo_11" => [
            "name" => "Cooking Oil 1L",
            "category" => "Cooking Essentials",
            "price" => 105,
            "stock" => 30,
            "image" => "https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500"
        ],

        "demo_12" => [
            "name" => "White Sugar 1kg",
            "category" => "Cooking Essentials",
            "price" => 85,
            "stock" => 40,
            "image" => "https://images.unsplash.com/photo-1581441363689-1f3c3c414635?w=500"
        ],

        "demo_13" => [
            "name" => "Dishwashing Liquid",
            "category" => "Household",
            "price" => 65,
            "stock" => 25,
            "image" => "https://images.unsplash.com/photo-1603905179139-7c9b4e5c5e9e?w=500"
        ],

        "demo_14" => [
            "name" => "Laundry Detergent",
            "category" => "Household",
            "price" => 125,
            "stock" => 30,
            "image" => "https://images.unsplash.com/photo-1585421514738-01798e348b17?w=500"
        ],

        "demo_15" => [
            "name" => "Potato Chips",
            "category" => "Snacks",
            "price" => 45,
            "stock" => 45,
            "image" => "https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=500"
        ],

        "demo_16" => [
            "name" => "Chocolate Cookies",
            "category" => "Snacks",
            "price" => 65,
            "stock" => 35,
            "image" => "https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=500"
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

include __DIR__ . "/includes/header.php";
?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-bg-shape shape-one"></div>
    <div class="hero-bg-shape shape-two"></div>
    <div class="hero-bg-shape shape-three"></div>

    <div class="container-fluid px-4">
        <div class="hero-content">

            <div class="hero-text">

                <span class="hero-label">
                    <i class="bi bi-basket2-fill"></i>
                    WELCOME TO KLICK MO
                </span>

                <h1>
                    Everything You Need,
                    <span>All in One Place.</span>
                </h1>

                <p>
                    Discover amazing grocery products and enjoy
                    shopping from the comfort of your home.
                    Fresh finds, great deals, and everyday
                    essentials await!
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
                        <i class="bi bi-basket2-fill"></i>
                    </div>

                    <div>
                        <strong>Shop Anytime</strong>
                        <small>Your daily essentials</small>
                    </div>
                </div>

                <div class="hero-floating-card card-two">
                    <div class="floating-icon yellow-icon">
                        <i class="bi bi-tags-fill"></i>
                    </div>

                    <div>
                        <strong>Great Deals</strong>
                        <small>Discover more products</small>
                    </div>
                </div>

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

<!-- CATEGORIES -->
<section class="container-fluid px-4 category-section">

    <div class="section-heading">
        <h3>Shop by Category</h3>
        <span>Explore our grocery collections</span>
    </div>

    <div class="row g-3">

        <?php
        $categories = [
            ["Rice & Grains", "bi-basket"],
            ["Fruits", "bi-apple"],
            ["Vegetables", "bi-flower1"],
            ["Dairy & Eggs", "bi-egg"],
            ["Beverages", "bi-cup-straw"],
            ["Snacks", "bi-cup-hot"],
            ["Bakery", "bi-bread-slice"],
            ["Household", "bi-house"],
            ["Cooking Essentials", "bi-box-seam"]
        ];

        foreach ($categories as $category):
        ?>

        <div class="col-6 col-md-4 col-lg-2">
            <a
                class="category-card"
                href="index.php?search=<?= urlencode($category[0]) ?>"
            >
                <i class="bi <?= htmlspecialchars($category[1]) ?>"></i>

                <span><?= htmlspecialchars($category[0]) ?></span>
            </a>
        </div>

        <?php endforeach; ?>

    </div>
</section>

<!-- PRODUCTS -->
<section class="container-fluid px-4 product-section" id="products">

    <div class="section-heading">

        <div>
            <h3>Featured Products</h3>
            <span>Fresh finds and everyday essentials</span>
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
                        Walang products na tumugma sa iyong search.
                    </p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
