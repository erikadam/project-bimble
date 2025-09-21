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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_us`
--

LOCK TABLES `about_us` WRITE;
/*!40000 ALTER TABLE `about_us` DISABLE KEYS */;
INSERT INTO `about_us` VALUES (1,'About US','coba\r\ncoba\r\nCOBA','about-us-images/MCy57UehSDEb026KB37M2EUREnScqbG79YlwXpLl.jpg','2025-09-12 15:30:29','2025-09-12 15:30:29');
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
INSERT INTO `cache` VALUES ('laravel-cache-guru@test.com|103.189.201.142','i:1;',1757793731),('laravel-cache-guru@test.com|103.189.201.142:timer','i:1757793731;',1757793731),('laravel-cache-guru@test.com|114.79.20.155','i:2;',1757810238),('laravel-cache-guru@test.com|114.79.20.155:timer','i:1757810238;',1757810238);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_goals`
--

LOCK TABLES `company_goals` WRITE;
/*!40000 ALTER TABLE `company_goals` DISABLE KEYS */;
INSERT INTO `company_goals` VALUES (2,'visi','VISI UTAMA','Menjadi wadah belajar mengajar yang mewujudkan pelajar maupun pengajar berkualitas dalam bidang pendidikan secara global','2025-09-13 11:08:08','2025-09-13 11:08:08'),(3,'misi','MISI 1','Meningkatkan kualitas pondasi literasi dan numerasi, serta produktivitas dalam pengajaran secara konsep.','2025-09-13 11:08:51','2025-09-13 11:08:51');
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
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban_pesertas`
--

LOCK TABLES `jawaban_pesertas` WRITE;
/*!40000 ALTER TABLE `jawaban_pesertas` DISABLE KEYS */;
INSERT INTO `jawaban_pesertas` VALUES (309,10,16,'<p>He has the fastest bicycle in the world</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(310,10,20,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(311,10,21,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(312,10,24,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(313,10,25,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(314,10,27,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(315,10,28,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(316,10,29,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(317,10,31,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(318,10,33,'<p>the olympics promotes unity and fair play</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(319,10,35,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(320,10,37,'<p>Conserving water.</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(321,10,39,'<p>Conducted a sustainability symposium.</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(322,10,41,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(323,10,43,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(324,10,44,'<p>squats</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(325,10,46,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(326,10,48,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(327,10,49,'<p>The educational programs in museums.</p>',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(328,10,51,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(329,10,53,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(330,10,54,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(331,10,56,'',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(332,10,58,'[\"<p>his record-breaking speed in sprinting<\\/p>\",\"<p>his silent and shy personality<\\/p>\"]',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(333,10,60,'[\"<p>His appearance in the FIFA World Cup.<\\/p>\",\"<p>Winning and setting records in the 100m and 200m races.<\\/p>\"]',NULL,0,'2025-09-14 03:14:08','2025-09-14 03:14:08',21),(334,10,15,'<p>hal pertama yang dilakukan pengunjung Pusat Dokumentasi Sastra H.B. Jassin adalah melihat lemari berisi buku, majalah, dan map-map</p>',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(335,10,17,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(336,10,18,'<p>karyanya dimuat di beberapa majalah dan dibacakan di acara radio</p>',NULL,1,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(337,10,19,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(338,10,22,'<p>Teks 1 menggunakan bahasa tidak resmi; Teks 2 menggunakan bahasa resmi</p>',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(339,10,23,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(340,10,26,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(341,10,30,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(342,10,32,'<p>filosofi nama baru Facebook</p>',NULL,1,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(343,10,34,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(344,10,36,'<p>kurikulum hanyalah dokumen dan benda mati sehingga sebaik apa pun kurikulum tidak akan signifikan jika tidak dijalankan dengan baik<br><br><br><br></p>',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(345,10,38,'<p><br>perubahan sikap Sinta karena tidak ingin menjadi korban perundungan</p>',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(346,10,40,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(347,10,42,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(348,10,45,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(349,10,47,'<p>Sejauh manakah kepedulian masyarakat terhadap tradisi batimung?</p>',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(350,10,50,'<p>mengajarkan cara berdoa yang benar<br><br><br><br><br></p>',NULL,1,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(351,10,52,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(352,10,55,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(353,10,57,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(354,10,59,'[\"<p>di dalam saluran pencernaan ikan terdapat kandungan plastik<br><br><br><br><\\/p>\",\"<p>sifatnya tidak mudah terurai<\\/p>\"]',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(355,10,61,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(356,10,62,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(357,10,63,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(358,10,64,'',NULL,0,'2025-09-14 03:14:36','2025-09-14 03:14:36',21),(359,10,71,'<p>24</p>',NULL,1,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(360,10,91,'<p>2</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(361,10,92,'<p>x = - 1 y = 3 dan z = 6</p>',NULL,1,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(362,10,93,'<p>0</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(363,10,94,'<p>(2) dan (4)</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(364,10,95,'<p>285</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(365,10,96,'<p>10% sampai 15%</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(366,10,97,'<p>9</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(367,10,98,'<p>22.</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(368,10,99,'[\"<p>garis g memotong sumbu X di titik (2, 0)<\\/p>\"]',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(369,10,100,'[\"<p>lampu LED yang diproduksi paling sedikit jumlahnya lebih dari 75 lampu<\\/p>\"]',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(370,10,102,'[\"<p>penjahit akan mendapat keuntungan minimum jika hanya membuat pakaian model A yang jumlahnya kurang dari 7<\\/p>\"]',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(371,10,103,'<p>9/20</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(372,10,104,'<p>Rp1.200.000,00</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(373,10,105,'<p>80</p>',NULL,1,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(374,10,107,'<p>7</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(375,10,108,'',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(376,10,109,'<p>x<sup>2</sup> + y<sup>2 </sup>+ 4x - 6y + 3 = 0</p>',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(377,10,114,'[\"<p>banyak bakteri akan mencapai 100.000.000 setelah setengah jam<\\/p>\",\"<p>bentuk eksponen dari banyak bakteri pada menit kedelapan adalah 2<\\/p>\"]',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(378,10,118,'[\"<p>selisih harga paket prima dan paket melodi tidak kurang dari Rp5.000,00<\\/p>\"]',NULL,0,'2025-09-14 03:15:06','2025-09-14 03:15:06',21),(379,10,152,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(380,10,153,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(381,10,154,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(382,10,155,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(383,10,156,'<p>e. membatasi kegiatan sosial hanya pada generasi muda yang melek teknologi</p>',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(384,10,157,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(385,10,158,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(386,10,159,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(387,10,160,'<p>d. 3) dan 4) karena menyangkut infrastruktur dan migrasi penduduk</p>',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(388,10,161,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(389,10,162,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(390,10,163,'<p>e. menjaga ketertiban dengan membatasi ekspresi budaya masing-masing kelompok</p>',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(391,10,164,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(392,10,165,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(393,10,166,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(394,10,167,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(395,10,168,'<p>c. <em>performing</em> dan kelompok sekunder</p>',NULL,1,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(396,10,169,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(397,10,170,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(398,10,171,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(399,10,172,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(400,10,173,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(401,10,174,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(402,10,175,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(403,10,176,'',NULL,0,'2025-09-14 03:16:38','2025-09-14 03:16:38',21),(404,10,65,'<p>keragamannya</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(405,10,72,'<p>bentuk, warna, ukuran, dan penam-pilan</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(406,10,73,'<p>(1), (2), dan (5)</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(407,10,74,'',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(408,10,75,'<p>(1) dan (2)</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(409,10,76,'<p>terganggunya biomassa pada piramida makanan</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(410,10,77,'<p>meningkatnya jumlah populasi hewan-hewan</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(411,10,78,'<p>populasi katak dan populasi belalang meningkat</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(412,10,79,'<p>asam piruvat</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(413,10,80,'<p>(3) - (5) - (4) - (1) - (2)</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(414,10,81,'<p>(3) - (5) - (4) - (2) - (1)</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(415,10,82,'<p>Ribulose diphospat (RDP)</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(416,10,83,'',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(417,10,84,'',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(418,10,85,'<p>tubulus kontortus proksimal</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(419,10,86,'<p>membantu proses pelepasan sel telur</p>',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(420,10,87,'[\"<p>bersifat eukariotik, yaitu memiliki inti sel tetapi tidak memiliki membran inti<\\/p>\"]',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(421,10,88,'[\"<p>auditori<\\/p>\"]',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(422,10,89,'[\"<p>peredaran darah dalam tubuh<\\/p>\"]',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21),(423,10,90,'[\"<p>menghentikan siklus menstruasi pada wanita<\\/p>\"]',NULL,0,'2025-09-14 03:17:44','2025-09-14 03:17:44',21);
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
  `jawaban_teks` text DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_pelajaran`
