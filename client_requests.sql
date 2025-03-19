-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 19, 2025 at 11:48 AM
-- Server version: 10.11.6-MariaDB-0+deb12u1
-- PHP Version: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s21103565_web_dev_finals_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_requests`
--

CREATE TABLE `client_requests` (
  `request_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `request_title` varchar(100) NOT NULL,
  `request_description` varchar(1000) NOT NULL,
  `request_location` varchar(100) NOT NULL,
  `request_status` enum('in progress','finished','cancelled','') NOT NULL DEFAULT 'in progress',
  `date_created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_requests`
--

INSERT INTO `client_requests` (`request_id`, `author_id`, `request_title`, `request_description`, `request_location`, `request_status`, `date_created`) VALUES
(1, 15, 'Fix mah phone', 'Pls fix my phone. Will pay in robux', 'United Kingdom', 'in progress', '2025-03-19'),
(2, 18, 'Oh no! My table! It\'s broken', 'My table broke. Boohoo. I am very sad.', 'Lahug', 'in progress', '2025-03-19'),
(3, 15, 'I need help tending vegetables in my garden', 'Bahay kubo, kahit munti, ang halaman doon ay sari-sari (smth)', 'Mandaluyong', 'in progress', '2025-03-19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
