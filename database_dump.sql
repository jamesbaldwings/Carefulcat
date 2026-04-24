/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u774526707_carefulcat_db
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admin_sessions`
--

DROP TABLE IF EXISTS `admin_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_sessions` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `admin_id` varchar(36) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `admin_id` (`admin_id`),
  KEY `idx_token` (`token`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `admin_sessions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admin_sessions` WRITE;
/*!40000 ALTER TABLE `admin_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uniq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
('99ada276-af72-11f0-a6da-41bcea632fc0','admin@carefulcatrescue.org','$2b$12$P3x/cVa4MxjfEq8qssOMl.dYqllNXEIpoa2Zgd2FJp8k3MHuQhhF2','Admin','User','super_admin',1,'2025-11-02 15:23:15','2025-10-22 18:11:58'),
('edabbeb5-b8b7-11f0-a6da-41bcea632fc0','aviceen@gmail.com','$2b$12$a0uAZ6.NnRSdZe8kGlqESOO.rFB4rOSVses7UEVAr7UF6CuNw9qGG','Admin','User','super_admin',1,'2025-11-06 12:45:35','2025-11-03 13:20:55');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `adoptions`
--

DROP TABLE IF EXISTS `adoptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `adoptions` (
  `id` varchar(36) NOT NULL,
  `cat_id` varchar(36) NOT NULL,
  `adopter_name` varchar(255) NOT NULL,
  `adopter_email` varchar(255) NOT NULL,
  `adopter_phone` varchar(50) DEFAULT NULL,
  `is_18_or_older` tinyint(1) DEFAULT 0,
  `address_city` varchar(100) DEFAULT NULL,
  `address_state` varchar(50) DEFAULT NULL,
  `address_zip` varchar(20) DEFAULT NULL,
  `phone_home` varchar(50) DEFAULT NULL,
  `phone_cell` varchar(50) DEFAULT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `residence_type` varchar(50) DEFAULT NULL,
  `residence_ownership` enum('own','rent','other') DEFAULT NULL,
  `landlord_verified` tinyint(1) DEFAULT 0,
  `years_at_address` varchar(50) DEFAULT NULL,
  `has_allergies` tinyint(1) DEFAULT 0,
  `num_children` int(11) DEFAULT 0,
  `children_ages` text DEFAULT NULL,
  `home_activity_level` enum('quiet','moderate','active') DEFAULT NULL,
  `has_current_pets` tinyint(1) DEFAULT 0,
  `current_pets_details` text DEFAULT NULL,
  `past_pets_details` text DEFAULT NULL,
  `surrendered_pet_before` tinyint(1) DEFAULT 0,
  `surrender_reason` text DEFAULT NULL,
  `adoption_reason` text DEFAULT NULL,
  `cat_location` varchar(100) DEFAULT NULL,
  `scratching_plan` text DEFAULT NULL,
  `prepared_for_costs` tinyint(1) DEFAULT 0,
  `vet_name` varchar(255) DEFAULT NULL,
  `vet_clinic` varchar(255) DEFAULT NULL,
  `vet_phone` varchar(50) DEFAULT NULL,
  `needs_vet_help` tinyint(1) DEFAULT 0,
  `open_to_bonded_pair` tinyint(1) DEFAULT 0,
  `open_to_special_needs` tinyint(1) DEFAULT 0,
  `adopted_before` tinyint(1) DEFAULT 0,
  `signature` varchar(255) DEFAULT NULL,
  `signature_date` date DEFAULT NULL,
  `agree_info_true` tinyint(1) DEFAULT 0,
  `agree_vet_care` tinyint(1) DEFAULT 0,
  `agree_no_declaw` tinyint(1) DEFAULT 0,
  `agree_return_if_unable` tinyint(1) DEFAULT 0,
  `status` enum('pending','approved','denied') NOT NULL DEFAULT 'pending',
  `adoption_fee` decimal(10,2) DEFAULT NULL,
  `applied_at` datetime NOT NULL DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `denied_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_adoptions_cat_id` (`cat_id`),
  KEY `idx_adoptions_status` (`status`),
  KEY `idx_adoptions_applied_at` (`applied_at`),
  CONSTRAINT `fk_adopt_cat` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adoptions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `adoptions` WRITE;
/*!40000 ALTER TABLE `adoptions` DISABLE KEYS */;
INSERT INTO `adoptions` VALUES
('9575d4ae-ba66-11f0-a6da-41bcea632fc0','9572fe20-ba66-11f0-a6da-41bcea632fc0','Jennifer Smith','jennifer.smith@example.com','555-0201',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,0,0,0,'pending',75.00,'2025-11-05 16:43:40',NULL,NULL,'2025-11-05 16:43:40'),
('9575d9b2-ba66-11f0-a6da-41bcea632fc0','95730177-ba66-11f0-a6da-41bcea632fc0','David Brown','david.brown@example.com','555-0202',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,0,0,0,'pending',75.00,'2025-11-05 16:43:40',NULL,NULL,'2025-11-05 16:43:40'),
('9575daa5-ba66-11f0-a6da-41bcea632fc0','957302a2-ba66-11f0-a6da-41bcea632fc0','Lisa Martinez','lisa.m@example.com','555-0203',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,0,0,0,0,NULL,NULL,0,0,0,0,'approved',75.00,'2025-11-05 16:43:40',NULL,NULL,'2025-11-05 16:43:40');
/*!40000 ALTER TABLE `adoptions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` mediumtext NOT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `published_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `session_type` varchar(50) NOT NULL,
  `number_of_people` int(11) NOT NULL DEFAULT 1,
  `requested_date` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_requested_date` (`requested_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cat_treatments`
--

DROP TABLE IF EXISTS `cat_treatments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cat_treatments` (
  `id` varchar(36) NOT NULL,
  `cat_id` varchar(36) NOT NULL,
  `treatment_type` enum('spay_neuter','vaccine1','vaccine2','vaccine3','vaccine4','rabies','other') NOT NULL,
  `date_administered` date NOT NULL,
  `notes` text DEFAULT NULL,
  `administered_by` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_treatments_cat_id` (`cat_id`),
  CONSTRAINT `fk_treat_cat` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_treatments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cat_treatments` WRITE;
/*!40000 ALTER TABLE `cat_treatments` DISABLE KEYS */;
/*!40000 ALTER TABLE `cat_treatments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cats`
--

DROP TABLE IF EXISTS `cats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cats` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `name` varchar(255) NOT NULL,
  `species` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `sex` char(1) NOT NULL,
  `age` varchar(50) NOT NULL,
  `fee` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL DEFAULT 'Murfreesboro, TN',
  `bio` text NOT NULL,
  `medical` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medical`)),
  `badges` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`badges`)),
  `readiness` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`readiness`)),
  `hero_photo` varchar(500) NOT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `videos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`videos`)),
  `intake_date` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `shelter_tag` varchar(32) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shelter_tag` (`shelter_tag`),
  KEY `idx_status` (`status`),
  KEY `idx_intake_date` (`intake_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cats`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cats` WRITE;
/*!40000 ALTER TABLE `cats` DISABLE KEYS */;
INSERT INTO `cats` VALUES
('5aa8aa29-baac-11f0-a6da-41bcea632fc0','Oliver','Domestic Shorthair','sanctuary','M','8 years',0,'Murfreesboro, TN','Oliver is a sweet senior cat who has special medical needs. He lives permanently at our sanctuary where he receives daily care and lots of love.',NULL,NULL,NULL,'https://placekitten.com/403/403',NULL,NULL,'2025-11-06 01:03:06','2025-11-06 01:03:06','RES-2025-001',NULL,NULL),
('5aa8ae17-baac-11f0-a6da-41bcea632fc0','Bella','Calico','sanctuary','F','10 years',0,'Murfreesboro, TN','Bella is our gentle sanctuary resident who prefers a quiet life. She enjoys sunbathing and gentle pets from our volunteers.',NULL,NULL,NULL,'https://placekitten.com/404/404',NULL,NULL,'2025-11-06 01:03:06','2025-11-06 01:03:06','RES-2025-002',NULL,NULL),
('5aa8aea0-baac-11f0-a6da-41bcea632fc0','Max','Maine Coon Mix','sanctuary','M','12 years',0,'Murfreesboro, TN','Max is a majestic senior who has been with us for years. He loves his routine and is a favorite among our staff and volunteers.',NULL,NULL,NULL,'https://placekitten.com/405/405',NULL,NULL,'2025-11-06 01:03:06','2025-11-06 01:03:06','RES-2025-003',NULL,NULL),
('9572fe20-ba66-11f0-a6da-41bcea632fc0','Whiskers','Domestic Shorthair','adoptable','M','2 years',75,'Murfreesboro, TN','Friendly orange tabby who loves to play',NULL,NULL,NULL,'https://placekitten.com/400/400',NULL,NULL,'2025-11-05 16:43:40','2025-11-05 16:43:40','CAT-2025-001',NULL,NULL),
('95730177-ba66-11f0-a6da-41bcea632fc0','Luna','Siamese','adoptable','F','1 year',75,'Murfreesboro, TN','Beautiful Siamese with blue eyes',NULL,NULL,NULL,'https://placekitten.com/401/401',NULL,NULL,'2025-11-05 16:43:40','2025-11-05 16:43:40','CAT-2025-002',NULL,NULL),
('957302a2-ba66-11f0-a6da-41bcea632fc0','Shadow','Domestic Longhair','adoptable','M','3 years',75,'Murfreesboro, TN','Gentle giant with long black fur',NULL,NULL,NULL,'https://placekitten.com/402/402',NULL,NULL,'2025-11-05 16:43:40','2025-11-05 16:43:40','CAT-2025-003',NULL,NULL),
('a671b278-b9b5-11f0-a6da-41bcea632fc0','Test Cat','','','','',NULL,'Murfreesboro, TN','',NULL,NULL,NULL,'',NULL,NULL,'2025-11-04 19:37:08','2025-11-04 19:37:08',NULL,NULL,NULL),
('fd94480f-b9b5-11f0-a6da-41bcea632fc0','Test Cat','','','','',NULL,'Murfreesboro, TN','',NULL,NULL,NULL,'',NULL,NULL,'2025-11-04 19:39:34','2025-11-04 19:39:34',NULL,NULL,NULL);
/*!40000 ALTER TABLE `cats` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(500) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'unread' COMMENT 'Message status: unread, read, replied, archived',
  `replied_at` timestamp NULL DEFAULT NULL COMMENT 'When admin replied to this message',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_status` (`status`),
  KEY `idx_replied_at` (`replied_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES
('6cd80451-ba65-11f0-a6da-41bcea632fc0','Karen','White','karen.white@example.com',NULL,'Question about adoption process','Hi, I am interested in adopting a cat. Can you tell me more about the adoption process and fees?','unread',NULL,'2025-11-03 16:35:22'),
('6cd807b6-ba65-11f0-a6da-41bcea632fc0','Steven','Harris','steven.harris@example.com',NULL,'Volunteer inquiry','I would like to volunteer at your rescue. What are the requirements and available shifts?','unread',NULL,'2025-11-04 16:35:22'),
('6cd8089f-ba65-11f0-a6da-41bcea632fc0','Michelle','Clark','michelle.clark@example.com',NULL,'Found stray cat','I found a stray cat in my neighborhood. Can you help? The cat seems friendly but has no collar.','unread',NULL,'2025-11-05 16:35:22'),
('6cd808f8-ba65-11f0-a6da-41bcea632fc0','Daniel','Lewis','daniel.lewis@example.com',NULL,'Donation receipt','I made a donation last week but have not received a receipt yet. Can you please send it?','unread',NULL,'2025-11-05 16:35:22'),
('8beb8793-7f2d-4756-afc0-8edf4726975c','Lindsay','Grisanti','lindsay.a.grisanti@vumc.org','6153221399','Other','I’m reaching out to see if your organization might be able to assist with rehoming two senior cats who are in need of loving homes. My name is Lindsay, and I am a social worker with the Vanderbilt-Ingram Cancer Center in Nashville, TN. I am working with a patient who is currently on hospice care and nearing end-of-life given the reason for surrender. \n\nThe cats are around 10 years old. Please see the images attached. The brown cat is Pipa and the white one is sissy. \n\nPlease let me know if you’re able to help in any capacity, or if you need more information about Sissy and Pipa. I truly appreciate all the work your organization does to help animals in need.\n\nThank you so much for your consideration!\n\nWith Kindness, Lindsay','unread',NULL,'2026-02-03 16:58:20'),
('95774343-ba66-11f0-a6da-41bcea632fc0','Karen','White','karen.white@example.com','555-0301','Question about adoption process','Hi, I am interested in adopting a cat. Can you tell me more about the adoption process and fees?','unread',NULL,'2025-11-05 16:43:40'),
('95774607-ba66-11f0-a6da-41bcea632fc0','Steven','Harris','steven.harris@example.com','555-0302','Volunteer inquiry','I would like to volunteer at your rescue. What are the requirements and available shifts?','unread',NULL,'2025-11-05 16:43:40'),
('957746c8-ba66-11f0-a6da-41bcea632fc0','Michelle','Clark','michelle.clark@example.com','555-0303','Found stray cat','I found a stray cat in my neighborhood. Can you help? The cat seems friendly but has no collar.','unread',NULL,'2025-11-05 16:43:40'),
('95774732-ba66-11f0-a6da-41bcea632fc0','Daniel','Lewis','daniel.lewis@example.com','555-0304','Donation receipt','I made a donation last week but have not received a receipt yet. Can you please send it?','unread',NULL,'2025-11-05 16:43:40'),
('f9ed97e6-9ff5-47b2-b007-13334c8f7f2e','Lauren','Guff','laurenguff@yahoo.com','9012191343','General Question','Hi! My daughters and I stayed in the club hotel Nashville this past weekend and found a very sweet stray. We were able to pet and feed the cat but couldn’t bring it back to memphis with us, as we’re already over the pet limit. I’m really hoping to find someone to help this cat and I’m happy to donate to its care. I reached out to to Nashville cat rescue and haven’t heard back. I have pictures and more details about location. Could you please help or point me in the right direction. Thank you!','unread',NULL,'2026-03-30 03:34:56');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `donor_phone` varchar(50) DEFAULT NULL COMMENT 'Donor phone number',
  `donor_address` text DEFAULT NULL COMMENT 'Donor mailing address',
  `amount` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `sponsored_cat_id` varchar(36) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Payment method used',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT 'Transaction ID from payment processor',
  `donation_type` varchar(50) DEFAULT NULL COMMENT 'Type of donation: one-time, monthly, memorial, etc.',
  `is_recurring` tinyint(1) DEFAULT 0 COMMENT 'Is this a recurring donation?',
  `recurring_frequency` varchar(50) DEFAULT NULL COMMENT 'Frequency: monthly, quarterly, yearly',
  `is_anonymous` tinyint(1) DEFAULT 0 COMMENT 'Donor wishes to remain anonymous',
  `message` text DEFAULT NULL COMMENT 'Message from donor',
  `dedication` text DEFAULT NULL COMMENT 'In memory of / In honor of',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Last update timestamp',
  PRIMARY KEY (`id`),
  KEY `sponsored_cat_id` (`sponsored_cat_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`sponsored_cat_id`) REFERENCES `cats` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
INSERT INTO `donations` VALUES
('6cd7399c-ba65-11f0-a6da-41bcea632fc0','Robert','Wilson','robert.wilson@example.com',NULL,NULL,50,'',NULL,'pi_test_12345',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-10-31 16:35:22',NULL),
('6cd73e7a-ba65-11f0-a6da-41bcea632fc0','Amanda','Taylor','amanda.taylor@example.com',NULL,NULL,100,'',NULL,'pi_test_12346',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-02 16:35:22',NULL),
('6cd73f76-ba65-11f0-a6da-41bcea632fc0','James','Anderson','james.anderson@example.com',NULL,NULL,25,'',NULL,'pi_test_12347',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-04 16:35:22',NULL),
('6cd73fbc-ba65-11f0-a6da-41bcea632fc0','Patricia','Thomas','patricia.thomas@example.com',NULL,NULL,200,'',NULL,'pi_test_12348',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-05 16:35:22',NULL),
('6cd73ff9-ba65-11f0-a6da-41bcea632fc0','Christopher','Jackson','chris.jackson@example.com',NULL,NULL,75,'',NULL,'pi_test_12349',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'pending','2025-11-05 16:35:22',NULL),
('95768d6a-ba66-11f0-a6da-41bcea632fc0','Robert','Wilson','robert.wilson@example.com',NULL,NULL,50,'',NULL,'pi_test_12345',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-05 16:43:40',NULL),
('9576909c-ba66-11f0-a6da-41bcea632fc0','Amanda','Taylor','amanda.taylor@example.com',NULL,NULL,100,'',NULL,'pi_test_12346',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-05 16:43:40',NULL),
('95769183-ba66-11f0-a6da-41bcea632fc0','James','Anderson','james.anderson@example.com',NULL,NULL,25,'',NULL,'pi_test_12347',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-05 16:43:40',NULL),
('9576921a-ba66-11f0-a6da-41bcea632fc0','Patricia','Thomas','patricia.thomas@example.com',NULL,NULL,200,'',NULL,'pi_test_12348',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'completed','2025-11-05 16:43:40',NULL),
('9576927a-ba66-11f0-a6da-41bcea632fc0','Christopher','Jackson','chris.jackson@example.com',NULL,NULL,75,'',NULL,'pi_test_12349',NULL,NULL,NULL,0,NULL,0,NULL,NULL,'pending','2025-11-05 16:43:40',NULL);
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `foster_assignments`
--

DROP TABLE IF EXISTS `foster_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `foster_assignments` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `foster_id` varchar(36) NOT NULL,
  `cat_id` varchar(36) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_foster_id` (`foster_id`),
  KEY `idx_cat_id` (`cat_id`),
  CONSTRAINT `foster_assignments_ibfk_1` FOREIGN KEY (`foster_id`) REFERENCES `fosters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `foster_assignments_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foster_assignments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `foster_assignments` WRITE;
/*!40000 ALTER TABLE `foster_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `foster_assignments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `fosters`
--

DROP TABLE IF EXISTS `fosters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fosters` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `housing_type` varchar(50) NOT NULL,
  `has_yard` tinyint(1) DEFAULT 0,
  `own_or_rent` varchar(50) NOT NULL,
  `landlord_approval` tinyint(1) DEFAULT 0,
  `current_pets` text DEFAULT NULL,
  `experience` text NOT NULL,
  `available_space` text NOT NULL,
  `max_cats` int(11) NOT NULL DEFAULT 1,
  `can_foster_kittens` tinyint(1) DEFAULT 0,
  `can_foster_special_needs` tinyint(1) DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fosters`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `fosters` WRITE;
/*!40000 ALTER TABLE `fosters` DISABLE KEYS */;
/*!40000 ALTER TABLE `fosters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `merch_orders`
--

DROP TABLE IF EXISTS `merch_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `merch_orders` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `customer_email` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `products` text NOT NULL,
  `subtotal` int(11) NOT NULL,
  `shipping` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_customer_email` (`customer_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merch_orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `merch_orders` WRITE;
/*!40000 ALTER TABLE `merch_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `merch_orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `merch_products`
--

DROP TABLE IF EXISTS `merch_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `merch_products` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `name` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sizes`)),
  `colors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`colors`)),
  `printful_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merch_products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `merch_products` WRITE;
/*!40000 ALTER TABLE `merch_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `merch_products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `newsletter_subscriptions`
--

DROP TABLE IF EXISTS `newsletter_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscriptions` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `subscribe_date` timestamp NULL DEFAULT current_timestamp(),
  `unsubscribe_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscriptions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `newsletter_subscriptions` WRITE;
/*!40000 ALTER TABLE `newsletter_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `page_visibility`
--

DROP TABLE IF EXISTS `page_visibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_visibility` (
  `page` varchar(100) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_visibility`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `page_visibility` WRITE;
/*!40000 ALTER TABLE `page_visibility` DISABLE KEYS */;
INSERT INTO `page_visibility` VALUES
('about',1),
('adopt',1),
('blog',1),
('contact',1),
('donate',1),
('home',1),
('sponsors',1);
/*!40000 ALTER TABLE `page_visibility` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `slug` varchar(500) NOT NULL,
  `title` varchar(500) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `cover_image_url` varchar(500) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `category` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `og_image_url` varchar(500) DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_video` tinyint(1) DEFAULT 0,
  `video_id` varchar(255) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_duration` int(11) DEFAULT NULL,
  `video_thumbnail` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `video_id` (`video_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_published_at` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsors` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `name` varchar(500) NOT NULL,
  `logo_url` varchar(500) NOT NULL,
  `website_url` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sponsors`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sponsors` WRITE;
/*!40000 ALTER TABLE `sponsors` DISABLE KEYS */;
/*!40000 ALTER TABLE `sponsors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_settings` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `system_settings` WRITE;
/*!40000 ALTER TABLE `system_settings` DISABLE KEYS */;
INSERT INTO `system_settings` VALUES
('99acddde-af72-11f0-a6da-41bcea632fc0','volunteer_applications_enabled','true','Enable or disable volunteer applications','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99acdf59-af72-11f0-a6da-41bcea632fc0','page_adoptions_visible','true','Show or hide adoptions page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace03f-af72-11f0-a6da-41bcea632fc0','page_blog_visible','true','Show or hide blog page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace09f-af72-11f0-a6da-41bcea632fc0','page_shop_visible','true','Show or hide shop page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace0e9-af72-11f0-a6da-41bcea632fc0','page_residents_visible','true','Show or hide residents page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace125-af72-11f0-a6da-41bcea632fc0','page_book_visit_visible','true','Show or hide book visit page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace165-af72-11f0-a6da-41bcea632fc0','page_volunteer_visible','true','Show or hide volunteer page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace1a9-af72-11f0-a6da-41bcea632fc0','page_volunteer_events_visible','true','Show or hide volunteer events page','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace200-af72-11f0-a6da-41bcea632fc0','site_name','Careful Cat Rescue','Website name','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace247-af72-11f0-a6da-41bcea632fc0','site_email','info@carefulcatrescue.org','Contact email','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace286-af72-11f0-a6da-41bcea632fc0','site_phone','(615) 555-0123','Contact phone number','2025-10-22 18:11:58','2025-10-22 18:11:58'),
('99ace2ca-af72-11f0-a6da-41bcea632fc0','site_address','Murfreesboro, TN','Physical address','2025-10-22 18:11:58','2025-10-22 18:11:58');
/*!40000 ALTER TABLE `system_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `volunteer_event_signups`
--

DROP TABLE IF EXISTS `volunteer_event_signups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `volunteer_event_signups` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `volunteer_id` varchar(36) NOT NULL,
  `event_id` varchar(36) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'signed_up',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_signup` (`volunteer_id`,`event_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `volunteer_event_signups_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `volunteer_event_signups_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `volunteer_events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_event_signups`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `volunteer_event_signups` WRITE;
/*!40000 ALTER TABLE `volunteer_event_signups` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_event_signups` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `volunteer_events`
--

DROP TABLE IF EXISTS `volunteer_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `volunteer_events` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `title` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `max_volunteers` int(11) NOT NULL DEFAULT 5,
  `volunteers_needed` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_date` (`date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `volunteer_events` WRITE;
/*!40000 ALTER TABLE `volunteer_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `volunteers`
--

DROP TABLE IF EXISTS `volunteers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `volunteers` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `experience` text NOT NULL,
  `availability` text NOT NULL,
  `interests` text NOT NULL,
  `emergency_contact` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `volunteer_id` varchar(20) DEFAULT NULL,
  `background_check` tinyint(1) DEFAULT 0,
  `orientation_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_volunteer_id` (`volunteer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `volunteers` WRITE;
/*!40000 ALTER TABLE `volunteers` DISABLE KEYS */;
INSERT INTO `volunteers` VALUES
('95721b6b-ba66-11f0-a6da-41bcea632fc0','Sarah','Johnson','sarah.johnson@example.com','555-0101','123 Main St, Springfield, IL 62701','I have 2 cats at home and volunteer at local shelter','Weekends','Cat socialization, feeding','John Johnson 555-0199','pending',NULL,0,0,'2025-11-05 16:43:40'),
('95722110-ba66-11f0-a6da-41bcea632fc0','Michael','Chen','michael.chen@example.com','555-0102','456 Oak Ave, Springfield, IL 62702','Former vet tech with 5 years experience','Weekday evenings','Medical care, fostering','Lisa Chen 555-0299','pending',NULL,0,0,'2025-11-05 16:43:40'),
('95722230-ba66-11f0-a6da-41bcea632fc0','Emily','Rodriguez','emily.r@example.com','555-0103','789 Elm St, Springfield, IL 62703','Cat owner for 10 years','Flexible schedule','Adoption events, transport','Carlos Rodriguez 555-0399','approved',NULL,0,0,'2025-11-05 16:43:40');
/*!40000 ALTER TABLE `volunteers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `waitlist`
--

DROP TABLE IF EXISTS `waitlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `waitlist` (
  `id` varchar(36) NOT NULL DEFAULT uuid(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `preferences` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `waitlist`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `waitlist` WRITE;
/*!40000 ALTER TABLE `waitlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `waitlist` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-24  4:25:39
