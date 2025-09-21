-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: project_bimbel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `about_us`
--

DROP TABLE IF EXISTS `about_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_us` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_us`
--

LOCK TABLES `about_us` WRITE;
/*!40000 ALTER TABLE `about_us` DISABLE KEYS */;
INSERT INTO `about_us` VALUES (3,'About Us','Petpid Education merupakan bimbingan belajar yang berdiri sejak tahun 2020. Sesuai dengan visi \"Bimbingan Belajar dalam Berakhlak dan  Berprestasi\", petpid education memberikan wadah dan bimbingan terhadap siswa SD,SMP,SMA,SMK, hingga GAPYEAR untuk berprestasi dibidang akademik dengan tutor yang berpengalaman dibidangnya. \r\n\r\nLokasi  1 : GKB, Jalan Kudus Nomor 10, Yosowilangun, Manyar, Gresik.\r\nLokasi  2 : GKB, Jalan Pati Nomor 39, Yosowilangun, Manyar, Gresik.\r\nWA : +62 895-0120-6226\r\nIG : petpid.education','about-us-images/3LOBsBCRDSWIgRJl1yxMJeIQyc4bnHbQr9enL1Nj.jpg','2025-09-14 11:54:51','2025-09-14 11:54:51');
/*!40000 ALTER TABLE `about_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_goals`
--

DROP TABLE IF EXISTS `company_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_goals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('visi','misi','tujuan') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_goals`
--

LOCK TABLES `company_goals` WRITE;
/*!40000 ALTER TABLE `company_goals` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jawaban_pesertas`
--

DROP TABLE IF EXISTS `jawaban_pesertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jawaban_pesertas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paket_tryout_id` bigint(20) unsigned NOT NULL,
  `soal_id` bigint(20) unsigned NOT NULL,
  `jawaban_peserta` varchar(255) NOT NULL,
  `waktu_pengerjaan` int(11) DEFAULT NULL,
  `apakah_benar` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jawaban_pesertas_paket_tryout_id_foreign` (`paket_tryout_id`),
  KEY `jawaban_pesertas_soal_id_foreign` (`soal_id`),
  KEY `jawaban_pesertas_student_id_foreign` (`student_id`),
  CONSTRAINT `jawaban_pesertas_paket_tryout_id_foreign` FOREIGN KEY (`paket_tryout_id`) REFERENCES `paket_tryout` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_pesertas_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_pesertas_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban_pesertas`
--

