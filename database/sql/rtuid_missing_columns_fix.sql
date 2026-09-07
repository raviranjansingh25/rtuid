-- ============================================================
-- RTUID: Missing columns fix (phpMyAdmin / MySQL)
-- Database: rtuid
-- Run once in phpMyAdmin -> SQL tab -> Go
-- Safe: columns add only if missing
-- ============================================================

USE `rtuid`;

-- ----------------------------------------------------------
-- 1) tournaments table - district tournament flags
-- ----------------------------------------------------------
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'is_district_tournament') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `is_district_tournament` TINYINT(1) NOT NULL DEFAULT 0 AFTER `draw_sheet_verified`',
  'SELECT ''tournaments.is_district_tournament already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'district_id') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `district_id` VARCHAR(100) NULL AFTER `is_district_tournament`',
  'SELECT ''tournaments.district_id already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'district_apply_open') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `district_apply_open` TINYINT(1) NOT NULL DEFAULT 0 AFTER `district_id`',
  'SELECT ''tournaments.district_apply_open already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'district_draw_sheet_open') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `district_draw_sheet_open` TINYINT(1) NOT NULL DEFAULT 0 AFTER `district_apply_open`',
  'SELECT ''tournaments.district_draw_sheet_open already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'coach_apply_weight_open') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `coach_apply_weight_open` TINYINT(1) NOT NULL DEFAULT 0 AFTER `district_draw_sheet_open`',
  'SELECT ''tournaments.coach_apply_weight_open already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'athlete_apply_weight_open') = 0,
  'ALTER TABLE `tournaments` ADD COLUMN `athlete_apply_weight_open` TINYINT(1) NOT NULL DEFAULT 0 AFTER `coach_apply_weight_open`',
  'SELECT ''tournaments.athlete_apply_weight_open already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Backfill tournament flags from old columns (if exist)
UPDATE `tournaments`
SET
  `coach_apply_weight_open` = `allow_coach_weight`,
  `athlete_apply_weight_open` = `allow_athlete_weight`
WHERE EXISTS (
  SELECT 1 FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tournaments' AND COLUMN_NAME = 'allow_coach_weight'
);

UPDATE `tournaments`
SET
  `is_district_tournament` = 1,
  `district_id` = `district`
WHERE (`created_by_subadmin` IS NOT NULL)
   OR (`district` IS NOT NULL AND `district` <> '');

-- ----------------------------------------------------------
-- 2) admins table - can_apply_tournament
-- ----------------------------------------------------------
SET @sql = IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'admins' AND COLUMN_NAME = 'can_apply_tournament') = 0,
  'ALTER TABLE `admins` ADD COLUMN `can_apply_tournament` TINYINT(1) NOT NULL DEFAULT 0 AFTER `apply_tournament`',
  'SELECT ''admins.can_apply_tournament already exists'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE `admins`
SET `can_apply_tournament` = `apply_tournament`
WHERE EXISTS (
  SELECT 1 FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'admins' AND COLUMN_NAME = 'apply_tournament'
);

-- ----------------------------------------------------------
-- 3) admins - single district should NOT be all-district mode
-- ----------------------------------------------------------
UPDATE `admins`
SET `select_all_district` = 0
WHERE `district` IS NOT NULL
  AND `district` <> ''
  AND `district` <> 'all'
  AND `select_all_district` = 1;

-- ----------------------------------------------------------
-- Done
-- ----------------------------------------------------------
SELECT 'RTUID missing columns fix completed.' AS result;
