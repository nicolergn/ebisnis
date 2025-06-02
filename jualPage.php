<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
if (!$isLoggedIn) {
    header("Location: index.php");
    exit();
}

$notification = ""; // Variabel untuk menyimpan notifikasi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session
    $title = $_POST['title'];
    $expected_price = $_POST['expected_price'];
    $product_condition = $_POST['product_condition'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Menyimpan data produk ke database
    $stmt = $conn->prepare("INSERT INTO products (user_id, title, product_condition, description, expected_price, location) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $product_condition, $description, $expected_price, $location);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; // Ambil ID produk yang baru saja disimpan

        // Proses upload gambar
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $target_dir = "uploads/"; // Folder untuk menyimpan gambar
            $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));

            // Format nama file: productid_userid_productphoto.jpg
            $new_file_name = $target_dir . $product_id . "_" . $user_id . "_productphoto." . $imageFileType;

            // Validasi file gambar
            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if ($check !== false) {
                if ($_FILES["product_image"]["size"] > 5000000) {
                    $notification = "<p class='notif-error'>Maaf, ukuran file terlalu besar.</p>";
                } else {
                    if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $new_file_name)) {
                            // Simpan URL gambar ke database
                            $stmt_photo = $conn->prepare("INSERT INTO product_photos (product_id, photo_url) VALUES (?, ?)");
                            $stmt_photo->bind_param("is", $product_id, $new_file_name);
                            $stmt_photo->execute();
                            $stmt_photo->close();

                            $notification = "<p class='notif-success'>Barang berhasil dijual dan gambar berhasil di-upload!</p>";
                        } else {
                            $notification = "<p class='notif-error'>Maaf, terjadi kesalahan saat meng-upload gambar.</p>";
                        }
                    } else {
                        $notification = "<p class='notif-error'>Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.</p>";
                    }
                }
            } else {
                $notification = "<p class='notif-error'>File yang di-upload bukan gambar.</p>";
            }
        } else {
            $notification = "<p class='notif-success'>Barang berhasil dijual, tetapi tidak ada gambar yang di-upload.</p>";
        }
    } else {
        $notification = "<p class='notif-error'>Terjadi kesalahan: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jual Barang - Sekening</title>
    <?php include "includes/styles.php"; ?>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/signModal.php"; ?>

    <main class="container">
        <section class="jual-barang">
            <h2>Jual Barang</h2>

            <!-- Tampilkan notifikasi jika ada -->
            <?php if (!empty($notification)): ?>
                <div class="notif-box"><?= $notification ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <p class="label">Nama Barang</p>
                <input type="text" name="title" placeholder="Nama Barang" required>

                <p class="label">Harga Barang</p>
                <input type="text" name="expected_price" placeholder="Harga Barang" required>

                <p class="label">Kondisi Barang</p>
                <select name="product_condition" id="kondisi-select" required>
                    <option value="new">Barang baru</option>
                    <option value="used">Digunakan</option>
                </select>

                <p class="label">Asal Pengiriman</p>
                <input type="text" name="location" placeholder="Asal Pengiriman" required>

                <p class="label">Keterangan Barang</p>
                <textarea name="description" placeholder="Deskripsikan barang Anda secara singkat..." required></textarea>

                <p class="label">Foto Barang</p>
                <div class="upload-area">
                    <div class="upload-box">
                        <span class="upload-icon"><i class="fa-solid fa-arrow-up-from-bracket"></i></span>
                        <p>Unggah Gambar</p>
                        <input type="file" name="product_image" accept="image/*" required>
                    </div>
                </div>

                <button type="submit">Jual Barang</button>
            </form>
        </section>
    </main>

    <?php include "includes/chatWidget.php"; ?>
    <?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>
</body>
</html>