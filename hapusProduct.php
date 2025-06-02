<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if (!$isLoggedIn || !$product_id) {
    header("Location: userPage.php?user_id=" . $_SESSION['user_id']);
    exit();
}

// Pastikan hanya pemilik produk yang bisa menghapus
$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

header("Location: userPage.php?user_id=" . $_SESSION['user_id']);
exit();
?>