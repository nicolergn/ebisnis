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
        <?php include "includes/promoBanner.php"; ?>
        <?php include "includes/recommendation.php"; ?>
    </main>

    <?php include "includes/footer.php"; ?>

    <?php include "includes/scripts.php"; ?>

    <?php 
    $conn->close(); 
    ?>
</body>
</html>