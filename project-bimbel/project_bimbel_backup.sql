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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_goals`
--

LOCK TABLES `company_goals` WRITE;
/*!40000 ALTER TABLE `company_goals` DISABLE KEYS */;
INSERT INTO `company_goals` VALUES (1,'visi','VISI','Menjadi wadah belajar\r\n mengajar yang\r\n mewujudkan pelajar\r\n maupun pengajar\r\n berkualitas dalam\r\n bidang pendidikan\r\n secara global','2025-09-08 11:21:50','2025-09-08 11:21:50'),(2,'misi','1','Meningkatkan kualitas pondasi literasi dan numerasi, serta produktivitas dalam pengajaran secara konsep.','2025-09-08 11:27:35','2025-09-08 11:29:43'),(3,'misi','2','Konsisten mengutamakan kualitas belajar pelajar maupun pengajar secara dinamis.','2025-09-08 11:27:58','2025-09-08 11:29:24'),(5,'misi','3','Memberikan lapangan kerja dan pelatihan berkala kepada pengajar.','2025-09-08 11:29:10','2025-09-08 11:29:10'),(6,'misi','4','Memberikan program belajar berkualitas yang ekonomis kepada pelajar secara global.','2025-09-08 11:30:08','2025-09-08 11:30:15'),(7,'misi','5','Mengadakan program beasiswa bagi siswa kurang mampu, yatim dan yatim piatu.','2025-09-08 11:30:53','2025-09-08 11:30:53'),(8,'tujuan','1','Meningkatkan budaya cinta belajar dan membaca dan berpikir bagi pelajar maupun maupun pengajar','2025-09-08 11:31:16','2025-09-08 11:31:31'),(9,'tujuan','2','Menghilangkan budaya menyontek yang terlalu berorientasi pada hasil sehingga dapat meningkatkan karakter pelajar maupun pengajar','2025-09-08 11:31:58','2025-09-08 11:31:58');
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
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban_pesertas`
--

