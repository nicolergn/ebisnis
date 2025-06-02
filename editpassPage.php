<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'];

if (!$isLoggedIn) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Ambil password hash dari database
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password_lama, $user['password_hash'])) {
        // Cek apakah kata sandi baru dan konfirmasi sama
        if ($password_baru === $konfirmasi_password) {
            // Hash kata sandi baru
            $new_password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

            // Update password di database
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_password_hash, $user_id);

            if ($stmt->execute()) {
                echo "<p>Kata sandi berhasil diperbarui!</p>";
            } else {
                echo "<p>Terjadi kesalahan saat memperbarui kata sandi.</p>";
            }
        } else {
            echo "<p>Kata sandi baru dan konfirmasi tidak cocok.</p>";
        }
    } else {
        echo "<p>Kata sandi lama salah.</p>";
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
            <li><a href="editProfil.php">Edit Profil</a></li>
            <li><a href="editpassPage.php" class="active">Ubah Kata Sandi</a></li>
          </ul>
        </section>

        <section class="edit-password">
          <h2>Ubah Kata Sandi</h2>

          <form action="" method="POST">
            <p class="label-pass">Kata Sandi Lama</p>
            <input type="password" id="password-lama" name="password_lama" required>
            <p class="label-pass">Kata Sandi Baru</p>
            <input type="password" id="password-baru" name="password_baru" required>
            <p class="label-pass">Konfirmasi Kata Sandi Baru</p>
            <input type="password" id="konfirmasi-password" name="konfirmasi_password" required>

            <button type="submit">Perbarui Profil</button>
          </form>
        </section>
      </section>
    </main>

  <?php include "includes/chatWidget.php"; ?>
  <?php include "includes/footer.php"; ?>
  <?php include "includes/scripts.php"; ?>
</body>

</html>
