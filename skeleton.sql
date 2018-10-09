/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(64) NOT NULL,
  `user_pass` char(32) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `display_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_login` (`user_login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `user_hash` */

DROP TABLE IF EXISTS `user_hash`;

CREATE TABLE `user_hash` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `ip_int` int(11) unsigned NOT NULL,
  `dt` int(11) NOT NULL,
  `hash` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `ip_int` (`ip_int`,`hash`),
  KEY `dt` (`dt`),
  CONSTRAINT `user_hash_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `user_rights` */

DROP TABLE IF EXISTS `user_rights`;

CREATE TABLE `user_rights` (
  `id_user` int(11) NOT NULL,
  `id_right` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_right`),
  KEY `id_right` (`id_right`),
  CONSTRAINT `user_rights_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_rights_ibfk_2` FOREIGN KEY (`id_right`) REFERENCES `user_rights_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_rights_list` */

DROP TABLE IF EXISTS `user_rights_list`;

CREATE TABLE `user_rights_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_name` char(16) NOT NULL,
  `con_act` char(32) NOT NULL,
  `controller` varchar(32) NOT NULL,
  `action` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `id_user` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_user`,`id_role`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`id_role`) REFERENCES `user_role_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_role_list` */

DROP TABLE IF EXISTS `user_role_list`;

CREATE TABLE `user_role_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `user_role_rights` */

DROP TABLE IF EXISTS `user_role_rights`;

CREATE TABLE `user_role_rights` (
  `id_role` int(11) NOT NULL,
  `id_right` int(11) NOT NULL,
  PRIMARY KEY (`id_role`,`id_right`),
  KEY `id_right` (`id_right`),
  CONSTRAINT `user_role_rights_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `user_role_list` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_role_rights_ibfk_2` FOREIGN KEY (`id_right`) REFERENCES `user_rights_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
