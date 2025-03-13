-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 02:53 PM
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
-- Database: `web_dev_finals_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `job_assignments`
--

CREATE TABLE `job_assignments` (
  `job_assignment_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `date_assigned` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_assignments`
--

INSERT INTO `job_assignments` (`job_assignment_id`, `job_id`, `worker_id`, `date_assigned`) VALUES
(1, 1, 14, '2025-03-13'),
(2, 1, 16, '2025-03-13'),
(3, 1, 17, '2025-03-13'),
(4, 1, 18, '2025-03-13'),
(5, 2, 14, '2025-03-13'),
(6, 2, 16, '2025-03-13'),
(7, 3, 16, '2025-03-13'),
(8, 3, 17, '2025-03-13'),
(9, 3, 18, '2025-03-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `job_assignments`
--
ALTER TABLE `job_assignments`
  ADD PRIMARY KEY (`job_assignment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `job_assignments`
--
ALTER TABLE `job_assignments`
  MODIFY `job_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
