<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if (!$isLoggedIn || !$product_id) {
    header("Location: userPage.php?user_id=" . $_SESSION['user_id']); // Redirect jika tidak login atau tidak ada ID produk
    exit();
}

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Produk tidak ditemukan.");
}

$notification = ""; // Variabel untuk menyimpan notifikasi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expected_price = $_POST['expected_price'];
    $status = $_POST['status'];

    // Proses upload foto produk
    $product_photo = $product['photo_url']; // Foto lama
    if (isset($_FILES['product_photo']) && $_FILES['product_photo']['error'] == 0) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["product_photo"]["name"], PATHINFO_EXTENSION));

        // Format nama file: productid_userid_productphoto.jpg
        $new_file_name = $target_dir . $product_id . "_" . $_SESSION['user_id'] . "_productphoto." . $imageFileType;

        // Validasi file gambar
        $check = getimagesize($_FILES["product_photo"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["product_photo"]["size"] > 5000000) {
                $notification = "<p class='notif-error'>Maaf, ukuran file terlalu besar.</p>";
            } else {
                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    // Hapus foto lama jika ada
                    if ($product_photo !== 'uploads/pic_placeholder_sellitem.jpg' && file_exists($product_photo)) {
                        unlink($product_photo);
                    }
                    if (move_uploaded_file($_FILES["product_photo"]["tmp_name"], $new_file_name)) {
                        $product_photo = $new_file_name;
                    } else {
                        $notification = "<p class='notif-error'>Maaf, terjadi kesalahan saat meng-upload gambar.</p>";
                    }
                } else {
                    $notification = "<p class='notif-error'>Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.</p>";
                }
            }
        } else {
            $notification = "<p class='notif-error'>File yang di-upload bukan gambar.</p>";
        }
    }

    // Update produk di database dengan foto baru
    $stmt_update = $conn->prepare("UPDATE products SET title = ?, description = ?, expected_price = ?, status = ?, photo_url = ? WHERE product_id = ?");
    $stmt_update->bind_param("ssdssi", $title, $description, $expected_price, $status, $product_photo, $product_id);

    if ($stmt_update->execute()) {
        header("Location: userPage.php?user_id=" . $_SESSION['user_id']); // Redirect setelah update
        exit();
    } else {
        $notification = "<p class='notif-error'>Terjadi kesalahan saat memperbarui produk.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk</title>
    <?php include "includes/styles.php"; ?>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/signModal.php"; ?>

    <main class="container">
        <h2>Edit Produk</h2>

        <!-- Tampilkan notifikasi jika ada -->
        <?php if (!empty($notification)): ?>
            <div class="notif-box"><?= $notification ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <p>Judul</p>
            <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required>

            <p>Deskripsi</p>
            <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

            <p>Harga yang Diharapkan</p>
            <input type="number" name="expected_price" value="<?= htmlspecialchars($product['expected_price']) ?>" required>

            <p>Status</p>
            <select name="status">
                <option value="available" <?= $product['status'] == 'available' ? 'selected' : '' ?>>Tersedia</option>
                <option value="sold" <?= $product['status'] == 'sold' ? 'selected' : '' ?>>Terjual</option>
            </select>

            <p>Foto Produk</p>
            <div class="upload-container">
                <img src="/sekening_02/<?= htmlspecialchars($product['photo_url']) ?>" alt="Foto Produk" class="product-image-preview" />
                <input type="file" name="product_photo" accept="image/*">
            </div>

            <button type="submit">Perbarui Produk</button>
        </form>
    </main>

    <?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>
</body>
</html>