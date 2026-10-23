CREATE TABLE IF NOT EXISTS `merit_certificate_batches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_by` BIGINT UNSIGNED NULL,
  `recipient_type` VARCHAR(20) NOT NULL,
  `template` TINYINT NOT NULL DEFAULT 1,
  `course_title` VARCHAR(255) NOT NULL,
  `place` VARCHAR(255) NULL,
  `date_from` VARCHAR(255) NULL,
  `date_on` VARCHAR(255) NULL,
  `issue_date` DATE NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'issued',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `merit_certificates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_id` BIGINT UNSIGNED NOT NULL,
  `recipient_id` BIGINT UNSIGNED NOT NULL,
  `serial_no` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `merit_certificates_batch_recipient` (`batch_id`, `recipient_id`),
  KEY `merit_certificates_recipient` (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