LOCK TABLES `jawaban_pesertas` WRITE;
/*!40000 ALTER TABLE `jawaban_pesertas` DISABLE KEYS */;
INSERT INTO `jawaban_pesertas` VALUES (71,3,4,'',NULL,0,'2025-09-06 17:11:59','2025-09-06 17:11:59',17),(72,3,10,'',NULL,0,'2025-09-06 17:11:59','2025-09-06 17:11:59',17),(73,3,5,'',NULL,0,'2025-09-06 17:12:00','2025-09-06 17:12:00',17),(74,3,7,'',NULL,0,'2025-09-06 17:12:00','2025-09-06 17:12:00',17),(75,3,11,'',NULL,0,'2025-09-06 17:12:00','2025-09-06 17:12:00',17),(76,3,6,'',NULL,0,'2025-09-06 17:12:00','2025-09-06 17:12:00',17),(77,3,8,'',NULL,0,'2025-09-06 17:12:01','2025-09-06 17:12:01',17),(78,5,4,'',NULL,0,'2025-09-06 17:15:19','2025-09-06 17:15:19',18),(79,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-06 17:15:19','2025-09-06 17:15:19',18),(80,5,5,'Ya',NULL,1,'2025-09-06 17:15:22','2025-09-06 17:15:22',18),(81,5,7,'',NULL,0,'2025-09-06 17:15:24','2025-09-06 17:15:24',18),(82,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:15:24','2025-09-06 17:15:24',18),(83,5,6,'Ya',NULL,1,'2025-09-06 17:15:27','2025-09-06 17:15:27',18),(84,5,2,'',NULL,0,'2025-09-06 17:15:29','2025-09-06 17:15:29',18),(85,5,3,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:15:29','2025-09-06 17:15:29',18),(86,5,4,'',NULL,0,'2025-09-06 17:28:57','2025-09-06 17:28:57',19),(87,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-06 17:28:57','2025-09-06 17:28:57',19),(88,5,5,'Ya',NULL,1,'2025-09-06 17:29:01','2025-09-06 17:29:01',19),(89,5,7,'',NULL,0,'2025-09-06 17:29:05','2025-09-06 17:29:05',19),(90,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:29:05','2025-09-06 17:29:05',19),(91,5,6,'Ya',NULL,1,'2025-09-06 17:29:07','2025-09-06 17:29:07',19),(92,5,2,'',NULL,0,'2025-09-06 17:29:09','2025-09-06 17:29:09',19),(93,5,3,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:29:09','2025-09-06 17:29:09',19),(94,5,7,'',NULL,0,'2025-09-06 17:35:46','2025-09-06 17:35:46',20),(95,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:35:46','2025-09-06 17:35:46',20),(96,5,4,'',NULL,0,'2025-09-06 17:42:17','2025-09-06 17:42:17',20),(97,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-06 17:42:18','2025-09-06 17:42:18',20),(98,5,5,'Ya',NULL,1,'2025-09-06 17:42:20','2025-09-06 17:42:20',20),(99,5,8,'Ya',NULL,1,'2025-09-06 17:42:22','2025-09-06 17:42:22',20),(100,5,6,'Ya',NULL,1,'2025-09-06 17:42:24','2025-09-06 17:42:24',20),(101,5,7,'',NULL,0,'2025-09-06 17:42:53','2025-09-06 17:42:53',21),(102,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:42:53','2025-09-06 17:42:53',21),(103,5,4,'',NULL,0,'2025-09-06 17:43:01','2025-09-06 17:43:01',21),(104,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-06 17:43:01','2025-09-06 17:43:01',21),(105,5,5,'Ya',NULL,1,'2025-09-06 17:43:04','2025-09-06 17:43:04',21),(106,5,6,'Ya',NULL,1,'2025-09-06 17:43:06','2025-09-06 17:43:06',21),(107,5,2,'',NULL,0,'2025-09-06 17:43:11','2025-09-06 17:43:11',21),(108,5,3,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-06 17:43:11','2025-09-06 17:43:11',21),(111,10,7,'Ya',NULL,1,'2025-09-07 02:45:38','2025-09-07 02:45:38',29),(112,10,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 02:45:38','2025-09-07 02:45:38',29),(113,11,5,'Ya',NULL,1,'2025-09-07 03:55:01','2025-09-07 03:55:01',30),(114,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 04:02:48','2025-09-07 04:02:48',31),(115,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-07 04:02:52','2025-09-07 04:02:52',31),(116,5,5,'Ya',NULL,1,'2025-09-07 04:02:54','2025-09-07 04:02:54',31),(117,5,6,'Ya',NULL,1,'2025-09-07 04:02:56','2025-09-07 04:02:56',31),(118,5,3,'[\"<p>Tidak<\\/p>\",\"<p>Ya<\\/p>\"]',NULL,0,'2025-09-07 04:03:06','2025-09-07 04:03:06',31),(119,5,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 04:03:29','2025-09-07 04:03:29',32),(120,5,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-07 04:03:32','2025-09-07 04:03:32',32),(121,5,5,'Ya',NULL,1,'2025-09-07 04:03:34','2025-09-07 04:03:34',32),(122,5,6,'Ya',NULL,1,'2025-09-07 04:03:36','2025-09-07 04:03:36',32),(123,5,3,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 04:03:39','2025-09-07 04:03:39',32),(124,9,7,'Ya',NULL,1,'2025-09-07 04:05:36','2025-09-07 04:06:01',33),(125,9,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 04:05:36','2025-09-07 04:06:01',33),(126,9,4,'Ya',NULL,1,'2025-09-07 04:07:01','2025-09-07 04:07:01',33),(127,9,10,'',NULL,0,'2025-09-07 04:07:01','2025-09-07 04:07:01',33),(128,11,7,'Ya',NULL,1,'2025-09-07 04:09:51','2025-09-07 04:10:00',35),(129,11,11,'[\"<p>Ya<\\/p>\"]',NULL,1,'2025-09-07 04:09:51','2025-09-07 04:10:00',35),(130,11,4,'Ya',NULL,1,'2025-09-07 04:10:14','2025-09-07 04:11:00',35),(131,11,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-07 04:10:14','2025-09-07 04:11:00',35),(132,11,5,'Tidak',NULL,0,'2025-09-07 04:12:02','2025-09-07 04:12:02',35),(133,9,4,'Ya',NULL,1,'2025-09-07 04:16:01','2025-09-07 04:16:01',36),(134,9,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-07 04:16:01','2025-09-07 04:16:01',36),(135,10,4,'',NULL,0,'2025-09-07 04:22:02','2025-09-07 04:22:02',38),(136,10,10,'',NULL,0,'2025-09-07 04:22:02','2025-09-07 04:22:02',38),(137,10,5,'',NULL,0,'2025-09-07 04:23:01','2025-09-07 04:23:01',38),(138,10,6,'Tidak',NULL,0,'2025-09-07 04:23:32','2025-09-07 04:24:00',38),(139,10,2,'',NULL,0,'2025-09-07 04:24:22','2025-09-07 04:24:22',38),(140,10,3,'',NULL,0,'2025-09-07 04:24:22','2025-09-07 04:24:22',38),(141,5,10,'',NULL,0,'2025-09-07 04:47:58','2025-09-07 04:47:58',41),(142,5,11,'',NULL,0,'2025-09-07 04:48:04','2025-09-07 04:48:04',41),(143,5,6,'',NULL,0,'2025-09-07 04:48:07','2025-09-07 04:48:07',41),(144,5,8,'',NULL,0,'2025-09-07 04:48:11','2025-09-07 04:48:11',41);
/*!40000 ALTER TABLE `jawaban_pesertas` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_pelajaran`
--

LOCK TABLES `mata_pelajaran` WRITE;
/*!40000 ALTER TABLE `mata_pelajaran` DISABLE KEYS */;
INSERT INTO `mata_pelajaran` VALUES (4,'Matematika','SD',1,'2025-08-25 05:11:57','2025-08-25 05:11:57'),(5,'Agama','SD',0,'2025-08-25 05:12:02','2025-08-25 05:12:02'),(6,'Bahasa Indonesia','SD',1,'2025-08-25 05:12:16','2025-08-25 05:33:13'),(7,'Bahasa Inggris','SD',1,'2025-08-25 05:12:20','2025-08-25 05:12:20'),(8,'Penjas','SD',0,'2025-08-25 05:12:29','2025-08-25 05:12:29'),(9,'Fisika','SD',0,'2025-08-25 05:12:38','2025-08-25 05:12:38'),(10,'Matematika2','SMP',1,'2025-08-25 05:31:37','2025-08-25 05:36:34'),(11,'Bahasa Indonesia2','SMP',1,'2025-08-25 05:31:55','2025-08-25 05:36:27'),(12,'Bahasa Inggris2','SMP',1,'2025-08-25 05:32:00','2025-08-25 05:36:31'),(13,'Fisika2','SMP',0,'2025-08-25 05:32:07','2025-08-25 05:36:20'),(14,'Agama2','SMP',0,'2025-08-25 05:32:11','2025-08-25 05:36:16'),(15,'Penjas2','SMP',0,'2025-08-25 05:32:17','2025-08-25 05:36:24'),(16,'Matematika3','SMA',1,'2025-08-25 05:32:31','2025-08-25 05:36:48'),(17,'Bahasa Indonesia3','SMA',1,'2025-08-25 05:32:36','2025-08-25 05:36:41'),(18,'Bahasa Inggris3','SMA',1,'2025-08-25 05:32:42','2025-08-25 05:36:44'),(19,'Fisika3','SMA',0,'2025-08-25 05:33:34','2025-08-25 05:36:57'),(20,'Penjas3','SMA',0,'2025-08-25 05:33:38','2025-08-25 05:37:01'),(21,'Agama3','SMA',0,'2025-08-25 05:33:43','2025-08-25 05:36:53'),(22,'Pemrograman','SMA',0,'2025-08-27 02:54:19','2025-08-27 02:54:19');
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_08_24_065517_create_mata_pelajaran_table',1),(5,'2025_08_24_065718_create_soal_table',1),(6,'2025_08_24_065720_create_pilihan_jawaban_table',1),(7,'2025_08_24_082930_create_paket_tryout_table',1),(8,'2025_08_24_083001_create_paket_tryout_soal_table',1),(9,'2025_08_24_085306_add_status_to_soal_table',1),(10,'2025_08_24_085340_create_paket_mapel_table',1),(11,'2025_08_24_085350_drop_paket_tryout_soal_table',1),(12,'2025_08_24_112439_create_jawaban_pesertas_table',1),(13,'2025_08_24_115927_add_gambar_path_to_soal_and_pilihan_jawaban_table',1),(14,'2025_08_24_140511_create_students_table',1),(15,'2025_08_24_140601_update_jawaban_pesertas_table',1),(17,'2025_08_25_114808_add_jenjang_pendidikan_to_mata_pelajaran_table',2),(18,'2025_09_06_094131_modify_tipe_soal_in_soal_table',3),(19,'2025_09_07_000132_create_paket_tryout_soal_table',4),(20,'2025_09_07_015731_add_event_schedule_to_paket_tryout_table',5),(21,'2025_09_07_113619_add_selection_rules_to_paket_tryout_table',6),(22,'2025_09_07_115956_add_bobot_to_paket_tryout_soal_table',7),(23,'2025_09_07_122035_change_bobot_column_type_in_paket_tryout_soal_table',8),(24,'2025_09_07_193749_create_slider_images_table',9),(25,'2025_09_08_165709_create_testimonials_table',10),(26,'2025_09_08_180426_create_company_goals_table',11);
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_mapel`
--

LOCK TABLES `paket_mapel` WRITE;
/*!40000 ALTER TABLE `paket_mapel` DISABLE KEYS */;
INSERT INTO `paket_mapel` VALUES (13,3,10,0,15,NULL,NULL),(14,3,11,0,15,NULL,NULL),(15,3,12,0,15,NULL,NULL),(16,3,13,0,15,NULL,NULL),(17,3,14,0,15,NULL,NULL),(18,3,15,0,15,NULL,NULL),(19,4,10,0,20,NULL,NULL),(20,4,11,0,30,NULL,NULL),(21,4,12,0,20,NULL,NULL),(22,4,13,0,10,NULL,NULL),(23,4,14,0,15,NULL,NULL),(24,4,15,0,15,NULL,NULL),(25,5,10,0,1,NULL,NULL),(26,5,11,1,15,NULL,NULL),(27,5,12,2,15,NULL,NULL),(28,5,13,3,15,NULL,NULL),(29,5,14,4,15,NULL,NULL),(30,5,15,5,15,NULL,NULL),(42,9,10,0,1,NULL,NULL),(43,9,11,1,1,NULL,NULL),(44,10,10,0,1,NULL,NULL),(45,10,11,1,1,NULL,NULL),(46,10,12,2,1,NULL,NULL),(47,10,13,3,1,NULL,NULL),(48,10,14,4,1,NULL,NULL),(49,11,10,0,3,NULL,NULL),(50,11,11,1,1,NULL,NULL),(51,11,12,2,1,NULL,NULL);
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
INSERT INTO `paket_tryout` VALUES (3,3,'ZXB73F','Tryout SMP','tryout',NULL,NULL,NULL,90,NULL,'draft','2025-08-27 03:00:03','2025-09-06 17:21:02'),(4,3,'2FD4OM','Tryout 2025','tryout','asx',NULL,NULL,110,NULL,'draft','2025-09-06 04:39:06','2025-09-06 17:21:03'),(5,3,'8WOWYH','Tryout 2025 SMP','tryout','asasas',2,2,76,NULL,'published','2025-09-06 17:14:59','2025-09-07 04:41:54'),(9,3,'EKNV8F','Tryout 2025 SMP E2','event','dsdsdsd',NULL,NULL,2,'2025-09-07 11:14:00','published','2025-09-06 19:34:01','2025-09-07 04:13:31'),(10,3,'IKGDQN','Tryout 2025 SMP E5','event','dsdsds',NULL,NULL,5,'2025-09-07 11:20:00','published','2025-09-07 02:36:47','2025-09-07 04:19:01'),(11,3,'LADDHP','Tryout 2025 SMP E4','event','Coba',NULL,NULL,5,'2025-09-07 11:07:00','published','2025-09-07 03:01:23','2025-09-07 04:04:20');
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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_tryout_soal`
--