--

LOCK TABLES `mata_pelajaran` WRITE;
/*!40000 ALTER TABLE `mata_pelajaran` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_mapel`
--

LOCK TABLES `paket_mapel` WRITE;
/*!40000 ALTER TABLE `paket_mapel` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_tryout`
--

LOCK TABLES `paket_tryout` WRITE;
/*!40000 ALTER TABLE `paket_tryout` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paket_tryout_soal`
--

LOCK TABLES `paket_tryout_soal` WRITE;
/*!40000 ALTER TABLE `paket_tryout_soal` DISABLE KEYS */;
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
-- Table structure for table `pernyataan_pilihan_jawaban`
--

DROP TABLE IF EXISTS `pernyataan_pilihan_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pernyataan_pilihan_jawaban` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `soal_pernyataan_id` bigint(20) unsigned NOT NULL,
  `pilihan_teks` text NOT NULL,
  `apakah_benar` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pernyataan_pilihan_jawaban_soal_pernyataan_id_foreign` (`soal_pernyataan_id`),
  CONSTRAINT `pernyataan_pilihan_jawaban_soal_pernyataan_id_foreign` FOREIGN KEY (`soal_pernyataan_id`) REFERENCES `soal_pernyataans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pernyataan_pilihan_jawaban`
--

LOCK TABLES `pernyataan_pilihan_jawaban` WRITE;
/*!40000 ALTER TABLE `pernyataan_pilihan_jawaban` DISABLE KEYS */;
INSERT INTO `pernyataan_pilihan_jawaban` VALUES (9,5,'A',1,'2025-09-17 17:04:50','2025-09-17 17:04:50'),(10,5,'AA',0,'2025-09-17 17:04:50','2025-09-17 17:04:50'),(11,6,'A',0,'2025-09-17 17:04:50','2025-09-17 17:04:50'),(12,6,'AA',1,'2025-09-17 17:04:50','2025-09-17 17:04:50');
/*!40000 ALTER TABLE `pernyataan_pilihan_jawaban` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pilihan_jawaban`
--

