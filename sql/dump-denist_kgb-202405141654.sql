-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: mysql-denist.alwaysdata.net    Database: denist_kgb
-- ------------------------------------------------------
-- Server version	5.5.5-10.6.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actor`
--

DROP TABLE IF EXISTS `actor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actor` (
  `id_person` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `identificationCode` int(11) DEFAULT NULL,
  `id_country` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `actor_person_FK` (`id_person`),
  KEY `actor_country_FK` (`id_country`),
  CONSTRAINT `actor_country_FK` FOREIGN KEY (`id_country`) REFERENCES `country` (`id`),
  CONSTRAINT `actor_person_FK` FOREIGN KEY (`id_person`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actor`
--

LOCK TABLES `actor` WRITE;
/*!40000 ALTER TABLE `actor` DISABLE KEYS */;
INSERT INTO `actor` VALUES (2,2,'1879-10-26',65421,1),(3,3,'1913-02-07',45124,3),(4,4,'1902-02-24',2315,4),(5,5,'2023-10-22',6235,2);
/*!40000 ALTER TABLE `actor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `actor_speciality`
--

DROP TABLE IF EXISTS `actor_speciality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actor_speciality` (
  `id_actor` int(11) NOT NULL,
  `id_speciality` int(11) NOT NULL,
  PRIMARY KEY (`id_actor`,`id_speciality`),
  KEY `actor_speciality_speciality_FK` (`id_speciality`),
  CONSTRAINT `actor_speciality_actor_FK` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id`),
  CONSTRAINT `actor_speciality_speciality_FK` FOREIGN KEY (`id_speciality`) REFERENCES `speciality` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actor_speciality`
--

LOCK TABLES `actor_speciality` WRITE;
/*!40000 ALTER TABLE `actor_speciality` DISABLE KEYS */;
INSERT INTO `actor_speciality` VALUES (3,1),(5,3);
/*!40000 ALTER TABLE `actor_speciality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrator` (
  `id_person` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `insertAt` date NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  PRIMARY KEY (`id_person`,`id`),
  CONSTRAINT `administrator_person_FK` FOREIGN KEY (`id_person`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator`
--

LOCK TABLES `administrator` WRITE;
/*!40000 ALTER TABLE `administrator` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country`
--

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;
INSERT INTO `country` VALUES (1,'Russie','Russe'),(2,'Belgique','Belge'),(3,'Espagne','Espagnol'),(4,'Mexique','Mexicaine');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hideout`
--

DROP TABLE IF EXISTS `hideout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hideout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL,
  `id_country` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hideout_country_FK` (`id_country`),
  CONSTRAINT `hideout_country_FK` FOREIGN KEY (`id_country`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hideout`
--

LOCK TABLES `hideout` WRITE;
/*!40000 ALTER TABLE `hideout` DISABLE KEYS */;
INSERT INTO `hideout` VALUES (1,'4578','15 calle de la Paz','Appartement',4),(2,'2022','1 rue de la Paix','Chambre de bonne',2);
/*!40000 ALTER TABLE `hideout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mission`
--

DROP TABLE IF EXISTS `mission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `codeName` varchar(100) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `id_typemission` int(11) NOT NULL,
  `id_statut` int(11) NOT NULL,
  `id_country` int(11) NOT NULL,
  `id_speciality` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mission_typemission_FK` (`id_typemission`),
  KEY `mission_statut_FK` (`id_statut`),
  KEY `mission_country_FK` (`id_country`),
  KEY `mission_speciality_FK` (`id_speciality`),
  CONSTRAINT `mission_country_FK` FOREIGN KEY (`id_country`) REFERENCES `country` (`id`),
  CONSTRAINT `mission_speciality_FK` FOREIGN KEY (`id_speciality`) REFERENCES `speciality` (`id`),
  CONSTRAINT `mission_statut_FK` FOREIGN KEY (`id_statut`) REFERENCES `statut` (`id`),
  CONSTRAINT `mission_typemission_FK` FOREIGN KEY (`id_typemission`) REFERENCES `typemission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mission`
--

LOCK TABLES `mission` WRITE;
/*!40000 ALTER TABLE `mission` DISABLE KEYS */;
INSERT INTO `mission` VALUES (1,'Elimination de Léon Trotsky','Né le 26 octobre 1879 (7 novembre dans le calendrier grégorien) à Ianovka (alors dans l\'Empire russe, aujourd\'hui en Ukraine) et mort assassiné le 21 août 1940 à Mexico (Mexique), est un révolutionnaire communiste et homme politique russe, puis soviétique.\r\n\r\nMilitant social-démocrate puis marxiste plusieurs fois déporté en Sibérie ou exilé de Russie, militant du Parti ouvrier social-démocrate de Russie (POSDR) à partir de 1903, d\'abord menchevik, - c\'est comme tel qu\'il est président du soviet de Pétrograd lors de la révolution russe de 1905 -, il devient bolchevik, à partir de l\'été 1917, après son retour en Russie, et il est un des principaux acteurs, avec Vladimir Lénine, de la révolution d\'Octobre qui permet aux bolcheviks d\'arriver au pouvoir.    ','Kill Trotski','1929-02-01','1940-08-21',2,3,4,1);
/*!40000 ALTER TABLE `mission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mission_actor_role`
--

DROP TABLE IF EXISTS `mission_actor_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mission_actor_role` (
  `id_mission` int(11) NOT NULL,
  `id_actor` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  PRIMARY KEY (`id_mission`,`id_actor`,`id_role`),
  KEY `mission_actor_role_actor_FK` (`id_actor`),
  KEY `mission_actor_role_role_FK` (`id_role`),
  CONSTRAINT `mission_actor_role_actor_FK` FOREIGN KEY (`id_actor`) REFERENCES `actor` (`id`),
  CONSTRAINT `mission_actor_role_mission_FK` FOREIGN KEY (`id_mission`) REFERENCES `mission` (`id`),
  CONSTRAINT `mission_actor_role_role_FK` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mission_actor_role`
--

LOCK TABLES `mission_actor_role` WRITE;
/*!40000 ALTER TABLE `mission_actor_role` DISABLE KEYS */;
INSERT INTO `mission_actor_role` VALUES (1,2,3),(1,3,1),(1,4,2),(1,5,2),(1,5,3);
/*!40000 ALTER TABLE `mission_actor_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mission_hideout`
--

DROP TABLE IF EXISTS `mission_hideout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mission_hideout` (
  `id_mission` int(11) NOT NULL,
  `id_hideout` int(11) NOT NULL,
  PRIMARY KEY (`id_mission`,`id_hideout`),
  KEY `mission_hideout_hideout_FK` (`id_hideout`),
  CONSTRAINT `mission_hideout_hideout_FK` FOREIGN KEY (`id_hideout`) REFERENCES `hideout` (`id`),
  CONSTRAINT `mission_hideout_mission_FK` FOREIGN KEY (`id_mission`) REFERENCES `mission` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mission_hideout`
--

LOCK TABLES `mission_hideout` WRITE;
/*!40000 ALTER TABLE `mission_hideout` DISABLE KEYS */;
INSERT INTO `mission_hideout` VALUES (1,1);
/*!40000 ALTER TABLE `mission_hideout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (2,'Trotski','Léon'),(3,'Mercader','Ramón'),(4,'Rodriguez','José'),(5,'rtydy','rtyrt');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Agent'),(2,'Contact'),(3,'Cible');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `speciality`
--

DROP TABLE IF EXISTS `speciality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `speciality` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `speciality`
--

LOCK TABLES `speciality` WRITE;
/*!40000 ALTER TABLE `speciality` DISABLE KEYS */;
INSERT INTO `speciality` VALUES (1,'Assassinat ciblé'),(3,'Intimidation'),(4,'Torture');
/*!40000 ALTER TABLE `speciality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statut`
--

DROP TABLE IF EXISTS `statut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statut` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statut`
--

LOCK TABLES `statut` WRITE;
/*!40000 ALTER TABLE `statut` DISABLE KEYS */;
INSERT INTO `statut` VALUES (1,'A venir'),(2,'En cours'),(3,'Réussie'),(4,'En échec');
/*!40000 ALTER TABLE `statut` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `typemission`
--

DROP TABLE IF EXISTS `typemission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `typemission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeMission` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `typemission`
--

LOCK TABLES `typemission` WRITE;
/*!40000 ALTER TABLE `typemission` DISABLE KEYS */;
INSERT INTO `typemission` VALUES (1,'Surveillance'),(2,'Assassinat'),(3,'Infiltration');
/*!40000 ALTER TABLE `typemission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `roles` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'denist_kgb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-14 16:54:17
