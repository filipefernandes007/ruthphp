/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50613
 Source Host           : localhost
 Source Database       : localdb_solar_system

 Target Server Type    : MySQL
 Target Server Version : 50613
 File Encoding         : utf-8

 Date: 05/26/2014 13:31:04 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `images`
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_planet` int(10) unsigned NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  KEY `id_planet` (`id_planet`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`id_planet`) REFERENCES `planet` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `images`
-- ----------------------------
BEGIN;
INSERT INTO `images` VALUES ('1', '1', 'earth001.jpg', 'Earth'), ('2', '3', 'jp001.jpg', 'Jupiter, the Big One!'), ('5', '2', 'mars002.jpg', 'Mars the Red Planet, The God of War!'), ('6', '2', 'mars003.jpg', 'Mars, what happen to you?'), ('8', '2', 'mars004.jpg', 'Mars is a desert!');
COMMIT;

-- ----------------------------
--  Table structure for `info_planet`
-- ----------------------------
DROP TABLE IF EXISTS `info_planet`;
CREATE TABLE `info_planet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `size` float unsigned DEFAULT NULL,
  `diameter` int(11) unsigned DEFAULT NULL,
  `translation` float unsigned DEFAULT NULL,
  `rotation` float unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `planet`
-- ----------------------------
DROP TABLE IF EXISTS `planet`;
CREATE TABLE `planet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `age` int(10) unsigned DEFAULT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `planet`
-- ----------------------------
BEGIN;
INSERT INTO `planet` VALUES ('1', 'Terra', 'earth001.jpg', null, 'Is the third planet from the Sun, and the densest and fifth-largest of the eight planets in the Solar System. It is also the largest of the Solar System\'s four terrestrial planets. It is sometimes referred to as the world or the Blue Planet ', null), ('2', 'Marte', 'mars002.jpg', null, 'Is the fourth planet from the Sun and the second smallest planet in the Solar System. Named after the Roman god of war, it is often described as the \"Red Planet\", as the iron oxide prevalent on its surface gives it a reddish appearance.', null), ('3', 'Jupiter', 'jp001.jpg', null, 'Is composed primarily of gaseous and liquid matter. It is the largest of four gas giants as well as the largest planet in the Solar System with a diameter of 142,984 km (88,846 mi) at its equator. The density of Jupiter, 1.326 g/cm3, is the second', null);
COMMIT;

-- ----------------------------
--  Table structure for `satellite`
-- ----------------------------
DROP TABLE IF EXISTS `satellite`;
CREATE TABLE `satellite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_planet` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_planet` (`id_planet`),
  CONSTRAINT `satellite_ibfk_1` FOREIGN KEY (`id_planet`) REFERENCES `planet` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `satellite`
-- ----------------------------
BEGIN;
INSERT INTO `satellite` VALUES ('1', '2', 'Deimos', 'Deimos001.jpg'), ('2', '2', 'Phobos', 'Phobos001.jpg');
COMMIT;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `role` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `users`
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('1', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2013-05-16 23:06:27', '0000-00-00 00:00:00', '0'), ('23', 'Leonor', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2013-05-16 23:10:49', '0000-00-00 00:00:00', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
