<?php
include 'dbconn.php';

// Tangkap data dari permintaan AJAX
$data = json_decode(file_get_contents("php://input"), true);
$offset = isset($data['offset']) ? intval($data['offset']) : 0;
$limit = 36;
$loaded_ids = isset($data['loaded_ids']) && is_array($data['loaded_ids']) ? array_map('intval', $data['loaded_ids']) : [];
$exclude_product_id = isset($data['exclude_product_id']) ? intval($data['exclude_product_id']) : null;

$notInClause = '';
$types = '';
$params = [];

// Tambahkan pengecualian untuk produk yang sedang dibuka
if (!empty($loaded_ids)) {
    $placeholders = implode(',', array_fill(0, count($loaded_ids), '?'));
    $notInClause = "AND p.product_id NOT IN ($placeholders)";

    foreach ($loaded_ids as $id) {
        $params[] = $id;
        $types .= 'i'; // Setiap product_id adalah integer
    }
}

// Query untuk mengambil produk tambahan dengan pengecualian `product_id` yang sedang dibuka
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
    WHERE p.status = 'available' 
    " . ($exclude_product_id ? "AND p.product_id != ?" : "") . " 
    $notInClause
    ORDER BY RAND()
    LIMIT ?
";

// Tambahkan parameter ke `bind_param()`
$params[] = $limit;
$types .= 'i';

if ($exclude_product_id) {
    array_unshift($params, $exclude_product_id); // Pastikan `exclude_product_id` masuk pertama
    $types = 'i' . $types; // Tambahkan tipe integer untuk pengecualian
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        ?>
        <a href="productPage.php?product_id=<?= htmlspecialchars($product['product_id']) ?>" class="rec-recommendation-card-link">
            <div class="rec-recommendation-card">
                <img 
                    src="/sekening_02/<?= htmlspecialchars($product['photo_url']) ?>" 
                    alt="<?= htmlspecialchars($product['title']) ?>" 
                    class="rec-recommendation-image"
                />
                <div class="rec-recommendation-info">
                    <h4 class="rec-title"><?= htmlspecialchars($product['title']) ?></h4>
                    <p class="rec-price">Rp <?= number_format($product['expected_price'], 0, ',', '.') ?></p>
                    <p class="rec-desc"><?= ucfirst(htmlspecialchars($product['product_condition'])) ?></p>
                    <p class="rec-location"><?= htmlspecialchars($product['location']) ?></p>
                </div>
            </div>
        </a>
        <?php
    }
} else {
    echo ""; // Tidak ada produk baru
}
?>