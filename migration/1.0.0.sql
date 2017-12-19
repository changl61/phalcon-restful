/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50716
 Source Host           : localhost
 Source Database       : meetpanda

 Target Server Type    : MySQL
 Target Server Version : 50716
 File Encoding         : utf-8

 Date: 12/19/2017 23:00:03 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `dish`
-- ----------------------------
DROP TABLE IF EXISTS `dish`;
CREATE TABLE `dish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `price` decimal(10,0) NOT NULL COMMENT '价格',
  `createTime` datetime DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='菜品';

-- ----------------------------
--  Records of `dish`
-- ----------------------------
BEGIN;
INSERT INTO `dish` VALUES ('1', '鱼香肉丝2', '89', '2017-12-18 14:39:33', '2017-12-19 21:43:01'), ('2', '鱼香肉丝', '18', '2017-12-18 14:39:49', '2017-12-18 14:39:51'), ('3', '红烧鲫鱼', '30', '2017-12-18 14:40:19', '2017-12-18 14:40:21'), ('4', '油爆大虾', '40', '2017-12-18 14:40:55', '2017-12-18 14:40:57'), ('5', '鱼香肉丝1', '89', '2017-12-19 09:18:53', null);
COMMIT;

-- ----------------------------
--  Table structure for `privilege`
-- ----------------------------
DROP TABLE IF EXISTS `privilege`;
CREATE TABLE `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(255) NOT NULL COMMENT '资源',
  `fields` text,
  `search` enum('0','1') NOT NULL DEFAULT '0',
  `detail` enum('0','1') NOT NULL DEFAULT '0',
  `create` enum('0','1') NOT NULL DEFAULT '0',
  `update` enum('0','1') NOT NULL DEFAULT '0',
  `delete` enum('0','1') NOT NULL DEFAULT '0',
  `userRoleId` int(11) NOT NULL,
  `createTime` datetime DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userRoleId` (`userRoleId`),
  CONSTRAINT `privilege_ibfk_1` FOREIGN KEY (`userRoleId`) REFERENCES `user_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
--  Records of `privilege`
-- ----------------------------
BEGIN;
INSERT INTO `privilege` VALUES ('1', '/', null, '0', '1', '0', '0', '0', '1', null, null), ('2', '/', null, '0', '1', '0', '0', '0', '2', null, null), ('3', '/', null, '0', '1', '0', '0', '0', '3', null, null), ('4', '_token', null, '0', '1', '0', '0', '1', '1', null, null), ('5', '_token', null, '0', '1', '0', '0', '1', '2', null, null), ('6', '_token', null, '0', '1', '0', '0', '1', '3', null, null), ('7', 'dishes', null, '1', '1', '0', '0', '0', '2', null, null), ('8', 'dishes', null, '1', '1', '1', '1', '1', '3', null, null);
COMMIT;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `agent` tinytext,
  `token` varchar(255) DEFAULT NULL,
  `loginTime` datetime DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roleId` (`roleId`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `user_role` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
--  Records of `user`
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('1', '3', 'changl61', '18966668888', null, '123456', '1234', 'Apache-HttpClient/4.4.1 (Java/1.8.0_76-release)', 'bdc78le0htc4qe6duc3e0kt590', null, null, '2017-12-19 20:51:33');
COMMIT;

-- ----------------------------
--  Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `zhName` varchar(255) NOT NULL,
  `createTime` datetime DEFAULT NULL,
  `updateTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
--  Records of `user_role`
-- ----------------------------
BEGIN;
INSERT INTO `user_role` VALUES ('1', 'guest', '访客', '2017-11-05 21:37:41', null), ('2', 'user', '会员', '2017-11-05 21:38:12', null), ('3', 'admin', '管理员', '2017-11-05 21:38:59', null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
