-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2024 at 09:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toy_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `toy_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ease_of_use_rating` int(11) DEFAULT NULL,
  `toy_condition_rating` int(11) DEFAULT NULL,
  `overall_satisfaction_rating` int(11) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `improvement_suggestions` text DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `member_id`, `toy_id`, `rating`, `ease_of_use_rating`, `toy_condition_rating`, `overall_satisfaction_rating`, `comments`, `improvement_suggestions`, `feedback_date`) VALUES
(1, 1, 1, 5, 5, 5, 5, 'Nice', 'no need of improvements nice service', '2024-03-21 18:22:37'),
(2, 1, 2, 5, 4, 3, 5, 'good', 'na', '2024-03-21 18:23:07'),
(3, 1, 3, 5, 5, 5, 5, 'best one', 'no need of improvement best toy', '2024-03-21 18:23:37'),
(4, 1, 4, 5, 4, 5, 4, 'goood', 'na', '2024-03-21 18:24:03'),
(5, 1, 7, 4, 3, 5, 2, 'nice', 'can be better', '2024-03-21 18:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`id`, `username`, `password`) VALUES
(1, 'sr', '93c768d0152f72bc8d5e782c0b585acc35fb0442');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `deposit` decimal(10,2) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `password`, `full_name`, `email`, `date_of_birth`, `address`, `contact_number`, `deposit`, `profile_picture`, `registration_date`) VALUES
(1, 'pankaj', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Pankaj', 'Pankaj@gmail.com', '2000-03-03', 'Pune', '76668448404', 1500.00, './profile_pic/1.jpeg', '2024-03-21 15:20:25'),
(2, 'sr', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'sr', 'sr@gmail.com', '2001-01-01', 'Pune,Karve Nagar', '8888204780', 1500.00, './profile_pic/2.jpeg', '2024-03-21 15:20:32'),
(3, 'Santosh', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Santosh ', 'Santosh@gmail.com', '2006-03-01', 'dhule', '7894561234', 1500.00, './profile_pic/3.jpeg', '2024-03-21 15:20:37'),
(4, 'Vedant ', '1fdcc98cb8050b15a6bdd899a2de9e339b3d06fe', 'Vedant ', 'Vedant@gmail.com', '2007-02-04', 'Mumbai', '6987456324', 1500.00, './profile_pic/4.jpeg', '2024-03-21 15:20:44'),
(5, 'Ankur', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Ankur ', 'Ankur@gmail.com', '2004-02-05', 'Delhi', '7894562484', 2000.00, './profile_pic/5.jpeg', '2024-03-21 15:20:49'),
(6, 'Abhishek ', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Abhishek ', 'Abhishek@gmail.com', '2005-01-04', 'Amravti', '7666848405', 2000.00, './profile_pic/6.jpeg', '2024-03-21 15:20:55'),
(7, 'Sumit ', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Sumit ', 'Sumit@gmail.com', '2005-01-01', 'Delhi', '7666848402', 2000.00, './profile_pic/7.jpeg', '2024-03-21 15:21:01'),
(8, 'Nilesh', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Nilesh', 'Nilesh@gmail.com', '2004-02-05', 'Vadodara', '7666848407', 2000.00, './profile_pic/8.jpeg', '2024-03-21 15:21:06'),
(9, 'ram', 'e78fab6dd04f4042b3adace8b4d5c69da9ea3ecc', 'Ram Patil', 'ram@gmail.com', '2024-01-02', 'Loni , Tal Rahata dist Nagar', '9086768756', 1500.00, './profile_pic/th (3).jpeg', '2024-03-21 15:55:35'),
(10, 'srgsdg', '6ea942c694d8bed3bda0949c07233a636dd15b1e', 'gsdfgsdfg', 'sanjot.raut.sdfgdfg23@mespune.in', '2024-03-12', 'dfgsdfg', '9370321080', 2000.00, './profile_pic/9345bc8159ece7ab54c562776b622060.jpg', '2024-03-22 07:52:52');

-- --------------------------------------------------------

--
-- Table structure for table `membership_details`
--

CREATE TABLE `membership_details` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `amount_paid` decimal(10,2) NOT NULL,
  `num_toys_borrowed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_details`
--

INSERT INTO `membership_details` (`id`, `member_id`, `type`, `duration`, `start_date`, `end_date`, `status`, `amount_paid`, `num_toys_borrowed`) VALUES
(1, 1, 'Regular', 3, '2024-03-21 15:20:26', '2024-06-21', 'Active', 1500.00, 0),
(2, 2, 'Regular', 3, '2024-03-21 15:20:32', '2024-06-21', 'Active', 1500.00, 2),
(3, 3, 'Regular', 6, '2024-03-21 15:20:37', '2024-09-21', 'Active', 3000.00, 2),
(4, 4, 'Regular', 3, '2024-03-21 15:20:44', '2024-06-21', 'Active', 1500.00, 2),
(5, 5, 'Premium', 1, '2024-03-21 15:20:49', '2024-04-21', 'Active', 1000.00, 3),
(6, 6, 'Premium', 3, '2024-03-21 15:20:55', '2024-06-21', 'Active', 3000.00, 3),
(7, 7, 'Premium', 1, '2024-03-21 15:21:01', '2024-04-21', 'Active', 1000.00, 3),
(8, 8, 'Premium', 3, '2024-03-21 15:21:06', '2024-06-21', 'Active', 3000.00, 3),
(9, 9, 'Regular', 3, '2024-03-21 15:55:35', '2024-06-21', 'Active', 1500.00, 2),
(10, 10, 'Premium', 1, '2024-03-22 07:52:52', '2024-04-22', 'Active', 1000.00, 3);

--
-- Triggers `membership_details`
--
DELIMITER $$
CREATE TRIGGER `calculate_end_date` BEFORE INSERT ON `membership_details` FOR EACH ROW BEGIN
    SET NEW.end_date = DATE_ADD(NEW.start_date, INTERVAL NEW.duration MONTH);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_registrations`
--

CREATE TABLE `pending_registrations` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `membership_type` enum('Regular','Premium') NOT NULL,
  `membership_duration` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_registrations`
--

INSERT INTO `pending_registrations` (`id`, `username`, `password`, `full_name`, `email`, `date_of_birth`, `address`, `contact_number`, `membership_type`, `membership_duration`, `profile_picture`, `registration_date`) VALUES
(11, 'ramsdf', 'bd3a0f8e445467f3f51e17898046b4ba1d3dad03', 'gdsfgeewr', 'hgfhgasds@dfgfd', '2024-02-26', 'sfasdf', '5435435342', 'Premium', 1, './profile_pic/th (3).jpeg', '2024-03-21 18:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `toy_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `toy_id`, `member_id`, `request_date`, `status`) VALUES
(1, 3, 1, '2024-03-21 17:37:19', 'Approved'),
(2, 4, 1, '2024-03-21 17:37:27', 'Approved'),
(3, 2, 1, '2024-03-21 17:37:57', 'Approved'),
(4, 1, 1, '2024-03-21 18:11:14', 'Approved'),
(5, 7, 1, '2024-03-21 18:11:27', 'Approved'),
(6, 15, 1, '2024-03-22 07:16:34', 'Approved'),
(7, 11, 1, '2024-03-22 07:20:55', 'Approved'),
(8, 3, 1, '2024-03-22 07:22:15', 'Approved'),
(9, 12, 1, '2024-03-22 07:24:32', 'Rejected'),
(10, 14, 1, '2024-03-22 07:30:45', 'Approved'),
(11, 4, 1, '2024-03-22 07:33:51', 'Approved'),
(12, 3, 1, '2024-03-22 07:53:54', 'Approved'),
(13, 6, 1, '2024-03-22 07:54:00', 'Approved'),
(14, 11, 1, '2024-03-22 07:54:04', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `return_requests`
--

CREATE TABLE `return_requests` (
  `id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `toy_id` int(11) NOT NULL,
  `status` enum('Pending','Rejected','Confirmed') DEFAULT 'Pending',
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_requests`
--

INSERT INTO `return_requests` (`id`, `log_id`, `member_id`, `toy_id`, `status`, `date`) VALUES
(1, 4, 1, 1, 'Confirmed', '2024-03-21 18:18:47'),
(2, 3, 1, 2, 'Confirmed', '2024-03-21 18:18:53'),
(3, 1, 1, 3, 'Confirmed', '2024-03-21 18:18:57'),
(4, 2, 1, 4, 'Confirmed', '2024-03-21 18:19:01'),
(5, 5, 1, 7, 'Confirmed', '2024-03-21 18:19:07'),
(6, 6, 1, 11, 'Confirmed', '2024-03-22 07:50:22'),
(7, 8, 1, 4, 'Confirmed', '2024-03-22 07:50:24'),
(8, 7, 1, 3, 'Confirmed', '2024-03-22 07:50:26');

-- --------------------------------------------------------

--
-- Table structure for table `toys`
--

CREATE TABLE `toys` (
  `id` int(11) NOT NULL,
  `toy_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `available_quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `type` enum('Regular','Premium') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toys`
--

INSERT INTO `toys` (`id`, `toy_name`, `description`, `category`, `quantity`, `available_quantity`, `image_url`, `type`) VALUES
(1, 'Spider Man ', 'Spider man', 'Action Figures', 100, 100, 'Spider_Man.jpg', 'Premium'),
(2, 'Hulk ', 'Hulk The super hero', 'Action Figures', 100, 100, 'Hulk.jpg', 'Regular'),
(3, 'Iron Man', 'Iron Man', 'Action Figures', 150, 149, 'Iron Man.jpg', 'Premium'),
(4, 'Captain America', 'Captain America', 'Action Figures', 50, 50, 'Capton America.jpg', 'Premium'),
(5, 'Abigail', 'Abigail', 'Dolls', 40, 40, '1.jpg', 'Regular'),
(6, 'Acorn', 'Acorn', 'Dolls', 50, 49, '2.jpg', 'Regular'),
(7, 'Aelafynn', 'Aelafynn', 'Dolls', 40, 40, '3.jpg', 'Premium'),
(8, 'Aerlyn', 'Aerlyn', 'Dolls', 60, 60, '4.jpg', 'Premium'),
(9, 'Tic Tac Toe ', 'Tic Tac Toe ', 'Educational Toys', 100, 100, 'Tic Tak to.jpg', 'Regular'),
(10, 'Shape Sorting', 'Shape Sorting', 'Educational Toys', 40, 40, 'Shape Sorter.jpg', 'Regular'),
(11, 'Musical Rhymes', 'Musical Rhymes', 'Educational Toys', 50, 50, 'Musical Rythm.jpg', 'Premium'),
(12, 'Spike the Fine Motor Hedgehog', 'Spike the Fine Motor Hedgehog', 'Educational Toys', 40, 40, 'Spike the Fine Motor Hedgehog.jpg', 'Premium'),
(13, 'Building Block', 'Building Block', 'Building Blocks', 40, 40, 'Bulding Block.jpg', 'Regular'),
(14, 'Kipa Gaming MagPlay Magnetic', 'Kipa Gaming MagPlay Magnetic', 'Art and Craft', 40, 40, 'Kipa Gaming MagPlay Magnetic.jpg', 'Regular'),
(15, 'Mirada Glitter Tattoo Kit', 'Mirada Glitter Tattoo Kit', 'Art and Craft', 50, 50, 'download.jpg', 'Premium');

-- --------------------------------------------------------

--
-- Table structure for table `toy_logs`
--

CREATE TABLE `toy_logs` (
  `log_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `librarian_id` int(11) NOT NULL,
  `confirmation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` timestamp NOT NULL DEFAULT (current_timestamp() + interval 10 day),
  `return_date` timestamp NULL DEFAULT NULL,
  `status` enum('Borrowed','Returned') DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toy_logs`
--

INSERT INTO `toy_logs` (`log_id`, `request_id`, `librarian_id`, `confirmation_date`, `due_date`, `return_date`, `status`) VALUES
(1, 1, 1, '2024-03-21 18:09:38', '2024-03-31 18:09:38', '2024-03-21 18:21:48', 'Returned'),
(2, 2, 1, '2024-03-21 18:09:41', '2024-03-31 18:09:41', '2024-03-21 18:21:49', 'Returned'),
(3, 3, 1, '2024-03-21 18:09:43', '2024-03-31 18:09:43', '2024-03-21 18:21:47', 'Returned'),
(4, 4, 1, '2024-03-21 18:16:20', '2024-03-31 18:16:20', '2024-03-21 18:21:47', 'Returned'),
(5, 5, 1, '2024-03-21 18:16:22', '2024-03-31 18:16:22', '2024-03-21 18:21:49', 'Returned'),
(6, 7, 1, '2024-03-22 07:21:15', '2024-04-01 07:21:15', '2024-03-22 07:50:55', 'Returned'),
(7, 8, 1, '2024-03-22 07:22:51', '2024-04-01 07:22:51', '2024-03-22 07:50:56', 'Returned'),
(8, 11, 1, '2024-03-22 07:49:50', '2024-04-01 07:49:50', '2024-03-22 07:50:55', 'Returned'),
(9, 12, 1, '2024-03-22 07:54:59', '2024-04-01 07:54:59', NULL, 'Borrowed'),
(10, 13, 1, '2024-03-22 07:55:05', '2024-04-01 07:55:05', NULL, 'Borrowed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `toy_id` (`toy_id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `membership_details`
--
ALTER TABLE `membership_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_registrations`
--
ALTER TABLE `pending_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `toy_id` (`toy_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_id` (`log_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `toy_id` (`toy_id`);

--
-- Indexes for table `toys`
--
ALTER TABLE `toys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `toy_logs`
--
ALTER TABLE `toy_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `librarian_id` (`librarian_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `membership_details`
--
ALTER TABLE `membership_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pending_registrations`
--
ALTER TABLE `pending_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `return_requests`
--
ALTER TABLE `return_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `toys`
--
ALTER TABLE `toys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `toy_logs`
--
ALTER TABLE `toy_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`toy_id`) REFERENCES `toys` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`toy_id`) REFERENCES `toys` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `return_requests`
--
ALTER TABLE `return_requests`
  ADD CONSTRAINT `return_requests_ibfk_1` FOREIGN KEY (`log_id`) REFERENCES `toy_logs` (`log_id`),
  ADD CONSTRAINT `return_requests_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `return_requests_ibfk_3` FOREIGN KEY (`toy_id`) REFERENCES `toys` (`id`);

--
-- Constraints for table `toy_logs`
--
ALTER TABLE `toy_logs`
  ADD CONSTRAINT `toy_logs_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `toy_logs_ibfk_2` FOREIGN KEY (`librarian_id`) REFERENCES `librarian` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_status_event` ON SCHEDULE EVERY 1 HOUR STARTS '2024-03-22 13:46:02' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE membership_details SET status = 'Inactive' WHERE end_date <= CURDATE()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