LOCK TABLES `paket_tryout_soal` WRITE;
/*!40000 ALTER TABLE `paket_tryout_soal` DISABLE KEYS */;
INSERT INTO `paket_tryout_soal` VALUES (1,5,11,0.00),(2,5,10,0.00),(3,5,5,0.00),(4,5,6,0.00),(5,5,3,10.00),(6,5,8,1.00),(25,9,7,1.00),(26,9,11,1.00),(27,9,4,1.00),(28,9,10,1.00),(29,10,7,1.00),(30,10,11,1.00),(31,10,4,1.00),(32,10,10,1.00),(33,10,5,1.00),(34,10,6,1.00),(35,10,2,1.00),(36,10,3,1.00),(37,11,7,10.00),(38,11,11,20.00),(39,11,4,10.00),(40,11,10,20.00),(41,11,5,40.00);
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
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pilihan_jawaban`
--

LOCK TABLES `pilihan_jawaban` WRITE;
/*!40000 ALTER TABLE `pilihan_jawaban` DISABLE KEYS */;
INSERT INTO `pilihan_jawaban` VALUES (3,1,'Ya','pilihan-images/W1ZFXhWoGVhg3U4FEK9TPwCC9H5Ka0mkSIg1cj8B.png',1,'2025-08-25 05:34:42','2025-08-25 05:34:42'),(4,1,'Tidak',NULL,0,'2025-08-25 05:34:42','2025-08-25 05:34:42'),(5,2,'Ya',NULL,1,'2025-08-25 06:29:04','2025-08-25 06:29:04'),(6,2,'Tidak',NULL,0,'2025-08-25 06:29:04','2025-08-25 06:29:04'),(9,4,'Ya',NULL,1,'2025-08-25 06:29:28','2025-08-25 06:29:28'),(10,4,'Tidak',NULL,0,'2025-08-25 06:29:28','2025-08-25 06:29:28'),(11,5,'Ya',NULL,1,'2025-08-25 06:29:45','2025-08-25 06:29:45'),(12,5,'Tidak',NULL,0,'2025-08-25 06:29:45','2025-08-25 06:29:45'),(13,6,'Ya',NULL,1,'2025-08-25 06:29:58','2025-08-25 06:29:58'),(14,6,'Tidak',NULL,0,'2025-08-25 06:29:58','2025-08-25 06:29:58'),(15,7,'Ya',NULL,1,'2025-08-25 06:30:09','2025-08-25 06:30:09'),(16,7,'Tidak',NULL,0,'2025-08-25 06:30:09','2025-08-25 06:30:09'),(17,8,'Ya',NULL,1,'2025-08-25 06:30:21','2025-08-25 06:30:21'),(18,8,'Tidak',NULL,0,'2025-08-25 06:30:21','2025-08-25 06:30:21'),(22,9,'YA',NULL,0,'2025-08-27 02:58:50','2025-08-27 02:58:50'),(23,9,'TIDAK',NULL,1,'2025-08-27 02:58:50','2025-08-27 02:58:50'),(24,9,'MUNGKIN',NULL,0,'2025-08-27 02:58:50','2025-08-27 02:58:50'),(63,10,'<p><strong>Ya<sup>2</sup></strong></p>',NULL,1,'2025-09-05 07:47:02','2025-09-05 07:47:02'),(64,10,'<p>Tidak</p>',NULL,0,'2025-09-05 07:47:02','2025-09-05 07:47:02'),(65,3,'<p>Ya</p>',NULL,1,'2025-09-06 04:02:26','2025-09-06 04:02:26'),(66,3,'<p>Tidak</p>',NULL,0,'2025-09-06 04:02:26','2025-09-06 04:02:26'),(70,11,'<p>Tidak</p>',NULL,0,'2025-09-06 04:20:49','2025-09-06 04:20:49'),(71,11,'<p>Ya</p>',NULL,1,'2025-09-06 04:20:49','2025-09-06 04:20:49'),(72,11,'<p>Salah</p>',NULL,0,'2025-09-06 04:20:49','2025-09-06 04:20:49');
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
INSERT INTO `sessions` VALUES ('zb2oU5Z2BGpGTOGfa7lQspHXkMlPS3LPAaTIZYn1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.19.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGZ2dkVRWTQwak11V2JJMEk5dEExYzRXS2JlRnZDbE9jR0JHeHo3aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0Lz9oZXJkPXByZXZpZXciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1757507781),('zMLWwiEgb9aK8a5BprOc7gafyxRtABSd7pllCS1y',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMXJ3dG13TXoxNzNGTWt1SjR3WnFaYzd0VjVMdkV5WnBiejJJV0NjeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0L2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=',1757508003);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider_images`
--

