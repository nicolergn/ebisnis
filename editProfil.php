<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$user_data = null;
$notification = ""; // Variabel untuk menyimpan notifikasi

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT first_name, last_name, student_id, location, email, profile_photo, bio, phone_number 
        FROM users WHERE user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $student_id = $_POST['student_id'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $phone_number = $_POST['phone_number']; // Ambil nomor telepon dari formulir

    // Proses upload foto profil
    $photo_url = $user_data['profile_photo']; 
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_photo"]["name"], PATHINFO_EXTENSION));
        $new_file_name = $target_dir . $user_id . "_profilephoto." . $imageFileType;

        // Validasi file gambar
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["profile_photo"]["size"] > 5000000) {
                $notification = "<p class='notif-error'>Maaf, ukuran file terlalu besar.</p>";
            } else {
                if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($photo_url !== 'uploads/placeholders/profile.jpg' && file_exists($photo_url)) {
                        unlink($photo_url); 
                    }
                    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $new_file_name)) {
                        $photo_url = $new_file_name;
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

    // Update database
    $stmt = $conn->prepare("
        UPDATE users SET first_name = ?, last_name = ?, student_id = ?, location = ?, email = ?, bio = ?, phone_number = ?, profile_photo = ?
        WHERE user_id = ?
    ");
    $stmt->bind_param("ssssssssi", $first_name, $last_name, $student_id, $location, $email, $bio, $phone_number, $photo_url, $user_id);

    if ($stmt->execute()) {
        $notification = "<p class='notif-success'>Profil berhasil diperbarui!</p>";
    } else {
        $notification = "<p class='notif-error'>Terjadi kesalahan: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sekening - Toko Barang Bekas Mahasiswa</title>
    <?php include "includes/styles.php"; ?>
</head>

<body>
    <!-- Load Components -->
    <?php include "includes/header.php"; ?>
    <?php include "includes/signModal.php"; ?>

    <main class="container">
        <section class="edit-sec">
            <section class="sidebar">
                <ul>
                    <li><a href="editProfil.php" class="active">Edit Profil</a></li>
                    <li><a href="editpassPage.php">Ubah Kata Sandi</a></li>
                </ul>
            </section>

            <section class="edit-profil">
                <h2>Edit Profil</h2>

                <!-- Tampilkan notifikasi jika ada -->
                <?php if (isset($notification) && !empty($notification)): ?>
                    <div class="notif-box">
                        <?= $notification ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <h3>Foto Profil</h3>
                    <div class="foto-profil">
                        <img
                            src="/sekening_02/<?= htmlspecialchars($user_data['profile_photo'] ?: 'uploads/placeholders/profile.jpg') ?>"
                            alt="Foto Profil <?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?>"
                        />
                        <div class="up-desc">
                            <p>Foto wajah depan yang jelas merupakan cara penting bagi pembeli dan penjual untuk saling mengenal.</p>
                            <input type="file" name="profile_photo" accept="image/*">
                        </div>
                    </div>

                    <div class="profil-publik">
                        <h3>Profile Publik</h3>

                        <p class="label-profil">Nama Depan</p>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($user_data['first_name']) ?>" required>

                        <p class="label-profil">Nama Belakang</p>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($user_data['last_name']) ?>" required>

                        <p class="label-profil">NIM</p>
                        <input type="text" name="student_id" value="<?= htmlspecialchars($user_data['student_id']) ?>" required>

                        <p class="label-profil">Asal</p>
                        <input type="text" name="location" value="<?= htmlspecialchars($user_data['location']) ?>" required>

                        <p class="label-profil">Email</p>
                        <input type="email" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" required>

                        <p class="label-profil">Nomor Telepon(nomor WhatsApp yang aktif dan menggunakan kode negara)</p>
                        <input type="text" name="phone_number" value="<?= htmlspecialchars($user_data['phone_number']) ?>" required>

                        <p class="label-profil">Bio</p>
                        <textarea name="bio" required><?= htmlspecialchars($user_data['bio']) ?></textarea>

                        <button type="submit">Perbarui Profil</button>
                    </div>
                </form>
            </section>
        </section>
    </main>

    <?php include "includes/chatWidget.php"; ?>
    <?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>
</body>
</html>
