-- MySQL dump 10.13  Distrib 9.0.1, for Win64 (x86_64)
--
-- Host: localhost    Database: kost
-- ------------------------------------------------------
-- Server version	9.0.1

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
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tenant_id` int NOT NULL,
  `month` varchar(10) DEFAULT NULL,
  `amount` int NOT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `due_date` date NOT NULL,
  `year` varchar(5) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tenant_month` (`tenant_id`,`month`),
  KEY `idx_tenant_id` (`tenant_id`),
  KEY `idx_month` (`month`),
  KEY `idx_status` (`status`),
  KEY `idx_due_date` (`due_date`),
  CONSTRAINT `fk_bills_tenant_id` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bills`
--

LOCK TABLES `bills` WRITE;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
INSERT INTO `bills` VALUES (1,2,'Juni',900000,'unpaid','2025-06-16','2025',NULL),(2,2,'Mei',900000,'paid','2025-05-16','2025','2025-05-10'),(3,2,'April',900000,'paid','2025-04-16','2025','2025-04-15'),(5,1,'Juni',900000,'paid','2025-06-06','2025','2025-06-01'),(6,3,'Juni',950000,'paid','2025-06-01','2025','2025-06-01'),(7,4,'Juni',950000,'unpaid','2025-06-25','2025',NULL),(8,5,'Juni',900000,'paid','2025-06-10','2025','2025-06-07'),(9,6,'Juni',750000,'unpaid','2025-06-10','2025',NULL);
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kosts`
--

DROP TABLE IF EXISTS `kosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kosts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `owner_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_kosts_owner_id` (`owner_id`),
  CONSTRAINT `fk_kosts_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kosts`
--

LOCK TABLES `kosts` WRITE;
/*!40000 ALTER TABLE `kosts` DISABLE KEYS */;
INSERT INTO `kosts` VALUES (1,'Kost pak Andi','Jln.Medokan Asri Barat 3, Surabaya',1),(2,'Kost Bu Eka','Jln.Medokan Asri Barat 3, Surabaya',4);
/*!40000 ALTER TABLE `kosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_wallets`
--

DROP TABLE IF EXISTS `payment_wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_wallets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `wallet_type` enum('bank_transfer','virtual_account','qris','dompet_digital') DEFAULT NULL,
  `wallet_name` varchar(50) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  CONSTRAINT `payment_wallets_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_wallets`
--

LOCK TABLES `payment_wallets` WRITE;
/*!40000 ALTER TABLE `payment_wallets` DISABLE KEYS */;
INSERT INTO `payment_wallets` VALUES (1,1,'bank_transfer','Transfer Bank BRI','5169567068778269','Pak Andi Kost'),(3,1,'dompet_digital','Dana','082138536551','Pak Andi Dana'),(4,1,'dompet_digital','GoPay','082138536551','Pak Andi GoPay'),(5,1,'dompet_digital','OVO','082138536551','Pak Andi OVO');
/*!40000 ALTER TABLE `payment_wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bill_id` int NOT NULL,
  `paid_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amount_paid` int NOT NULL,
  `method` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bill_id` (`bill_id`),
  KEY `idx_paid_at` (`paid_at`),
  KEY `idx_method` (`method`),
  CONSTRAINT `fk_payments_bill_id` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kost_id` int NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rooms_kost_number` (`kost_id`,`room_number`),
  KEY `idx_rooms_kost_id` (`kost_id`),
  KEY `idx_rooms_price` (`price`),
  CONSTRAINT `fk_rooms_kost` FOREIGN KEY (`kost_id`) REFERENCES `kosts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,1,'A1',900000),(2,1,'A2',900000),(3,1,'A3',950000),(4,1,'A4',950000),(5,1,'A5',900000),(11,1,'B1',750000),(12,1,'B2',750000),(13,1,'B3',700000),(14,1,'B4',675000),(15,1,'B5',650000),(16,2,'A21',750000),(17,2,'A22',875000),(18,2,'A23',750000),(19,2,'A24',875000),(20,2,'A25',900000),(21,2,'B21',875000),(22,2,'B22',850000),(23,2,'B23',650000),(24,2,'B24',600000),(25,2,'B25',550000),(26,1,'C5',775000);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `userID` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_room_id` (`room_id`),
  KEY `idx_user_id` (`userID`),
  KEY `idx_start_date` (`start_date`),
  KEY `idx_end_date` (`end_date`),
  CONSTRAINT `fk_tenants_room_id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tenants_user_id` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,1,2,'2025-01-06',NULL),(2,2,3,'2025-01-16',NULL),(3,3,5,'2025-02-01',NULL),(4,4,6,'2025-02-25',NULL),(5,5,7,'2025-03-10',NULL),(6,12,8,'2025-06-10',NULL);
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','tenant') NOT NULL DEFAULT 'tenant',
  `username` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Pak Andi','pakandikostmedokan@gmail.com','pakandiowner','owner','Pak Andi','+62 812-3456-7891'),(2,'Bryan Benedict Bangun','bryan@gmail.com','bryanbenect','tenant','bryan','+62 812-3456-7890'),(3,'Bentar Segara Buana','segarabuana212@gmail.com','segarabuana212','tenant','Segaara','+62 821-3853-6551'),(4,'Bu Eka','buekakostmedokan@gmail.com','buekaowner','owner','bu eka','+62 815-2341-6545'),(5,'Pandu Setya Permana','pandusetya@gmail.com','pandusetya123','tenant','pandu','+62 812-9119-5247'),(6,'Suki Sukirman','sukirman123@gmail.com','sukirman123','tenant','Suki','+62 823-2969-9739'),(7,'Hermanto Suyono','hermansuryo@gmail.com','hermansuryono','tenant','Herman S','+62 823-2430-5384'),(8,'Muhammad Afriaji','segaraaji@gmail.com','segaraaji','tenant','Aji','081295112814');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-12  7:34:45