LOCK TABLES `slider_images` WRITE;
/*!40000 ALTER TABLE `slider_images` DISABLE KEYS */;
INSERT INTO `slider_images` VALUES (1,'sliders/dXWg0XjUujJxAMhGJEeCH0wVZlKUrzPsxVSC3CHV.jpg','2025-09-07 12:45:26','2025-09-07 12:45:26'),(2,'sliders/xiDpKMi98cRHQQ0eBrgQvNNrQ6tt5OB7t280Pxp0.jpg','2025-09-07 12:45:36','2025-09-07 12:45:36'),(3,'sliders/ejhbMCtugvBVKkmxKqq9gSb0QyQSC8uALyj9udp5.jpg','2025-09-07 12:45:41','2025-09-07 12:45:41');
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
  `tipe_soal` enum('pilihan_ganda','isian','pilihan_ganda_majemuk') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `soal_user_id_foreign` (`user_id`),
  CONSTRAINT `soal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `soal_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal`
--

LOCK TABLES `soal` WRITE;
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
INSERT INTO `soal` VALUES (1,5,3,'qwer','soal-images/7JslYBVNejI0i5wbqBmW1pZV3Ap2HcsenATr8H36.png','pilihan_ganda','aktif','2025-08-25 05:34:20','2025-08-25 05:34:20'),(2,14,3,'123213123',NULL,'pilihan_ganda','aktif','2025-08-25 06:29:04','2025-08-25 06:29:04'),(3,14,3,'<p><strong>213213213213<sup>2</sup></strong></p>',NULL,'pilihan_ganda_majemuk','aktif','2025-08-25 06:29:14','2025-09-06 04:02:26'),(4,11,3,'123213213213',NULL,'pilihan_ganda','aktif','2025-08-25 06:29:28','2025-08-25 06:29:28'),(5,12,3,'3213213123123',NULL,'pilihan_ganda','aktif','2025-08-25 06:29:45','2025-08-25 06:29:45'),(6,13,3,'213213213213',NULL,'pilihan_ganda','aktif','2025-08-25 06:29:58','2025-08-25 06:29:58'),(7,10,3,'21312321312',NULL,'pilihan_ganda','aktif','2025-08-25 06:30:09','2025-08-25 06:30:09'),(8,15,3,'123123213123',NULL,'pilihan_ganda','aktif','2025-08-25 06:30:21','2025-08-25 06:30:21'),(9,22,3,'Test 1\r\nTest 2',NULL,'pilihan_ganda','aktif','2025-08-27 02:58:33','2025-08-27 02:58:33'),(10,11,3,'<h2>BAB I PENDAHULUAN</h2>\r\n<h4>1.1 Latar Belakang</h4>\r\n<p style=\"text-align:justify;\">Perkembangan teknologi komunikasi di masa modern ini semakin banyak inovasi. Dengan berkembangnya teknologi informasi serta semakin banyak persaingan dalam dunia Internasional akan sangat mempengaruhi teknologi di dunia antara lain meningkatnya pertumbuhan ekonomi dan dalam bidang perdagangan dll, terutama perkembangan jasa di tanah air. Banyak persaingan akan dapat dipenuhi apabila perusahaan bisa menciptakan dan mempertahankan pelanggan. Untuk mencapai tujuan tersebut maka perusahaan memerlukan berbagai inovasi yang baru dan terdepan dalam jasa tersebut.</p>\r\n<p style=\"text-align:justify;\">Pada masa perkembangan teknologi informasi dan komunikasi, perusahaan semakin pintar dalam menghadapi era pasar bebas yang kompetitif yang kian menajam, maka target atau sasaran customer harus lebih difokuskan kepada kepentingan setiap steakholder perusahaan. Selama diyakini bahwa kunci utama untuk memenangkan persaingan adalah memberikan kepuasaan serta layanan yang bagus pada produk dan jasa yang berkualitas dan dapat bersaing dengan berbagai perusahaaan.</p>\r\n<p style=\"text-align:justify;\">PT.Telekomunikasi Indonesia (PT.Telkom) adalah BUMN yang dimiliki oleh Negara. PT.Telkom merupakan BUMN yang paling banyak mendapatkan keuntungan jika dibandingkan dengan BUMN yang ada di Indonesia, karena PT.Telkom selama beberapa tahun ini mendapatkan keuntungan setiap tahunnya PT.Telekomunikasi 2 Indonesia (PT.Telkom) mendapatkan peningkatan jumlah pelanggan.</p>\r\n<p style=\"text-align:justify;\">Oleh sebab itu diperlukan adanya kerja praktik untuk mempersiapkan dalam menghadapi dunia kerja setelah lulus kuliah nanti. Selain itu mahasiswa dapat menerapkaan ilmu yang didapat ketika di bangku perkuliahan kedalam dunia kerja.</p>',NULL,'pilihan_ganda','aktif','2025-09-05 06:09:24','2025-09-05 07:47:02'),(11,10,3,'<h1>Header</h1>\r\n<p><em><strong>Bold<sup>2∑</sup></strong></em></p>\r\n<p> </p>\r\n<ol>\r\n<li><em><strong><sup>Italic</sup></strong></em></li>\r\n</ol>\r\n<p> </p>',NULL,'pilihan_ganda_majemuk','aktif','2025-09-06 04:20:05','2025-09-06 04:20:49');
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;
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
  `jenjang_pendidikan` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_session_id_unique` (`session_id`),
  KEY `students_paket_tryout_id_foreign` (`paket_tryout_id`),
  CONSTRAINT `students_paket_tryout_id_foreign` FOREIGN KEY (`paket_tryout_id`) REFERENCES `paket_tryout` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (11,3,'Murid 2','SMP','B2','6746ae62-9286-4f9f-8eb8-4a8172e053a7','2025-08-27 03:00:22','2025-08-27 03:00:22'),(17,3,'Siswa54545','SMP','6A','f862e97b-6a43-4b17-82e1-bd37738651b6','2025-09-06 17:11:50','2025-09-06 17:11:50'),(18,5,'Siswa54545','SMP','6A','fe739fc0-5e8b-4f1c-a06c-670ca8576c92','2025-09-06 17:15:12','2025-09-06 17:15:12'),(19,5,'Siswa2121','SMP','6A','adc91fbb-20f4-497a-a5de-4706bf78b85a','2025-09-06 17:28:49','2025-09-06 17:28:49'),(20,5,'Siswa2121','SMP','6A','38ff23f4-7169-496b-a604-e4d14ad91058','2025-09-06 17:34:39','2025-09-06 17:34:39'),(21,5,'Herman','SMP','6A','3b9c7aad-ef15-4af4-a3c4-306040e14c92','2025-09-06 17:42:41','2025-09-06 17:42:41'),(29,10,'Herman','SMP','6A','582e6714-bdbb-4f2d-a1a7-8236dab49efd','2025-09-07 02:40:08','2025-09-07 02:40:08'),(30,11,'Heri','SMP','6A','ef1a5d4a-35ae-460d-ba92-284a483c0fd7','2025-09-07 03:52:58','2025-09-07 03:52:58'),(31,5,'Herman','SMP','6A','bff5165f-7dd1-47e2-97ee-3d5b77817a8f','2025-09-07 04:02:34','2025-09-07 04:02:34'),(32,5,'Herman','SMP','6A','0848b4ff-7261-4463-bf0c-66b0be298b17','2025-09-07 04:03:23','2025-09-07 04:03:23'),(33,9,'Heri','SMP','6A','93bb56bf-d724-463b-a542-2de7256391c9','2025-09-07 04:05:19','2025-09-07 04:05:19'),(34,11,'Heri','SMP','6A','c9c7d959-c45c-4ab8-9dfb-72e1538cf5ba','2025-09-07 04:09:20','2025-09-07 04:09:20'),(35,11,'Heri','SMP','A','3f49f3b3-3555-4672-9c2c-374bd52bd813','2025-09-07 04:09:26','2025-09-07 04:09:26'),(36,9,'Heri','SMP','6A','29bcffd4-c00f-4b9e-b453-0bd1d437c704','2025-09-07 04:15:10','2025-09-07 04:15:10'),(37,10,'Herman','SMP','8','a9d98ca5-6937-4500-b2fe-736fc3c477c0','2025-09-07 04:21:02','2025-09-07 04:21:02'),(38,10,'Herman','SMP','6A','af4000c2-7db4-461a-9c4a-8e84d7a6a16f','2025-09-07 04:21:07','2025-09-07 04:21:07'),(39,5,'Herman','SMP','8','a00c6712-f767-4f7a-a7b1-a6df24259f04','2025-09-07 04:42:12','2025-09-07 04:42:12'),(40,5,'Herman','SMP','8','1a9b9bbc-fd46-4a5c-93dd-cf3168136a8f','2025-09-07 04:42:16','2025-09-07 04:42:16'),(41,5,'Herman','SMP','8','42d121d6-7992-4817-baaf-5dd5c2952535','2025-09-07 04:44:33','2025-09-07 04:44:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Guru Test','guru@test.com',NULL,'$2y$12$jrIVE6MeW3CsAjASh1UmZOC4jLdZ0FRRqCXu4IySWAGPtVilGCSci','WJkZ4UfK9j9h3nBiniP3tjjtlOoWuwNiE2iA6slshHfq00HJBEPRlTXBeAZO','2025-08-24 07:14:10','2025-08-24 07:14:10');
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

-- Dump completed on 2025-09-10 19:41:07
