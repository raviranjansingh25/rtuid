-- ============================================================
-- Best Test Certificate - Live Server SQL (phpMyAdmin)
-- Database select karke ye pura query run karo
-- Safe: IF NOT EXISTS / duplicate column ignore style
-- ============================================================

-- 1) Applications / batches (subadmin apply, admin grade)
CREATE TABLE IF NOT EXISTS `best_test_batches` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_by` BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'subadmin admins.id',
  `district` VARCHAR(255) DEFAULT NULL COMMENT 'district / tags.id',
  `exam_date` DATE DEFAULT NULL,
  `place` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'applied' COMMENT 'applied | graded',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Athletes inside each batch
CREATE TABLE IF NOT EXISTS `best_test_athletes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `belt_type` VARCHAR(100) NOT NULL,
  `grade` VARCHAR(50) DEFAULT NULL,
  `certificate_ready` TINYINT(4) NOT NULL DEFAULT 0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'applied' COMMENT 'applied | graded',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `best_test_athletes_batch_user` (`batch_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) Optional: admin panel permission (sirf non-super admins ke liye)
-- Super admin (id=1) ko zarurat nahi. Baaki admin ids apne hisaab se badal lo.
-- Agar group_permissions table nahi hai to ye part skip karo.

INSERT INTO `group_permissions` (`subadmin_id`, `controller`, `permission`, `created_at`, `updated_at`)
SELECT a.`id`, 'BestTestCertificateController', 1, NOW(), NOW()
FROM `admins` a
WHERE (a.`role` IS NULL OR a.`role` != 2)
  AND a.`id` > 1
  AND NOT EXISTS (
    SELECT 1
    FROM `group_permissions` g
    WHERE g.`subadmin_id` = a.`id`
      AND g.`controller` = 'BestTestCertificateController'
  );
