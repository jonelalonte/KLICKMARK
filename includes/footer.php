
<?php
// Dynamic Base URL for Localhost and Render
if (!isset($baseUrl)) {
    $baseUrl = (
        isset($_SERVER['HTTP_HOST']) &&
        strpos($_SERVER['HTTP_HOST'], 'localhost') !== false
    ) ? "/KLICK%20MARK" : "";
}
?>

<footer class="shop-footer">
    <div class="container text-center">

        <h5>KLICK MO</h5>

        <p>Shop anytime. Shop anywhere.</p>

        <small>
            &copy; <?= date("Y") ?> KLICK MO. All rights reserved.
        </small>

    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main JavaScript -->
<script src="<?= $baseUrl ?>/assets/js/script.js"></script>

</body>
</html>
