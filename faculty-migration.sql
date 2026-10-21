-- Run this once on an EXISTING radiant_institute database
-- (phpMyAdmin -> radiant_institute -> SQL tab -> paste -> Go).
-- New installs don't need it: radiant_institute(2).sql already includes it.

-- 1) Faculty module table
CREATE TABLE IF NOT EXISTS `faculty` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) OPTIONAL: remove the old Social Links tables.
--    The admin no longer uses them, but this permanently deletes their rows,
--    so it is commented out. Un-comment only when you're sure.
-- DROP TABLE IF EXISTS `social_link_images`;
-- DROP TABLE IF EXISTS `social_links`;
