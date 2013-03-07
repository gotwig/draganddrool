SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `grid` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ownerid` int(11) DEFAULT NULL,
  `lastchange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ownerid` (`ownerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=226 ;

CREATE TABLE IF NOT EXISTS `gridentries` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `cssstyle` varchar(255) NOT NULL,
  `gridtable` int(255) NOT NULL,
  `datarow` int(11) NOT NULL,
  `datacolumn` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gridtable` (`gridtable`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2219 ;

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ban` tinyint(255) NOT NULL,
  `lastusedgrid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `lastusedgrid` (`lastusedgrid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;


ALTER TABLE `grid`
  ADD CONSTRAINT `grid_ibfk_1` FOREIGN KEY (`ownerid`) REFERENCES `login` (`id`);

ALTER TABLE `gridentries`
  ADD CONSTRAINT `gridentries_ibfk_1` FOREIGN KEY (`gridtable`) REFERENCES `grid` (`id`);

ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`lastusedgrid`) REFERENCES `grid` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