LOCK TABLES `jawaban_pesertas` WRITE;
/*!40000 ALTER TABLE `jawaban_pesertas` DISABLE KEYS */;
INSERT INTO `jawaban_pesertas` VALUES (1,2,3,'<p>Test</p>',NULL,1,'2025-09-14 02:07:24','2025-09-14 02:07:24',1),(2,2,2,'<p>Test</p>',NULL,1,'2025-09-14 02:07:28','2025-09-14 02:07:28',1),(3,2,3,'<p>Test</p>',NULL,1,'2025-09-14 02:15:40','2025-09-14 02:15:40',2),(4,2,2,'<p>Test</p>',NULL,1,'2025-09-14 02:15:44','2025-09-14 02:15:44',2),(5,2,2,'<p>Test</p>',NULL,1,'2025-09-14 02:18:57','2025-09-14 02:18:57',3),(6,2,3,'<p>Test</p>',NULL,1,'2025-09-14 02:19:00','2025-09-14 02:19:00',3),(7,2,2,'<p>Test</p>',NULL,1,'2025-09-14 02:19:24','2025-09-14 02:19:24',4),(8,2,3,'<p>Test</p>',NULL,1,'2025-09-14 02:19:29','2025-09-14 02:19:29',4),(9,3,3,'',NULL,0,'2025-09-14 03:35:02','2025-09-14 03:35:02',5),(10,3,2,'<p>Test</p>',NULL,1,'2025-09-14 03:35:07','2025-09-14 03:35:07',5),(11,3,2,'<p>Test</p>',NULL,1,'2025-09-14 03:36:02','2025-09-14 03:36:02',6),(14,8,2,'<p>Test</p>',NULL,1,'2025-09-14 04:03:08','2025-09-14 04:03:08',8),(15,8,2,'<p>Test</p>',NULL,1,'2025-09-14 04:04:02','2025-09-14 04:04:02',9),(16,10,3,'<p>Test</p>',NULL,1,'2025-09-14 04:05:55','2025-09-14 04:07:00',10),(17,10,2,'<p>Test</p>',NULL,1,'2025-09-14 04:07:58','2025-09-14 04:07:58',10),(18,9,3,'<p>Test</p>',NULL,1,'2025-09-14 05:00:07','2025-09-14 05:00:07',11),(19,9,2,'<p>Test</p>',NULL,1,'2025-09-14 05:00:10','2025-09-14 05:00:10',11),(20,2,3,'',NULL,0,'2025-09-14 05:00:37','2025-09-14 05:00:37',12),(21,2,2,'',NULL,0,'2025-09-14 05:02:28','2025-09-14 05:02:28',12),(22,11,3,'<p>Test</p>',NULL,1,'2025-09-14 05:56:31','2025-09-14 05:56:31',14),(23,11,2,'<p>Test</p>',NULL,1,'2025-09-14 05:56:46','2025-09-14 05:56:52',14),(24,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 05:56:46','2025-09-14 05:56:52',14),(25,10,3,'',NULL,0,'2025-09-14 07:33:58','2025-09-14 07:35:01',15),(26,10,2,'',NULL,0,'2025-09-14 07:37:57','2025-09-14 07:37:57',15),(27,11,3,'<p>Test</p>',NULL,1,'2025-09-14 08:30:10','2025-09-14 08:30:10',16),(28,11,2,'',NULL,0,'2025-09-14 08:30:19','2025-09-14 08:30:19',16),(29,11,4,'',NULL,0,'2025-09-14 08:30:19','2025-09-14 08:30:19',16),(30,11,3,'<p>Test</p>',NULL,1,'2025-09-14 08:35:32','2025-09-14 08:35:32',17),(31,11,2,'',NULL,0,'2025-09-14 08:45:37','2025-09-14 08:45:37',17),(32,11,4,'',NULL,0,'2025-09-14 08:45:37','2025-09-14 08:45:37',17),(33,11,3,'<p>Test</p>',NULL,1,'2025-09-14 08:45:55','2025-09-14 08:45:55',18),(34,11,2,'',NULL,0,'2025-09-14 08:51:25','2025-09-14 08:51:25',18),(35,11,4,'',NULL,0,'2025-09-14 08:51:25','2025-09-14 08:51:25',18),(36,11,3,'<p>Test</p>',NULL,1,'2025-09-14 08:53:03','2025-09-14 08:53:03',19),(37,11,2,'',NULL,0,'2025-09-14 09:02:59','2025-09-14 09:02:59',19),(38,11,4,'',NULL,0,'2025-09-14 09:02:59','2025-09-14 09:02:59',19),(39,11,3,'<p>Test</p>',NULL,1,'2025-09-14 09:03:29','2025-09-14 09:03:29',20),(40,11,2,'',NULL,0,'2025-09-14 09:11:27','2025-09-14 09:11:27',20),(41,11,4,'',NULL,0,'2025-09-14 09:11:27','2025-09-14 09:11:27',20),(42,11,3,'<p>Test</p>',NULL,1,'2025-09-14 09:29:18','2025-09-14 09:29:18',21),(43,11,2,'<p>Test</p>',NULL,1,'2025-09-14 09:37:01','2025-09-14 09:37:01',21),(44,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 09:37:01','2025-09-14 09:37:01',21),(45,11,3,'<p>Test</p>',NULL,1,'2025-09-14 09:52:23','2025-09-14 09:52:23',22),(46,11,2,'<p>Test</p>',NULL,1,'2025-09-14 09:52:28','2025-09-14 09:52:28',22),(47,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 09:52:28','2025-09-14 09:52:28',22),(48,11,3,'<p>Test</p>',NULL,1,'2025-09-14 13:40:01','2025-09-14 13:40:01',23),(49,11,2,'<p>Test</p>',NULL,1,'2025-09-14 13:40:06','2025-09-14 13:40:06',23),(50,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 13:40:06','2025-09-14 13:40:06',23),(51,11,3,'<p>Test</p>',NULL,1,'2025-09-14 13:41:59','2025-09-14 13:41:59',24),(52,11,2,'<p>Test</p>',NULL,1,'2025-09-14 13:42:04','2025-09-14 13:42:04',24),(53,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 13:42:04','2025-09-14 13:42:04',24),(54,11,3,'<p>Test</p>',NULL,1,'2025-09-14 13:54:28','2025-09-14 13:54:28',25),(55,11,2,'',NULL,0,'2025-09-14 14:04:38','2025-09-14 14:04:38',25),(56,11,4,'',NULL,0,'2025-09-14 14:04:38','2025-09-14 14:04:38',25),(57,11,2,'',NULL,0,'2025-09-14 14:09:43','2025-09-14 14:09:43',26),(58,11,4,'',NULL,0,'2025-09-14 14:09:43','2025-09-14 14:09:43',26),(59,11,3,'',NULL,0,'2025-09-14 14:09:45','2025-09-14 14:09:45',26),(60,10,3,'',NULL,0,'2025-09-14 14:14:00','2025-09-14 14:14:00',27),(61,10,2,'',NULL,0,'2025-09-14 14:16:00','2025-09-14 14:16:00',27),(62,10,4,'',NULL,0,'2025-09-14 14:16:00','2025-09-14 14:16:00',27),(63,11,3,'<p>Test</p>',NULL,1,'2025-09-14 14:25:53','2025-09-14 14:25:53',28),(64,11,2,'<p>Test</p>',NULL,1,'2025-09-14 14:26:02','2025-09-14 14:26:02',28),(65,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 14:26:02','2025-09-14 14:26:02',28),(66,10,3,'',NULL,0,'2025-09-14 14:29:00','2025-09-14 14:29:00',29),(67,10,2,'',NULL,0,'2025-09-14 14:31:00','2025-09-14 14:31:00',29),(68,10,4,'',NULL,0,'2025-09-14 14:31:00','2025-09-14 14:31:00',29),(69,11,3,'<p>Test</p>',NULL,1,'2025-09-14 14:37:08','2025-09-14 14:38:52',30),(70,11,2,'<p>Test</p>',NULL,1,'2025-09-14 14:37:17','2025-09-14 14:42:51',30),(71,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 14:37:18','2025-09-14 14:42:51',30),(72,11,3,'<p>Test</p>',NULL,1,'2025-09-14 14:43:57','2025-09-14 14:44:00',31),(73,11,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 14:44:03','2025-09-14 14:52:47',31),(74,11,2,'[\"<p>Test1<\\/p>\",\"<p>Test2<\\/p>\",\"<p>Test3<\\/p>\"]',NULL,0,'2025-09-14 14:46:25','2025-09-14 14:52:47',31),(75,10,3,'',NULL,0,'2025-09-16 11:11:00','2025-09-16 11:11:00',32);
/*!40000 ALTER TABLE `jawaban_pesertas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jawaban_ulangans`
--

DROP TABLE IF EXISTS `jawaban_ulangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jawaban_ulangans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ulangan_session_id` bigint(20) unsigned NOT NULL,
  `soal_id` bigint(20) unsigned NOT NULL,
  `pilihan_jawaban_id` bigint(20) unsigned DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jawaban_ulangans_ulangan_session_id_foreign` (`ulangan_session_id`),
  KEY `jawaban_ulangans_soal_id_foreign` (`soal_id`),
  KEY `jawaban_ulangans_pilihan_jawaban_id_foreign` (`pilihan_jawaban_id`),
  CONSTRAINT `jawaban_ulangans_pilihan_jawaban_id_foreign` FOREIGN KEY (`pilihan_jawaban_id`) REFERENCES `pilihan_jawaban` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jawaban_ulangans_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_ulangans_ulangan_session_id_foreign` FOREIGN KEY (`ulangan_session_id`) REFERENCES `ulangan_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban_ulangans`
--

LOCK TABLES `jawaban_ulangans` WRITE;
/*!40000 ALTER TABLE `jawaban_ulangans` DISABLE KEYS */;
/*!40000 ALTER TABLE `jawaban_ulangans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_pelajaran`
--

DROP TABLE IF EXISTS `mata_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mata_pelajaran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_mapel` varchar(255) NOT NULL,
  `jenjang_pendidikan` varchar(255) DEFAULT NULL,
  `is_wajib` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_pelajaran`
--

LOCK TABLES `mata_pelajaran` WRITE;
/*!40000 ALTER TABLE `mata_pelajaran` DISABLE KEYS */;
INSERT INTO `mata_pelajaran` VALUES (1,'Matematika','SD',1,'2025-09-11 16:29:56','2025-09-11 16:29:56'),(2,'Bahasa Indonesia','SD',1,'2025-09-11 16:36:24','2025-09-11 16:36:24');
/*!40000 ALTER TABLE `mata_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_08_24_065517_create_mata_pelajaran_table',1),(5,'2025_08_24_065718_create_soal_table',1),(6,'2025_08_24_065720_create_pilihan_jawaban_table',1),(7,'2025_08_24_082930_create_paket_tryout_table',1),(8,'2025_08_24_083001_create_paket_tryout_soal_table',1),(9,'2025_08_24_085306_add_status_to_soal_table',1),(10,'2025_08_24_085340_create_paket_mapel_table',1),(11,'2025_08_24_085350_drop_paket_tryout_soal_table',1),(12,'2025_08_24_112439_create_jawaban_pesertas_table',1),(13,'2025_08_24_115927_add_gambar_path_to_soal_and_pilihan_jawaban_table',1),(14,'2025_08_24_140511_create_students_table',1),(15,'2025_08_24_140601_update_jawaban_pesertas_table',1),(16,'2025_08_25_114808_add_jenjang_pendidikan_to_mata_pelajaran_table',1),(17,'2025_09_06_094131_modify_tipe_soal_in_soal_table',1),(18,'2025_09_07_000132_create_paket_tryout_soal_table',1),(19,'2025_09_07_015731_add_event_schedule_to_paket_tryout_table',1),(20,'2025_09_07_113619_add_selection_rules_to_paket_tryout_table',1),(21,'2025_09_07_115956_add_bobot_to_paket_tryout_soal_table',1),(22,'2025_09_07_122035_change_bobot_column_type_in_paket_tryout_soal_table',1),(23,'2025_09_07_193749_create_slider_images_table',1),(24,'2025_09_08_165709_create_testimonials_table',1),(25,'2025_09_08_180426_create_company_goals_table',1),(26,'2025_09_11_181612_add_ulangan_features_to_tables',1),(27,'2025_09_11_181618_add_ulangan_fields_to_students_and_soal_table',1),(28,'2025_09_11_192830_create_ulangans_table',1),(29,'2025_09_11_192836_create_ulangan_soal_table',1),(30,'2025_09_11_192843_create_ulangan_sessions_table',1),(31,'2025_09_11_192859_create_jawaban_ulangans_table',1),(32,'2025_09_12_221503_create_about_us_table',2),(33,'2025_09_14_081950_add_nama_sekolah_to_ulangan_sessions_table',3),(34,'2025_09_14_224955_buat_tabel_soal_pernyataan',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paket_mapel`
--

DROP TABLE IF EXISTS `paket_mapel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paket_mapel` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paket_tryout_id` bigint(20) unsigned NOT NULL,
  `mata_pelajaran_id` bigint(20) unsigned NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `durasi_menit` int(11) NOT NULL COMMENT 'Durasi pengerjaan per mata pelajaran',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paket_mapel_paket_tryout_id_foreign` (`paket_tryout_id`),
  KEY `paket_mapel_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  CONSTRAINT `paket_mapel_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paket_mapel_paket_tryout_id_foreign` FOREIGN KEY (`paket_tryout_id`) REFERENCES `paket_tryout` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_mapel`
--

LOCK TABLES `paket_mapel` WRITE;
/*!40000 ALTER TABLE `paket_mapel` DISABLE KEYS */;
INSERT INTO `paket_mapel` VALUES (2,2,1,0,2,NULL,NULL),(3,2,2,1,15,NULL,NULL),(4,3,1,0,1,NULL,NULL),(5,3,2,1,1,NULL,NULL),(12,7,1,0,1,NULL,NULL),(13,7,2,1,1,NULL,NULL),(14,8,1,0,1,NULL,NULL),(15,8,2,1,1,NULL,NULL),(16,9,1,0,2,NULL,NULL),(17,9,2,1,1,NULL,NULL),(18,10,1,0,2,NULL,NULL),(19,10,2,1,2,NULL,NULL),(20,11,1,0,15,NULL,NULL),(21,11,2,1,15,NULL,NULL);
/*!40000 ALTER TABLE `paket_mapel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paket_tryout`
--

DROP TABLE IF EXISTS `paket_tryout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paket_tryout` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guru_id` bigint(20) unsigned NOT NULL,
  `kode_soal` varchar(255) NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `tipe_paket` enum('tryout','ulangan','event') NOT NULL DEFAULT 'tryout',
  `deskripsi` text DEFAULT NULL,
  `min_wajib` int(11) DEFAULT NULL,
  `max_opsional` int(11) DEFAULT NULL,
  `durasi_menit` int(11) NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paket_tryout_kode_soal_unique` (`kode_soal`),
  KEY `paket_tryout_guru_id_foreign` (`guru_id`),
  CONSTRAINT `paket_tryout_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_tryout`
--

LOCK TABLES `paket_tryout` WRITE;
/*!40000 ALTER TABLE `paket_tryout` DISABLE KEYS */;
INSERT INTO `paket_tryout` VALUES (2,1,'5JBJXB','Tryout SD','tryout','2ds',2,2,17,NULL,'published','2025-09-14 02:03:45','2025-09-14 04:10:05'),(3,1,'57EH50','Tryout 2025 SMP E2','event','wewe',1,2,2,'2025-09-14 10:34:00','published','2025-09-14 03:31:51','2025-09-14 03:31:51'),(7,1,'CH3ROP','Tryout SD','event','121',1,2,2,'2025-09-14 10:00:00','published','2025-09-14 03:59:03','2025-09-14 03:59:03'),(8,1,'UGGA97','Tryout SD','event','sasas',1,2,2,'2025-09-14 11:02:00','published','2025-09-14 04:00:00','2025-09-14 04:00:00'),(9,1,'KYEQAG','Tryout SD','tryout','dss',1,2,3,NULL,'published','2025-09-14 04:04:51','2025-09-14 04:04:51'),(10,1,'F1NFVW','2','event','23',1,1,4,'2025-09-16 18:09:00','published','2025-09-14 04:05:35','2025-09-16 11:09:49'),(11,1,'M1EGIT','Tryout SD','tryout','2dwsad',1,2,30,NULL,'published','2025-09-14 04:18:36','2025-09-14 04:18:36');
/*!40000 ALTER TABLE `paket_tryout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paket_tryout_soal`
--

DROP TABLE IF EXISTS `paket_tryout_soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paket_tryout_soal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paket_tryout_id` bigint(20) unsigned NOT NULL,
  `soal_id` bigint(20) unsigned NOT NULL,
  `bobot` decimal(8,2) NOT NULL DEFAULT 1.00,
  PRIMARY KEY (`id`),
  KEY `paket_tryout_soal_paket_tryout_id_foreign` (`paket_tryout_id`),
  KEY `paket_tryout_soal_soal_id_foreign` (`soal_id`),
  CONSTRAINT `paket_tryout_soal_paket_tryout_id_foreign` FOREIGN KEY (`paket_tryout_id`) REFERENCES `paket_tryout` (`id`) ON DELETE CASCADE,
  CONSTRAINT `paket_tryout_soal_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_tryout_soal`
--

LOCK TABLES `paket_tryout_soal` WRITE;
/*!40000 ALTER TABLE `paket_tryout_soal` DISABLE KEYS */;
INSERT INTO `paket_tryout_soal` VALUES (2,2,3,1.00),(3,2,2,1.00),(4,3,3,1.00),(5,3,2,1.00),(12,7,3,1.00),(13,7,2,1.00),(14,8,3,1.00),(15,8,2,1.00),(16,9,3,1.00),(17,9,2,1.00),(18,10,3,1.00),(19,10,2,1.00),(20,11,3,0.00),(21,11,2,0.00),(22,11,4,1.00),(23,9,4,1.00),(24,10,4,1.00);
/*!40000 ALTER TABLE `paket_tryout_soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pilihan_jawaban`
--

DROP TABLE IF EXISTS `pilihan_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pilihan_jawaban` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `soal_id` bigint(20) unsigned NOT NULL,
  `pilihan_teks` text NOT NULL,
  `gambar_path` varchar(255) DEFAULT NULL,
  `apakah_benar` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pilihan_jawaban_soal_id_foreign` (`soal_id`),
  CONSTRAINT `pilihan_jawaban_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pilihan_jawaban`
--

LOCK TABLES `pilihan_jawaban` WRITE;
/*!40000 ALTER TABLE `pilihan_jawaban` DISABLE KEYS */;
INSERT INTO `pilihan_jawaban` VALUES (7,3,'<p>Test</p>',NULL,1,'2025-09-14 01:47:31','2025-09-14 01:47:31'),(8,3,'<p>Test</p>',NULL,0,'2025-09-14 01:47:31','2025-09-14 01:47:31'),(43,4,'<p>$\\frac{x^2}{2}$</p>',NULL,1,'2025-09-14 11:35:01','2025-09-14 11:35:01'),(44,4,'<p>test</p>',NULL,0,'2025-09-14 11:35:01','2025-09-14 11:35:01'),(45,2,'<p>Test1</p>',NULL,1,'2025-09-14 14:43:38','2025-09-14 14:43:38'),(46,2,'<p>Test2</p>',NULL,0,'2025-09-14 14:43:38','2025-09-14 14:43:38'),(47,2,'<p>Test3</p>',NULL,1,'2025-09-14 14:43:38','2025-09-14 14:43:38');
/*!40000 ALTER TABLE `pilihan_jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5OocW4LsB3V1nXQRKmvdYndcuxXrOg4LROszG9wu',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2hnNG05UVM1ejVIbm9GeGRWTFV5VkkxcHJxbk8xVXN6bXJtMGRDbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0L21hdGEtcGVsYWphcmFuLzIvc29hbC9jcmVhdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1757870647),('ANvwgPxjoxyRgQJuSEzd3vgdoWqbib7Ar6b7cgw5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU0JINnRKSVljR3ZTdmRrUExZM2x4cTMwQ05PMnA2RlEwc3pqaUJ1MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0L3VqaWFuLXNpc3dhLzEwL3NvYWwvMiI7fXM6MTE6InVqaWFuX3Npc3dhIjthOjY6e3M6MTA6InN0dWRlbnRfaWQiO2k6MzI7czo4OiJwYWtldF9pZCI7aToxMDtzOjE0OiJtYXBlbF9zZWthcmFuZyI7TjtzOjIyOiJtYXBlbF9zdWRhaF9kaWtlcmpha2FuIjthOjE6e2k6MDtzOjE6IjEiO31zOjEzOiJtYXBlbF9waWxpaGFuIjthOjA6e31zOjEwOiJzdGFydF90aW1lIjtpOjE3NTgwMjEwMTI7fX0=',1758021062),('UOBO0GCdXv5NpPjBhpBElBzTTRf1EcFvywFhDV1z',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.19.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQW14ZFZNNjkxa0ZNU0d0clRMZTNmZ3NDc0ZkSVQ3MVpXN3ZxTWZBaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758020399);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider_images`
--

DROP TABLE IF EXISTS `slider_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider_images`
--

LOCK TABLES `slider_images` WRITE;
/*!40000 ALTER TABLE `slider_images` DISABLE KEYS */;
INSERT INTO `slider_images` VALUES (1,'sliders/0g8HemFgpgCKu4iVVn1VvHm2MkBkUeDfhgxn8NlS.jpg','2025-09-12 13:35:23','2025-09-12 13:35:23'),(2,'sliders/dkpDLaKHOWAdltD4U3GiVNEPB00nla9Sbbu0nJKc.jpg','2025-09-12 13:35:31','2025-09-12 13:35:31');
/*!40000 ALTER TABLE `slider_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soal`
--

DROP TABLE IF EXISTS `soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_pelajaran_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `pertanyaan` text NOT NULL,
  `gambar_path` varchar(255) DEFAULT NULL,
  `tipe_soal` enum('pilihan_ganda','isian','pilihan_ganda_majemuk','benar_salah_tabel') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `tingkat_kesulitan` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `soal_user_id_foreign` (`user_id`),
  CONSTRAINT `soal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `soal_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal`
--

LOCK TABLES `soal` WRITE;
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
INSERT INTO `soal` VALUES (2,2,1,'<p>Test</p>',NULL,'pilihan_ganda_majemuk','aktif',43.75,'2025-09-14 01:47:11','2025-09-14 15:09:57'),(3,1,1,'<p>Test</p>',NULL,'pilihan_ganda','aktif',87.50,'2025-09-14 01:47:31','2025-09-14 15:10:03'),(4,2,1,'<table style=\"width:69.6721%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width:49.6241%;\">1</td>\r\n<td style=\"width:49.6241%;\">2</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width:49.6241%;\">3</td>\r\n<td style=\"width:49.6241%;\">4</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>1 2 3 4 2       3      </p>\r\n<table style=\"border-collapse:collapse;width:38.0623%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td> </td>\r\n<td> </td>\r\n</tr>\r\n<tr>\r\n<td> </td>\r\n<td> </td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>       </p>','soal-images/7VOPnLOeMm6AQb1lhYKbnNduSl0e9XmILPOSQuFf.png','pilihan_ganda','aktif',50.00,'2025-09-14 05:04:46','2025-09-14 15:10:03');
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soal_pernyataan`
--

DROP TABLE IF EXISTS `soal_pernyataan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soal_pernyataan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `soal_id` bigint(20) unsigned NOT NULL,
  `pernyataan` text NOT NULL,
  `jawaban_benar` tinyint(1) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_pernyataan_soal_id_foreign` (`soal_id`),
  CONSTRAINT `soal_pernyataan_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal_pernyataan`
--

LOCK TABLES `soal_pernyataan` WRITE;
/*!40000 ALTER TABLE `soal_pernyataan` DISABLE KEYS */;
/*!40000 ALTER TABLE `soal_pernyataan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `paket_tryout_id` bigint(20) unsigned NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `jenjang_pendidikan` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_session_id_unique` (`session_id`),
  KEY `students_paket_tryout_id_foreign` (`paket_tryout_id`),
  CONSTRAINT `students_paket_tryout_id_foreign` FOREIGN KEY (`paket_tryout_id`) REFERENCES `paket_tryout` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,2,'Murid 1','IPA2','SD','B2','SMA','758067e2-a477-49ae-acea-8b06ae378631','2025-09-14 02:07:17','2025-09-14 02:07:17'),(2,2,'Murid 2','IPA2','SD','B2','SMA','a2d128ed-ba4b-4b4d-904d-dfdfea681716','2025-09-14 02:15:36','2025-09-14 02:15:36'),(3,2,'Murid 1','IPA2','SD','B2','SMA','b699eddb-5d3a-4686-8a32-902f97a57350','2025-09-14 02:18:53','2025-09-14 02:18:53'),(4,2,'Murid 1','IPA2','SD','B2','SMA','f355aa76-2913-4e74-b17c-1d8c2cd9ca14','2025-09-14 02:19:17','2025-09-14 02:19:17'),(5,3,'Murid 1','IPA2','SD','B2','SMA','6232bbd2-93ed-4409-87cc-1b56b286af5f','2025-09-14 03:34:56','2025-09-14 03:34:56'),(6,3,'Murid 2','IPA2','SD','B2','SMA','ba391cd6-3efa-4d87-bdc2-df6941a10df0','2025-09-14 03:35:24','2025-09-14 03:35:24'),(8,8,'Murid 1','IPA2','SD','B2','SMA','61075740-94e6-4f3c-81d2-30efe1a17c2a','2025-09-14 04:03:03','2025-09-14 04:03:03'),(9,8,'Murid 2','IPA2','SD','B2','SMA','2d266da4-a58f-45b5-8d02-3a943804af39','2025-09-14 04:03:46','2025-09-14 04:03:46'),(10,10,'Murid 2','IPA2','SD','B2','SMA','f0fe83ca-f4c2-478d-9d16-07649b358c6b','2025-09-14 04:05:50','2025-09-14 04:05:50'),(11,9,'Murid 2','IPA2','SD','B2','SMA','31d86950-2916-47af-a82d-2a2452781240','2025-09-14 04:59:54','2025-09-14 04:59:54'),(12,2,'Murid 2','IPA2','SD','B2','SMA','33499dce-ec43-43a9-864d-b7a1a9a811e5','2025-09-14 05:00:27','2025-09-14 05:00:27'),(13,11,'Murid 2','IPA2','SD','B2','SMA','0b441306-c7bb-4b3c-93e6-8fb40938655a','2025-09-14 05:56:18','2025-09-14 05:56:18'),(14,11,'Murid 2','IPA2','SD','B2','SMA','0e68cfb6-6c1a-4e8a-94c5-784449974d85','2025-09-14 05:56:24','2025-09-14 05:56:24'),(15,10,'Murid 2','IPA2','SD','B2','SMA','371f020b-bf59-4594-8c92-3543185b7528','2025-09-14 07:33:07','2025-09-14 07:33:07'),(16,11,'Murid 2','IPA2','SD','B2','SMA','006ede4f-7715-4c81-9e28-b2573a667e9d','2025-09-14 08:30:05','2025-09-14 08:30:05'),(17,11,'Murid 2','IPA2','SD','B2','SMA','db624c11-082c-4a45-8314-37e998f2c29e','2025-09-14 08:35:28','2025-09-14 08:35:28'),(18,11,'Murid 2','IPA2','SD','B2','SMA','91453e3f-f60b-481b-80e1-bb901c75ec13','2025-09-14 08:45:50','2025-09-14 08:45:50'),(19,11,'Murid 2','IPA2','SD','B2','SMA','31e5d265-66a8-4c82-8e45-db357ff62e05','2025-09-14 08:52:57','2025-09-14 08:52:57'),(20,11,'Murid 2','IPA2','SD','B2','SMA','111701df-82d0-4f37-bfc4-52fac2eb285f','2025-09-14 09:03:24','2025-09-14 09:03:24'),(21,11,'Murid 2',NULL,'SD','B2',NULL,'d2e0b77a-314f-49c3-bc66-98e63a070f6d','2025-09-14 09:29:11','2025-09-14 09:29:11'),(22,11,'Murid 2','IPA2','SD','B2','SMA','8f53c11d-4791-4313-8f2c-6349049947f0','2025-09-14 09:52:18','2025-09-14 09:52:18'),(23,11,'Herman','Admin','SD','Admin','Admin','d2c27f96-9373-415f-a454-b8d8dbe9b42a','2025-09-14 13:39:26','2025-09-14 13:39:26'),(24,11,'Herman','4','SD','Admin','SMP NU','7a6c079a-a9d8-4ecd-a4e4-ebc3ad483cd3','2025-09-14 13:41:54','2025-09-14 13:41:54'),(25,11,'Herman','4','SD','Admin','Admin','b8651879-2ed7-4d25-bec8-6d4f56eb2079','2025-09-14 13:48:20','2025-09-14 13:48:20'),(26,11,'Herman','4','SD','Admin','SMP NU','6f2da61e-b29a-46cd-a105-e4b4d5c1118e','2025-09-14 14:09:16','2025-09-14 14:09:16'),(27,10,'Herman','4','SD','Admin','SMP NU','10b9ddbc-35e7-4171-a46a-57c2f004aff6','2025-09-14 14:12:09','2025-09-14 14:12:09'),(28,11,'Herman','Admin','SD','Admin','Admin','69084827-483e-4f05-9ebe-f9d8067ccd88','2025-09-14 14:17:54','2025-09-14 14:17:54'),(29,10,'Herman','4','SD','Admin','SMP NU','4ac99bb1-70a9-4a83-a696-7efd2bfdf842','2025-09-14 14:27:09','2025-09-14 14:27:09'),(30,11,'Herman','4','SD','6A','SMP NU','7cbaf295-0445-426e-ae57-735c11a41f04','2025-09-14 14:35:22','2025-09-14 14:35:22'),(31,11,'Herman','4','SD','Admin','Admin','26ccd1a1-c2b8-4117-9c02-022d70b67480','2025-09-14 14:43:54','2025-09-14 14:43:54'),(32,10,'Herman','4','SD','Admin','SMP NU','c43e9f28-7262-46e6-a64e-fcd25e898b8e','2025-09-16 11:10:12','2025-09-16 11:10:12');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulangan_sessions`
--

DROP TABLE IF EXISTS `ulangan_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ulangan_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ulangan_id` bigint(20) unsigned NOT NULL,
  `nama_siswa` varchar(255) NOT NULL,
  `nama_sekolah` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) NOT NULL,
  `asal_sekolah` varchar(255) NOT NULL,
  `jenjang` varchar(255) NOT NULL,
  `waktu_mulai` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `jumlah_benar` int(11) DEFAULT NULL,
  `jumlah_salah` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ulangan_sessions_ulangan_id_foreign` (`ulangan_id`),
  CONSTRAINT `ulangan_sessions_ulangan_id_foreign` FOREIGN KEY (`ulangan_id`) REFERENCES `ulangans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulangan_sessions`
--

LOCK TABLES `ulangan_sessions` WRITE;
/*!40000 ALTER TABLE `ulangan_sessions` DISABLE KEYS */;
INSERT INTO `ulangan_sessions` VALUES (7,1,'Herman',NULL,'4','SMP NU','SD','2025-09-14 13:42:30','2025-09-14 13:42:32',0,0,'2025-09-14 13:42:30','2025-09-14 13:42:32');
/*!40000 ALTER TABLE `ulangan_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulangan_soal`
--

DROP TABLE IF EXISTS `ulangan_soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ulangan_soal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ulangan_id` bigint(20) unsigned NOT NULL,
  `soal_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ulangan_soal_ulangan_id_foreign` (`ulangan_id`),
  KEY `ulangan_soal_soal_id_foreign` (`soal_id`),
  CONSTRAINT `ulangan_soal_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ulangan_soal_ulangan_id_foreign` FOREIGN KEY (`ulangan_id`) REFERENCES `ulangans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulangan_soal`
--

LOCK TABLES `ulangan_soal` WRITE;
/*!40000 ALTER TABLE `ulangan_soal` DISABLE KEYS */;
/*!40000 ALTER TABLE `ulangan_soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulangans`
--

DROP TABLE IF EXISTS `ulangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ulangans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mata_pelajaran_id` bigint(20) unsigned DEFAULT NULL,
  `nama_ulangan` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ulangans_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  CONSTRAINT `ulangans_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulangans`
--

LOCK TABLES `ulangans` WRITE;
/*!40000 ALTER TABLE `ulangans` DISABLE KEYS */;
INSERT INTO `ulangans` VALUES (1,2,'Coba','asasas','published','2025-09-11 17:47:51','2025-09-12 15:58:49');
/*!40000 ALTER TABLE `ulangans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Guru Test','guru@test.com',NULL,'$2y$12$Z6EnGNdRMYZCDdEhcaKdx.6FtiuY9KbQbInZJYLt/mJJz8nTbKrxq',NULL,'2025-09-11 16:28:38','2025-09-11 16:28:38');
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

-- Dump completed on 2025-09-16 18:11:15
