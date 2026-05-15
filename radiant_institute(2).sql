-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 15, 2026 at 10:52 AM
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
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 'JEE', 'JEE', 1, '2026-05-06 18:30:00', '2026-05-06 18:30:00'),
(5, 'NEET', 'NEET', 1, '2026-05-12 18:30:00', '2026-05-12 18:30:00'),
(6, 'MH-CET', 'MH-CET', 0, '2026-05-12 18:30:00', '2026-05-12 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `course_details`
--

DROP TABLE IF EXISTS `course_details`;
CREATE TABLE IF NOT EXISTS `course_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int DEFAULT NULL,
  `why_title_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `why_title_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `why_title_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `why_description_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `program_highlight` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `highlight_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_title_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `module_title_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `module_title_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_description_3` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_details`
--

INSERT INTO `course_details` (`id`, `course_id`, `why_title_1`, `why_description_1`, `why_title_2`, `why_description_2`, `why_title_3`, `why_description_3`, `program_highlight`, `highlight_image`, `module_title_1`, `module_description_1`, `module_title_2`, `module_description_2`, `module_title_3`, `module_description_3`, `created_at`, `updated_at`) VALUES
(10, 4, 'Rerum esse illo quo', 'Do non tempore volu', 'Vel natus dolores co', 'Tempora aut minim al', 'Repudiandae sint ull', 'In voluptatem obcaec', 'Aliqua Nisi obcaeca', '1778666325_53607.jpg', 'Rerum quis omnis ass', 'Soluta ipsam qui bla', 'Iste fugiat quisqua', 'Nisi ipsum voluptat', 'Rerum sapiente quisq', 'Inventore quo conseq', '2026-05-13', '2026-05-13'),
(12, 5, 'Dignissimos optio t', 'Ad omnis sint qui a', 'Velit harum et aut', 'Ut omnis perspiciati', 'Est vel libero odio', 'Cum perspiciatis ex', 'Velit ducimus face', '', 'Sit enim reprehende', 'Voluptatem sequi mol', 'Non non minus ullamc', 'Aliqua Quis magna h', 'Consequatur Fugiat', 'Est magna qui qui du', '2026-05-13', '2026-05-13'),
(11, 6, 'Beatae proident har', 'Similique sint aliq', 'Pariatur Modi in ul', 'Ullamco non fugiat', 'Fugiat dolores sint', 'Laboris ratione culp', 'Dolore est doloremqu', '', 'Quia eu eaque qui se', 'Soluta possimus et', 'Et quia est alias c', 'Repudiandae est aut', 'Est perferendis vol', 'Saepe perspiciatis', '2026-05-13', '2026-05-13');

-- --------------------------------------------------------

--
-- Table structure for table `course_images`
--

DROP TABLE IF EXISTS `course_images`;
CREATE TABLE IF NOT EXISTS `course_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_images`
--

INSERT INTO `course_images` (`id`, `course_id`, `image`) VALUES
(1, 8, '1778328710_img_10_68765efaa5f1a_10410.jpg'),
(2, 8, '1778328710_img_9_68765efaa5571_10410.jpg'),
(3, 5, '1778656021_img_9_68765efaa5571_10410.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `crash_courses`
--

DROP TABLE IF EXISTS `crash_courses`;
CREATE TABLE IF NOT EXISTS `crash_courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `duration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crash_courses`
--

INSERT INTO `crash_courses` (`id`, `title`, `start_date`, `duration`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 'MHT-CET', '2026-05-21', '3', 'description', '2026-05-12', '2026-05-12', 1),
(2, 'Odio minima laborum', '2001-02-21', 'Lorem excepteur ulla', 'Ad doloremque delect', '2026-05-12', '2026-05-12', 1),
(3, 'Et et omnis ab velit', '2024-02-10', 'Ut praesentium culpa', 'Rerum omnis nesciunt', '2026-05-12', '2026-05-12', 0),
(4, 'Rerum et in odit dol', '1991-02-24', 'Enim excepteur id no', 'Labore laborum incid', '2026-05-12', '2026-05-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `crash_course_highlights`
--

DROP TABLE IF EXISTS `crash_course_highlights`;
CREATE TABLE IF NOT EXISTS `crash_course_highlights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `crash_course_id` int NOT NULL,
  `highlight` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `crash_course_id` (`crash_course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crash_course_highlights`
--

INSERT INTO `crash_course_highlights` (`id`, `crash_course_id`, `highlight`, `created_at`) VALUES
(1, 1, 'dcdcfdc', '2026-05-12 10:23:55'),
(2, 1, 'ddfd', '2026-05-12 10:23:55'),
(3, 1, 'dcdcd', '2026-05-12 10:23:55'),
(4, 1, 'dvfdvdvf', '2026-05-12 10:23:55'),
(27, 4, 'fevevvf', '2026-05-12 11:12:27'),
(40, 2, 'Iusto beatae natus s', '2026-05-12 12:27:20'),
(39, 2, 'Odit inventore illo', '2026-05-12 12:27:20'),
(36, 3, 'Aliquam velit vel se', '2026-05-12 11:12:52'),
(35, 3, 'Praesentium mollitia', '2026-05-12 11:12:52'),
(34, 3, 'Incidunt debitis el', '2026-05-12 11:12:52'),
(33, 3, 'Quo ipsa ipsa mole', '2026-05-12 11:12:52'),
(32, 3, 'Reprehenderit volupt', '2026-05-12 11:12:52'),
(31, 3, 'Quia asperiores et u', '2026-05-12 11:12:52'),
(30, 3, 'Ut aspernatur alias', '2026-05-12 11:12:52'),
(26, 4, 'Provident sint quas', '2026-05-12 11:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `extra_curricular_activities`
--

DROP TABLE IF EXISTS `extra_curricular_activities`;
CREATE TABLE IF NOT EXISTS `extra_curricular_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `occasion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `student_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `course_id`, `year`, `created_at`, `updated_at`) VALUES
(6, 5, '2021', '2026-05-13', '2026-05-13'),
(5, 4, '2023', '2026-05-12', '2026-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `result_images`
--

DROP TABLE IF EXISTS `result_images`;
CREATE TABLE IF NOT EXISTS `result_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `result_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `result_images`
--

INSERT INTO `result_images` (`id`, `result_id`, `image`) VALUES
(4, 5, '1778594595_53607.jpg'),
(5, 6, '1778656059_img_4_68765efaa31f3_10410.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(3, 'JEE', 'JEE', '1778834658_53607.jpg', '2026-05-14 18:30:00', '2026-05-14 18:30:00'),
(4, 'MH-CET', '<p>MH-CET</p>', '1778840909_linkedIn cover.jpg', '2026-05-14 18:30:00', '2026-05-14 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
CREATE TABLE IF NOT EXISTS `social_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
