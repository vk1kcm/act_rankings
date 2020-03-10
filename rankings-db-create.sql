-- MySQL dump 10.13  Distrib 5.7.29, for FreeBSD12.0 (amd64)
--
-- Host: localhost    Database: actfa_rankings
-- ------------------------------------------------------
-- Server version	5.7.29-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED='239e3f70-2982-11e7-ad00-0022686d32bc:1-534921';

--
-- Table structure for table `comp`
--

DROP TABLE IF EXISTS `comp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comp` (
  `idcomp` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `weapon` enum('Foil','Epee','Sabre') NOT NULL,
  `category` enum('U9','U11','U13','U15','U17','U20','U23','Novice','Veteran','Open') DEFAULT NULL,
  `level` enum('Club','State','National','International') DEFAULT NULL,
  `date` date DEFAULT NULL,
  `isact` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idcomp`),
  KEY `idx_comp_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=535 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fencer`
--

DROP TABLE IF EXISTS `fencer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fencer` (
  `idfencer` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `club` varchar(45) DEFAULT NULL,
  `actfamember` date DEFAULT NULL,
  `name_alt` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idfencer`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2732 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `result` (
  `idresult` int(11) NOT NULL AUTO_INCREMENT,
  `fencer_id` int(11) NOT NULL,
  `comp_id` int(11) NOT NULL,
  `place` int(10) unsigned NOT NULL,
  `points` double DEFAULT NULL,
  `points2` double DEFAULT NULL,
  PRIMARY KEY (`idresult`),
  KEY `fencer_id_idx` (`fencer_id`),
  KEY `comp_id_idx` (`comp_id`),
  CONSTRAINT `comp_id` FOREIGN KEY (`comp_id`) REFERENCES `comp` (`idcomp`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fencer_id` FOREIGN KEY (`fencer_id`) REFERENCES `fencer` (`idfencer`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1444 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-10 17:04:38
