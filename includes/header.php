<?php
include 'includes/session.php';
include 'includes/dbconn.php';

// Ambil informasi pengguna yang sedang login
$logged_in_user = null;

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("
        SELECT user_id, email, first_name, last_name,  
               COALESCE(NULLIF(profile_photo, ''), 'uploads/placeholders/profile.jpg') AS profile_photo  
        FROM users WHERE user_id = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $logged_in_user = $result->fetch_assoc();
}

// Handle logout action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<header>
    <div class="container sk-header-content">

        <!-- Logo -->
        <div class="sk-logo">
            <a href="index.php">sekening</a>
        </div>

       <!-- Search Bar -->
        <form class="sk-search-bar" action="search.php" method="GET">
            <div class="sk-search-container">
                <input type="search" name="search" placeholder="Cari barang..." aria-label="Cari Barang" required>
                <button type="submit" class="sk-search-btn">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>

        <!-- Navigation -->
        <nav class="sk-nav">
            <?php if ($logged_in_user): ?>
                <div class="sk-profile-dropdown">
                    <a href="#" class="sk-profile-link">
                        <img src="/sekening_02/<?= htmlspecialchars($logged_in_user['profile_photo']) ?>"  
                             alt="Profil" class="sk-profile-img">
                    </a>
                    <div class="sk-dropdown-content" id="profileDropdown">
                        <a href="userPage.php?user_id=<?= htmlspecialchars($logged_in_user['user_id']); ?>">Lihat Profil</a>
                        <a href="jualPage.php">Jual Barang</a>
                        <a href="index.php?action=logout">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="#" onclick="openModal('login')" class="sk-nav-link">Masuk</a>
                <a href="#" onclick="openModal('register')" class="sk-nav-link">Daftar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileLink = document.querySelector('.sk-profile-link');
        const dropdown = document.getElementById('profileDropdown');

        profileLink.addEventListener('click', function (event) {
            event.preventDefault();
            dropdown.classList.toggle('show');
        });

        window.addEventListener('click', function (event) {
            if (!event.target.matches('.sk-profile-link') && !event.target.closest('.sk-profile-dropdown')) {
                dropdown.classList.remove('show');
            }
        });
    });
</script>