/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Version : 50632
 Source Host           : localhost
 Source Database       : test

 Target Server Version : 50632
 File Encoding         : utf-8

 Date: 08/19/2017 12:11:43 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `gender` int(11) NOT NULL DEFAULT '0',
  `account` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `users`
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('1', 'li ju', '0', '35343'), ('2', 'wen190', '0', '3534343434');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
