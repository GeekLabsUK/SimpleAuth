-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 18, 2020 at 06:37 PM
-- Server version: 5.7.19
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpleadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

DROP TABLE IF EXISTS `auth_logins`;
CREATE TABLE IF NOT EXISTS `auth_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `successfull` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `user_id`, `firstname`, `lastname`, `role`, `ip_address`, `date`, `successfull`) VALUES
(19, 1, 'super', 'admin', '2', '::1', '2020-11-18 12:37:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE IF NOT EXISTS `auth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedvalidator` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(250) NOT NULL,
  `reset_expire` datetime DEFAULT NULL,
  `activated` tinyint(1) NOT NULL,
  `activate_token` varchar(250) DEFAULT NULL,
  `activate_expire` varchar(250) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `reset_token`, `reset_expire`, `activated`, `activate_token`, `activate_expire`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'super', 'admin', 'superadmin@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$bjRmUGs5QkdFSzUuWG1MYw$jV+mMJB0xr7lSul9VHBxU1m8+X6lfFeyKba77mx6CJs', '', NULL, 1, NULL, NULL, 2, '2020-11-18 18:35:43', '2020-11-18 18:35:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_name`) VALUES
(1, 'SuperAdmin'),
(2, 'Tenant'),
(3, 'Customer');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
