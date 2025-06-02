<?php
include 'includes/dbconn.php';

// Tangkap `product_id` dari URL dengan validasi
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

if (!$product_id) {
    die("Produk tidak ditemukan.");
}

// Ambil data produk beserta informasi penjual dan foto produk dalam query langsung
$stmt = $conn->prepare("
    SELECT p.*, 
           u.first_name, u.last_name, u.bio, u.location, u.created_at, u.phone_number,
           COALESCE(NULLIF(u.profile_photo, ''), 'uploads/placeholders/profile.jpg') AS profile_photo,
           COALESCE(NULLIF(ph.photo_url, ''), 'uploads/placeholders/product.jpg') AS product_photo
    FROM products p
    JOIN users u ON p.user_id = u.user_id
    LEFT JOIN product_photos ph ON p.product_id = ph.product_id
    WHERE p.product_id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$product = $result->fetch_assoc();
?>

<!-- SECTION: Info Produk -->
<section class="pd-product-detail">
    <div class="pd-carousel">
        <img src="/sekening_02/<?= htmlspecialchars($product['product_photo']) ?>"  
             alt="<?= htmlspecialchars($product['title']) ?>" class="pd-carousel-img">
    </div>

    <div class="pd-detail-info">
        <h4 class="pd-title"><?= htmlspecialchars($product['title']) ?></h4>
        <h3 class="pd-price">Rp <?= number_format($product['expected_price'], 0, ',', '.') ?></h3>

        <div class="pd-info-meta">
            <div class="pd-info-item">
                <p class="pd-label">Kondisi Barang</p>
                <p><?= ucfirst(htmlspecialchars($product['product_condition'])) ?></p>
            </div>
            <div class="pd-info-item">
                <p class="pd-label">Asal Pengiriman</p>
                <p><?= htmlspecialchars($product['location']) ?></p>
            </div>
        </div>

        <div class="pd-description">
            <p class="pd-label">Deskripsi Barang</p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </div>
</section>

<!-- SECTION: Profil Penjual -->
<section class="pd-seller-preview">
    <div class="pd-seller-left">
        <div class="pd-preview-profile">
            <a href="userPage.php?user_id=<?= htmlspecialchars($product['user_id']) ?>" class="pd-profile-link">
                <img src="/sekening_02/<?= htmlspecialchars($product['profile_photo']) ?>"  
                     alt="Profil Penjual" class="pd-profile-pic">
            </a>

            <div class="pd-seller-details">
                <h4 class="pd-seller-name">
                    <a href="userPage.php?user_id=<?= htmlspecialchars($product['user_id']) ?>" class="pd-profile-link">
                        <?= htmlspecialchars($product['first_name'] . ' ' . $product['last_name']) ?>
                    </a>
                </h4>
                <p class="pd-last-active">Aktif 1 menit lalu</p>
                <div class="pd-seller-buttons">
                    <a href="https://wa.me/<?= htmlspecialchars($product['phone_number']) ?>" class="pd-chat-btn" target="_blank">Chat Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <div class="pd-vertical-line"></div>

    <div class="pd-seller-right">
        <div class="pd-seller-stats">
            <div>
                <p class="pd-label">Penilaian</p>
                <p class="pd-value">4,5</p>
            </div>
            <div>
                <p class="pd-label">Persentase Chat Dibalas</p>
                <p class="pd-value">100%</p>
            </div>
            <div>
                <p class="pd-label">Bergabung</p>
                <p class="pd-value"><?= date('Y', strtotime($product['created_at'])) ?></p>
            </div>
            <div>
                <p class="pd-label">Produk</p>
                <p class="pd-value">3</p>
            </div>
            <div>
                <p class="pd-label">Waktu Chat Dibalas</p>
                <p class="pd-value">Hitungan jam</p>
            </div>
            <div>
                <p class="pd-label">Pengikut</p>
                <p class="pd-value">419</p>
            </div>
        </div>
    </div>
</section>

<div class="pd-image-popup-overlay" id="pdImagePopup" onclick="closeImagePopup(event)">
  <button class="pd-popup-nav pd-prev" onclick="prevPopupImage(event)">&#10094;</button>
  <img id="pdPopupImg" src="" alt="Gambar Produk" />
  <button class="pd-popup-nav pd-next" onclick="nextPopupImage(event)">&#10095;</button>
</div>

<?php
$stmt->close();
$conn->close();
?>
