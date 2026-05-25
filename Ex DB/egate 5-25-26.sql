/*
 Navicat Premium Dump SQL

 Source Server         : newlocal
 Source Server Type    : MySQL
 Source Server Version : 90700 (9.7.0)
 Source Host           : localhost:3306
 Source Schema         : egate

 Target Server Type    : MySQL
 Target Server Version : 90700 (9.7.0)
 File Encoding         : 65001

 Date: 25/05/2026 13:00:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('egate-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:6:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:4:\"code\";s:1:\"d\";s:9:\"parent_id\";s:1:\"e\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:38:{i:0;a:6:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Users\";s:1:\"c\";s:5:\"users\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:8;}}i:1;a:6:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"Create Users\";s:1:\"c\";s:12:\"users.create\";s:1:\"d\";i:1;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:8;}}i:2;a:6:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"Update Users\";s:1:\"c\";s:12:\"users.update\";s:1:\"d\";i:1;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:6:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"Delete Users\";s:1:\"c\";s:12:\"users.delete\";s:1:\"d\";i:1;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:6:{s:1:\"a\";i:9;s:1:\"b\";s:21:\"Update User Passwords\";s:1:\"c\";s:17:\"users.update.pass\";s:1:\"d\";i:1;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:6:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"View Users\";s:1:\"c\";s:10:\"users.view\";s:1:\"d\";i:1;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:8;}}i:6;a:6:{s:1:\"a\";i:11;s:1:\"b\";s:5:\"Roles\";s:1:\"c\";s:5:\"roles\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:6:{s:1:\"a\";i:17;s:1:\"b\";s:10:\"View Roles\";s:1:\"c\";s:10:\"roles.view\";s:1:\"d\";i:11;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:6:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"Create Roles\";s:1:\"c\";s:12:\"roles.create\";s:1:\"d\";i:11;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:6:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"Update Roles\";s:1:\"c\";s:12:\"roles.update\";s:1:\"d\";i:11;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:6:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"Delete Roles\";s:1:\"c\";s:12:\"roles.delete\";s:1:\"d\";i:11;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:6:{s:1:\"a\";i:21;s:1:\"b\";s:12:\"Student Logs\";s:1:\"c\";s:4:\"logs\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;i:4;i:9;}}i:12;a:6:{s:1:\"a\";i:22;s:1:\"b\";s:9:\"View Logs\";s:1:\"c\";s:9:\"logs.view\";s:1:\"d\";i:21;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;i:4;i:9;}}i:13;a:6:{s:1:\"a\";i:23;s:1:\"b\";s:4:\"Data\";s:1:\"c\";s:4:\"Data\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;i:4;i:9;}}i:14;a:6:{s:1:\"a\";i:24;s:1:\"b\";s:9:\"View Data\";s:1:\"c\";s:9:\"data.view\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;i:4;i:9;}}i:15;a:6:{s:1:\"a\";i:25;s:1:\"b\";s:11:\"Create Data\";s:1:\"c\";s:11:\"data.create\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:9;}}i:16;a:6:{s:1:\"a\";i:26;s:1:\"b\";s:11:\"Update Data\";s:1:\"c\";s:11:\"data.update\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:9;}}i:17;a:6:{s:1:\"a\";i:27;s:1:\"b\";s:11:\"Delete Data\";s:1:\"c\";s:11:\"data.delete\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:6:{s:1:\"a\";i:28;s:1:\"b\";s:10:\"Print Data\";s:1:\"c\";s:10:\"data.print\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:9;}}i:19;a:6:{s:1:\"a\";i:29;s:1:\"b\";s:11:\"Export Data\";s:1:\"c\";s:11:\"data.export\";s:1:\"d\";i:23;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:8;i:2;i:9;}}i:20;a:6:{s:1:\"a\";i:31;s:1:\"b\";s:11:\"Delete Logs\";s:1:\"c\";s:11:\"logs.delete\";s:1:\"d\";i:21;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:6:{s:1:\"a\";i:32;s:1:\"b\";s:10:\"Print Logs\";s:1:\"c\";s:10:\"logs.print\";s:1:\"d\";i:21;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:6;i:2;i:8;i:3;i:9;}}i:22;a:6:{s:1:\"a\";i:35;s:1:\"b\";s:11:\"Export Logs\";s:1:\"c\";s:11:\"export.logs\";s:1:\"d\";i:21;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:6;i:2;i:8;i:3;i:9;}}i:23;a:6:{s:1:\"a\";i:36;s:1:\"b\";s:8:\"Time Log\";s:1:\"c\";s:4:\"time\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;}}i:24;a:6:{s:1:\"a\";i:37;s:1:\"b\";s:2:\"In\";s:1:\"c\";s:7:\"time.in\";s:1:\"d\";i:36;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:8;}}i:25;a:6:{s:1:\"a\";i:40;s:1:\"b\";s:3:\"Out\";s:1:\"c\";s:8:\"time.out\";s:1:\"d\";i:36;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:8;}}i:26;a:6:{s:1:\"a\";i:41;s:1:\"b\";s:13:\"Employee Logs\";s:1:\"c\";s:5:\"emlog\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:5;i:2;i:8;i:3;i:9;}}i:27;a:6:{s:1:\"a\";i:42;s:1:\"b\";s:4:\"View\";s:1:\"c\";s:10:\"emlog.view\";s:1:\"d\";i:41;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:5;i:2;i:8;i:3;i:9;}}i:28;a:6:{s:1:\"a\";i:44;s:1:\"b\";s:5:\"Print\";s:1:\"c\";s:11:\"emlog.print\";s:1:\"d\";i:41;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:29;a:6:{s:1:\"a\";i:45;s:1:\"b\";s:6:\"Delete\";s:1:\"c\";s:12:\"emlog.delete\";s:1:\"d\";i:41;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:30;a:6:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"Setup Sched Schedule\";s:1:\"c\";s:13:\"setschedcehed\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:31;a:6:{s:1:\"a\";i:47;s:1:\"b\";s:4:\"View\";s:1:\"c\";s:18:\"setschedcehed.view\";s:1:\"d\";i:46;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:32;a:6:{s:1:\"a\";i:48;s:1:\"b\";s:6:\"Create\";s:1:\"c\";s:20:\"setschedcehed.create\";s:1:\"d\";i:46;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:33;a:6:{s:1:\"a\";i:49;s:1:\"b\";s:6:\"Update\";s:1:\"c\";s:20:\"setschedcehed.update\";s:1:\"d\";i:46;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:34;a:6:{s:1:\"a\";i:50;s:1:\"b\";s:6:\"Delete\";s:1:\"c\";s:20:\"setschedcehed.delete\";s:1:\"d\";i:46;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:35;a:6:{s:1:\"a\";i:51;s:1:\"b\";s:20:\"Setup Sched Employee\";s:1:\"c\";s:10:\"setschedem\";s:1:\"d\";N;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:36;a:6:{s:1:\"a\";i:52;s:1:\"b\";s:4:\"View\";s:1:\"c\";s:15:\"setschedem.view\";s:1:\"d\";i:51;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}i:37;a:6:{s:1:\"a\";i:53;s:1:\"b\";s:6:\"Update\";s:1:\"c\";s:17:\"setschedem.update\";s:1:\"d\";i:51;s:1:\"e\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:9;}}}s:5:\"roles\";a:5:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"e\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:5:\"Staff\";s:1:\"e\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:8:\"Employee\";s:1:\"e\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:5:\"Guard\";s:1:\"e\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:2:\"HR\";s:1:\"e\";s:3:\"web\";}}}', 1779775197);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_locks_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `control` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES (1, 'Manual Login', 1, '2026-05-19 14:04:40', '2026-05-25 12:58:02');
INSERT INTO `config` VALUES (2, 'RFID Login', 1, '2026-05-19 14:04:40', '2026-05-25 12:58:02');

-- ----------------------------
-- Table structure for egate_data
-- ----------------------------
DROP TABLE IF EXISTS `egate_data`;
CREATE TABLE `egate_data`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_number` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lrn` bigint UNSIGNED NULL DEFAULT NULL,
  `rfid` bigint NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sex` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `course` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `school_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `grade_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sched` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `egate_data_student_number_logged_at_index`(`student_number` ASC) USING BTREE,
  INDEX `egate_data_student_number_index`(`student_number` ASC) USING BTREE,
  INDEX `egate_data_lrn_index`(`lrn` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of egate_data
-- ----------------------------
INSERT INTO `egate_data` VALUES (1, '11', NULL, NULL, 'Maria, Santos, Dela Cruz', 1, NULL, NULL, 'Female', 'College of Computing Studies', 'BS Information Technology', NULL, '1st Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:54:55', NULL);
INSERT INTO `egate_data` VALUES (2, '22', NULL, NULL, 'John Paul, Villanueva, Reyes', 1, NULL, NULL, 'Male', 'College of Computing Studies', 'BS Computer Science', NULL, '2nd Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:54:56', NULL);
INSERT INTO `egate_data` VALUES (3, '33', NULL, 595930496, 'Angela, Lopez, Garcia', 2, NULL, NULL, 'Female', 'College of Engineering', 'BS Civil Engineering', NULL, '3rd Year', NULL, '2026-05-19 23:19:45', '2026-05-25 12:07:55', 1);
INSERT INTO `egate_data` VALUES (4, '66', 44, NULL, 'Carlo, Ramos, Mendoza', 1, NULL, NULL, 'Male', 'College of Business Administration', 'BS Accountancy', NULL, '4th Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:54:59', NULL);
INSERT INTO `egate_data` VALUES (5, '3762720653', 55, NULL, 'Bea, Fernandez, Torres', 1, NULL, NULL, 'Female', 'College of Education', 'BSEd English', NULL, '2nd Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:00', NULL);
INSERT INTO `egate_data` VALUES (6, '2026-00006', NULL, NULL, 'Ethan, Diaz, Navarro', 1, NULL, NULL, 'Male', 'College of Computing Studies', 'BS Information Systems', NULL, '1st Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:05', NULL);
INSERT INTO `egate_data` VALUES (7, '2026-00007', NULL, NULL, 'Sofia, Lim, Aquino', 2, NULL, NULL, 'Female', 'College of Nursing', 'BS Nursing', NULL, '3rd Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:23', NULL);
INSERT INTO `egate_data` VALUES (8, '2026-00008', NULL, NULL, 'Miguel, Perez, Castro', 2, NULL, NULL, 'Male', 'College of Arts and Sciences', 'BA Communication', NULL, '4th Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:21', NULL);
INSERT INTO `egate_data` VALUES (9, '2026-00009', NULL, NULL, 'Patricia, Ocampo, Luna', 2, NULL, NULL, 'Female', 'College of Engineering', 'BS Electrical Engineering', NULL, '2nd Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:17', NULL);
INSERT INTO `egate_data` VALUES (10, '2026-00010', NULL, NULL, 'Noah, Rivera, Bautista', 2, NULL, NULL, 'Male', 'College of Hospitality Management', 'BS Hospitality Management', NULL, '1st Year', NULL, '2026-05-19 23:19:45', '2026-05-22 10:55:15', NULL);
INSERT INTO `egate_data` VALUES (11, '2026-00011', NULL, NULL, 'Claire Manalo, Santiago', 2, NULL, NULL, NULL, 'College of Education', 'BEEd General Education', NULL, '4th Year', NULL, '2026-05-19 23:19:45', '2026-05-22 23:02:39', NULL);
INSERT INTO `egate_data` VALUES (12, '2026-00012', NULL, NULL, 'Adrian, Tan, Flores', 2, NULL, NULL, 'Male', 'College of Computing Studies', 'BS Computer Science', NULL, '3rd Year', NULL, '2026-05-19 23:19:45', '2026-05-25 11:45:40', 1);

-- ----------------------------
-- Table structure for egate_logs
-- ----------------------------
DROP TABLE IF EXISTS `egate_logs`;
CREATE TABLE `egate_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `egate_data_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT 2,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `egate_logs_egate_data_id_foreign`(`egate_data_id` ASC) USING BTREE,
  CONSTRAINT `egate_logs_egate_data_id_foreign` FOREIGN KEY (`egate_data_id`) REFERENCES `egate_data` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 392 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of egate_logs
-- ----------------------------
INSERT INTO `egate_logs` VALUES (1, 2, '22', 1, '2026-05-19 23:45:18', '2026-05-19 23:45:18');
INSERT INTO `egate_logs` VALUES (2, 1, '11', 1, '2026-05-19 23:45:19', '2026-05-19 23:45:19');
INSERT INTO `egate_logs` VALUES (3, 3, '33', 1, '2026-05-19 23:45:19', '2026-05-19 23:45:19');
INSERT INTO `egate_logs` VALUES (4, 1, '11', 1, '2026-05-19 23:45:21', '2026-05-19 23:45:21');
INSERT INTO `egate_logs` VALUES (5, 2, '22', 1, '2026-05-19 23:45:21', '2026-05-19 23:45:21');
INSERT INTO `egate_logs` VALUES (366, 4, '0595930496', 1, '2026-05-21 10:12:50', '2026-05-21 10:12:50');
INSERT INTO `egate_logs` VALUES (367, 4, '0595930496', 1, '2026-05-21 10:12:51', '2026-05-21 10:12:51');
INSERT INTO `egate_logs` VALUES (368, 4, '0595930496', 1, '2026-05-21 10:12:52', '2026-05-21 10:12:52');
INSERT INTO `egate_logs` VALUES (369, 4, '0595930496', 1, '2026-05-21 10:13:20', '2026-05-21 10:13:20');
INSERT INTO `egate_logs` VALUES (370, 4, '0595930496', 1, '2026-05-21 10:13:22', '2026-05-21 10:13:22');
INSERT INTO `egate_logs` VALUES (371, 4, '0595930496', 1, '2026-05-21 10:13:44', '2026-05-21 10:13:44');
INSERT INTO `egate_logs` VALUES (372, 4, '0595930496', 1, '2026-05-21 10:13:46', '2026-05-21 10:13:46');
INSERT INTO `egate_logs` VALUES (373, 4, '0595930496', 1, '2026-05-21 10:13:49', '2026-05-21 10:13:49');
INSERT INTO `egate_logs` VALUES (374, 4, '0595930496', 0, '2026-05-21 10:13:56', '2026-05-21 10:13:56');
INSERT INTO `egate_logs` VALUES (375, 4, '0595930496', 1, '2026-05-21 12:52:50', '2026-05-21 12:52:50');
INSERT INTO `egate_logs` VALUES (376, 4, '0595930496', 0, '2026-05-21 12:53:06', '2026-05-21 12:53:06');
INSERT INTO `egate_logs` VALUES (377, 1, '11', 1, '2026-05-22 01:54:12', '2026-05-22 01:54:12');
INSERT INTO `egate_logs` VALUES (378, 1, '11', 1, '2026-05-22 01:54:14', '2026-05-22 01:54:14');
INSERT INTO `egate_logs` VALUES (379, 2, '22', 1, '2026-05-22 01:54:16', '2026-05-22 01:54:16');
INSERT INTO `egate_logs` VALUES (380, 3, '33', 1, '2026-05-22 01:54:17', '2026-05-22 01:54:17');
INSERT INTO `egate_logs` VALUES (381, 1, '11', 0, '2026-05-22 01:56:17', '2026-05-22 01:56:17');
INSERT INTO `egate_logs` VALUES (382, 2, '22', 0, '2026-05-22 01:56:17', '2026-05-22 01:56:17');
INSERT INTO `egate_logs` VALUES (383, 3, '33', 0, '2026-05-22 01:56:18', '2026-05-22 01:56:18');
INSERT INTO `egate_logs` VALUES (384, 1, '11', 1, '2026-05-25 10:03:24', '2026-05-25 10:03:24');
INSERT INTO `egate_logs` VALUES (385, 2, '22', 1, '2026-05-25 10:03:25', '2026-05-25 10:03:25');
INSERT INTO `egate_logs` VALUES (386, 1, '11', 1, '2026-05-25 10:03:25', '2026-05-25 10:03:25');
INSERT INTO `egate_logs` VALUES (387, 2, '22', 1, '2026-05-25 10:03:26', '2026-05-25 10:03:26');
INSERT INTO `egate_logs` VALUES (388, 1, '11', 0, '2026-05-25 10:03:29', '2026-05-25 10:03:29');
INSERT INTO `egate_logs` VALUES (389, 2, '22', 0, '2026-05-25 10:03:29', '2026-05-25 10:03:29');
INSERT INTO `egate_logs` VALUES (390, 1, '11', 0, '2026-05-25 10:03:30', '2026-05-25 10:03:30');
INSERT INTO `egate_logs` VALUES (391, 2, '22', 0, '2026-05-25 10:03:30', '2026-05-25 10:03:30');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_05_15_000003_create_egate_logs_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_05_18_155527_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (6, '2026_05_18_162302_egate_logs', 1);
INSERT INTO `migrations` VALUES (7, '2026_05_18_200000_align_users_table_for_rbac', 1);
INSERT INTO `migrations` VALUES (8, '2026_05_19_000001_add_egate_data_id_to_egate_logs_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_05_19_000002_add_lrn_to_egate_data_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_05_19_000003_create_config_table', 1);
INSERT INTO `migrations` VALUES (11, '2026_05_19_000004_convert_egate_times_to_datetime', 1);
INSERT INTO `migrations` VALUES (12, '2026_05_21_000001_add_rfid_to_egate_data_table', 2);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (6, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (5, 'App\\Models\\User', 3);
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 4);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_code_guard_name_unique`(`code` ASC, `guard_name` ASC) USING BTREE,
  INDEX `permissions_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `permissions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'Users', 'users', NULL, 'web', '2026-05-19 14:03:31', '2026-05-19 14:03:31');
INSERT INTO `permissions` VALUES (4, 'Create Users', 'users.create', 1, 'web', '2026-05-19 14:03:31', '2026-05-19 14:03:31');
INSERT INTO `permissions` VALUES (5, 'Update Users', 'users.update', 1, 'web', '2026-05-19 14:03:31', '2026-05-19 14:03:31');
INSERT INTO `permissions` VALUES (6, 'Delete Users', 'users.delete', 1, 'web', '2026-05-19 14:03:31', '2026-05-19 14:03:31');
INSERT INTO `permissions` VALUES (9, 'Update User Passwords', 'users.update.pass', 1, 'web', '2026-05-21 01:30:40', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (10, 'View Users', 'users.view', 1, 'web', '2026-05-21 01:30:33', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (11, 'Roles', 'roles', NULL, 'web', '2026-05-21 01:30:25', '2026-05-21 01:30:29');
INSERT INTO `permissions` VALUES (17, 'View Roles', 'roles.view', 11, 'web', '2026-05-21 01:30:21', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (18, 'Create Roles', 'roles.create', 11, 'web', '2026-05-21 01:30:11', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (19, 'Update Roles', 'roles.update', 11, 'web', '2026-05-21 01:30:07', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (20, 'Delete Roles', 'roles.delete', 11, 'web', '2026-05-21 01:29:51', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (21, 'Student Logs', 'logs', NULL, 'web', '2026-05-21 01:29:55', '2026-05-22 10:44:06');
INSERT INTO `permissions` VALUES (22, 'View Logs', 'logs.view', 21, 'web', '2026-05-21 01:29:40', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (23, 'Data', 'Data', NULL, 'web', '2026-05-21 01:26:52', '2026-05-21 01:27:02');
INSERT INTO `permissions` VALUES (24, 'View Data', 'data.view', 23, 'web', '2026-05-21 01:27:05', '2026-05-21 02:59:39');
INSERT INTO `permissions` VALUES (25, 'Create Data', 'data.create', 23, 'web', '2026-05-21 01:26:45', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (26, 'Update Data', 'data.update', 23, 'web', '2026-05-21 01:27:37', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (27, 'Delete Data', 'data.delete', 23, 'web', '2026-05-21 01:28:13', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (28, 'Print Data', 'data.print', 23, 'web', '2026-05-21 01:29:31', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (29, 'Export Data', 'data.export', 23, 'web', '2026-05-21 01:29:22', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (31, 'Delete Logs', 'logs.delete', 21, 'web', '2026-05-21 01:32:58', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (32, 'Print Logs', 'logs.print', 21, 'web', '2026-05-21 01:33:04', '2026-05-21 03:02:42');
INSERT INTO `permissions` VALUES (35, 'Export Logs', 'export.logs', 21, 'web', '2026-05-21 03:10:36', '2026-05-21 03:10:36');
INSERT INTO `permissions` VALUES (36, 'Time Log', 'time', NULL, 'web', '2026-05-21 09:54:15', '2026-05-21 09:54:18');
INSERT INTO `permissions` VALUES (37, 'In', 'time.in', 36, 'web', '2026-05-21 09:54:22', '2026-05-21 09:54:25');
INSERT INTO `permissions` VALUES (40, 'Out', 'time.out', 36, 'web', '2026-05-21 09:55:53', '2026-05-25 12:51:56');
INSERT INTO `permissions` VALUES (41, 'Employee Logs', 'emlog', NULL, 'web', '2026-05-22 10:39:37', '2026-05-22 10:39:40');
INSERT INTO `permissions` VALUES (42, 'View', 'emlog.view', 41, 'web', '2026-05-22 10:43:25', '2026-05-22 10:43:25');
INSERT INTO `permissions` VALUES (44, 'Print', 'emlog.print', 41, 'web', '2026-05-22 10:45:09', '2026-05-22 10:45:09');
INSERT INTO `permissions` VALUES (45, 'Delete', 'emlog.delete', 41, 'web', '2026-05-22 10:45:36', '2026-05-22 10:45:36');
INSERT INTO `permissions` VALUES (46, 'Setup Sched Schedule', 'setschedcehed', NULL, 'web', '2026-05-25 11:59:49', '2026-05-25 11:59:49');
INSERT INTO `permissions` VALUES (47, 'View', 'setschedcehed.view', 46, 'web', '2026-05-25 12:00:10', '2026-05-25 12:00:17');
INSERT INTO `permissions` VALUES (48, 'Create', 'setschedcehed.create', 46, 'web', '2026-05-25 12:00:34', '2026-05-25 12:00:34');
INSERT INTO `permissions` VALUES (49, 'Update', 'setschedcehed.update', 46, 'web', '2026-05-25 12:00:48', '2026-05-25 12:00:48');
INSERT INTO `permissions` VALUES (50, 'Delete', 'setschedcehed.delete', 46, 'web', '2026-05-25 12:02:12', '2026-05-25 12:02:12');
INSERT INTO `permissions` VALUES (51, 'Setup Sched Employee', 'setschedem', NULL, 'web', '2026-05-25 12:07:01', '2026-05-25 12:07:01');
INSERT INTO `permissions` VALUES (52, 'View', 'setschedem.view', 51, 'web', '2026-05-25 12:07:14', '2026-05-25 12:07:14');
INSERT INTO `permissions` VALUES (53, 'Update', 'setschedem.update', 51, 'web', '2026-05-25 12:07:32', '2026-05-25 12:07:32');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (26, 1);
INSERT INTO `role_has_permissions` VALUES (27, 1);
INSERT INTO `role_has_permissions` VALUES (28, 1);
INSERT INTO `role_has_permissions` VALUES (29, 1);
INSERT INTO `role_has_permissions` VALUES (31, 1);
INSERT INTO `role_has_permissions` VALUES (32, 1);
INSERT INTO `role_has_permissions` VALUES (35, 1);
INSERT INTO `role_has_permissions` VALUES (36, 1);
INSERT INTO `role_has_permissions` VALUES (37, 1);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (21, 5);
INSERT INTO `role_has_permissions` VALUES (22, 5);
INSERT INTO `role_has_permissions` VALUES (23, 5);
INSERT INTO `role_has_permissions` VALUES (24, 5);
INSERT INTO `role_has_permissions` VALUES (36, 5);
INSERT INTO `role_has_permissions` VALUES (37, 5);
INSERT INTO `role_has_permissions` VALUES (40, 5);
INSERT INTO `role_has_permissions` VALUES (41, 5);
INSERT INTO `role_has_permissions` VALUES (42, 5);
INSERT INTO `role_has_permissions` VALUES (21, 6);
INSERT INTO `role_has_permissions` VALUES (22, 6);
INSERT INTO `role_has_permissions` VALUES (23, 6);
INSERT INTO `role_has_permissions` VALUES (24, 6);
INSERT INTO `role_has_permissions` VALUES (32, 6);
INSERT INTO `role_has_permissions` VALUES (35, 6);
INSERT INTO `role_has_permissions` VALUES (36, 6);
INSERT INTO `role_has_permissions` VALUES (37, 6);
INSERT INTO `role_has_permissions` VALUES (1, 8);
INSERT INTO `role_has_permissions` VALUES (4, 8);
INSERT INTO `role_has_permissions` VALUES (10, 8);
INSERT INTO `role_has_permissions` VALUES (21, 8);
INSERT INTO `role_has_permissions` VALUES (22, 8);
INSERT INTO `role_has_permissions` VALUES (23, 8);
INSERT INTO `role_has_permissions` VALUES (24, 8);
INSERT INTO `role_has_permissions` VALUES (25, 8);
INSERT INTO `role_has_permissions` VALUES (26, 8);
INSERT INTO `role_has_permissions` VALUES (28, 8);
INSERT INTO `role_has_permissions` VALUES (29, 8);
INSERT INTO `role_has_permissions` VALUES (32, 8);
INSERT INTO `role_has_permissions` VALUES (35, 8);
INSERT INTO `role_has_permissions` VALUES (36, 8);
INSERT INTO `role_has_permissions` VALUES (37, 8);
INSERT INTO `role_has_permissions` VALUES (40, 8);
INSERT INTO `role_has_permissions` VALUES (41, 8);
INSERT INTO `role_has_permissions` VALUES (42, 8);
INSERT INTO `role_has_permissions` VALUES (21, 9);
INSERT INTO `role_has_permissions` VALUES (22, 9);
INSERT INTO `role_has_permissions` VALUES (23, 9);
INSERT INTO `role_has_permissions` VALUES (24, 9);
INSERT INTO `role_has_permissions` VALUES (25, 9);
INSERT INTO `role_has_permissions` VALUES (26, 9);
INSERT INTO `role_has_permissions` VALUES (28, 9);
INSERT INTO `role_has_permissions` VALUES (29, 9);
INSERT INTO `role_has_permissions` VALUES (32, 9);
INSERT INTO `role_has_permissions` VALUES (35, 9);
INSERT INTO `role_has_permissions` VALUES (41, 9);
INSERT INTO `role_has_permissions` VALUES (42, 9);
INSERT INTO `role_has_permissions` VALUES (44, 9);
INSERT INTO `role_has_permissions` VALUES (45, 9);
INSERT INTO `role_has_permissions` VALUES (46, 9);
INSERT INTO `role_has_permissions` VALUES (47, 9);
INSERT INTO `role_has_permissions` VALUES (48, 9);
INSERT INTO `role_has_permissions` VALUES (49, 9);
INSERT INTO `role_has_permissions` VALUES (50, 9);
INSERT INTO `role_has_permissions` VALUES (51, 9);
INSERT INTO `role_has_permissions` VALUES (52, 9);
INSERT INTO `role_has_permissions` VALUES (53, 9);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Admin', 'web', '2026-05-19 23:19:45', '2026-05-21 13:23:59');
INSERT INTO `roles` VALUES (5, 'Employee', 'web', '2026-05-20 15:57:32', '2026-05-21 13:24:15');
INSERT INTO `roles` VALUES (6, 'Guard', 'web', '2026-05-21 02:52:48', '2026-05-21 02:52:48');
INSERT INTO `roles` VALUES (7, 'Student', 'web', '2026-05-21 13:24:22', '2026-05-21 13:24:22');
INSERT INTO `roles` VALUES (8, 'Staff', 'web', '2026-05-22 01:36:15', '2026-05-22 01:36:15');
INSERT INTO `roles` VALUES (9, 'HR', 'web', '2026-05-25 11:51:06', '2026-05-25 11:51:13');

-- ----------------------------
-- Table structure for sched_details
-- ----------------------------
DROP TABLE IF EXISTS `sched_details`;
CREATE TABLE `sched_details`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `schedule_id` int NOT NULL,
  `day` int NOT NULL,
  `am_in` time NULL DEFAULT NULL,
  `am_out` time NULL DEFAULT NULL,
  `pm_in` time NULL DEFAULT NULL,
  `pm_out` time NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `schedule_id`(`schedule_id` ASC, `day` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sched_details
-- ----------------------------
INSERT INTO `sched_details` VALUES (22, 1, 1, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (23, 1, 2, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (24, 1, 3, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (25, 1, 4, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (26, 1, 5, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (27, 1, 6, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');
INSERT INTO `sched_details` VALUES (28, 1, 7, '08:00:00', '12:00:00', '13:00:00', '17:00:00', '2026-05-25 12:08:20', '2026-05-25 12:08:20');

-- ----------------------------
-- Table structure for schedules
-- ----------------------------
DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of schedules
-- ----------------------------
INSERT INTO `schedules` VALUES (1, 'AJSKAHS', '2026-05-25 09:37:21', '2026-05-25 10:17:46');
INSERT INTO `schedules` VALUES (3, 'JAhkjhakjds', '2026-05-25 11:44:11', '2026-05-25 11:44:11');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('Mt2JGTFPhXQlm2p42Amsbv10SWQAt2acsQXumyU1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNTRzVHFuZU9mdVl0Nk1xemFLWFRCRjJ2dUpBRmVuUnhiOTczTEFHeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yb2xlcy9mZXRjaD9wYWdlPTEiO3M6NToicm91dGUiO3M6MTc6ImFkbWluLnJvbGVzLmZldGNoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1779688798);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'test@example.com', '$2y$12$S88gxvq3CmEyr8zyeDGWkuNM29C4kq05FY3gGImpGCqWY.ATF6dIy', '2026-05-22 01:37:28', '2026-05-22 01:37:28');
INSERT INTO `users` VALUES (2, 'guard', 'guard@gmail.com', '$2y$12$S88gxvq3CmEyr8zyeDGWkuNM29C4kq05FY3gGImpGCqWY.ATF6dIy', '2026-05-21 01:21:44', '2026-05-21 01:21:44');
INSERT INTO `users` VALUES (3, 'staff', 'staff@gmail.com', '$2y$12$S88gxvq3CmEyr8zyeDGWkuNM29C4kq05FY3gGImpGCqWY.ATF6dIy', '2026-05-21 02:54:22', '2026-05-21 02:54:22');

SET FOREIGN_KEY_CHECKS = 1;