LOCK TABLES `pilihan_jawaban` WRITE;
/*!40000 ALTER TABLE `pilihan_jawaban` DISABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('hiVPbfA6Av7OgtCP77gTMizg0NMOAeZF9AWT0xod',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZm5NWkJvdUVHaWVXQlBSbnk3RDV5bHEzWmRLTXU0OTBpd3MxM3czUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9wcm9qZWN0LWJpbWJlbC50ZXN0L2xvZ2luIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjUxOiJodHRwOi8vcHJvamVjdC1iaW1iZWwudGVzdC9sYXBvcmFuLXVqaWFuLzIvYW5hbHlzaXMiO319',1758138452);
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
  `pilihan_kompleks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pilihan_kompleks`)),
  `gambar_path` varchar(255) DEFAULT NULL,
  `tipe_soal` enum('pilihan_ganda','isian','pilihan_ganda_majemuk','benar_salah_tabel','pilihan_ganda_kompleks') NOT NULL,
  `opsi_kolom` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`opsi_kolom`)),
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `tingkat_kesulitan` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `soal_user_id_foreign` (`user_id`),
  KEY `soal_parent_id_foreign` (`parent_id`),
  CONSTRAINT `soal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `soal_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `soal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `soal_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal`
--

LOCK TABLES `soal` WRITE;
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soal_pernyataans`
--

DROP TABLE IF EXISTS `soal_pernyataans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soal_pernyataans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `soal_id` bigint(20) unsigned NOT NULL,
  `pernyataan_teks` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_pernyataans_soal_id_foreign` (`soal_id`),
  CONSTRAINT `soal_pernyataans_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal_pernyataans`
--

LOCK TABLES `soal_pernyataans` WRITE;
/*!40000 ALTER TABLE `soal_pernyataans` DISABLE KEYS */;
INSERT INTO `soal_pernyataans` VALUES (5,18,'A','2025-09-17 17:04:50','2025-09-17 17:04:50'),(6,18,'AA','2025-09-17 17:04:50','2025-09-17 17:04:50');
/*!40000 ALTER TABLE `soal_pernyataans` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulangan_sessions`
--

LOCK TABLES `ulangan_sessions` WRITE;
/*!40000 ALTER TABLE `ulangan_sessions` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulangans`
--

LOCK TABLES `ulangans` WRITE;
/*!40000 ALTER TABLE `ulangans` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2025-09-18 18:00:45
