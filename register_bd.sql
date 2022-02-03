-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 03, 2022 at 05:37 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `register_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `text` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `text`, `user_id`, `img_id`, `created_at`, `modified_at`) VALUES
(1, 'Twstin', 'fniqwnfiwq', 1, 2, '2021-10-13 13:08:01', '2021-10-13 16:08:01'),
(2, 'My first here', 'test???', 3, 6, '2021-10-13 14:05:46', '2021-10-13 17:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `imgs`
--

CREATE TABLE `imgs` (
  `id` int(11) NOT NULL,
  `src` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `imgs`
--

INSERT INTO `imgs` (`id`, `src`, `created_at`, `modified_at`) VALUES
(1, '/storage/default/user.png', '2021-10-13 13:04:46', '2021-10-13 16:08:41'),
(2, '/storage/user-1/change-typographic-button.png', '2021-10-13 13:08:01', '2021-10-13 16:08:01'),
(3, '/storage/default/user.png', '2021-10-13 13:15:43', '2021-10-13 16:17:00'),
(4, '/storage/default/user.png', '2021-10-13 13:18:17', '2021-10-13 16:18:44'),
(5, '/storage/default/user.png', '2021-10-13 13:19:11', '2021-10-13 16:19:11'),
(6, '/storage/user-3/icons8-article-60.png', '2021-10-13 14:05:46', '2021-10-13 17:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `user_id`, `role`, `created_at`) VALUES
(1, 1, 'admin', '2021-10-13 13:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `img_id` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`, `modified_at`, `img_id`) VALUES
(1, 'admin', 'admin', 'admin@test.com', '$2y$10$hZI3RP/SOQR7W93JPpTr/e0uHee0VWOXWRe.1CM6gQaBBvRjze0.a', '2021-10-13 13:04:46', '2021-10-13 16:04:46', 1),
(2, 'Joshua', 'Valkis', 'josh@test.com', '$2y$10$fQ8IxgnN96EhYAbBihxONeJx6EKsphp7w6FgC4EewMdpL761BtbeO', '2021-10-13 13:15:43', '2021-10-13 16:15:43', 3),
(3, 'Ann', 'Nikols', 'ann@test.com', '$2y$10$gRSijoXsAum2T/NEKMIQ2OHIy1gC7y0NBSrEUXZAE3Ve/u3khvTKu', '2021-10-13 13:18:17', '2021-10-13 16:18:17', 4),
(4, 'Andrew', 'Kish', 'andrew@test.com', '$2y$10$Me0k4PG1kdfSrgP6.u3JoeAlqPvK7IAFfZWd3PgZ2cI6Iok.orcy.', '2021-10-13 13:19:11', '2021-10-13 16:19:11', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `img_id` (`img_id`);

--
-- Indexes for table `imgs`
--
ALTER TABLE `imgs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `imgs`
--
ALTER TABLE `imgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`img_id`) REFERENCES `imgs` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
