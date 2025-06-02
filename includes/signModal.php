<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$errors = [];

// === PROSES LOGIN ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Password salah.";
        }
    } else {
        $errors[] = "Email tidak ditemukan.";
    }
}

// === PROSES REGISTER ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $first_name = $_POST['reg_first_name'];
    $last_name  = $_POST['reg_last_name'];
    $student_id = $_POST['reg_student_id'];
    $email      = $_POST['reg_email'];
    $password   = $_POST['reg_password'];
    $confirm    = $_POST['reg_confirm'];

    // Validasi format email untuk domain "@student.unsrat.ac.id"
    if (!preg_match("/@student\.unsrat\.ac\.id$/", $email)) {
        $errors[] = "Email harus menggunakan domain @student.unsrat.ac.id.";
    }

    // Validasi kecocokan password
    if ($password !== $confirm) {
        $errors[] = "Password tidak cocok.";
    } else {
        // Periksa apakah email sudah digunakan
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email sudah digunakan.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, student_id, email, password_hash, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $first_name, $last_name, $student_id, $email, $password_hash);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Gagal mendaftar: " . $stmt->error;
            }
        }
    }
}
?>

<!-- ========== OVERLAY ========== -->
<div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>

<!-- ========== MODAL LOGIN ========== -->
<div id="loginModal" class="modal">
  <h2>Masuk</h2>
  <form method="POST" action="">
    <input type="email" name="login_email" placeholder="Email" required>
    <input type="password" name="login_password" placeholder="Password" required>
    <button type="submit" name="login">Masuk</button>
  </form>
  <p>Belum punya akun? <a href="#" onclick="switchModal('register')">Daftar</a></p>
</div>

<!-- ========== MODAL REGISTER ========== -->
<div id="registerModal" class="modal">
  <h2>Daftar</h2>
  <form method="POST" action="">
    <input type="text" name="reg_first_name" placeholder="First Name" required>
    <input type="text" name="reg_last_name" placeholder="Last Name" required>
    <input type="text" name="reg_student_id" placeholder="Student ID" required>
    <input type="email" name="reg_email" placeholder="Email" required>
    <input type="password" name="reg_password" placeholder="Password" required>
    <input type="password" name="reg_confirm" placeholder="Ulangi Password" required>
    <button type="submit" name="register">Daftar</button>
  </form>
  <p>Sudah punya akun? <a href="#" onclick="switchModal('login')">Masuk</a></p>
</div>

<!-- ========== TAMPILKAN ERROR ========== -->
<?php if (!empty($errors)): ?>
  <div id="errorModal" class="modal" style="display: block; background: #f8d7da; color: #721c24;">
    <ul>
      <?php foreach ($errors as $e) {
          echo "<li>$e</li>";
      } ?>
    </ul>
  </div>
  <script>
    document.getElementById("modalOverlay").style.display = "block";
    <?php if (isset($_POST['login'])): ?>
      document.getElementById("loginModal").style.display = "block";
    <?php else: ?>
      document.getElementById("registerModal").style.display = "block";
    <?php endif; ?>
  </script>
<?php endif; ?>