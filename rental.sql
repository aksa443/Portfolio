-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2023 at 11:23 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cameras`
--

CREATE TABLE `cameras` (
  `camera_id` int(11) NOT NULL,
  `camera_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cameras`
--

INSERT INTO `cameras` (`camera_id`, `camera_name`, `description`, `rental_price`, `status`, `stock`) VALUES
(1, 'Nikon D850', 'High-resolution full-frame DSLR camera', 150000.00, 'Tersedia', 58),
(2, 'Canon EOS R5', 'Mirrorless camera with advanced features', 180000.00, 'Sewa', 50),
(3, 'Sony A7 III', 'Full-frame mirrorless camera', 140000.00, 'Sewa', 49),
(4, 'Fujifilm XT 5', 'Mirrorless camera with analog camera looks features', 150000.00, 'Sewa', 50);

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `camera_id` int(11) NOT NULL,
  `rental_date` date NOT NULL,
  `return_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `user_id`, `camera_id`, `rental_date`, `return_date`, `total_price`, `status`) VALUES
(1, 3, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(2, 3, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(3, 3, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(4, 3, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(5, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(6, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(7, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(8, 2, 4, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(9, 2, 2, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(10, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(11, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(12, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(13, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(14, 2, 2, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(15, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(16, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(17, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(18, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(19, 2, 3, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(20, 3, 4, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(21, 2, 1, '0000-00-00', '0000-00-00', 0.00, 'Kembali'),
(22, 3, 3, '0000-00-00', '0000-00-00', 0.00, NULL),
(23, 2, 2, '0000-00-00', '0000-00-00', 0.00, 'Kembali');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin123', 'admin'),
(2, 'user1', 'user1@gmail.com', 'user123', 'user'),
(3, 'user2', 'user2@gmail.com', 'user456', 'user'),
(4, 'user3', 'user3@gmail.com', 'user789', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cameras`
--
ALTER TABLE `cameras`
  ADD PRIMARY KEY (`camera_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `fk_rentals_users_idx` (`user_id`),
  ADD KEY `fk_rentals_cameras_idx` (`camera_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cameras`
--
ALTER TABLE `cameras`
  MODIFY `camera_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `fk_rentals_cameras` FOREIGN KEY (`camera_id`) REFERENCES `cameras` (`camera_id`),
  ADD CONSTRAINT `fk_rentals_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
