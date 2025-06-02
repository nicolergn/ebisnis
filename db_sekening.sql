-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 08:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sekening`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `product_condition` enum('new','used') NOT NULL,
  `description` text DEFAULT NULL,
  `expected_price` decimal(15,2) NOT NULL,
  `final_price` decimal(15,2) DEFAULT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `location` varchar(100) DEFAULT NULL,
  `sold_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `user_id`, `title`, `product_condition`, `description`, `expected_price`, `final_price`, `status`, `location`, `sold_at`, `created_at`) VALUES
(1, 2, 'Setrika Uap', 'used', 'Dijual karena sudah ada setrika baru. Mines pemakaian.', 150000.00, NULL, 'available', 'Manado', NULL, '2025-06-02 05:48:14'),
(2, 2, 'Gitar Akustik', 'new', 'Belum pernah dipakai, sudah punya gitar lain.', 200000.00, NULL, 'available', 'Manado', NULL, '2025-06-02 05:49:12'),
(3, 2, 'Kemeja Flanel Biru', 'used', 'Baru digunakan sekali, tidak suka.', 50000.00, NULL, 'available', 'Manado', NULL, '2025-06-02 05:49:56'),
(4, 2, 'Sepatu Converse', 'used', 'Masih bagus, jarang dipakai.', 250000.00, NULL, 'available', 'Manado', NULL, '2025-06-02 05:50:53'),
(5, 2, 'Wajan', 'used', 'Barang bekas kost, kondisi masih bagus.', 50000.00, NULL, 'available', 'Manado', NULL, '2025-06-02 05:51:45'),
(6, 1, 'Sweater Coklat', 'new', 'Baru 3 kali pakai', 75000.00, NULL, 'available', 'Maumbi', NULL, '2025-06-02 05:55:09'),
(7, 1, 'Matras Yoga', 'new', 'Belum pernah dipakai, gak jadi workout. malas', 250000.00, NULL, 'available', 'Maumbi', NULL, '2025-06-02 05:55:41'),
(8, 1, 'Headphone Hitam', 'used', 'Sering dipakai, tapi masih bagus.', 25000.00, NULL, 'available', 'Maumbi', NULL, '2025-06-02 05:56:12'),
(9, 1, 'Kulkas Mini', 'used', 'Bekas kost, sudah tidak digunakan. Kondisi masih bagus.', 655000.00, NULL, 'available', 'Maumbi', NULL, '2025-06-02 05:56:55'),
(10, 1, 'Kompor Gas', 'used', 'Hanya Kompor.', 88000.00, NULL, 'available', 'Maumbi', NULL, '2025-06-02 05:57:23'),
(11, 3, 'Helm', 'used', 'Masih bagus', 99000.00, NULL, 'available', 'Bahu', NULL, '2025-06-02 05:59:24'),
(12, 3, 'Jam Dinding', 'new', 'Masih bagus, tanpa baterai.', 70000.00, NULL, 'available', 'Bahu', NULL, '2025-06-02 06:00:13'),
(13, 3, 'Jaket Bomber', 'new', 'Masih baru.', 700000.00, NULL, 'available', 'Bahu', NULL, '2025-06-02 06:00:42'),
(14, 3, 'Topi Putih', 'used', 'Masih bagus.', 20000.00, NULL, 'available', 'Bahu', NULL, '2025-06-02 06:01:23'),
(15, 3, 'TV 24inch', 'used', 'Masih bagus', 900000.00, NULL, 'available', 'Bahu', NULL, '2025-06-02 06:02:01'),
(16, 5, 'Jaket', 'used', 'Masih bagus, jarang dipakai.', 850000.00, NULL, 'available', 'Tateli', NULL, '2025-06-02 06:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `product_photos`
--

CREATE TABLE `product_photos` (
  `photo_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_photos`
--

INSERT INTO `product_photos` (`photo_id`, `product_id`, `photo_url`, `created_at`) VALUES
(1, 1, 'uploads/1_2_productphoto.jpeg', '2025-06-02 05:48:14'),
(2, 2, 'uploads/2_2_productphoto.jpeg', '2025-06-02 05:49:12'),
(3, 3, 'uploads/3_2_productphoto.jpeg', '2025-06-02 05:49:56'),
(4, 4, 'uploads/4_2_productphoto.jpeg', '2025-06-02 05:50:53'),
(5, 5, 'uploads/5_2_productphoto.jpeg', '2025-06-02 05:51:45'),
(6, 6, 'uploads/6_1_productphoto.jpeg', '2025-06-02 05:55:09'),
(7, 7, 'uploads/7_1_productphoto.jpeg', '2025-06-02 05:55:41'),
(8, 8, 'uploads/8_1_productphoto.jpeg', '2025-06-02 05:56:12'),
(9, 9, 'uploads/9_1_productphoto.jpeg', '2025-06-02 05:56:55'),
(10, 10, 'uploads/10_1_productphoto.jpeg', '2025-06-02 05:57:23'),
(11, 11, 'uploads/11_3_productphoto.jpeg', '2025-06-02 05:59:24'),
(12, 12, 'uploads/12_3_productphoto.jpeg', '2025-06-02 06:00:13'),
(13, 13, 'uploads/13_3_productphoto.jpeg', '2025-06-02 06:00:42'),
(14, 14, 'uploads/14_3_productphoto.jpeg', '2025-06-02 06:01:23'),
(15, 15, 'uploads/15_3_productphoto.jpeg', '2025-06-02 06:02:01'),
(16, 16, 'uploads/16_5_productphoto.jpeg', '2025-06-02 06:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `user_dob` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `student_id`, `user_dob`, `email`, `password_hash`, `profile_photo`, `location`, `phone_number`, `bio`, `created_at`) VALUES
(1, 'Sabdawita', 'Salibana', '22021106010243', NULL, 'sabdawitasalibana026@student.unsrat.ac.id', '$2y$10$D94.J36x8mhoffbyi8UI7.Tpw5EIEoOWkMoaJ9HAPE5X0nQJsCWL2', 'uploads/1_profilephoto.jpeg', 'Maumbi', '+62895380102241', 'Grow a Garden for life!', '2025-06-02 05:43:17'),
(2, 'Nicole', 'Pakiding', '220211060106', NULL, 'nicolepakiding026@student.unsrat.ac.id', '$2y$10$6pxjciWtUcGiPdcW9qOEDePFOVxm0ZBqJnbbC6uR6lo9TLWldRsI6', 'uploads/2_profilephoto.jpeg', 'Manado', '089697976505', 'Selling Preloved Items!', '2025-06-02 05:46:05'),
(3, 'Catherine', 'Assa', '22021106010353', NULL, 'catherineassa026@student.unsrat.ac.id', '$2y$10$bBC0fhxAnM2xLvRIYb7wI.784uiEjtoO/ybazJHOEXNMxi9F59lEC', 'uploads/3_profilephoto.jpg', 'Bahu', '+62 822-5239-8113', 'Hi!', '2025-06-02 05:58:10'),
(5, 'jane', 'pandelaki', '22021106010335', NULL, 'janepandelaki@student.unsrat.ac.id', '$2y$10$9F.DC48aQxgaM7CIgXZ5HeloOUFGEFc9g20yaGhiApQkIoNHwaDsa', 'uploads/5_profilephoto.jpeg', 'Tateli', '+6289697976505', 'Saya adalah pengguna baru.', '2025-06-02 06:16:26'),
(6, 'lucia', 'pantow', '22021106010100', NULL, 'luciapantow@student.unsrat.ac.id', '$2y$10$JRUnOILC.H2AlePA8MdIxuy0ESGXunavr4HTohdjWDSHHO6ug7QWi', 'uploads/6_profilephoto.jpeg', 'Manado', '+6289697976505', 'Saya adalah pengguna baru.', '2025-06-02 06:24:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_photos`
--
ALTER TABLE `product_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_photos`
--
ALTER TABLE `product_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_photos`
--
ALTER TABLE `product_photos`
  ADD CONSTRAINT `product_photos_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
