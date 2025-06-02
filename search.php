<?php
include 'includes/session.php';
include 'includes/dbconn.php';

$isLoggedIn = isset($_SESSION['user_id']);
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
        <ul class="search-grid">
            <?php
            // Ambil kata kunci pencarian dari GET
            $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

            if (!empty($keyword)) {
                // Query untuk mencari produk berdasarkan pencarian
                $stmt = $conn->prepare("
                    SELECT p.product_id, p.title, p.expected_price, p.product_condition, p.location,
                           COALESCE(NULLIF(pp.photo_url, ''), 'uploads/placeholders/product.jpg') AS photo_url
                    FROM products p
                    LEFT JOIN (
                        SELECT product_id, MIN(photo_id) as min_photo_id
                        FROM product_photos
                        GROUP BY product_id
                    ) grouped_photos ON p.product_id = grouped_photos.product_id
                    LEFT JOIN product_photos pp ON pp.product_id = grouped_photos.product_id AND pp.photo_id = grouped_photos.min_photo_id
                    WHERE p.status = 'available' AND p.title LIKE CONCAT('%', ?, '%')
                    ORDER BY p.title ASC
                ");

                $stmt->bind_param("s", $keyword);
                $stmt->execute();
                $result = $stmt->get_result();

                // Jika ada hasil, tampilkan produk
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="productPage.php?product_id=' . htmlspecialchars($row['product_id']) . '" class="search-card-link">';
                        echo '<div class="search-card">';
                        echo '<img src="/sekening_02/' . htmlspecialchars($row['photo_url']) . '" alt="' . htmlspecialchars($row['title']) . '" class="search-image">';
                        echo '<div class="search-info">';
                        echo '<h4 class="search-title">' . htmlspecialchars($row['title']) . '</h4>';
                        echo '<p class="search-price">Rp ' . number_format($row['expected_price'], 0, ',', '.') . '</p>';
                        echo '<p class="search-desc">' . ucfirst(htmlspecialchars($row['product_condition'])) . '</p>';
                        echo '<p class="search-location">' . htmlspecialchars($row['location']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p class="no-results">Tidak ada hasil untuk "' . htmlspecialchars($keyword) . '". Coba kata kunci lain!</p>';
                }

                $stmt->close();
            } else {
                echo '<p class="no-results">Silakan masukkan kata kunci pencarian.</p>';
            }
            ?>
        </ul>

        <?php include "includes/recommendation.php"; ?>
        
    </main>

    <?php
    if ($conn) {
        $conn->close();
    }
    ?>

    <?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>

</body>
</html>