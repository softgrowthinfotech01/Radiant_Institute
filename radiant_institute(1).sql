-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 13, 2026 at 06:17 AM
-- Server version: 8.4.7
-- PHP Version: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `radiant_institute`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_images`
--

DROP TABLE IF EXISTS `activity_images`;
CREATE TABLE IF NOT EXISTS `activity_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `activity_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_images`
--

INSERT INTO `activity_images` (`id`, `activity_id`, `image`) VALUES
(1, 3, '1778147986_53607.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', 'pass', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 'JEE', 'JEE', 1, '2026-05-06 18:30:00', '2026-05-06 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

DROP TABLE IF EXISTS `course_details`;
CREATE TABLE IF NOT EXISTS `course_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int DEFAULT NULL,
  `why_title_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_1` text COLLATE utf8mb4_unicode_ci,
  `why_title_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_2` text COLLATE utf8mb4_unicode_ci,
  `why_title_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_3` text COLLATE utf8mb4_unicode_ci,
  `program_highlight` text COLLATE utf8mb4_unicode_ci,
  `highlight_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_title_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_1` text COLLATE utf8mb4_unicode_ci,
  `module_title_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_2` text COLLATE utf8mb4_unicode_ci,
  `module_title_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_3` text COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`id`, `course_id`, `why_title_1`, `why_description_1`, `why_title_2`, `why_description_2`, `why_title_3`, `why_description_3`, `program_highlight`, `highlight_image`, `module_title_1`, `module_description_1`, `module_title_2`, `module_description_2`, `module_title_3`, `module_description_3`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Labore qui qui velit', 'Cupidatat repudianda', 'Earum rerum quia deb', 'Eum pariatur Sint e', 'Obcaecati exercitati', 'Tempore cupidatat b', 'Asperiores ut commod', '', 'Sit aliquip sit su', 'Sit magni autem impe', 'Officia incidunt ut', 'Odit tenetur quibusd', 'Quae tempor perspici', 'Consequatur iusto mi', '2026-05-12', '2026-05-12'),
(2, NULL, 'Ratione qui animi a', 'Eu voluptate iusto i', 'Consectetur exceptur', 'Ut quis consectetur', 'Corrupti autem nequ', 'Laborum quo et eveni', 'Ipsam magni pariatur', '', 'Cum rerum architecto', 'Ea blanditiis ex ad', 'Porro quia sit mole', 'Aut dolor excepteur', 'Veniam dolor odit f', 'Odit neque dolor ips', '2026-05-12', '2026-05-12'),
(3, NULL, 'Maxime excepteur cup', 'Sapiente voluptatem', 'Eiusmod deserunt com', 'Dolore aperiam qui r', 'Commodo et reprehend', 'Eveniet officia in', 'Et placeat ullam ut', '', 'Rem cumque repudiand', 'Labore minus aut aut', 'Voluptatem tempora e', 'Enim lorem sint volu', 'Vel possimus atque', 'Voluptate voluptatem', '2026-05-12', '2026-05-12'),
(4, 4, 'Enim voluptas dolore', 'Possimus illum qua', 'Veniam obcaecati co', 'Tenetur do reprehend', 'Beatae numquam verit', 'Placeat reprehender', 'Ullam enim voluptate', '', 'Illum modi eos pos', 'Voluptatum sed rerum', 'Aperiam eaque est f', 'Enim dolor inventore', 'Autem eiusmod error', 'Excepturi dolore fac', '2026-05-12', '2026-05-12'),
(5, 4, 'Enim ea aliquid sint', 'Ut in pariatur Sunt', 'Culpa unde ipsum vo', 'Cumque ipsum in vero', 'Duis unde at porro q', 'Hic qui doloribus si', 'Id aut id autem inci', '', 'Inventore aute volup', 'Aut sequi irure solu', 'In non aliqua Esse', 'Accusamus harum tene', 'Est minim est volupt', 'A commodo libero non', '2026-05-12', '2026-05-12'),
(6, 4, 'Voluptatum mollit ve', 'Qui et et fugiat re', 'Iste provident simi', 'Ea fugit aut volupt', 'Itaque sunt voluptat', 'Ab officia quibusdam', 'Dolores voluptates e', '', 'Voluptates velit mag', 'Dolore nesciunt omn', 'Illum quam expedita', 'Enim reprehenderit e', 'Qui nisi consequat', 'Cum quis suscipit im', '2026-05-12', '2026-05-12'),
(9, 4, 'Maiores et eiusmod n', 'Enim eos odit conse', 'Sequi minima tempor', 'Magnam quae consequa', 'Consequatur repelle', 'Officia ut facere of', 'Reiciendis odio enim', '1778593139_53607.jpg', 'Velit impedit eius', 'Voluptas voluptatibu', 'Blanditiis nisi ulla', 'Quis ex blanditiis d', 'Labore aliquid conse', 'Velit itaque sed ve', '2026-05-12', '2026-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `extra_curricular_activities`
--

DROP TABLE IF EXISTS `extra_curricular_activities`;
CREATE TABLE IF NOT EXISTS `extra_curricular_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `occasion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extra_curricular_activities`
--

INSERT INTO `extra_curricular_activities` (`id`, `occasion`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Cricket Tournament', 'Cricket Tournament', '2026-05-07', '2026-05-07'),
(3, 'Trip', 'Trip', '2026-05-07', '2026-05-07');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Trip', 'Images of trip', '2026-05-07', '2026-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gallery_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `gallery_id`, `image`) VALUES
(4, 1, '1778591461_53607.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_toppers`
--

DROP TABLE IF EXISTS `monthly_toppers`;
CREATE TABLE IF NOT EXISTS `monthly_toppers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topper_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monthly_toppers`
--

INSERT INTO `monthly_toppers` (`id`, `student_name`, `course_name`, `description`, `created_at`, `updated_at`, `image`, `topper_date`) VALUES
(3, 'Rahul Verma', 'JEE', '', '2026-05-12', '2026-05-12', '1778572876_53607.jpg', '2023-06-22');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `year` year NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `course_id`, `year`, `created_at`, `updated_at`) VALUES
(4, 3, '2022', '2026-05-07', '2026-05-07'),
(5, 4, '2023', '2026-05-12', '2026-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `result_images`
--

DROP TABLE IF EXISTS `result_images`;
CREATE TABLE IF NOT EXISTS `result_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `result_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `result_images`
--

INSERT INTO `result_images` (`id`, `result_id`, `image`) VALUES
(4, 5, '1778594595_53607.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
CREATE TABLE IF NOT EXISTS `social_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `title`, `link`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Demo Tutorial', 'https://music.youtube.com/', 'Demo Tutorial', '2026-05-07', '2026-05-07'),
(2, 'Quia ut dignissimos', 'https://www.vulas.tv', 'Quia sunt ullam et c', '2026-05-12', '2026-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `social_link_images`
--

DROP TABLE IF EXISTS `social_link_images`;
CREATE TABLE IF NOT EXISTS `social_link_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `social_link_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_link_images`
--

INSERT INTO `social_link_images` (`id`, `social_link_id`, `image`) VALUES
(1, 2, '1778594746_53607.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
