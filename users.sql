-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 05:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_dev_finals_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','worker','client') NOT NULL,
  `profile_picture` varchar(200) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `application_status` enum('approved','pending','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `user_type`, `profile_picture`, `first_name`, `last_name`, `email`, `phone_number`, `application_status`) VALUES
(12, 'apotato369', '$2y$10$LBO8bWtTqMrAikit3lZUZ.vWevDmpSIa5F2Lg7a8x6yFV84H0uke2', 'admin', NULL, 'John Andre', 'Yap', 'apotato369@gmail.com', '09150443019', 'approved'),
(13, 'JuanDelaCruz', '$2y$10$S7z1KxUFiUrixWbB.vvL4OQsXBjUe9mYNovBnOrgegVoGZzNWrKi2', 'admin', NULL, 'Juan', 'Dela Cruz', 'juan.delacruz@email.com', '09123456789', 'approved'),
(14, 'MariaClara', '$2y$10$qcJPhjbXxfZuexXgmjfVw.0v4FQO2vKts1U116B5Cuqzhggs5O7vK', 'worker', NULL, 'Maria', 'Clara', 'maria.clara@gmail.com', '09176543210', 'approved'),
(15, 'PedroPenduko', '$2y$10$9aKopP/6xKlu7zQL2yJ4O.VqntOSkAzY8BeVaBu7viAW.wTg52hby', 'client', NULL, 'Pedro', 'Penduko', 'pedro.penduko@gmail.com', '092711112233', 'approved'),
(16, 'UlingRoasters', '$2y$10$qcJPhjbXxfZuexXgmjfVw.0v4FQO2vKts1U116B5Cuqzhggs5O7vK', 'worker', NULL, 'Uling', 'Roasters', 'uling.roasters@gmail.com', '09176543210', 'approved'),
(17, 'CrispinBasilio', '$2y$10$qcJPhjbXxfZuexXgmjfVw.0v4FQO2vKts1U116B5Cuqzhggs5O7vK', 'worker', NULL, 'Crispin', 'Basilio', 'crispin.basilio@gmail.com', '09176543210', 'approved'),
(18, 'CrisostomoIbarra', '$2y$10$qcJPhjbXxfZuexXgmjfVw.0v4FQO2vKts1U116B5Cuqzhggs5O7vK', 'worker', NULL, 'Crisostomo', 'Ibarra', 'crisostomo.ibarra@gmail.com', '09176543210', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
