<?php
include 'includes/dbconn.php';

// Gunakan global `$exclude_product_id` yang berasal dari `productPage.php`
global $exclude_product_id;

// Ambil produk secara acak, kecuali produk yang sedang dilihat
function getRandomProducts($limit = 12, $exclude_product_id = null) {
    global $conn;

    $query = "
        SELECT p.product_id, p.title, p.expected_price, p.product_condition, p.location,
               COALESCE(NULLIF(pp.photo_url, ''), 'uploads/placeholders/product.jpg') AS photo_url
        FROM products p
        LEFT JOIN (
            SELECT product_id, MIN(photo_id) as min_photo_id
            FROM product_photos
            GROUP BY product_id
        ) grouped_photos ON p.product_id = grouped_photos.product_id
        LEFT JOIN product_photos pp ON pp.product_id = grouped_photos.product_id AND pp.photo_id = grouped_photos.min_photo_id
        WHERE p.status = 'available' " . ($exclude_product_id ? "AND p.product_id != ?" : "") . "
        ORDER BY RAND()
        LIMIT ?";

    $stmt = $conn->prepare($query);

    if ($exclude_product_id) {
        $stmt->bind_param("ii", $exclude_product_id, $limit);
    } else {
        $stmt->bind_param("i", $limit);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}

$products = getRandomProducts(12, $exclude_product_id);
?>

<section class="rec-recommendation">
    <h3>Rekomendasi</h3>

    <div class="rec-recommendation-grid" id="recommendationGrid">
        <?php foreach ($products as $product): ?>
            <a href="productPage.php?product_id=<?= htmlspecialchars($product['product_id']) ?>" class="rec-recommendation-card-link">
                <div class="rec-recommendation-card">
                    <img src="/sekening_02/<?= htmlspecialchars($product['photo_url']) ?>" 
                         alt="<?= htmlspecialchars($product['title']) ?>" class="rec-recommendation-image">
                    <div class="rec-recommendation-info">
                        <h4 class="rec-title"><?= htmlspecialchars($product['title']) ?></h4>
                        <p class="rec-price">Rp <?= number_format($product['expected_price'], 0, ',', '.') ?></p>
                        <p class="rec-desc"><?= ucfirst(htmlspecialchars($product['product_condition'])) ?></p>
                        <p class="rec-location"><?= htmlspecialchars($product['location']) ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="rec-load-more">
        <button id="loadMore" data-offset="12">Lihat Lebih Banyak</button>
        <p id="noMoreProducts" style="display: none;">Tidak ada produk lain untuk ditampilkan</p>
    </div>
</section>