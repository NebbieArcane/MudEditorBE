-- MySQL dump 10.13  Distrib 5.7.15, for osx10.11 (x86_64)
--
-- Host: localhost    Database: mud
-- ------------------------------------------------------
-- Server version	5.7.15

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

--
-- Table structure for table `mobs`
--

DROP TABLE IF EXISTS `mobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobs` (
  `vnum` int(11) NOT NULL,
  `aliasList` json NOT NULL COMMENT 'json array',
  `shortDescription` varchar(255) NOT NULL,
  `longDescription` text NOT NULL,
  `detailedDescription` text NOT NULL,
  `actionBitvector` json NOT NULL COMMENT 'json array',
  `affectionBitvector` json NOT NULL COMMENT 'json array',
  `aligment` smallint(6) NOT NULL,
  `typeFlag` enum('N','A','B','L','S','D') NOT NULL,
  `numAttack` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `thac0` int(11) NOT NULL,
  `ac` int(11) NOT NULL,
  `maxHitPoints` int(11) NOT NULL,
  `bareHandDamage` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `xpBonus` int(11) NOT NULL,
  `race` enum('HUMAN','DEMON') NOT NULL DEFAULT 'HUMAN',
  `loadPosition` enum('STANDING','SLEEP') NOT NULL DEFAULT 'STANDING',
  `defaultPosition` enum('STANDING','SLEEP') NOT NULL DEFAULT 'STANDING',
  `sex` enum('FEMALE','MALE','NEUTRAL') NOT NULL DEFAULT 'FEMALE',
  `sameRoomSound` varchar(255) DEFAULT NULL,
  `adiacentRoomSound` varchar(255) DEFAULT NULL,
  `specialId` int(11) DEFAULT NULL,
  PRIMARY KEY (`vnum`),
  KEY `specialId` (`specialId`),
  CONSTRAINT `mobs_ibfk_1` FOREIGN KEY (`specialId`) REFERENCES `specials` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mobs`
--

LOCK TABLES `mobs` WRITE;
/*!40000 ALTER TABLE `mobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `mobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects` (
  `vnum` int(11) NOT NULL,
  `aliasList` text NOT NULL COMMENT 'json array',
  `shortDescription` varchar(255) NOT NULL,
  `longDescription` text NOT NULL,
  `actionDescription` varchar(255) NOT NULL,
  `typeFlag` varchar(255) NOT NULL COMMENT 'json array',
  `extraAffect` varchar(255) NOT NULL COMMENT 'json array',
  `wear` varchar(255) NOT NULL COMMENT 'json array',
  `value` varchar(255) NOT NULL COMMENT 'json object',
  `weigth` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `rent` int(11) NOT NULL,
  `extraDescriptions` text NOT NULL COMMENT 'json object',
  `affectFields` text NOT NULL COMMENT 'json object',
  `specialId` int(11) DEFAULT NULL,
  PRIMARY KEY (`vnum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rooms` (
  `zoneId` int(11) NOT NULL,
  `vnum` int(11) NOT NULL,
  `roomName` varchar(255) NOT NULL,
  `roomDescrioption` text NOT NULL,
  `roomBitvector` json NOT NULL COMMENT 'json format',
  `sectorType` json NOT NULL COMMENT 'json format',
  `exits` json NOT NULL COMMENT 'json format',
  `objInRoom` json DEFAULT NULL COMMENT 'json format',
  `mobInRoom` json DEFAULT NULL,
  `specialId` int(11) DEFAULT NULL,
  PRIMARY KEY (`zoneId`,`vnum`),
  KEY `specialId` (`specialId`),
  CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`zoneId`) REFERENCES `zoneList` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specials`
--

DROP TABLE IF EXISTS `specials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specType` enum('M','O','R') NOT NULL,
  `extra` json DEFAULT NULL COMMENT 'json array',
  PRIMARY KEY (`id`),
  CONSTRAINT `specials_ibfk_1` FOREIGN KEY (`id`) REFERENCES `rooms` (`specialId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specials`
--

LOCK TABLES `specials` WRITE;
/*!40000 ALTER TABLE `specials` DISABLE KEYS */;
/*!40000 ALTER TABLE `specials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zoneCommands`
--

DROP TABLE IF EXISTS `zoneCommands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zoneCommands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zoneCmd` enum('M','E','G','O','R','D') NOT NULL,
  `zoneId` int(11) NOT NULL,
  `vnum` int(11) NOT NULL,
  `cap` int(11) DEFAULT NULL,
  `room` int(11) DEFAULT NULL,
  `slot` int(11) DEFAULT NULL,
  `slotDesc` enum('Used as light','Worn on right finger','Worn on left finger','First object worn around neck','Second object worn around neck','Worn on body','Worn on head','Worn on legs','Worn on feet','Worn on hands','Worn on arms','Worn as shield','Worn about body','Worn around waist','Worn around right wrist','Worn around left wrist','Wielded as a weapon','Held') DEFAULT NULL,
  `intoObj` int(11) DEFAULT NULL,
  `exits` enum('North','East','South','West','Up','Down') DEFAULT NULL,
  `state` enum('Open','Closed','Locked') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zoneId` (`zoneId`),
  CONSTRAINT `zonecommands_ibfk_1` FOREIGN KEY (`zoneId`) REFERENCES `zoneList` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zoneCommands`
--

LOCK TABLES `zoneCommands` WRITE;
/*!40000 ALTER TABLE `zoneCommands` DISABLE KEYS */;
/*!40000 ALTER TABLE `zoneCommands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zoneList`
--

DROP TABLE IF EXISTS `zoneList`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zoneList` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(50) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `status` enum('EDIT','DEPLOYED_DEV','DEPLOYED_MASTER','RELEASED') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zoneList`
--

LOCK TABLES `zoneList` WRITE;
/*!40000 ALTER TABLE `zoneList` DISABLE KEYS */;
/*!40000 ALTER TABLE `zoneList` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zones` (
  `vnum` int(11) NOT NULL,
  `zoneId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lifeSpan` int(11) NOT NULL,
  `resetMode` enum('Never','IfEmpty','Always','') NOT NULL DEFAULT 'Never',
  PRIMARY KEY (`vnum`),
  UNIQUE KEY `vnum` (`vnum`),
  KEY `zoneId_F` (`zoneId`),
  CONSTRAINT `zoneId_F` FOREIGN KEY (`zoneId`) REFERENCES `zoneList` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-30 14:56:55
