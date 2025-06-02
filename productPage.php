<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if (!$product_id) {
    die("Produk tidak ditemukan.");
}

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$product = $result->fetch_assoc();
$stmt->close();

// Simpan product_id untuk dipakai di rekomendasi dan fitur Load More
$exclude_product_id = $product_id;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($product['title']) ?> - Sekening</title>
    <?php include "includes/styles.php"; ?>
</head>

<!-- Tambahkan `data-exclude-id` agar JavaScript bisa membaca `product_id` -->
<body data-exclude-id="<?= htmlspecialchars($exclude_product_id) ?>">

    <?php include "includes/header.php"; ?>
    <?php include "includes/signModal.php"; ?>

    <main class="container">
        <?php include "includes/productDetail.php"; ?>
        <?php include "includes/recommendation.php"; ?>
    </main>

    <?php include "includes/chatWidget.php"; ?>
    <?php include "includes/footer.php"; ?>
    
    <?php include "includes/scripts.php"; ?>

    <?php $conn->close(); ?>
</body>
</html>