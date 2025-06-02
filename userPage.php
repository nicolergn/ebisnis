<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$user_profile = null;
$isOwnProfile = ($isLoggedIn && $_SESSION['user_id'] == $user_id);

// Ambil data profil pengguna
if ($user_id) {
    $stmt = $conn->prepare("
        SELECT u.user_id, u.first_name, u.last_name, u.bio, u.location, u.created_at, u.phone_number,
               COALESCE(NULLIF(u.profile_photo, ''), 'uploads/pic_placeholder_potrait.jpg') AS profile_photo
        FROM users u
        WHERE u.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_profile = $result->fetch_assoc();
}

if (!$user_profile) {
    die("Profil pengguna tidak ditemukan.");
}

// Pastikan profile_photo tidak kosong setelah diambil dari database
$profile_photo = !empty($user_profile['profile_photo']) ? $user_profile['profile_photo'] : 'pic_placeholder_potrait.jpg';

// Ambil produk yang dijual oleh pengguna ini
$products = [];
if ($user_id) {
    $stmt_products = $conn->prepare("
        SELECT p.*, COALESCE(ph.photo_url, 'uploads/pic_placeholder_sellitem.jpg') AS photo_url 
        FROM products p
        LEFT JOIN product_photos ph ON p.product_id = ph.product_id
        WHERE p.user_id = ?
        ORDER BY p.product_id DESC
    ");
    $stmt_products->bind_param("i", $user_id);
    $stmt_products->execute();
    $result_products = $stmt_products->get_result();

    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}
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
    <?php include "includes/header.php"; ?>
    <?php include "includes/signModal.php"; ?>

    <main class="container">
        <!-- Profil Pengguna -->
        <section class="user-info">
            <div class="bagian-atas">
                <div class="profile-container">
                    <img src="/sekening_02/<?= htmlspecialchars($profile_photo) ?>" 
                         alt="Profil Pengguna" class="profile-pic" />
                    <div class="profile-details">
                        <h2 class="user-name">
                            <?= !empty($user_profile['first_name']) && !empty($user_profile['last_name']) 
                            ? htmlspecialchars(trim($user_profile['first_name']) . ' ' . trim($user_profile['last_name'])) 
                            : "Nama tidak tersedia"; ?>
                        </h2>
                        <p class="last-active">Aktif 1 menit lalu</p>
                        <div class="user-actions">
                            <a href="https://wa.me/<?= htmlspecialchars($user_profile['phone_number']) ?>" class="chat-btn" target="_blank">Chat Sekarang</a>
                            <?php if ($isOwnProfile): ?>
                                <button class="edit-profile-btn" onclick="window.location.href='editProfil.php'">Edit Profil</button>
                            <?php endif; ?>
                    </div>
                    </div>
                </div>

                <!-- Statistik penjual -->
                <div class="seller-stats">
                    <div><p class="label">Produk</p><p class="value"><?= count($products); ?></p></div>
                    <div><p class="label">Bergabung</p><p class="value"><?= date('Y', strtotime($user_profile['created_at'])); ?></p></div>
                    <div><p class="label">Asal</p><p class="value"><?= htmlspecialchars($user_profile['location']) ?: "Asal tidak tersedia."; ?></p></div>  
                </div>
            </div>

            <!-- Bio penjual dipindahkan ke bawah -->
            <div class="seller-bio">
                <p class="label">Bio</p>
                <pre class="value"><?= htmlspecialchars($user_profile['bio']) ?: "Bio tidak tersedia."; ?></pre>
            </div>
        </section>

        <!-- Daftar Produk -->
        <section class="user-listing">
            <div class="list-header">
                <h3 class="section-title">Produk yang Dijual</h3>
                <?php if ($isOwnProfile): ?>
                    <button class="add-item-btn" onclick="window.location.href='jualPage.php'">
                        Tambah Barang <i class="fa-solid fa-plus"></i>
                    </button>
                <?php endif; ?>
            </div>
            <hr>

            <div class="user-listing-grid">
                <?php foreach ($products as $product): ?>
                    <div class="user-listing-card">
                        <a href="productPage.php?product_id=<?= htmlspecialchars($product['product_id']) ?>">
                            <img src="/sekening_02/<?= htmlspecialchars($product['photo_url']) ?>"  
                                alt="<?= htmlspecialchars($product['title']) ?>" 
                                class="user-listing-image" />
                        </a>
                        <div class="user-listing-info">
                            <div class="user-listing-title"><?= htmlspecialchars($product['title']) ?></div>
                            <div class="user-listing-price">Rp <?= number_format($product['expected_price'], 0, ',', '.'); ?></div>
                        </div>
                        <?php if ($isOwnProfile): ?>
                            <div class="user-listing-actions">
                                <button class="edit-btn" onclick="window.location.href='editProduct.php?product_id=<?= $product['product_id'] ?>'">Edit</button>
                                <button class="delete-btn" onclick="hapusProduk(<?= $product['product_id'] ?>)">Hapus</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>

    <script>
        function hapusProduk(productId) {
            if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
                window.location.href = "hapusProduct.php?product_id=" + productId;
            }
        }
        </script>

<?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>

    <?php  
    $conn->close();  
    ?>
</body>
</html>