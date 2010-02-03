-- MySQL dump 10.10
--
-- Host: localhost    Database: C4_intbonjf_generic_tests
-- ------------------------------------------------------
-- Server version	5.0.22-Debian_0ubuntu6.06.2-log

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
-- Table structure for table `f_availabletags`
--

DROP TABLE IF EXISTS `f_availabletags`;
CREATE TABLE `f_availabletags` (
  `target` varchar(100) collate utf8_bin NOT NULL default '',
  `package` varchar(50) collate utf8_bin NOT NULL default '',
  `component_type` varchar(50) collate utf8_bin NOT NULL default '',
  `tag` varchar(100) collate utf8_bin NOT NULL default '',
  `icon` varchar(100) collate utf8_bin NOT NULL default '',
  `label` varchar(255) collate utf8_bin NOT NULL default '',
  `required` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`component_type`,`package`,`tag`),
  KEY `Package` (`package`),
  KEY `ModuleComponentType` (`package`,`component_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_availabletags`
--


/*!40000 ALTER TABLE `f_availabletags` DISABLE KEYS */;
LOCK TABLES `f_availabletags` WRITE;
INSERT INTO `f_availabletags` VALUES ('*','modules_fred','modules_fred/*','contextual_website_website_modules_fred_page-detail','bad','&modules.fred.bo.general.tag.Detail;',0),('*','modules_fred','modules_fred/*','contextual_website_website_modules_fred_page-list','bad','&modules.fred.bo.general.tag.List;',0),('modules_website','modules_fred','modules_website/page','contextual_website_website_modules_fred_page-detail','bed','&modules.fred.bo.general.tag.Detail;',0),('modules_website','modules_fred','modules_website/page','contextual_website_website_modules_fred_page-list','bed','&modules.fred.bo.general.tag.List;',0),('*','modules_generic','modules_generic/*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('modules_topics','modules_news','modules_page/pagereference','default_modules_news_page-list','documents','&modules.news.backoffice.tag.Page-list;',1),('modules_topics','modules_news','modules_page/pagereference','default_modules_news_page-detail','document_view','&modules.news.backoffice.tag.Page-detail;',1),('*','modules_website','modules_website/website','default_modules_website_default-website','earth_location','&modules.website.bo.tags.Default-website;',0),('*','modules_website','modules_website/menu','contextual_website_website_menu-header','window_sidebar','&modules.website.bo.tags.Header-menu;',0),('*','modules_website','modules_website/menu','contextual_website_website_menu-footer','window_sidebar','&modules.website.bo.tags.Footer-menu;',0),('*','modules_website','modules_website/menu','contextual_website_website_menu-main','window_sidebar','&modules.website.bo.tags.Main-menu;',0),('*','modules_website','modules_website/menu','contextual_website_website_menu-quicklinks','window_sidebar','&modules.website.bo.tags.Quicklinks-menu;',0),('*','modules_website','modules_website/page','contextual_website_website_error404','document_delete','&modules.website.bo.tags.Page-not-found;',0),('*','modules_website','modules_website/page','contextual_website_website_server-error','document_error','&modules.website.bo.tags.Server-error;',0),('*','modules_website','modules_website/page','contextual_website_website_error401-1','document_forbidden','&modules.website.bo.tags.Unauthorized-access;',0),('*','modules_website','modules_website/page','contextual_website_website_print','printer','&modules.website.bo.tags.Print;',0),('*','modules_website','modules_website/page','contextual_website_website_favorite','star_yellow_add','&modules.website.bo.tags.Favorite;',0),('*','modules_website','modules_website/page','contextual_website_website_legal','businessman','&modules.website.bo.tags.Legal-notice;',0),('*','modules_website','modules_website/page','contextual_website_website_help','help','&modules.website.bo.tags.Help;',0),('*','modules_form','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_fred','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_news','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_notification','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_task','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_users','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_website','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0),('*','modules_workflow','*','blocked','document_stop','&modules.generic.backoffice.tag.Blocked;',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_availabletags` ENABLE KEYS */;

--
-- Table structure for table `f_cache`
--

DROP TABLE IF EXISTS `f_cache`;
CREATE TABLE `f_cache` (
  `cache_key` varchar(100) collate utf8_bin NOT NULL,
  `text_value` mediumtext collate utf8_bin NOT NULL,
  PRIMARY KEY  (`cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Table structure for table `f_document`
--

DROP TABLE IF EXISTS `f_document`;
CREATE TABLE `f_document` (
  `document_id` int(11) NOT NULL auto_increment,
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_langs` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_document`
--


/*!40000 ALTER TABLE `f_document` DISABLE KEYS */;
LOCK TABLES `f_document` WRITE;
INSERT INTO `f_document` VALUES (1,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:28:\"Types d\'affichage des listes\";}}'),(2,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.list.bo.general.Module-name;\";}}'),(3,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"Types d\'affichage des champs boolÃ©ens\";}}'),(4,'modules_list/dynamiclist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:17:\"Liste des formats\";}}'),(5,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"Niveau de sÃ©curitÃ© pour mot de passe\";}}'),(6,'modules_list/editablelist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:31:\"CivilitÃ©s pour le module users\";}}'),(7,'modules_list/item','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:2:\"M.\";}}'),(8,'modules_list/item','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:4:\"Mlle\";}}'),(9,'modules_list/item','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:3:\"Mme\";}}'),(10,'modules_users/backenduser','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:32:\"wwwadmin - Administrateur Change\";}}'),(11,'modules_users/backendgroup','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"Utilisateurs Change\";}}'),(12,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"&modules.users.bo.general.Module-name;\";}}'),(13,'modules_users/frontendgroup','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:43:\"Utilisateurs enregistrÃ©s sur le(s) site(s)\";}}'),(14,'modules_list/dynamiclist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"Liste des templates\";}}'),(15,'modules_list/dynamiclist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:28:\"Liste des feuilles de styles\";}}'),(16,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:40:\"VisibilitÃ© des pages dans la navigation\";}}'),(17,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:26:\"Items de menu fonctionnels\";}}'),(18,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:25:\"Liste des types de places\";}}'),(19,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:27:\"Liste des types de triggers\";}}'),(20,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:20:\"Liste des directions\";}}'),(21,'modules_list/staticlist','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:22:\"Liste des types d\'arcs\";}}'),(22,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:45:\"&modules.notification.bo.general.Module-name;\";}}'),(23,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:59:\"Validation Ã  un niveau - Validation du contenu (crÃ©ation)\";}}'),(24,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:60:\"Validation Ã  un niveau - Validation du contenu (exÃ©cution)\";}}'),(25,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:60:\"Validation Ã  un niveau - Validation du contenu (annulation)\";}}'),(26,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:48:\"Validation Ã  un niveau - Activation du document\";}}'),(27,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:42:\"Validation Ã  un niveau - Retour brouillon\";}}'),(28,'modules_notification/notification','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:32:\"Validation Ã  un niveau - Erreur\";}}'),(29,'modules_workflow/workflow','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:23:\"Validation Ã  un niveau\";}}'),(30,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:47:\"Production du contenu -> SÃ©lection du valideur\";}}'),(31,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:22:\"SÃ©lection du valideur\";}}'),(32,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:21:\"Production du contenu\";}}'),(33,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:50:\"SÃ©lection du valideur -> Attente du valideur (OK)\";}}'),(34,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"Attente du valideur\";}}'),(35,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:51:\"SÃ©lection du valideur -> Attente d\'annulation (KO)\";}}'),(36,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:20:\"Attente d\'annulation\";}}'),(37,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:44:\"Attente du valideur -> Validation du contenu\";}}'),(38,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:21:\"Validation du contenu\";}}'),(39,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:52:\"Validation du contenu -> Contenu acceptÃ© (ACCEPTED)\";}}'),(40,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:16:\"Contenu acceptÃ©\";}}'),(41,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:50:\"Validation du contenu -> Contenu refusÃ© (REFUSED)\";}}'),(42,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:15:\"Contenu refusÃ©\";}}'),(43,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"Contenu acceptÃ© -> Activation\";}}'),(44,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Activation\";}}'),(45,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:46:\"Attente d\'annulation -> Annulation du workflow\";}}'),(46,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:22:\"Annulation du workflow\";}}'),(47,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:29:\"Annulation du workflow -> Fin\";}}'),(48,'modules_workflow/place','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:3:\"Fin\";}}'),(49,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:17:\"Activation -> Fin\";}}'),(50,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:29:\"Attente du valideur -> Rappel\";}}'),(51,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:6:\"Rappel\";}}'),(52,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:29:\"Rappel -> Attente du valideur\";}}'),(53,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:35:\"Contenu refusÃ© -> Retour brouillon\";}}'),(54,'modules_workflow/transition','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:16:\"Retour brouillon\";}}'),(55,'modules_workflow/arc','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:23:\"Retour brouillon -> Fin\";}}'),(56,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:41:\"&modules.workflow.bo.general.Module-name;\";}}'),(57,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:42:\"&modules.developer.bo.general.Module-name;\";}}'),(58,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.form.bo.general.Module-name;\";}}'),(59,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.fred.bo.general.Module-name;\";}}'),(60,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"&modules.media.bo.general.Module-name;\";}}'),(61,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.news.bo.general.Module-name;\";}}'),(62,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.task.bo.general.Module-name;\";}}'),(63,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.test.bo.general.Module-name;\";}}'),(64,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"&modules.uixul.bo.general.Module-name;\";}}'),(65,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:40:\"&modules.website.bo.general.Module-name;\";}}'),(66,'modules_website/website','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:6:\"rbs.fr\";}}'),(67,'modules_website/menufolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:46:\"&modules.website.bo.general.Menu-folder-label;\";}}'),(68,'modules_generic/systemfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:47:\"&modules.website.bo.general.System-folder-name;\";}}'),(72,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:37:\"&modules.skin.bo.general.Module-name;\";}}'),(73,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:7:\"Accueil\";}}'),(74,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Entreprise\";}}'),(75,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Solutions+\";}}'),(76,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"WebFactory\";}}'),(77,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:8:\"Produits\";}}'),(78,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:11:\"Recrutement\";}}'),(79,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:33:\"Mission, Innovation, Implantation\";}}'),(81,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:8:\"MÃ©tiers\";}}'),(82,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:8:\"MarchÃ©s\";}}'),(83,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:6:\"Outils\";}}'),(84,'modules_website/menu','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:12:\"Pied de page\";}}'),(86,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:6:\"Moyens\";}}'),(89,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:17:\"Mentions lÃ©gales\";}}'),(90,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";N;}}'),(92,'modules_generic/rootfolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:33:\"&modules..bo.general.Module-name;\";}}'),(94,'modules_website/menuitemfunction','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";N;}}'),(95,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:8:\"Imprimer\";}}'),(96,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"Ajouter aux favoris\";}}'),(97,'modules_website/menuitemfunction','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";N;}}'),(98,'modules_form/form','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:7:\"Contact\";}}'),(99,'modules_form/text','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:7:\"PrÃ©nom\";}}'),(101,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(102,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(103,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(104,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(105,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(106,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(107,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(108,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(109,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(110,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(111,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(112,'modules_media/media','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:6:\"gromit\";}}'),(113,'modules_media/media','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"PF_crittersims_icon\";}}'),(114,'modules_media/media','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:14:\"pingouin_brice\";}}'),(115,'modules_generic/folder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Wallpapers\";}}'),(116,'modules_media/media','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:14:\"051227203814_4\";}}'),(117,'modules_media/media','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:15:\"060809185403_74\";}}'),(118,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"RBS Change\";}}'),(119,'modules_website/topic','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:11:\"RBS AgilÃ©o\";}}'),(120,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"RBS Change\";}}'),(121,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:42:\"RBS Change - Technologies et architectures\";}}'),(122,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:39:\"Produits de Recherche et DÃ©veloppement\";}}'),(123,'modules_website/menu','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:14:\"Menu principal\";}}'),(124,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Entreprise\";}}'),(125,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"Solutions+\";}}'),(126,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"WebFactory\";}}'),(127,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:8:\"Produits\";}}'),(128,'modules_website/menuitemdocument','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:11:\"Recrutement\";}}'),(129,'modules_form/response','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:30:\"RÃ©ponse au formulaire Contact\";}}'),(130,'modules_users/preferences','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:25:\"PrÃ©fÃ©rences utilisateur\";}}'),(131,'modules_users/backenduser','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:29:\"bonjoufr - FrÃ©dÃ©ric BONJOUR\";}}'),(132,'modules_website/preferences','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:28:\"PrÃ©fÃ©rences sites et pages\";}}'),(133,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:36:\"RBS Change - Structure d\'application\";}}'),(134,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:29:\"RBS Change - FonctionnalitÃ©s\";}}'),(135,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:20:\"RBS Change - Modules\";}}'),(136,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:36:\"RBS Change - Structure d\'application\";}}'),(137,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:11:\"RBS AgilÃ©o\";}}'),(138,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:38:\"RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e\";}}'),(139,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:9:\"Solutions\";}}'),(140,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:10:\"WebFactory\";}}'),(141,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:15:\"RÃ©fÃ©rencement\";}}'),(142,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:12:\"Webmarketing\";}}'),(143,'modules_website/page','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:19:\"Nos offres d\'emploi\";}}'),(144,'modules_website/website','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:13:\"rbs-change.fr\";}}'),(145,'modules_website/menufolder','O:8:\"I18nInfo\":2:{s:14:\"\0I18nInfo\0m_vo\";s:2:\"fr\";s:18:\"\0I18nInfo\0m_labels\";a:1:{s:2:\"fr\";s:46:\"&modules.website.bo.general.Menu-folder-label;\";}}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_document` ENABLE KEYS */;

--
-- Table structure for table `f_document_revision`
--

DROP TABLE IF EXISTS `f_document_revision`;
CREATE TABLE `f_document_revision` (
  `document_id` int(11) NOT NULL auto_increment,
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(50) collate utf8_bin NOT NULL default '',
  `document_creationdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `document_modificationdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `document_publicationstatus` varchar(11) collate utf8_bin NOT NULL default '0',
  `document_author` varchar(50) collate utf8_bin NOT NULL default '',
  `document_startpublicationdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `document_endpublicationdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `document_lang` char(2) collate utf8_bin NOT NULL default '',
  `document_lockby` varchar(50) collate utf8_bin NOT NULL default '',
  `document_moduleversion` varchar(6) collate utf8_bin NOT NULL default '',
  `document_refid` int(11) NOT NULL default '0',
  `revision_number` int(11) NOT NULL default '0',
  `revision_type` varchar(50) collate utf8_bin NOT NULL default '',
  `revision_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`document_id`,`revision_number`,`revision_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_document_revision`
--


/*!40000 ALTER TABLE `f_document_revision` DISABLE KEYS */;
LOCK TABLES `f_document_revision` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_document_revision` ENABLE KEYS */;

--
-- Table structure for table `f_history`
--

DROP TABLE IF EXISTS `f_history`;
CREATE TABLE `f_history` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `session_id` varchar(32) collate utf8_bin NOT NULL default '0',
  `pageref` bigint(20) unsigned NOT NULL default '0',
  `request` text collate utf8_bin,
  `tag` varchar(255) collate utf8_bin default NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `data` text collate utf8_bin,
  PRIMARY KEY  (`id`),
  KEY `session_id` (`session_id`,`pageref`,`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_history`
--


/*!40000 ALTER TABLE `f_history` DISABLE KEYS */;
LOCK TABLES `f_history` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_history` ENABLE KEYS */;

--
-- Table structure for table `f_locale`
--

CREATE TABLE IF NOT EXISTS `f_locale` (
  `id` char(255) collate utf8_bin NOT NULL,
  `lang` char(2) collate utf8_bin NOT NULL,
  `content` text collate utf8_bin,
  `package` varchar(255) collate utf8_bin default NULL,
  `overridden` int(11) default NULL,
  `overridable` int(11) default NULL,
  PRIMARY KEY  (`id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Table structure for table `f_permission_compiled`
--

DROP TABLE IF EXISTS `f_permission_compiled`;
CREATE TABLE `f_permission_compiled` (
  `accessor_id` int(11) NOT NULL default '0',
  `permission` varchar(60) collate utf8_bin NOT NULL default '',
  `node_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`accessor_id`,`permission`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_permission_compiled`
--


/*!40000 ALTER TABLE `f_permission_compiled` DISABLE KEYS */;
LOCK TABLES `f_permission_compiled` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_permission_compiled` ENABLE KEYS */;

--
-- Table structure for table `f_relation`
--

DROP TABLE IF EXISTS `f_relation`;
CREATE TABLE `f_relation` (
  `relation_id1` int(11) NOT NULL default '0',
  `relation_id2` int(11) NOT NULL default '0',
  `relation_order` int(11) NOT NULL default '0',
  `relation_name` varchar(50) collate utf8_bin NOT NULL default '',
  `relation_type` varchar(50) collate utf8_bin NOT NULL default '',
  `document_model_id1` varchar(50) collate utf8_bin NOT NULL default '',
  `document_model_id2` varchar(50) collate utf8_bin NOT NULL default '',
  KEY `relation_id1` (`relation_id1`,`relation_name`,`relation_type`),
  KEY `relation_id2` (`relation_id2`,`relation_type`),
  KEY `relation_id2_2` (`relation_id2`,`relation_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_relation`
--


/*!40000 ALTER TABLE `f_relation` DISABLE KEYS */;
LOCK TABLES `f_relation` WRITE;
INSERT INTO `f_relation` VALUES (6,7,0,'itemdocuments','CHILD','modules_list/editablelist','modules_list/item'),(6,8,1,'itemdocuments','CHILD','modules_list/editablelist','modules_list/item'),(6,9,2,'itemdocuments','CHILD','modules_list/editablelist','modules_list/item'),(30,31,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(30,32,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(33,31,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(33,34,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(35,31,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(35,36,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(38,23,0,'creationnotification','CHILD','modules_workflow/transition','modules_notification/notification'),(38,24,0,'terminationnotification','CHILD','modules_workflow/transition','modules_notification/notification'),(38,25,0,'cancellationnotification','CHILD','modules_workflow/transition','modules_notification/notification'),(37,38,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(37,34,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(39,38,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(39,40,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(41,38,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(41,42,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(43,44,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(43,40,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(45,46,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(45,36,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(47,46,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(47,48,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(49,44,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(49,48,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(50,51,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(50,34,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(52,51,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(52,34,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(53,54,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(53,42,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(55,54,0,'transition','CHILD','modules_workflow/arc','modules_workflow/transition'),(55,48,0,'place','CHILD','modules_workflow/arc','modules_workflow/place'),(29,30,0,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,33,1,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,35,2,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,37,3,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,39,4,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,41,5,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,43,6,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,45,7,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,47,8,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,49,9,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,50,10,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,52,11,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,53,12,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,55,13,'arcs','CHILD','modules_workflow/workflow','modules_workflow/arc'),(29,32,0,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,48,1,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,34,2,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,40,3,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,36,4,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,42,5,'places','CHILD','modules_workflow/workflow','modules_workflow/place'),(29,31,0,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(29,38,1,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(29,44,2,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(29,46,3,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(29,51,4,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(29,54,5,'transitions','CHILD','modules_workflow/workflow','modules_workflow/transition'),(74,79,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(66,73,0,'indexPage','CHILD','modules_website/website','modules_website/page'),(118,120,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(77,122,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(124,74,0,'document','CHILD','modules_website/menuitemdocument','modules_website/topic'),(125,75,0,'document','CHILD','modules_website/menuitemdocument','modules_website/topic'),(126,76,0,'document','CHILD','modules_website/menuitemdocument','modules_website/topic'),(127,77,0,'document','CHILD','modules_website/menuitemdocument','modules_website/topic'),(128,78,0,'document','CHILD','modules_website/menuitemdocument','modules_website/topic'),(123,124,0,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(123,125,1,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(123,126,2,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(123,127,3,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(123,128,4,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(98,101,0,'response','CHILD','modules_form/form','modules_form/response'),(98,102,1,'response','CHILD','modules_form/form','modules_form/response'),(98,103,2,'response','CHILD','modules_form/form','modules_form/response'),(98,104,3,'response','CHILD','modules_form/form','modules_form/response'),(98,105,4,'response','CHILD','modules_form/form','modules_form/response'),(98,106,5,'response','CHILD','modules_form/form','modules_form/response'),(98,107,6,'response','CHILD','modules_form/form','modules_form/response'),(98,108,7,'response','CHILD','modules_form/form','modules_form/response'),(98,109,8,'response','CHILD','modules_form/form','modules_form/response'),(98,110,9,'response','CHILD','modules_form/form','modules_form/response'),(98,111,10,'response','CHILD','modules_form/form','modules_form/response'),(98,129,11,'response','CHILD','modules_form/form','modules_form/response'),(131,7,0,'title','CHILD','modules_users/backenduser','modules_list/item'),(11,131,0,'users','CHILD','modules_users/backendgroup','modules_users/backenduser'),(132,131,0,'checkersrecipient','CHILD','modules_website/preferences','modules_users/backenduser'),(119,137,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(75,139,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(76,140,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(78,143,0,'indexPage','CHILD','modules_website/topic','modules_website/page'),(90,89,0,'document','CHILD','modules_website/menuitemdocument','modules_website/page'),(84,94,0,'menuItem','CHILD','modules_website/menu','modules_website/menuitemfunction'),(84,90,1,'menuItem','CHILD','modules_website/menu','modules_website/menuitemdocument'),(84,97,2,'menuItem','CHILD','modules_website/menu','modules_website/menuitemfunction');
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_relation` ENABLE KEYS */;

--
-- Table structure for table `f_settings`
--

DROP TABLE IF EXISTS `f_settings`;
CREATE TABLE `f_settings` (
  `name` varchar(50) collate utf8_bin NOT NULL default '',
  `package` varchar(255) collate utf8_bin NOT NULL default '',
  `userid` bigint(20) unsigned NOT NULL default '0',
  `value` varchar(50) collate utf8_bin NOT NULL default '',
  UNIQUE KEY `NewIndex` (`name`,`package`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_settings`
--


/*!40000 ALTER TABLE `f_settings` DISABLE KEYS */;
LOCK TABLES `f_settings` WRITE;
INSERT INTO `f_settings` VALUES ('root_folder_id','modules_list',0,'2'),('root_folder_id','modules_users',0,'12'),('root_folder_id','modules_notification',0,'22'),('root_folder_id','modules_workflow',0,'56'),('root_folder_id','modules_developer',0,'57'),('root_folder_id','modules_form',0,'58'),('root_folder_id','modules_fred',0,'59'),('root_folder_id','modules_media',0,'60'),('root_folder_id','modules_news',0,'61'),('root_folder_id','modules_task',0,'62'),('root_folder_id','modules_test',0,'63'),('root_folder_id','modules_uixul',0,'64'),('root_folder_id','modules_website',0,'65'),('system_folder_id','modules_website/modules_website',0,'68'),('root_folder_id','modules_skin',0,'72'),('root_folder_id','modules_',0,'92'),('preferences_document_id','modules_users',0,'130'),('preferences_document_id','modules_website',0,'132');
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_settings` ENABLE KEYS */;

--
-- Table structure for table `f_tags`
--

DROP TABLE IF EXISTS `f_tags`;
CREATE TABLE `f_tags` (
  `id` int(11) default NULL,
  `tag` varchar(255) collate utf8_bin default NULL,
  UNIQUE KEY `NewIndex` (`id`,`tag`),
  KEY `NewIndex2` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_tags`
--


/*!40000 ALTER TABLE `f_tags` DISABLE KEYS */;
LOCK TABLES `f_tags` WRITE;
INSERT INTO `f_tags` VALUES (66,'default_modules_website_default-website'),(84,'contextual_website_website_menu-footer'),(95,'contextual_website_website_print'),(96,'contextual_website_website_favorite'),(123,'contextual_website_website_menu-main');
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_tags` ENABLE KEYS */;

--
-- Table structure for table `f_tree`
--

DROP TABLE IF EXISTS `f_tree`;
CREATE TABLE `f_tree` (
  `tree_id` int(11) NOT NULL default '0',
  `tree_left` int(11) NOT NULL default '0',
  `tree_right` int(11) NOT NULL default '0',
  `tree_level` int(11) NOT NULL default '0',
  `document_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`document_id`),
  UNIQUE KEY `tree_node` (`tree_id`,`tree_left`,`tree_right`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `f_tree`
--


/*!40000 ALTER TABLE `f_tree` DISABLE KEYS */;
LOCK TABLES `f_tree` WRITE;
INSERT INTO `f_tree` VALUES (2,2,3,1,1),(2,1,28,0,2),(2,4,5,1,3),(2,6,7,1,4),(2,8,9,1,5),(2,10,11,1,6),(12,2,3,1,11),(12,1,6,0,12),(12,4,5,1,13),(2,12,13,1,14),(2,14,15,1,15),(2,16,17,1,16),(2,18,19,1,17),(2,20,21,1,18),(2,22,23,1,19),(2,24,25,1,20),(2,26,27,1,21),(22,1,14,0,22),(22,2,3,1,23),(22,4,5,1,24),(22,6,7,1,25),(22,8,9,1,26),(22,10,11,1,27),(22,12,13,1,28),(56,2,3,1,29),(56,1,4,0,56),(57,1,2,0,57),(58,1,6,0,58),(59,1,2,0,59),(60,1,14,0,60),(61,1,2,0,61),(62,1,2,0,62),(63,1,2,0,63),(64,1,2,0,64),(65,1,74,0,65),(65,2,67,1,66),(65,3,8,2,67),(65,68,69,1,68),(72,1,2,0,72),(65,9,10,2,73),(65,11,20,2,74),(65,21,24,2,75),(65,25,32,2,76),(65,33,54,2,77),(65,55,58,2,78),(65,12,13,3,79),(65,14,15,3,81),(65,16,17,3,82),(65,59,66,2,83),(65,4,5,3,84),(65,18,19,3,86),(65,60,61,3,89),(92,1,2,0,92),(65,62,63,3,95),(65,64,65,3,96),(58,2,5,1,98),(58,3,4,2,99),(60,2,3,1,112),(60,4,5,1,113),(60,6,7,1,114),(60,8,13,1,115),(60,9,10,2,116),(60,11,12,2,117),(65,34,45,3,118),(65,46,51,3,119),(65,35,36,4,120),(65,37,38,4,121),(65,52,53,3,122),(65,6,7,3,123),(65,39,40,4,133),(65,41,42,4,134),(65,43,44,4,135),(65,47,48,4,137),(65,49,50,4,138),(65,22,23,3,139),(65,26,27,3,140),(65,28,29,3,141),(65,30,31,3,142),(65,56,57,3,143),(65,70,73,1,144),(65,71,72,2,145);
UNLOCK TABLES;
/*!40000 ALTER TABLE `f_tree` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_field`
--

DROP TABLE IF EXISTS `m_form_doc_field`;
CREATE TABLE `m_form_doc_field` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `fieldname` varchar(255) collate utf8_bin default NULL,
  `validators` text collate utf8_bin,
  `required` tinyint(1) default NULL,
  `helptext` text collate utf8_bin,
  `truelabel` varchar(255) collate utf8_bin default NULL,
  `falselabel` varchar(255) collate utf8_bin default NULL,
  `display` varchar(255) collate utf8_bin default NULL,
  `startdate` datetime default NULL,
  `enddate` datetime default NULL,
  `allowedextensions` varchar(255) collate utf8_bin default NULL,
  `mediafolder` int(11) default NULL,
  `datasource` int(11) default NULL,
  `multiple` tinyint(1) default NULL,
  `multiline` tinyint(1) default NULL,
  `cols` int(11) default NULL,
  `rows` int(11) default NULL,
  `maxlength` int(11) default NULL,
  `minlength` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_field`
--


/*!40000 ALTER TABLE `m_form_doc_field` DISABLE KEYS */;
LOCK TABLES `m_form_doc_field` WRITE;
INSERT INTO `m_form_doc_field` VALUES (99,'modules_form/text','PrÃ©nom','wwwadmin','2007-06-14 14:36:11','2007-06-14 14:36:11','PUBLICATED','fr','1.0',NULL,NULL,'prenom','blank:false;maxSize:20;minSize:2',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,50,3,20,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_field` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_field_i18n`
--

DROP TABLE IF EXISTS `m_form_doc_field_i18n`;
CREATE TABLE `m_form_doc_field_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `helptext_i18n` text collate utf8_bin,
  `truelabel_i18n` varchar(255) collate utf8_bin default NULL,
  `falselabel_i18n` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_field_i18n`
--


/*!40000 ALTER TABLE `m_form_doc_field_i18n` DISABLE KEYS */;
LOCK TABLES `m_form_doc_field_i18n` WRITE;
INSERT INTO `m_form_doc_field_i18n` VALUES (99,'fr','PrÃ©nom',NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_field_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_form`
--

DROP TABLE IF EXISTS `m_form_doc_form`;
CREATE TABLE `m_form_doc_form` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `formid` varchar(255) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  `submitlabel` varchar(255) collate utf8_bin default NULL,
  `contactemail` varchar(255) collate utf8_bin default NULL,
  `confirmmessage` text collate utf8_bin,
  `markup` varchar(255) collate utf8_bin default NULL,
  `usebacklink` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_form`
--


/*!40000 ALTER TABLE `m_form_doc_form` DISABLE KEYS */;
LOCK TABLES `m_form_doc_form` WRITE;
INSERT INTO `m_form_doc_form` VALUES (98,'modules_form/form','Contact','wwwadmin','2007-06-14 14:35:41','2007-06-14 18:02:11','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,'Envoyer',NULL,'Merci, {prenom} !',NULL,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_form` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_form_i18n`
--

DROP TABLE IF EXISTS `m_form_doc_form_i18n`;
CREATE TABLE `m_form_doc_form_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `description_i18n` text collate utf8_bin,
  `submitlabel_i18n` varchar(255) collate utf8_bin default NULL,
  `confirmmessage_i18n` text collate utf8_bin,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_form_i18n`
--


/*!40000 ALTER TABLE `m_form_doc_form_i18n` DISABLE KEYS */;
LOCK TABLES `m_form_doc_form_i18n` WRITE;
INSERT INTO `m_form_doc_form_i18n` VALUES (98,'fr','Contact',NULL,'Envoyer','Merci, {prenom} !');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_form_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_group`
--

DROP TABLE IF EXISTS `m_form_doc_group`;
CREATE TABLE `m_form_doc_group` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_group`
--


/*!40000 ALTER TABLE `m_form_doc_group` DISABLE KEYS */;
LOCK TABLES `m_form_doc_group` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_group` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_group_i18n`
--

DROP TABLE IF EXISTS `m_form_doc_group_i18n`;
CREATE TABLE `m_form_doc_group_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `description_i18n` text collate utf8_bin,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_group_i18n`
--


/*!40000 ALTER TABLE `m_form_doc_group_i18n` DISABLE KEYS */;
LOCK TABLES `m_form_doc_group_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_group_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_form_doc_response`
--

DROP TABLE IF EXISTS `m_form_doc_response`;
CREATE TABLE `m_form_doc_response` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `contents` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_form_doc_response`
--


/*!40000 ALTER TABLE `m_form_doc_response` DISABLE KEYS */;
LOCK TABLES `m_form_doc_response` WRITE;
INSERT INTO `m_form_doc_response` VALUES (101,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 12:58:57','2007-06-14 12:58:57','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 12:58:57\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">Fred</field>\n</response>\n'),(102,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 12:59:32','2007-06-14 12:59:32','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 12:59:32\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">Fred</field>\n</response>\n'),(103,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:05:54','2007-06-14 13:05:54','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:05:54\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">Fred</field>\n</response>\n'),(104,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:26:54','2007-06-14 13:26:54','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:26:54\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">fred</field>\n</response>\n'),(105,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:29:29','2007-06-14 13:29:29','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:29:29\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">fred</field>\n</response>\n'),(106,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:29:40','2007-06-14 13:29:40','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:29:40\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">frededdd</field>\n</response>\n'),(107,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:30:00','2007-06-14 13:30:00','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:30:00\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">Toto</field>\n</response>\n'),(108,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:31:06','2007-06-14 13:31:06','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:31:06\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">ss</field>\n</response>\n'),(109,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:39:42','2007-06-14 13:39:42','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:39:42\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">fred</field>\n</response>\n'),(110,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:40:03','2007-06-14 13:40:03','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:40:03\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">gggg</field>\n</response>\n'),(111,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 13:40:13','2007-06-14 13:40:13','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 13:40:13\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">ccc</field>\n</response>\n'),(129,'modules_form/response','RÃ©ponse au formulaire Contact','wwwadmin','2007-06-14 18:02:11','2007-06-14 18:02:11','DRAFT','fr','1.0',NULL,NULL,'<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<response lang=\"fr\" date=\"2007-06-14 18:02:11\">\n  <field name=\"prenom\" label=\"PrÃ©nom\" type=\"text\">dddd</field>\n</response>\n');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_form_doc_response` ENABLE KEYS */;

--
-- Table structure for table `m_fred_doc_doc`
--

DROP TABLE IF EXISTS `m_fred_doc_doc`;
CREATE TABLE `m_fred_doc_doc` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `document_correctionid` int(11) default NULL,
  `document_correctionofid` int(11) default NULL,
  `description` text collate utf8_bin,
  `status` tinyint(1) default NULL,
  `template` varchar(255) collate utf8_bin default NULL,
  `content` text collate utf8_bin,
  `user` int(11) default NULL,
  `age` int(11) default NULL,
  `height` float default NULL,
  `childcount` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_fred_doc_doc`
--


/*!40000 ALTER TABLE `m_fred_doc_doc` DISABLE KEYS */;
LOCK TABLES `m_fred_doc_doc` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_fred_doc_doc` ENABLE KEYS */;

--
-- Table structure for table `m_fred_doc_doc_i18n`
--

DROP TABLE IF EXISTS `m_fred_doc_doc_i18n`;
CREATE TABLE `m_fred_doc_doc_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `document_publicationstatus_i18n` varchar(50) collate utf8_bin default NULL,
  `document_correctionid_i18n` int(11) default NULL,
  `document_correctionofid_i18n` int(11) default NULL,
  `content_i18n` text collate utf8_bin,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_fred_doc_doc_i18n`
--


/*!40000 ALTER TABLE `m_fred_doc_doc_i18n` DISABLE KEYS */;
LOCK TABLES `m_fred_doc_doc_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_fred_doc_doc_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_generic_doc_documentlogentry`
--

DROP TABLE IF EXISTS `m_generic_doc_documentlogentry`;
CREATE TABLE `m_generic_doc_documentlogentry` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `documentid` int(11) default NULL,
  `decision` varchar(255) collate utf8_bin default NULL,
  `actor` varchar(255) collate utf8_bin default NULL,
  `commentary` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_generic_doc_documentlogentry`
--


/*!40000 ALTER TABLE `m_generic_doc_documentlogentry` DISABLE KEYS */;
LOCK TABLES `m_generic_doc_documentlogentry` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_generic_doc_documentlogentry` ENABLE KEYS */;

--
-- Table structure for table `m_generic_doc_folder`
--

DROP TABLE IF EXISTS `m_generic_doc_folder`;
CREATE TABLE `m_generic_doc_folder` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_generic_doc_folder`
--


/*!40000 ALTER TABLE `m_generic_doc_folder` DISABLE KEYS */;
LOCK TABLES `m_generic_doc_folder` WRITE;
INSERT INTO `m_generic_doc_folder` VALUES (2,'modules_generic/rootfolder','&modules.list.bo.general.Module-name;','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,NULL),(12,'modules_generic/rootfolder','&modules.users.bo.general.Module-name;','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,NULL),(22,'modules_generic/rootfolder','&modules.notification.bo.general.Module-name;','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,NULL),(56,'modules_generic/rootfolder','&modules.workflow.bo.general.Module-name;','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DRAFT','fr','1.0',NULL,NULL,NULL),(57,'modules_generic/rootfolder','&modules.developer.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(58,'modules_generic/rootfolder','&modules.form.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(59,'modules_generic/rootfolder','&modules.fred.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(60,'modules_generic/rootfolder','&modules.media.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(61,'modules_generic/rootfolder','&modules.news.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(62,'modules_generic/rootfolder','&modules.task.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(63,'modules_generic/rootfolder','&modules.test.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(64,'modules_generic/rootfolder','&modules.uixul.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(65,'modules_generic/rootfolder','&modules.website.bo.general.Module-name;','wwwadmin','2007-06-13 12:06:16','2007-06-13 12:06:16','DRAFT','fr','1.0',NULL,NULL,NULL),(67,'modules_website/menufolder','&modules.website.bo.general.Menu-folder-label;','wwwadmin','2007-06-13 14:07:02','2007-06-13 14:07:02','PUBLICATED','fr','1.0',NULL,NULL,NULL),(68,'modules_generic/systemfolder','&modules.website.bo.general.System-folder-name;','wwwadmin','2007-06-13 14:07:07','2007-06-13 14:07:07','DRAFT','fr','1.0',NULL,NULL,NULL),(72,'modules_generic/rootfolder','&modules.skin.bo.general.Module-name;','wwwadmin','2007-06-13 14:14:16','2007-06-13 14:14:16','DRAFT','fr','1.0',NULL,NULL,NULL),(92,'modules_generic/rootfolder','&modules..bo.general.Module-name;','wwwadmin','2007-06-13 17:52:42','2007-06-13 17:52:42','DRAFT','fr','1.0',NULL,NULL,NULL),(115,'modules_generic/folder','Wallpapers','wwwadmin','2007-06-14 16:45:58','2007-06-14 16:45:58','DRAFT','fr','1.0',NULL,NULL,NULL),(145,'modules_website/menufolder','&modules.website.bo.general.Menu-folder-label;','wwwadmin','2007-06-15 14:19:12','2007-06-15 14:19:12','PUBLICATED','fr','1.0',NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_generic_doc_folder` ENABLE KEYS */;

--
-- Table structure for table `m_generic_doc_groupacl`
--

DROP TABLE IF EXISTS `m_generic_doc_groupacl`;
CREATE TABLE `m_generic_doc_groupacl` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `group` int(11) default NULL,
  `role` varchar(255) collate utf8_bin default NULL,
  `documentid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_generic_doc_groupacl`
--


/*!40000 ALTER TABLE `m_generic_doc_groupacl` DISABLE KEYS */;
LOCK TABLES `m_generic_doc_groupacl` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_generic_doc_groupacl` ENABLE KEYS */;

--
-- Table structure for table `m_generic_doc_reference`
--

DROP TABLE IF EXISTS `m_generic_doc_reference`;
CREATE TABLE `m_generic_doc_reference` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `refdocumentid` int(11) default NULL,
  `visibility` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_generic_doc_reference`
--


/*!40000 ALTER TABLE `m_generic_doc_reference` DISABLE KEYS */;
LOCK TABLES `m_generic_doc_reference` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_generic_doc_reference` ENABLE KEYS */;

--
-- Table structure for table `m_generic_doc_useracl`
--

DROP TABLE IF EXISTS `m_generic_doc_useracl`;
CREATE TABLE `m_generic_doc_useracl` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `user` int(11) default NULL,
  `role` varchar(255) collate utf8_bin default NULL,
  `documentid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_generic_doc_useracl`
--


/*!40000 ALTER TABLE `m_generic_doc_useracl` DISABLE KEYS */;
LOCK TABLES `m_generic_doc_useracl` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_generic_doc_useracl` ENABLE KEYS */;

--
-- Table structure for table `m_list_doc_item`
--

DROP TABLE IF EXISTS `m_list_doc_item`;
CREATE TABLE `m_list_doc_item` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_list_doc_item`
--


/*!40000 ALTER TABLE `m_list_doc_item` DISABLE KEYS */;
LOCK TABLES `m_list_doc_item` WRITE;
INSERT INTO `m_list_doc_item` VALUES (7,'modules_list/item','M.','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL),(8,'modules_list/item','Mlle','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL),(9,'modules_list/item','Mme','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_list_doc_item` ENABLE KEYS */;

--
-- Table structure for table `m_list_doc_item_i18n`
--

DROP TABLE IF EXISTS `m_list_doc_item_i18n`;
CREATE TABLE `m_list_doc_item_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_list_doc_item_i18n`
--


/*!40000 ALTER TABLE `m_list_doc_item_i18n` DISABLE KEYS */;
LOCK TABLES `m_list_doc_item_i18n` WRITE;
INSERT INTO `m_list_doc_item_i18n` VALUES (7,'fr','M.'),(8,'fr','Mlle'),(9,'fr','Mme');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_list_doc_item_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_list_doc_list`
--

DROP TABLE IF EXISTS `m_list_doc_list`;
CREATE TABLE `m_list_doc_list` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `listid` varchar(255) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  `order` tinyint(1) default NULL,
  `itemvalues` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_list_doc_list`
--


/*!40000 ALTER TABLE `m_list_doc_list` DISABLE KEYS */;
LOCK TABLES `m_list_doc_list` WRITE;
INSERT INTO `m_list_doc_list` VALUES (1,'modules_list/staticlist','Types d\'affichage des listes','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,'modules_form/listdisplaytypes',NULL,NULL,'a:2:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:5:\"Liste\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"list\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:7:\"Boutons\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:7:\"buttons\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(3,'modules_list/staticlist','Types d\'affichage des champs boolÃ©ens','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,'modules_form/booleandisplaytypes',NULL,NULL,'a:2:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:13:\"Boutons radio\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:5:\"radio\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:14:\"Case Ã  cocher\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:8:\"checkbox\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(4,'modules_list/dynamiclist','Liste des formats','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,'modules_media/formats','Liste des formats',NULL,NULL),(5,'modules_list/staticlist','Niveau de sÃ©curitÃ© pour mot de passe','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,'modules_users/securitylevel','Niveau de sÃ©curitÃ© pour les mots de passe des utilisateurs (Basse, moyenne(defaut), Ã©levÃ©)',NULL,'a:3:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:38:\"&modules.users.list.securitylevel.low;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:3:\"low\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:41:\"&modules.users.list.securitylevel.medium;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:6:\"medium\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:39:\"&modules.users.list.securitylevel.high;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"high\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(6,'modules_list/editablelist','CivilitÃ©s pour le module users','system','2007-06-13 14:05:51','2007-06-13 14:05:51','DRAFT','fr','1.0',NULL,NULL,'modules_users/title','CivilitÃ©s pour le module users',0,NULL),(14,'modules_list/dynamiclist','Liste des templates','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_website/templates','Liste des templates',NULL,NULL),(15,'modules_list/dynamiclist','Liste des feuilles de styles','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_website/stylesheets','Liste des feuilles de styles',NULL,NULL),(16,'modules_list/staticlist','VisibilitÃ© des pages dans la navigation','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_website/navigationvisibility',NULL,NULL,'a:3:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:47:\"&modules.website.bo.general.visibility.Visible;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:1:\"1\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:59:\"&modules.website.bo.general.visibility.Hidden-in-menu-only;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:1:\"2\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:46:\"&modules.website.bo.general.visibility.Hidden;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:1:\"0\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(17,'modules_list/staticlist','Items de menu fonctionnels','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_website/menuitemfunctionlist',NULL,NULL,'a:4:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:51:\"&modules.website.bo.general.menuitemfunction.Print;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:14:\"function:print\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:62:\"&modules.website.bo.general.menuitemfunction.Add-to-favorites;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:22:\"function:addToFavorite\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:57:\"&modules.website.bo.general.menuitemfunction.Top-of-page;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"#top\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:3;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:56:\"&modules.website.bo.general.menuitemfunction.To-content;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:8:\"#content\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(18,'modules_list/staticlist','Liste des types de places','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_workflow/placetype','Liste des types de places.',NULL,'a:3:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:40:\"&modules.workflow.bo.general.StartPlace;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";i:1;s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:47:\"&modules.workflow.bo.general.IntermediatePlace;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";i:5;s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:38:\"&modules.workflow.bo.general.EndPlace;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";i:9;s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(19,'modules_list/staticlist','Liste des types de triggers','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_workflow/trigger','Liste des types de triggers existant pour les transitions.',NULL,'a:4:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:43:\"&modules.workflow.bo.general.ManualyByUser;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"USER\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:51:\"&modules.workflow.bo.general.AutomaticallyBySystem;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"AUTO\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:45:\"&modules.workflow.bo.general.ByExternalEvent;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:3:\"MSG\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:3;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:51:\"&modules.workflow.bo.general.AfterTimeLimitExpired;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:4:\"TIME\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(20,'modules_list/staticlist','Liste des directions','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_workflow/direction','Liste des directions que peuvent prendre les arcs.',NULL,'a:2:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:47:\"&modules.workflow.bo.general.PlaceToTransition;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:2:\"IN\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:47:\"&modules.workflow.bo.general.TransitionToPlace;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:3:\"OUT\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}'),(21,'modules_list/staticlist','Liste des types d\'arcs','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'modules_workflow/arctype','Liste des types que peuvent prendre les arcs.',NULL,'a:6:{i:0;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:52:\"&modules.workflow.bo.general.OrdinarySequentialFlow;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:3:\"SEQ\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:1;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:45:\"&modules.workflow.bo.general.ExplicitOrSplit;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:8:\"EX_OR_SP\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:2;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:45:\"&modules.workflow.bo.general.ImplicitOrSplit;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:8:\"IM_OR_SP\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:3;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:36:\"&modules.workflow.bo.general.OrJoin;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:5:\"OR_JO\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:4;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:38:\"&modules.workflow.bo.general.AndSplit;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:6:\"AND_SP\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}i:5;O:19:\"list_StaticListItem\":5:{s:29:\"\0list_StaticListItem\0labelKey\";s:37:\"&modules.workflow.bo.general.AndJoin;\";s:18:\"\0list_Item\0m_label\";N;s:18:\"\0list_Item\0m_value\";s:6:\"AND_JO\";s:17:\"\0list_Item\0m_type\";N;s:17:\"\0list_Item\0m_icon\";N;}}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_list_doc_list` ENABLE KEYS */;

--
-- Table structure for table `m_media_doc_media`
--

DROP TABLE IF EXISTS `m_media_doc_media`;
CREATE TABLE `m_media_doc_media` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `title` varchar(60) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  `credit` text collate utf8_bin,
  `mediatype` varchar(255) collate utf8_bin default NULL,
  `filename` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_media_doc_media`
--


/*!40000 ALTER TABLE `m_media_doc_media` DISABLE KEYS */;
LOCK TABLES `m_media_doc_media` WRITE;
INSERT INTO `m_media_doc_media` VALUES (112,'modules_media/media','gromit','wwwadmin','2007-06-14 16:45:21','2007-06-14 16:45:21','DRAFT','fr','1.0',NULL,NULL,NULL,NULL,NULL,'image','gromit.png'),(113,'modules_media/media','PF_crittersims_icon','wwwadmin','2007-06-14 16:45:35','2007-06-14 16:45:35','DRAFT','fr','1.0',NULL,NULL,NULL,NULL,NULL,'image','PF_crittersims_icon.jpg'),(114,'modules_media/media','pingouin_brice','wwwadmin','2007-06-14 16:45:36','2007-06-14 16:45:36','DRAFT','fr','1.0',NULL,NULL,NULL,NULL,NULL,'image','pingouin_brice.png'),(116,'modules_media/media','051227203814_4','wwwadmin','2007-06-14 16:46:13','2007-06-14 16:46:13','DRAFT','fr','1.0',NULL,NULL,NULL,NULL,NULL,'image','051227203814_4.jpg'),(117,'modules_media/media','060809185403_74','wwwadmin','2007-06-14 16:46:13','2007-06-14 16:46:13','DRAFT','fr','1.0',NULL,NULL,NULL,NULL,NULL,'image','060809185403_74.jpg');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_media_doc_media` ENABLE KEYS */;

--
-- Table structure for table `m_media_doc_media_i18n`
--

DROP TABLE IF EXISTS `m_media_doc_media_i18n`;
CREATE TABLE `m_media_doc_media_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `title_i18n` varchar(60) collate utf8_bin default NULL,
  `description_i18n` text collate utf8_bin,
  `credit_i18n` text collate utf8_bin,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_media_doc_media_i18n`
--


/*!40000 ALTER TABLE `m_media_doc_media_i18n` DISABLE KEYS */;
LOCK TABLES `m_media_doc_media_i18n` WRITE;
INSERT INTO `m_media_doc_media_i18n` VALUES (112,'fr','gromit',NULL,NULL,NULL),(113,'fr','PF_crittersims_icon',NULL,NULL,NULL),(114,'fr','pingouin_brice',NULL,NULL,NULL),(116,'fr','051227203814_4',NULL,NULL,NULL),(117,'fr','060809185403_74',NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_media_doc_media_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_news_doc_news`
--

DROP TABLE IF EXISTS `m_news_doc_news`;
CREATE TABLE `m_news_doc_news` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `summary` text collate utf8_bin,
  `date` datetime default NULL,
  `text` text collate utf8_bin,
  `url` int(11) default NULL,
  `image` int(11) default NULL,
  `dlfile` int(11) default NULL,
  `dlfilename` varchar(255) collate utf8_bin default NULL,
  `dateandschedules` text collate utf8_bin,
  `place` text collate utf8_bin,
  `contact` text collate utf8_bin,
  `accessmap` int(11) default NULL,
  `sortingdate` datetime default NULL,
  `begindate` datetime default NULL,
  `enddate` datetime default NULL,
  `publicationyear` varchar(255) collate utf8_bin default NULL,
  `publicationmonth` varchar(255) collate utf8_bin default NULL,
  `publicationweek` varchar(255) collate utf8_bin default NULL,
  `archiveyear` varchar(255) collate utf8_bin default NULL,
  `archivemonth` varchar(255) collate utf8_bin default NULL,
  `archiveweek` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_news_doc_news`
--


/*!40000 ALTER TABLE `m_news_doc_news` DISABLE KEYS */;
LOCK TABLES `m_news_doc_news` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_news_doc_news` ENABLE KEYS */;

--
-- Table structure for table `m_notification_doc_notification`
--

DROP TABLE IF EXISTS `m_notification_doc_notification`;
CREATE TABLE `m_notification_doc_notification` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `availableparameters` varchar(255) collate utf8_bin default NULL,
  `subject` varchar(255) collate utf8_bin default NULL,
  `body` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_notification_doc_notification`
--


/*!40000 ALTER TABLE `m_notification_doc_notification` DISABLE KEYS */;
LOCK TABLES `m_notification_doc_notification` WRITE;
INSERT INTO `m_notification_doc_notification` VALUES (23,'modules_notification/notification','Validation Ã  un niveau - Validation du contenu (crÃ©ation)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY','[Nouvelle tÃ¢che] {transitionLabel} sur le document {documentId}','Une nouvelle tÃ¢che vous a Ã©tÃ© affectÃ©e :\n\nTÃ¢che : {transitionLabel}\nDocument concernÃ© : {documentLabel} ({documentId})\nCommentaire : {__LAST_COMMENTARY}'),(24,'modules_notification/notification','Validation Ã  un niveau - Validation du contenu (exÃ©cution)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY, decision','[TÃ¢che exÃ©cutÃ©e] {transitionLabel} sur le document {documentId}','Votre dÃ©cision a bien Ã©tÃ© enregistrÃ©e pour la tÃ¢che suivante :\n\nTÃ¢che : {transitionLabel}\nDocument concernÃ© : {documentLabel} ({documentId})\nDÃ©cision : {__LAST_DECISION}\nCommentaire : {__LAST_COMMENTARY}'),(25,'modules_notification/notification','Validation Ã  un niveau - Validation du contenu (annulation)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY','[TÃ¢che annulÃ©e] {transitionLabel} sur le document {documentId}','La tÃ¢che suivante a Ã©tÃ© annulÃ©e :\n\nTÃ¢che : {transitionLabel}\nDocument concernÃ© : {documentLabel} ({documentId})\nCommentaire : {__LAST_COMMENTARY}'),(26,'modules_notification/notification','Validation Ã  un niveau - Activation du document','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY','[Acceptation] le document {documentId} a Ã©tÃ© acceptÃ©','Le document {documentId} a Ã©tÃ© validÃ© et est maintenant actif.'),(27,'modules_notification/notification','Validation Ã  un niveau - Retour brouillon','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY','[Retour brouillon] le document {documentId} est retournÃ© Ã  l\'Ã©tat brouillon','Le document {documentId} a Ã©tÃ© refusÃ©, il revient donc Ã  l\'Ã©tat de brouillon.'),(28,'modules_notification/notification','Validation Ã  un niveau - Erreur','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DEACTIVATED','fr','1.0',NULL,NULL,'documentId, documentLabel, documentLang, workflowId, workflowLabel, transitionId, transitionLabel, currentUserId, currentUserFullname, siteDomain, __LAST_COMMENTARY','[Retour brouillon] le document {documentId} est retournÃ© Ã  l\'Ã©tat brouillon','Une erreur s\'est produite durant le processus de validation du document {documentId}, il revient donc Ã  l\'Ã©tat de brouillon.');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_notification_doc_notification` ENABLE KEYS */;

--
-- Table structure for table `m_task_doc_usertask`
--

DROP TABLE IF EXISTS `m_task_doc_usertask`;
CREATE TABLE `m_task_doc_usertask` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `user` int(11) default NULL,
  `workitem` int(11) default NULL,
  `creationnotification` int(11) default NULL,
  `terminationnotification` int(11) default NULL,
  `cancellationnotification` int(11) default NULL,
  `description` text collate utf8_bin,
  `commentary` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_task_doc_usertask`
--


/*!40000 ALTER TABLE `m_task_doc_usertask` DISABLE KEYS */;
LOCK TABLES `m_task_doc_usertask` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_task_doc_usertask` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_a`
--

DROP TABLE IF EXISTS `m_test_doc_a`;
CREATE TABLE `m_test_doc_a` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_a`
--


/*!40000 ALTER TABLE `m_test_doc_a` DISABLE KEYS */;
LOCK TABLES `m_test_doc_a` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_a` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_addversion`
--

DROP TABLE IF EXISTS `m_test_doc_addversion`;
CREATE TABLE `m_test_doc_addversion` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `folder` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_addversion`
--


/*!40000 ALTER TABLE `m_test_doc_addversion` DISABLE KEYS */;
LOCK TABLES `m_test_doc_addversion` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_addversion` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_addversionmultiple`
--

DROP TABLE IF EXISTS `m_test_doc_addversionmultiple`;
CREATE TABLE `m_test_doc_addversionmultiple` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `folder` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_addversionmultiple`
--


/*!40000 ALTER TABLE `m_test_doc_addversionmultiple` DISABLE KEYS */;
LOCK TABLES `m_test_doc_addversionmultiple` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_addversionmultiple` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_adeux`
--

DROP TABLE IF EXISTS `m_test_doc_adeux`;
CREATE TABLE `m_test_doc_adeux` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_adeux`
--


/*!40000 ALTER TABLE `m_test_doc_adeux` DISABLE KEYS */;
LOCK TABLES `m_test_doc_adeux` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_adeux` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_agenda`
--

DROP TABLE IF EXISTS `m_test_doc_agenda`;
CREATE TABLE `m_test_doc_agenda` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_agenda`
--


/*!40000 ALTER TABLE `m_test_doc_agenda` DISABLE KEYS */;
LOCK TABLES `m_test_doc_agenda` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_agenda` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_aquatre`
--

DROP TABLE IF EXISTS `m_test_doc_aquatre`;
CREATE TABLE `m_test_doc_aquatre` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `c` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_aquatre`
--


/*!40000 ALTER TABLE `m_test_doc_aquatre` DISABLE KEYS */;
LOCK TABLES `m_test_doc_aquatre` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_aquatre` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_atrois`
--

DROP TABLE IF EXISTS `m_test_doc_atrois`;
CREATE TABLE `m_test_doc_atrois` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `c` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_atrois`
--


/*!40000 ALTER TABLE `m_test_doc_atrois` DISABLE KEYS */;
LOCK TABLES `m_test_doc_atrois` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_atrois` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_b`
--

DROP TABLE IF EXISTS `m_test_doc_b`;
CREATE TABLE `m_test_doc_b` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_b`
--


/*!40000 ALTER TABLE `m_test_doc_b` DISABLE KEYS */;
LOCK TABLES `m_test_doc_b` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_b` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_bogusindexable`
--

DROP TABLE IF EXISTS `m_test_doc_bogusindexable`;
CREATE TABLE `m_test_doc_bogusindexable` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `content` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_bogusindexable`
--


/*!40000 ALTER TABLE `m_test_doc_bogusindexable` DISABLE KEYS */;
LOCK TABLES `m_test_doc_bogusindexable` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_bogusindexable` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_bogusindexable_i18n`
--

DROP TABLE IF EXISTS `m_test_doc_bogusindexable_i18n`;
CREATE TABLE `m_test_doc_bogusindexable_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `content_i18n` text collate utf8_bin,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_bogusindexable_i18n`
--


/*!40000 ALTER TABLE `m_test_doc_bogusindexable_i18n` DISABLE KEYS */;
LOCK TABLES `m_test_doc_bogusindexable_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_bogusindexable_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_c`
--

DROP TABLE IF EXISTS `m_test_doc_c`;
CREATE TABLE `m_test_doc_c` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `value` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_c`
--


/*!40000 ALTER TABLE `m_test_doc_c` DISABLE KEYS */;
LOCK TABLES `m_test_doc_c` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_c` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_changestatus`
--

DROP TABLE IF EXISTS `m_test_doc_changestatus`;
CREATE TABLE `m_test_doc_changestatus` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `mail` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_changestatus`
--


/*!40000 ALTER TABLE `m_test_doc_changestatus` DISABLE KEYS */;
LOCK TABLES `m_test_doc_changestatus` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_changestatus` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_componenttypedoublon`
--

DROP TABLE IF EXISTS `m_test_doc_componenttypedoublon`;
CREATE TABLE `m_test_doc_componenttypedoublon` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `folder` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_componenttypedoublon`
--


/*!40000 ALTER TABLE `m_test_doc_componenttypedoublon` DISABLE KEYS */;
LOCK TABLES `m_test_doc_componenttypedoublon` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_componenttypedoublon` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_contact`
--

DROP TABLE IF EXISTS `m_test_doc_contact`;
CREATE TABLE `m_test_doc_contact` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `mail` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_contact`
--


/*!40000 ALTER TABLE `m_test_doc_contact` DISABLE KEYS */;
LOCK TABLES `m_test_doc_contact` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_contact` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_country`
--

DROP TABLE IF EXISTS `m_test_doc_country`;
CREATE TABLE `m_test_doc_country` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `code` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_country`
--


/*!40000 ALTER TABLE `m_test_doc_country` DISABLE KEYS */;
LOCK TABLES `m_test_doc_country` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_country` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_d`
--

DROP TABLE IF EXISTS `m_test_doc_d`;
CREATE TABLE `m_test_doc_d` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_d`
--


/*!40000 ALTER TABLE `m_test_doc_d` DISABLE KEYS */;
LOCK TABLES `m_test_doc_d` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_d` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_defaultvalue`
--

DROP TABLE IF EXISTS `m_test_doc_defaultvalue`;
CREATE TABLE `m_test_doc_defaultvalue` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `componentwithdefaultvalue` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_defaultvalue`
--


/*!40000 ALTER TABLE `m_test_doc_defaultvalue` DISABLE KEYS */;
LOCK TABLES `m_test_doc_defaultvalue` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_defaultvalue` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_folder`
--

DROP TABLE IF EXISTS `m_test_doc_folder`;
CREATE TABLE `m_test_doc_folder` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `folder` varchar(255) collate utf8_bin default NULL,
  `description` varchar(255) collate utf8_bin default NULL,
  `data` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_folder`
--


/*!40000 ALTER TABLE `m_test_doc_folder` DISABLE KEYS */;
LOCK TABLES `m_test_doc_folder` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_folder` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_i18n`
--

DROP TABLE IF EXISTS `m_test_doc_i18n`;
CREATE TABLE `m_test_doc_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `folder` varchar(255) collate utf8_bin default NULL,
  `description` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_i18n`
--


/*!40000 ALTER TABLE `m_test_doc_i18n` DISABLE KEYS */;
LOCK TABLES `m_test_doc_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_i18n_i18n`
--

DROP TABLE IF EXISTS `m_test_doc_i18n_i18n`;
CREATE TABLE `m_test_doc_i18n_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `document_publicationstatus_i18n` varchar(50) collate utf8_bin default NULL,
  `folder_i18n` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_i18n_i18n`
--


/*!40000 ALTER TABLE `m_test_doc_i18n_i18n` DISABLE KEYS */;
LOCK TABLES `m_test_doc_i18n_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_i18n_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_mycomponent`
--

DROP TABLE IF EXISTS `m_test_doc_mycomponent`;
CREATE TABLE `m_test_doc_mycomponent` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_mycomponent`
--


/*!40000 ALTER TABLE `m_test_doc_mycomponent` DISABLE KEYS */;
LOCK TABLES `m_test_doc_mycomponent` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_mycomponent` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_mysubcomponent`
--

DROP TABLE IF EXISTS `m_test_doc_mysubcomponent`;
CREATE TABLE `m_test_doc_mysubcomponent` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `mydata` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_mysubcomponent`
--


/*!40000 ALTER TABLE `m_test_doc_mysubcomponent` DISABLE KEYS */;
LOCK TABLES `m_test_doc_mysubcomponent` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_mysubcomponent` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_news`
--

DROP TABLE IF EXISTS `m_test_doc_news`;
CREATE TABLE `m_test_doc_news` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `email` varchar(255) collate utf8_bin default NULL,
  `date` varchar(255) collate utf8_bin default NULL,
  `text` text collate utf8_bin,
  `theme` int(11) default NULL,
  `url` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_news`
--


/*!40000 ALTER TABLE `m_test_doc_news` DISABLE KEYS */;
LOCK TABLES `m_test_doc_news` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_news` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_primarykey`
--

DROP TABLE IF EXISTS `m_test_doc_primarykey`;
CREATE TABLE `m_test_doc_primarykey` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `summary` varchar(255) collate utf8_bin default NULL,
  `theme` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_primarykey`
--


/*!40000 ALTER TABLE `m_test_doc_primarykey` DISABLE KEYS */;
LOCK TABLES `m_test_doc_primarykey` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_primarykey` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_rate`
--

DROP TABLE IF EXISTS `m_test_doc_rate`;
CREATE TABLE `m_test_doc_rate` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `code` varchar(255) collate utf8_bin default NULL,
  `rate` float default NULL,
  `description` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_rate`
--


/*!40000 ALTER TABLE `m_test_doc_rate` DISABLE KEYS */;
LOCK TABLES `m_test_doc_rate` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_rate` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_ref`
--

DROP TABLE IF EXISTS `m_test_doc_ref`;
CREATE TABLE `m_test_doc_ref` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `designation` varchar(255) collate utf8_bin default NULL,
  `gencode` varchar(255) collate utf8_bin default NULL,
  `codetva` int(11) default NULL,
  `price` int(11) default NULL,
  `stockquantity` varchar(255) collate utf8_bin default NULL,
  `zone` int(11) default NULL,
  `productstatus` varchar(255) collate utf8_bin default NULL,
  `stocklevels` varchar(255) collate utf8_bin default NULL,
  `activatebyerp` varchar(255) collate utf8_bin default NULL,
  `yearcollection` varchar(255) collate utf8_bin default NULL,
  `forcedisponibility` int(11) default NULL,
  `visual` int(11) default NULL,
  `colour` int(11) default NULL,
  `size` int(11) default NULL,
  `type` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_ref`
--


/*!40000 ALTER TABLE `m_test_doc_ref` DISABLE KEYS */;
LOCK TABLES `m_test_doc_ref` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_ref` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_setvaluegetvalue`
--

DROP TABLE IF EXISTS `m_test_doc_setvaluegetvalue`;
CREATE TABLE `m_test_doc_setvaluegetvalue` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_setvaluegetvalue`
--


/*!40000 ALTER TABLE `m_test_doc_setvaluegetvalue` DISABLE KEYS */;
LOCK TABLES `m_test_doc_setvaluegetvalue` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_setvaluegetvalue` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_status`
--

DROP TABLE IF EXISTS `m_test_doc_status`;
CREATE TABLE `m_test_doc_status` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `mail` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_status`
--


/*!40000 ALTER TABLE `m_test_doc_status` DISABLE KEYS */;
LOCK TABLES `m_test_doc_status` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_status` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_statuspriority`
--

DROP TABLE IF EXISTS `m_test_doc_statuspriority`;
CREATE TABLE `m_test_doc_statuspriority` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_statuspriority`
--


/*!40000 ALTER TABLE `m_test_doc_statuspriority` DISABLE KEYS */;
LOCK TABLES `m_test_doc_statuspriority` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_statuspriority` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_tag`
--

DROP TABLE IF EXISTS `m_test_doc_tag`;
CREATE TABLE `m_test_doc_tag` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_tag`
--


/*!40000 ALTER TABLE `m_test_doc_tag` DISABLE KEYS */;
LOCK TABLES `m_test_doc_tag` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_tag` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_type`
--

DROP TABLE IF EXISTS `m_test_doc_type`;
CREATE TABLE `m_test_doc_type` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `code` varchar(255) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_type`
--


/*!40000 ALTER TABLE `m_test_doc_type` DISABLE KEYS */;
LOCK TABLES `m_test_doc_type` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_type` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_useworkflow`
--

DROP TABLE IF EXISTS `m_test_doc_useworkflow`;
CREATE TABLE `m_test_doc_useworkflow` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `document_correctionid` int(11) default NULL,
  `document_correctionofid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_useworkflow`
--


/*!40000 ALTER TABLE `m_test_doc_useworkflow` DISABLE KEYS */;
LOCK TABLES `m_test_doc_useworkflow` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_useworkflow` ENABLE KEYS */;

--
-- Table structure for table `m_test_doc_withdate`
--

DROP TABLE IF EXISTS `m_test_doc_withdate`;
CREATE TABLE `m_test_doc_withdate` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `mydate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_test_doc_withdate`
--


/*!40000 ALTER TABLE `m_test_doc_withdate` DISABLE KEYS */;
LOCK TABLES `m_test_doc_withdate` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_test_doc_withdate` ENABLE KEYS */;

--
-- Table structure for table `m_users_doc_group`
--

DROP TABLE IF EXISTS `m_users_doc_group`;
CREATE TABLE `m_users_doc_group` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `isdefault` tinyint(1) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_users_doc_group`
--


/*!40000 ALTER TABLE `m_users_doc_group` DISABLE KEYS */;
LOCK TABLES `m_users_doc_group` WRITE;
INSERT INTO `m_users_doc_group` VALUES (11,'modules_users/backendgroup','Utilisateurs Change','system','2007-06-13 14:05:51','2007-06-14 21:57:25','DRAFT','fr','1.0',NULL,NULL,'Groupe regroupant tous les utilisateurs pouvant accÃ©der Ã  Change. Ce groupe ne peut pas Ãªtre supprimÃ©.',1),(13,'modules_users/frontendgroup','Utilisateurs enregistrÃ©s sur le(s) site(s)','system','2007-06-13 14:05:52','2007-06-13 14:05:52','DRAFT','fr','1.0',NULL,NULL,'Groupe regroupant tous les utilisateurs enregistrÃ©s sur le(s) site(s). Ce groupe ne peut pas Ãªtre supprimÃ©.',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_users_doc_group` ENABLE KEYS */;

--
-- Table structure for table `m_users_doc_preferences`
--

DROP TABLE IF EXISTS `m_users_doc_preferences`;
CREATE TABLE `m_users_doc_preferences` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `securitylevel` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_users_doc_preferences`
--


/*!40000 ALTER TABLE `m_users_doc_preferences` DISABLE KEYS */;
LOCK TABLES `m_users_doc_preferences` WRITE;
INSERT INTO `m_users_doc_preferences` VALUES (130,'modules_users/preferences','PrÃ©fÃ©rences utilisateur','wwwadmin','2007-06-14 21:55:55','2007-06-14 21:55:55','PUBLICATED','fr','1.0',NULL,NULL,'medium');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_users_doc_preferences` ENABLE KEYS */;

--
-- Table structure for table `m_users_doc_user`
--

DROP TABLE IF EXISTS `m_users_doc_user`;
CREATE TABLE `m_users_doc_user` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `title` int(11) default NULL,
  `firstname` varchar(255) collate utf8_bin default NULL,
  `lastname` varchar(255) collate utf8_bin default NULL,
  `email` varchar(255) collate utf8_bin default NULL,
  `login` varchar(255) collate utf8_bin default NULL,
  `passwordmd5` varchar(255) collate utf8_bin default NULL,
  `isroot` tinyint(1) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_users_doc_user`
--


/*!40000 ALTER TABLE `m_users_doc_user` DISABLE KEYS */;
LOCK TABLES `m_users_doc_user` WRITE;
INSERT INTO `m_users_doc_user` VALUES (10,'modules_users/backenduser','wwwadmin - Administrateur Change','system','2007-06-13 14:05:51','2007-06-13 14:05:51','PUBLICATED','fr','1.0','2000-01-01 00:00:00',NULL,0,'Administrateur','Change','support@devlinux.france.rbs.fr','wwwadmin','32ed280e3e30451c8ce45f65434deb1a',1),(131,'modules_users/backenduser','bonjoufr - FrÃ©dÃ©ric BONJOUR','wwwadmin','2007-06-14 21:57:24','2007-06-14 21:57:24','DRAFT','fr','1.0',NULL,NULL,7,'FrÃ©dÃ©ric','BONJOUR','frederic.bonjour@rbs.fr','bonjoufr','e99a18c428cb38d5f260853678922e03',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_users_doc_user` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_menu`
--

DROP TABLE IF EXISTS `m_website_doc_menu`;
CREATE TABLE `m_website_doc_menu` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `menuitemserialized` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_menu`
--


/*!40000 ALTER TABLE `m_website_doc_menu` DISABLE KEYS */;
LOCK TABLES `m_website_doc_menu` WRITE;
INSERT INTO `m_website_doc_menu` VALUES (84,'modules_website/menu','Pied de page','wwwadmin','2007-06-13 14:58:06','2007-06-15 09:53:10','PUBLICATED','fr','1.0',NULL,NULL,'a:3:{i:0;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:94;s:38:\"\0website_persistent_MenuItemBean\0popup\";N;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";s:14:\"function:print\";}i:1;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:89;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:1;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";s:20:\"width:800,height:500\";s:36:\"\0website_persistent_MenuItemBean\0url\";N;}i:2;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:97;s:38:\"\0website_persistent_MenuItemBean\0popup\";N;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";s:22:\"function:addToFavorite\";}}'),(123,'modules_website/menu','Menu principal','wwwadmin','2007-06-14 19:12:35','2007-06-14 19:12:43','PUBLICATED','fr','1.0',NULL,NULL,'a:5:{i:0;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:74;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:0;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";N;}i:1;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:75;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:0;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";N;}i:2;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:76;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:0;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";N;}i:3;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:77;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:0;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";N;}i:4;O:31:\"website_persistent_MenuItemBean\":4:{s:43:\"\0website_persistent_MenuItemBean\0documentId\";i:78;s:38:\"\0website_persistent_MenuItemBean\0popup\";b:0;s:48:\"\0website_persistent_MenuItemBean\0popupParameters\";N;s:36:\"\0website_persistent_MenuItemBean\0url\";N;}}');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_menu` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_menuitem`
--

DROP TABLE IF EXISTS `m_website_doc_menuitem`;
CREATE TABLE `m_website_doc_menuitem` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `document` int(11) default NULL,
  `popup` tinyint(1) default NULL,
  `popupparameters` text collate utf8_bin,
  `url` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_menuitem`
--


/*!40000 ALTER TABLE `m_website_doc_menuitem` DISABLE KEYS */;
LOCK TABLES `m_website_doc_menuitem` WRITE;
INSERT INTO `m_website_doc_menuitem` VALUES (90,'modules_website/menuitemdocument',NULL,'wwwadmin','2007-06-13 15:18:13','2007-06-15 09:53:10','ACTIVE','fr','1.0',NULL,NULL,89,1,'width:800,height:500',NULL),(94,'modules_website/menuitemfunction',NULL,'wwwadmin','2007-06-13 18:36:06','2007-06-13 18:36:06','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,'function:print'),(97,'modules_website/menuitemfunction',NULL,'wwwadmin','2007-06-13 18:49:29','2007-06-13 18:49:29','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,'function:addToFavorite'),(124,'modules_website/menuitemdocument','Entreprise','wwwadmin','2007-06-14 19:12:43','2007-06-14 19:12:43','ACTIVE','fr','1.0',NULL,NULL,74,0,NULL,NULL),(125,'modules_website/menuitemdocument','Solutions+','wwwadmin','2007-06-14 19:12:43','2007-06-14 19:12:43','ACTIVE','fr','1.0',NULL,NULL,75,0,NULL,NULL),(126,'modules_website/menuitemdocument','WebFactory','wwwadmin','2007-06-14 19:12:43','2007-06-14 19:12:43','ACTIVE','fr','1.0',NULL,NULL,76,0,NULL,NULL),(127,'modules_website/menuitemdocument','Produits','wwwadmin','2007-06-14 19:12:43','2007-06-14 19:12:43','ACTIVE','fr','1.0',NULL,NULL,77,0,NULL,NULL),(128,'modules_website/menuitemdocument','Recrutement','wwwadmin','2007-06-14 19:12:43','2007-06-14 19:12:43','ACTIVE','fr','1.0',NULL,NULL,78,0,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_menuitem` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_menuitem_i18n`
--

DROP TABLE IF EXISTS `m_website_doc_menuitem_i18n`;
CREATE TABLE `m_website_doc_menuitem_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_menuitem_i18n`
--


/*!40000 ALTER TABLE `m_website_doc_menuitem_i18n` DISABLE KEYS */;
LOCK TABLES `m_website_doc_menuitem_i18n` WRITE;
INSERT INTO `m_website_doc_menuitem_i18n` VALUES (90,'fr','Mentions lÃ©gales'),(94,'fr','Imprimer'),(97,'fr','Ajouter aux favoris'),(124,'fr','Entreprise'),(125,'fr','Solutions+'),(126,'fr','WebFactory'),(127,'fr','Produits'),(128,'fr','Recrutement');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_menuitem_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_page`
--

DROP TABLE IF EXISTS `m_website_doc_page`;
CREATE TABLE `m_website_doc_page` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `navigationtitle` varchar(80) collate utf8_bin default NULL,
  `metatitle` text collate utf8_bin,
  `description` text collate utf8_bin,
  `keywords` text collate utf8_bin,
  `indexingstatus` tinyint(1) default NULL,
  `template` varchar(255) collate utf8_bin default NULL,
  `content` text collate utf8_bin,
  `skin` int(11) default NULL,
  `navigationvisibility` int(11) default NULL,
  `isindexpage` tinyint(1) default NULL,
  `ishomepage` tinyint(1) default NULL,
  `currentversionid` int(11) default NULL,
  `document_correctionid` int(11) default NULL,
  `document_correctionofid` int(11) default NULL,
  `versionofid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_page`
--


/*!40000 ALTER TABLE `m_website_doc_page` DISABLE KEYS */;
LOCK TABLES `m_website_doc_page` WRITE;
INSERT INTO `m_website_doc_page` VALUES (73,'modules_website/page','Accueil','wwwadmin','2007-06-13 14:14:26','2007-06-14 14:37:16','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Accueil','Accueil',NULL,NULL,1,'tplHome','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplHome\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"header\" /><div id=\"center\" orient=\"horizontal\"><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"header\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"header\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout3\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate3\"><grid><columns><column /></columns><rows><row id=\"freeContainer4\" /><row id=\"freeContainer5\" /><row id=\"freeContainer3\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer4\" label=\"Free4\" editable=\"true\" /><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer4\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h2 class=\"heading-two\">Bienvenue sur le site de RBS !</h2>]]></wblock><wblock type=\"modules_form_form\" target=\"freeContainer5\" ref=\"98\" lang=\"fr\" display=\"class: modules-form-form;\" editable=\"true\" movable=\"true\" resizable=\"true\" /></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,1,0,0,NULL,NULL),(79,'modules_website/page','Mission, Innovation, Implantation','wwwadmin','2007-06-13 14:37:59','2007-06-13 14:40:23','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Mission, Innovation, Implantation','Mission, Innovation, Implantation',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Mission, Innovation, Implantation</h1>\n	\n	<h2>Mission</h2>\n  	<p class=\"normal\">Prestataire\nde service en ingÃ©nierie informatique, RBS maÃ®trise toutes les facettes\nde la gestion des systÃ¨mes dâ€™information. La multiplicitÃ© des flux au\nsein de lâ€™entreprise dâ€™une part et ses Ã©changes avec lâ€™extÃ©rieur\ndâ€™autre part, fait appel Ã  des mÃ©tiers dâ€™expertises et Ã  des\ntechnologies trÃ¨s diffÃ©rentes : Gestion de la Relation Client, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Solution Intranet RBS AgilÃ©o\">Intranet</a>, <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenu Web RBS Change\">Web</a>, <a href=\"http://www.rbs.fr/new/produits/moby/index.php\" title=\"Solution de mobilitÃ©\">MobilitÃ©</a>,  <a href=\"http://www.rbs.fr/new/solutions/infrastructures/index.php\" title=\"Infrastructures informatiques\">Infrastructures</a> Ainsi, RBS est Ã  la fois spÃ©cialiste et polyvalent.</p>\n  	<p class=\"normal\">Pour\nfaire bÃ©nÃ©ficier ses clients des Ã©volutions les plus rÃ©centes, RBS est\ndotÃ©e dâ€™un service de Recherche et DÃ©veloppement de pointe.</p>\n  	<p class=\"normal\">Ce\nvÃ©ritable rayonnement de compÃ©tences sâ€™appuie sur un savoir-faire\nÃ©prouvÃ© dans le domaine des systÃ¨mes informatiques, de la distribution\nde matÃ©riels et de logiciels et se dÃ©ploie jusquâ€™Ã  la conception et\nlâ€™intÃ©gration de <a href=\"http://www.rbs.fr/new/produits/index.php\" title=\"Logiciels RBS\">logiciels RBS</a>.</p>\n  	<p class=\"normal\">Aujourdâ€™hui, lâ€™expertise de RBS lui ouvre tout naturellement la voie vers le mÃ©tier dâ€™Ã©diteur.</p>\n	\n	 <h2>Innovation</h2>\n	<p class=\"normal\">Le caractÃ¨re innovant des projets de RBS est dÃ©sormais reconnu et saluÃ©, notamment par l\'Agence nationale pour l\'Innovation.</p>\n	<ul><li>Depuis\n2002, elle soutient les projets d\'innovation de la sociÃ©tÃ©, entre\nautres dans les domaines de la mobilitÃ©, et celui de la biomÃ©trie.</li><li>En 2005, OSEO-<abbr title=\"Agence nationale de valorisation de la recherche\">ANVAR</abbr><sup>*</sup> est entrÃ©e au capital de RBS en convertissant le montant de ses aides en bons de souscription d\'actions.</li><li>En 2006, RBS a obtenu le label Entreprise Innovante.</li></ul>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,0,NULL,NULL),(81,'modules_website/page','MÃ©tiers','wwwadmin','2007-06-13 14:40:57','2007-06-13 15:16:15','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'MÃ©tiers','MÃ©tiers',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">MÃ©tiers</h1><h2>Le service en ingÃ©nierie informatique</h2>\n	<p class=\"content-tagline\">Une SSII de rÃ©fÃ©rence</p> \n	<p class=\"normal\">Sur\nle marchÃ© des sociÃ©tÃ©s informatiques de conseil, RBS occupe sans\nconteste une place Ã  part, qu\'elle doit Ã  son sÃ©rieux, sa rigueur et sa\ntechnicitÃ© mais aussi Ã  la forte personnalitÃ© de ses personnels,\npassionnÃ©s, rÃ©actifs, disponibles et en prise directe avec le terrain.</p>\n	<p class=\"normal\">L\'origine\nde ses fondateurs, issus du monde de l\'entreprise, ainsi que la grande\npolyvalence de son Ã©quipe, mÃªlant des ingÃ©nieurs de haut niveau et des\nprofessionnels expÃ©rimentÃ©s, contribuent Ã  ce positionnement inÃ©dit.</p>\n	<p class=\"normal\">Depuis\n1997, date de sa crÃ©ation, RBS s\'est imposÃ©e comme une SSII de\nrÃ©fÃ©rence dans ses diffÃ©rents domaines de spÃ©cialitÃ©. Aujourd\'hui, prÃ¨s\nde 150 personnes se consacrent au conseil et prennent en charge des\nprojets complets, de la conception de solutions spÃ©cifiques pour chaque\nclient jusqu\'Ã  leur rÃ©alisation.</p>\n\n  	<h2>La conception de logiciels</h2>\n	<p class=\"content-tagline\">Une conjugaison de talent et de savoir-faire</p>\n	<p class=\"normal\">Aujourd\'hui, RBS offre sa propre solution dans les domaines du web, des applications collaboratives et de la mobilitÃ©.</p>\n	<p class=\"normal\">De\nl\'Ã©laboration des bases de donnÃ©es en passant par la dÃ©finition de\nl\'architecture et jusqu\'Ã  la programmation, l\'Ã©quipe de dÃ©veloppeurs\nRBS a su Ã©laborer des progiciels qui rivalisent avec les plus grands : <a href=\"http://www.rbs.fr/new/produits/moby/index.php\" title=\"Solution de mobilitÃ© RBS Moby\">RBS Moby</a>, <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenu Web RBS Change\">RBS Change</a>, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Solution collaborative Intranet/Extranet/GED RBS AgilÃ©o\">RBS AgilÃ©o</a>, <a href=\"http://www.rbs.fr/new/produits/partagenda/index.php\" title=\"Agenda partagÃ© RBS Partagenda\">RBS Partagenda</a>â€¦</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,0,NULL,NULL),(82,'modules_website/page','MarchÃ©s','wwwadmin','2007-06-13 14:41:53','2007-06-13 14:42:15','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'MarchÃ©s','MarchÃ©s',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">MarchÃ©s</h1>\n  	<h2>Des affinitÃ©s avec tous les secteurs d\'activitÃ©</h2>\n	<p class=\"normal\">RBS touche des entreprises extrÃªmement diverses qui se distinguent par :</p>\n	<ul><li>leur taille (TPE, PMI/PME ou grosses entreprises industrielles)</li><li>leur secteur d\'activitÃ©</li><li>leurs implantations</li><li>et leurs marchÃ©s qui peuvent Ãªtre locaux, rÃ©gionaux, nationaux ou internationaux.</li></ul>\n	<p class=\"normal\">Cette\ncapacitÃ© d\'adaptation rÃ©side dans la souplesse des Ã©quipes, leur\ndiversitÃ© d\'origine, leur pluridisciplinaritÃ©, mais aussi dans la\nnature mÃªme des produits qu\'elles dÃ©veloppent et commercialisent :\nouverts et modulables.</p>\n\n  	<h2>Des rÃ©ponses institutionnelles adaptÃ©es au Service Public</h2>\n	<p class=\"normal\">CollectivitÃ©s\nlocales, mairies, organismes, universitÃ©s, conseils gÃ©nÃ©raux,\nconsultent rÃ©guliÃ¨rement RBS, car les solutions apportÃ©es rÃ©pondent aux\nattentes de leurs publics : simplicitÃ© d\'accÃ¨s Ã  des informations\nsouvent nombreuses et complexes, lisibilitÃ© de leurs offres de\nservices, interactivitÃ©, etc.</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(86,'modules_website/page','Moyens','wwwadmin','2007-06-13 15:11:57','2007-06-13 15:15:14','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Moyens','Moyens',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Moyens</h1><h2>Des Ã©quipes Ã©nergiques</h2>\n	<p class=\"normal\">RBS compte aujourd\'hui un effectif\nde 150 personnes, aux compÃ©tences Ã  la fois pointues et diversifiÃ©es\ndans les domaines administratif, technique, commercial et de\nl\'ingÃ©nierie informatique. Fait exceptionnel pour une entreprise de la\ntaille de RBS, une quinzaine d\'ingÃ©nieurs constituent un pÃ´le recherche\net dÃ©veloppement.</p>\n	<p class=\"normal\">Le dynamisme et l\'Ã©lan des plus jeunes (la\nmoyenne d\'Ã¢ge est de 32 ans), conjuguÃ©s Ã  l\'expÃ©rience et la maturitÃ©\ndes seniors expliquent certainement la croissance particuliÃ¨rement\nrapide de la sociÃ©tÃ©.</p>\n \n	<h2>Un bÃ¢timent dÃ©diÃ© et une adresse accessible</h2>\n	<p class=\"normal\">Depuis\njanvier 2006, RBS dispose d\'un bÃ¢timent spÃ©cialement dÃ©diÃ© Ã  son\nactivitÃ©. Facile d\'accÃ¨s, il est situÃ© en pÃ©riphÃ©rie de Strasbourg,\ndans l\'immÃ©diate proximitÃ© de l\'aÃ©roport international d\'Entzheim.</p>\n	<p class=\"normal\">Cet\nespace de 2000 m2, Ã  la fois cloisonnÃ© et ouvert, offre des conditions\nidÃ©ales de travail sur deux niveaux : le niveau supÃ©rieur est dotÃ©\nd\'une coursive intÃ©rieure, permettant d\'embrasser du regard tout le\nniveau bas. Cette structure architecturale esthÃ©tique et fonctionnelle\npermet de capter toute la lumiÃ¨re naturelle. Ainsi, toutes les zones de\ntravail, de mÃªme que les diverses salles de rÃ©union de toutes tailles\ndisposÃ©es un peu partout dans le bÃ¢timent, sont propices Ã  la\nconcentration.</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,0,NULL,NULL),(89,'modules_website/page','Mentions lÃ©gales','wwwadmin','2007-06-13 15:17:17','2007-06-13 15:17:45','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Mentions lÃ©gales','Mentions lÃ©gales',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h2>Editeur</h2>\n		<p class=\"normal\">Vous Ãªtes actuellement connectÃ© au site Internet\nde la sociÃ©tÃ© Ready Business System SA (RBS). Directeur de la\npublication : Daniel Romani, PrÃ©sident - Directeur gÃ©nÃ©ral.</p>\n		\n		<h2>Loi informatique et libertÃ©s</h2>\n		<p class=\"normal\">Vous\ndisposez d\'un droit d\'accÃ¨s, de modification, de rectification et de\nsuppression des donnÃ©es qui vous concernent (art. 34 de la loi\n\"Informatique et LibertÃ©s\" du 6 janvier 1978). Pour exercer ce droit,\nadressez-vous par courrier Ã  :</p>\n		<p class=\"normal\">RBS <br/>\n		AÃ©roparc 1 <br/>\n		11, rue Icare <br/>\n		Strasbourg - Entzheim  <br/>\n		67836 Tanneries Cedex <br/>\n		</p><p class=\"normal\">Ou utilisez notre formulaire <a href=\"http://www.rbs.fr/new/contact.php\" title=\"Contact\">contact</a>.</p>\n		\n		<h2>La protection des donnÃ©es personnelles</h2> \n		<ul><li>Aucune information personnelle n\'est collectÃ©e Ã  votre insu</li><li>Aucune information personnelle n\'est cÃ©dÃ©e Ã  des tiers</li><li>Aucune information personnelle n\'est utilisÃ©e Ã  des fins non prÃ©vues</li></ul>\n\n		<h2>DÃ©claration</h2>\n		<p class=\"normal\">Le prÃ©sent site a fait l\'objet d\'une dÃ©claration Ã  la CNIL, sous le numÃ©ro 833525.</p>\n \n		<h2>Messagerie</h2>\n		<p class=\"normal\">Les\nmessages envoyÃ©s sur le rÃ©seau internet peuvent Ãªtre interceptÃ©s. Ne\ndivulguez pas d\'informations personnelles inutiles ou sensibles. Si\nvous souhaitez nous communiquer de telles informations, utilisez\nimpÃ©rativement la voie postale.</p>\n \n		<h2>Droit dâ€™auteur â€“ Copyright</h2>\n		<p class=\"normal\">L\'ensemble\nde ce site relÃ¨ve de la lÃ©gislation franÃ§aise et internationale sur le\ndroit d\'auteur et la propriÃ©tÃ© intellectuelle. Tous les droits de\nreproduction sont rÃ©servÃ©s, y compris pour les documents\ntÃ©lÃ©chargeables et les reprÃ©sentations iconographiques et\nphotographiques.</p> \n		<p class=\"normal\">La reproduction de tout ou partie de ce\nsite sur un support Ã©lectronique quel qu\'il soit est formellement\ninterdite sauf autorisation expresse du directeur de la publication.</p> \n		<p class=\"normal\">La reproduction des textes de ce site sur un support papier est strictement interdite, sauf autorisation expresse de notre part.</p>\n		<p class=\"normal\">RBS se rÃ©serve le droit de modifier le contenu de ce site Ã  tout moment et sans prÃ©avis.</p>\n		<p class=\"normal\">Les marques citÃ©es sur ce site sont dÃ©posÃ©es par les sociÃ©tÃ©s qui en sont propriÃ©taires.</p> \n		<p class=\"normal\">Ready Business System (RBS) Â®, AgilÃ©o, Partagenda Â®, WebEdit Â® et Moby Â® sont des marques dÃ©posÃ©es par Ready Business System SA.</p>\n		<p class=\"normal\">Ready Business System SA : RCS Strasbourg B 402 777 643</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(95,'modules_website/page','Imprimer','wwwadmin','2007-06-13 18:42:14','2007-06-13 18:43:04','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Imprimer','Imprimer',NULL,NULL,0,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[Pour imprimer une page, utilisez le menu \"Imprimer\" de votre navigateur ou le raccourci clavier CTRL+p.]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,0,0,0,0,NULL,NULL,NULL),(96,'modules_website/page','Ajouter aux favoris','wwwadmin','2007-06-13 18:48:10','2007-06-13 18:49:07','PUBLICATED','fr','1.0','2007-06-13 00:00:00',NULL,'Ajouter aux favoris','Ajouter aux favoris',NULL,NULL,0,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[Pour ajouter une page de ce site web Ã  vos liens favoris, utilisez le menu \"Ajouter aux favoris\" de votre navigateur ou le raccourci clavier CTRL+d.]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,0,0,0,0,NULL,NULL,NULL),(120,'modules_website/page','RBS Change','wwwadmin','2007-06-14 18:50:03','2007-06-14 18:50:23','PUBLICATED','fr','1.0','2007-06-14 00:00:00',NULL,'RBS Change','RBS Change',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<div id=\"contenu-main\">\n  	<h1 id=\"maintarget\">RBS Change</h1>\n  	<p class=\"content-tagline\">Gestion de contenus web</p>\n  	<p class=\"normal\">RBS Change est un progiciel de crÃ©ation et de gestion de contenu normalisÃ© pour le web. Il permet une gestion multi-sites\net multi-langues. Chaque site peut possÃ©der sa propre charte graphique ou partager la mÃªme charte que les autres sites.</p>\n	<p class=\"normal\">Change allie puissance et convivialitÃ© : maÃ®trise de la publication, intÃ©gration des contraintes de <a href=\"http://www.rbs.fr/new/webfactory/referencement.php\" title=\"RÃ©fÃ©rencement \">rÃ©fÃ©rencement</a>, conformitÃ© aux\ncritÃ¨res dâ€™accessibilitÃ©, etc.</p>\n	<p class=\"normal\">Change permet notamment :</p>\n	<ul><li>la gestion de lâ€™arborescence</li><li>lâ€™Ã©dition de texte enrichi</li><li>la gestion des contenus par module</li></ul>\n	<p class=\"normal\">Les contenus dâ€™un site Internet peuvent provenir de plusieurs sources :</p>\n	<ul><li>dÃ©jÃ  existants et structurÃ©s dans des bases de donnÃ©es ERP, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Intranet\">Intranet</a>, applications mÃ©tiers, etc.</li><li>dÃ©jÃ  existants sous forme de fichiers documents tÃ©lÃ©chargeables, images, animations, etc.</li><li>crÃ©Ã©s pour lâ€™occasion, ils sont dits Â« de contenu libre Â», saisis dans un Ã©diteur de texte par exemple.</li></ul>\n	<p class=\"normal\">Le point fort de Change, câ€™est sa capacitÃ© Ã  gÃ©rer diffÃ©rents types de contenus de sites Internet.</p>\n  </div>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL),(121,'modules_website/page','RBS Change - Technologies et architectures','wwwadmin','2007-06-14 18:50:57','2007-06-14 18:51:20','PUBLICATED','fr','1.0','2007-06-14 00:00:00',NULL,'RBS Change - Technologies et architectures','RBS Change - Technologies et architectures',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Technologies et architectures</h1>\n	 <p class=\"content-tagline\">RBS Change repose sur plusieurs fondamentaux</p>\n	 \n	<p class=\"normal\">RBS Change est dÃ©veloppÃ© avec des langages libres et non propriÃ©taires. Les technologies utilisÃ©es sont les suivantes :</p>\n	<ul><li>Langage de programmation <abbr title=\"Hypertext Preprocessor\">PHP</abbr> 5</li><li>Base de donnÃ©es MySQL</li><li>Serveur Apache</li><li>Librairies <abbr title=\"PHP Extension and Application Repository\">PEAR</abbr></li><li>Interface backoffice Mozilla/<abbr title=\"XML User Interface Language\">XUL</abbr> sous Firefox</li></ul>\n	<p class=\"normal\">Les sites gÃ©nÃ©rÃ©s par Change sont consultables sur les navigateurs standards sur PC (Mozilla, Firefox, Netscape et Internet\nExplorer jusquâ€™Ã  version v-1), sur MAC (Mozilla, Firefox, Netscape et Safari jusquâ€™Ã  version -1).</p>\n	<p class=\"normal\">Les sites gÃ©nÃ©rÃ©s par Change sont Ã©laborÃ©s dans l\'optique de respecter :</p>\n	<ul><li>au niveau bronze dâ€™<a href=\"http://www.accessiweb.org/\">Accessiweb</a></li><li>au niveau <strong>A</strong> du <abbr title=\"World Wide Web Consortium\">W3C</abbr>/<abbr title=\"Web Accessibility Initiative\">WAI</abbr>/<abbr title=\"Web Content Accessibility Guidelines\">WCAG</abbr> (<a href=\"http://www.w3.org/WAI/\">www.w3.org/WAI/</a>)</li></ul>\n	\n	<h2>Des composants logiciels OpenSource</h2>\n	<p class=\"normal\">Technologie <abbr title=\"Linux-Apache-MySQL-PHP\">LAMP</abbr>, notamment.\n\n	</p><h2>Des sources de contenu multiples</h2>\n	<p class=\"normal\">RBS\nChange prend en charge tous les types contenus, mÃªme provenant de\nplusieurs sources : contenus dÃ©jÃ  existants et structurÃ©s, contenus\nsous forme de fichiers, Â« contenu libre Â».</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(122,'modules_website/page','Produits de Recherche et DÃ©veloppement','wwwadmin','2007-06-14 18:52:07','2007-06-14 18:53:45','PUBLICATED','fr','1.0','2007-06-14 00:00:00',NULL,'Produits de Recherche et DÃ©veloppement','Produits de Recherche et DÃ©veloppement',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Produits de Recherche et DÃ©veloppement</h1>\n  	<p class=\"content-tagline\">En 2006, le budget Recherche et DÃ©veloppement de RBS a reprÃ©sentÃ© 7 % du CA de lâ€™entreprise.</p>\n  	<p class=\"normal\">Fait\nrare pour une sociÃ©tÃ© de service, RBS possÃ¨de sa propre Ã©quipe de\nRecherche et DÃ©veloppement composÃ©e dâ€™ingÃ©nieurs qui travaillent\nexclusivement Ã  la conception et au dÃ©veloppement de produits.</p>\n\n	<h2>Lâ€™innovation</h2>\n  	<p class=\"normal\">Pour\nRBS, la recherche est la clef dâ€™un dÃ©veloppement durable et le socle de\nses futurs produits. Ses Ã©quipes Ã©laborent des solutions qui conjuguent\nergonomie et stabilitÃ© sur le long terme.</p>\n  	<p class=\"normal\">Cette politique est encouragÃ©e par lâ€™agence nationale pour lâ€™innovation :</p> \n	<ul><li>Depuis 2002, elle soutient les projets dâ€™innovation de la sociÃ©tÃ©.</li><li>En 2006, RBS a obtenu le label Entreprise Innovante.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL),(133,'modules_website/page','RBS Change - Structure d\'application','wwwadmin','2007-06-15 09:03:14','2007-06-15 09:05:36','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS Change - Structure d\'application','RBS Change - Structure d\'application',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Structure d\'application</h1>\n	<p class=\"normal\">Lâ€™application est structurÃ©e de faÃ§on modulaire : un noyau qui comprend les fonctionnalitÃ©s de base, et des <a href=\"http://www.rbs.fr/new/produits/change/modules-gestion-contenu-cms.php\" title=\"Modules de la solution de gestion de contenu RBS Change\">modules</a> qui sont ajoutÃ©s en fonction des besoins du site.</p>\n	<p class=\"normal\">Le noyau comprend la gestion des composantes suivantes :</p>\n	<ul><li>modÃ¨les de pages</li><li>gestion des utilisateurs de Change</li><li>contenu</li><li>arborescence et pages du site</li><li>rÃ©fÃ©rencement (META, liens simplifiÃ©s)</li><li>mÃ©dias</li><li>documents</li><li>images</li><li>animations Flash</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(134,'modules_website/page','RBS Change - FonctionnalitÃ©s','wwwadmin','2007-06-15 09:03:33','2007-06-15 09:06:36','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS Change - FonctionnalitÃ©s','RBS Change - FonctionnalitÃ©s',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - FonctionnalitÃ©s</h1>\n  	<h2>Contenant/contenu</h2>\n	<h3>Lâ€™arborescence et les pages du site</h3>\n	<p class=\"normal\">Change\ngÃ¨re lâ€™arborescence complÃ¨te dâ€™un site (les rubriques et sous-rubriques\ndu site jusquâ€™Ã  Â« n Â» niveaux) et lâ€™emplacement des pages. Change\npermet de crÃ©er, modifier, dÃ©placer, bloquer ou supprimer des pages ou\ndes rubriques. En fonction de ces opÃ©rations, les menus de navigation\net le plan du site sont immÃ©diatement mis Ã  jour.</p>\n\n	<h3>La page et son contenu</h3>\n	<p class=\"normal\">Les\npages crÃ©Ã©es dans Change sont entiÃ¨rement modifiables, tant au niveau\nde la mise en page que du contenu. Tout ou partie de ce contenu peut\ncependant Ãªtre dÃ©clarÃ© non modifiable.</p>\n\n	<p class=\"normal\">Une page est composÃ©e\nde diffÃ©rents blocs, accueillant chacun un contenu prÃ©cis. Un Ã©diteur\npermet dâ€™assembler des blocs sur une page, et de crÃ©er ainsi une\nstructure totalement libre. Certains blocs sont destinÃ©s Ã  accueillir\nun contenu structurÃ©, issu des modules, dâ€™autres blocs Ã  accueillir des\ncontenus libres.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(135,'modules_website/page','RBS Change - Modules','wwwadmin','2007-06-15 09:03:51','2007-06-15 09:05:03','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS Change - Modules','RBS Change - Modules',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Modules</h1>\n  	<h2>MÃ©diathÃ¨que</h2>\n\n	<p class=\"normal\">Ce module permet :</p>\n	<ul><li>de gÃ©rer lâ€™affichage de banques dâ€™images,</li><li>dâ€™accÃ©der Ã  ces images en mode lecture,</li><li>de crÃ©er une arborescence spÃ©cifique,</li><li>dâ€™Ã©tablir une liste dâ€™images composÃ©e dâ€™un visuel, dâ€™un copyright, dâ€™un commentaire (facultatif) et dâ€™une extension,</li><li>de\nclasser et regrouper ces images dans des dossiers thÃ©matiques ; chaque\nimage est automatiquement redimensionnÃ©e aux diffÃ©rentes tailles\nsouhaitÃ©es (vignette, zoom, pleine pageâ€¦) et optimisÃ©e pour un site Web.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(136,'modules_website/page','RBS Change - Structure d\'application','wwwadmin','2007-06-15 09:03:14','2007-06-15 09:04:36','DEPRECATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS Change - Structure d\'application','RBS Change - Structure d\'application',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Modules</h1>\n  	<h2>MÃ©diathÃ¨que</h2>\n\n	<p class=\"normal\">Ce module permet :</p>\n	<ul><li>de gÃ©rer lâ€™affichage de banques dâ€™images,</li><li>dâ€™accÃ©der Ã  ces images en mode lecture,</li><li>de crÃ©er une arborescence spÃ©cifique,</li><li>dâ€™Ã©tablir une liste dâ€™images composÃ©e dâ€™un visuel, dâ€™un copyright, dâ€™un commentaire (facultatif) et dâ€™une extension,</li><li>de\nclasser et regrouper ces images dans des dossiers thÃ©matiques ; chaque\nimage est automatiquement redimensionnÃ©e aux diffÃ©rentes tailles\nsouhaitÃ©es (vignette, zoom, pleine pageâ€¦) et optimisÃ©e pour un site Web.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,133,NULL),(137,'modules_website/page','RBS AgilÃ©o','wwwadmin','2007-06-15 09:42:38','2007-06-15 09:42:52','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS AgilÃ©o','RBS AgilÃ©o',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS AgilÃ©o</h1>\n	<p class=\"content-tagline\">Une suite logicielle collaborative.</p>\n  	<p class=\"normal\">RBS AgilÃ©o est une solution collaborative permettant de canaliser les flux dâ€™informations.</p> \n	<p class=\"normal\">Pour tous les collaborateurs dâ€™une entreprise, elle est un lieu dâ€™Ã©changes et de partage, aisÃ©ment accessible.</p>\n\n	<p class=\"normal\">DotÃ©e\ndâ€™un noyau standard et de nombreux modules paramÃ©trables, RBS AgilÃ©o\npermet de traiter toutes les Ã©tapes dâ€™Ã©laboration, de crÃ©ation, de\nvalidation et de <a href=\"http://www.rbs.fr/new/produits/agileo/flux-documentaires-intranet-extranet-ged.php\" title=\"Gestion des documents\">gestion des documents</a>\ngÃ©nÃ©rÃ©s par une activitÃ©, ainsi que la collecte et le partage de\nressources documentaires, lâ€™informatisation et la gestion de piÃ¨ces\nadministratives.</p>\n\n	<p class=\"normal\">Sur le marchÃ© des applications\ncollaboratives, la force de RBS AgilÃ©o rÃ©side dans la richesse et la\ndiversitÃ© de ses fonctionnalitÃ©s et dans la modularitÃ© des solutions\noffertes.</p>\n\n	<p class=\"normal\">Elle sâ€™appuie Ã©galement sur la polyvalence de RBS qui apporte des solutions globales pour les SystÃ¨mes dâ€™Information.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL),(138,'modules_website/page','RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e','wwwadmin','2007-06-15 09:44:26','2007-06-15 09:44:51','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e','RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e</h1>\n	\n	<h2>Interface RBS AgilÃ©o</h2>\n  	<ul><li>Interface Web intuitive</li><li>Navigation simple</li><li>Recherche multi-critÃ¨res</li><li>Personnalisation de lâ€™affichage</li><li>DÃ©coupage modulaire</li><li>Application multilingue</li></ul>\n\n	<h2>Une charte graphique sur mesure</h2>\n	<ul><li>Feuilles de styles CSS</li><li>Prise en compte multi-enseignes </li><li>BibliothÃ¨que de templates (modÃ¨les)</li><li>Mise Ã  jour facilitÃ©e</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(139,'modules_website/page','Solutions','wwwadmin','2007-06-15 09:46:34','2007-06-15 09:46:53','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'Solutions','Solutions',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Solutions</h1>\n  	<p class=\"normal\">RBS a fait le choix de\nsâ€™appuyer sur des Ã©diteurs et des constructeurs de renom. Elle propose\ndes solutions Ã©prouvÃ©es et pÃ©rennes, qui offrent Ã  ses clients de trÃ¨s\nsÃ©rieux gages de technicitÃ© et de souplesse.</p>\n  	<p class=\"normal\">Afin de tirer\nle meilleur parti de ces partenariats, RBS forme et fait certifier trÃ¨s\nrÃ©guliÃ¨rement ses Ã©quipes. Elles sont composÃ©es de personnel stable\nqui, installation aprÃ¨s installation, acquiert un savoir-faire unique\ndans\nlâ€™intÃ©gration de solutions tierces.</p>\n  	<p class=\"normal\">Ses compÃ©tences dans ce\ndomaine de spÃ©cialitÃ© sont prÃ©cieuses pour ses clients, mais Ã©galement\npour les Ã©diteurs et les constructeurs avec lesquels RBS entretient une\nvÃ©ritable interactivitÃ© technologique.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL),(140,'modules_website/page','WebFactory','wwwadmin','2007-06-15 09:48:20','2007-06-15 09:48:35','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'WebFactory','WebFactory',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">WebFactory</h1>\n	<p class=\"content-tagline\">RBS a\nchoisi de regrouper ses activitÃ©s de production et de promotion des\nsites Internet au sein dâ€™une entitÃ© unique, offrant ainsi Ã  ses clients\nune gestion de bout en bout de leur projet web.</p>\n  	<p class=\"normal\">Les\nconsultants web de RBS Ã©tudient ou formalisent la stratÃ©gie et les\nmoyens online/offline Ã  mettre en oeuvre pour atteindre les objectifs\nde leurs clients. Lâ€™Ã©quipe de production crÃ©e, dÃ©veloppe et intÃ¨gre les\nsites Internet, sur la base du <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenus web RBS Change\">logiciel de gestion de contenus RBS Change</a>. La cellule experte en <a href=\"http://www.rbs.fr/new/webfactory/referencement.php\" title=\"RÃ©fÃ©rencement \">rÃ©fÃ©rencement</a> et <a href=\"http://www.rbs.fr/new/webfactory/webmarketing.php\" title=\"Webmarketing\">webmarketing</a>\nmet en oeuvre les techniques les plus efficaces pour assurer une\nvisibilitÃ© et une rentabilitÃ© maximale aux sites Internet qui lui sont\nconfiÃ©s.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL),(141,'modules_website/page','RÃ©fÃ©rencement','wwwadmin','2007-06-15 09:49:18','2007-06-15 09:49:33','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'RÃ©fÃ©rencement','RÃ©fÃ©rencement',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RÃ©fÃ©rencement</h1>\n  	<p class=\"normal\">RBS WebFactory fait\nindexer les sites de ses clients sur les principaux outils de recherche\nfrancophones et europÃ©ens et assure le cas Ã©chÃ©ant leur prÃ©sence sur\nles portails locaux et rÃ©gionaux.</p>\n  	\n  	<h2>En rÃ©gion</h2>\n\n		<h3>Un potentiel de trafic qualifiÃ©</h3>\n		<p class=\"normal\">Le rÃ©fÃ©rencement rÃ©gional permet de renforcer la visibilitÃ© du site grÃ¢ce Ã  des outils rÃ©actifs\n		 et peu concurrentiels, a fortiori si le marchÃ© est rÃ©gional.</p>\n\n		<h3>Une visibilitÃ© dans son domaine dâ€™activitÃ©</h3>\n		<p class=\"normal\">Une veille est effectuÃ©e sur le secteur dâ€™activitÃ©s concernÃ©, pour faire figurer le site dans les\n		 annuaires les plus pertinents.</p>\n	\n	<h2>En France</h2>\n\n		<h3>Une prÃ©sence sur les outils leader du marchÃ©</h3>\n		<p class=\"normal\">Le rÃ©fÃ©rencement sur les outils leader de la recherche francophone est complÃ©tÃ© par les outils \n		de recherche secondaires qui dÃ©veloppent ou renforcent le maillage de liens externes pointant vers \n		le site.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(142,'modules_website/page','Webmarketing','wwwadmin','2007-06-15 09:50:37','2007-06-15 09:50:54','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'Webmarketing','Webmarketing',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Webmarketing</h1>\n	<p class=\"normal\">RBS WebFactory câ€™est aussi\nune cellule de webmarketing qui apprÃ©hende le web comme un outil\nmarketing Ã  part entiÃ¨re au service des objectifs de lâ€™entreprise, de\nsa politique de dÃ©veloppement commercial, de son image et de sa\nnotoriÃ©tÃ©. Cette cellule mÃ¨ne des actions :</p>\n	<ul><li>de visibilitÃ© ponctuelle (campagnes dâ€™e-mailing par exemple)</li><li>dâ€™animation du site</li><li>dâ€™e-commerce</li><li>dans le domaine de la formation aux techniques dâ€™optimisation des sites.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,0,0,0,NULL,NULL,NULL),(143,'modules_website/page','Nos offres d\'emploi','wwwadmin','2007-06-15 09:51:36','2007-06-15 09:51:50','PUBLICATED','fr','1.0','2007-06-15 00:00:00',NULL,'Nos offres d\'emploi','Nos offres d\'emploi',NULL,NULL,1,'tplFree','<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Nos Offres d\'Emploi</h1>\n	<p class=\"normal\">Pour postuler aux\nannonces ci-dessous, merci d\'envoyer votre CV et votre lettre de\nmotivation, en prÃ©cisant la rÃ©fÃ©rence exacte du poste, Ã  :</p>\n	<p class=\"normal\"><strong>RBS - Ready Business System<br/>\n	Ressources Humaines<br/>\n	Aeroparc 1, 11 rue Icare<br/>\n	Strasbourg-Entzheim<br/>\n	67836 Tanneries Cedex</strong></p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,1,1,0,0,NULL,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_page` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_page_i18n`
--

DROP TABLE IF EXISTS `m_website_doc_page_i18n`;
CREATE TABLE `m_website_doc_page_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `document_publicationstatus_i18n` varchar(50) collate utf8_bin default NULL,
  `document_startpublicationdate_i18n` datetime default NULL,
  `document_endpublicationdate_i18n` datetime default NULL,
  `navigationtitle_i18n` varchar(80) collate utf8_bin default NULL,
  `metatitle_i18n` text collate utf8_bin,
  `description_i18n` text collate utf8_bin,
  `keywords_i18n` text collate utf8_bin,
  `content_i18n` text collate utf8_bin,
  `navigationvisibility_i18n` int(11) default NULL,
  `currentversionid_i18n` int(11) default NULL,
  `document_correctionid_i18n` int(11) default NULL,
  `document_correctionofid_i18n` int(11) default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_page_i18n`
--


/*!40000 ALTER TABLE `m_website_doc_page_i18n` DISABLE KEYS */;
LOCK TABLES `m_website_doc_page_i18n` WRITE;
INSERT INTO `m_website_doc_page_i18n` VALUES (73,'fr','Accueil','PUBLICATED','2007-06-13 00:00:00',NULL,'Accueil','Accueil',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplHome\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"header\" /><div id=\"center\" orient=\"horizontal\"><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"header\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"header\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout3\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate3\"><grid><columns><column /></columns><rows><row id=\"freeContainer4\" /><row id=\"freeContainer5\" /><row id=\"freeContainer3\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer4\" label=\"Free4\" editable=\"true\" /><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer4\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h2 class=\"heading-two\">Bienvenue sur le site de RBS !</h2>]]></wblock><wblock type=\"modules_form_form\" target=\"freeContainer5\" ref=\"98\" lang=\"fr\" display=\"class: modules-form-form;\" editable=\"true\" movable=\"true\" resizable=\"true\" /></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,0,NULL),(79,'fr','Mission, Innovation, Implantation','PUBLICATED','2007-06-13 00:00:00',NULL,'Mission, Innovation, Implantation','Mission, Innovation, Implantation',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Mission, Innovation, Implantation</h1>\n	\n	<h2>Mission</h2>\n  	<p class=\"normal\">Prestataire\nde service en ingÃ©nierie informatique, RBS maÃ®trise toutes les facettes\nde la gestion des systÃ¨mes dâ€™information. La multiplicitÃ© des flux au\nsein de lâ€™entreprise dâ€™une part et ses Ã©changes avec lâ€™extÃ©rieur\ndâ€™autre part, fait appel Ã  des mÃ©tiers dâ€™expertises et Ã  des\ntechnologies trÃ¨s diffÃ©rentes : Gestion de la Relation Client, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Solution Intranet RBS AgilÃ©o\">Intranet</a>, <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenu Web RBS Change\">Web</a>, <a href=\"http://www.rbs.fr/new/produits/moby/index.php\" title=\"Solution de mobilitÃ©\">MobilitÃ©</a>,  <a href=\"http://www.rbs.fr/new/solutions/infrastructures/index.php\" title=\"Infrastructures informatiques\">Infrastructures</a> Ainsi, RBS est Ã  la fois spÃ©cialiste et polyvalent.</p>\n  	<p class=\"normal\">Pour\nfaire bÃ©nÃ©ficier ses clients des Ã©volutions les plus rÃ©centes, RBS est\ndotÃ©e dâ€™un service de Recherche et DÃ©veloppement de pointe.</p>\n  	<p class=\"normal\">Ce\nvÃ©ritable rayonnement de compÃ©tences sâ€™appuie sur un savoir-faire\nÃ©prouvÃ© dans le domaine des systÃ¨mes informatiques, de la distribution\nde matÃ©riels et de logiciels et se dÃ©ploie jusquâ€™Ã  la conception et\nlâ€™intÃ©gration de <a href=\"http://www.rbs.fr/new/produits/index.php\" title=\"Logiciels RBS\">logiciels RBS</a>.</p>\n  	<p class=\"normal\">Aujourdâ€™hui, lâ€™expertise de RBS lui ouvre tout naturellement la voie vers le mÃ©tier dâ€™Ã©diteur.</p>\n	\n	 <h2>Innovation</h2>\n	<p class=\"normal\">Le caractÃ¨re innovant des projets de RBS est dÃ©sormais reconnu et saluÃ©, notamment par l\'Agence nationale pour l\'Innovation.</p>\n	<ul><li>Depuis\n2002, elle soutient les projets d\'innovation de la sociÃ©tÃ©, entre\nautres dans les domaines de la mobilitÃ©, et celui de la biomÃ©trie.</li><li>En 2005, OSEO-<abbr title=\"Agence nationale de valorisation de la recherche\">ANVAR</abbr><sup>*</sup> est entrÃ©e au capital de RBS en convertissant le montant de ses aides en bons de souscription d\'actions.</li><li>En 2006, RBS a obtenu le label Entreprise Innovante.</li></ul>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,0,NULL),(81,'fr','MÃ©tiers','PUBLICATED','2007-06-13 00:00:00',NULL,'MÃ©tiers','MÃ©tiers',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">MÃ©tiers</h1><h2>Le service en ingÃ©nierie informatique</h2>\n	<p class=\"content-tagline\">Une SSII de rÃ©fÃ©rence</p> \n	<p class=\"normal\">Sur\nle marchÃ© des sociÃ©tÃ©s informatiques de conseil, RBS occupe sans\nconteste une place Ã  part, qu\'elle doit Ã  son sÃ©rieux, sa rigueur et sa\ntechnicitÃ© mais aussi Ã  la forte personnalitÃ© de ses personnels,\npassionnÃ©s, rÃ©actifs, disponibles et en prise directe avec le terrain.</p>\n	<p class=\"normal\">L\'origine\nde ses fondateurs, issus du monde de l\'entreprise, ainsi que la grande\npolyvalence de son Ã©quipe, mÃªlant des ingÃ©nieurs de haut niveau et des\nprofessionnels expÃ©rimentÃ©s, contribuent Ã  ce positionnement inÃ©dit.</p>\n	<p class=\"normal\">Depuis\n1997, date de sa crÃ©ation, RBS s\'est imposÃ©e comme une SSII de\nrÃ©fÃ©rence dans ses diffÃ©rents domaines de spÃ©cialitÃ©. Aujourd\'hui, prÃ¨s\nde 150 personnes se consacrent au conseil et prennent en charge des\nprojets complets, de la conception de solutions spÃ©cifiques pour chaque\nclient jusqu\'Ã  leur rÃ©alisation.</p>\n\n  	<h2>La conception de logiciels</h2>\n	<p class=\"content-tagline\">Une conjugaison de talent et de savoir-faire</p>\n	<p class=\"normal\">Aujourd\'hui, RBS offre sa propre solution dans les domaines du web, des applications collaboratives et de la mobilitÃ©.</p>\n	<p class=\"normal\">De\nl\'Ã©laboration des bases de donnÃ©es en passant par la dÃ©finition de\nl\'architecture et jusqu\'Ã  la programmation, l\'Ã©quipe de dÃ©veloppeurs\nRBS a su Ã©laborer des progiciels qui rivalisent avec les plus grands : <a href=\"http://www.rbs.fr/new/produits/moby/index.php\" title=\"Solution de mobilitÃ© RBS Moby\">RBS Moby</a>, <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenu Web RBS Change\">RBS Change</a>, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Solution collaborative Intranet/Extranet/GED RBS AgilÃ©o\">RBS AgilÃ©o</a>, <a href=\"http://www.rbs.fr/new/produits/partagenda/index.php\" title=\"Agenda partagÃ© RBS Partagenda\">RBS Partagenda</a>â€¦</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,0,NULL),(82,'fr','MarchÃ©s','PUBLICATED','2007-06-13 00:00:00',NULL,'MarchÃ©s','MarchÃ©s',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">MarchÃ©s</h1>\n  	<h2>Des affinitÃ©s avec tous les secteurs d\'activitÃ©</h2>\n	<p class=\"normal\">RBS touche des entreprises extrÃªmement diverses qui se distinguent par :</p>\n	<ul><li>leur taille (TPE, PMI/PME ou grosses entreprises industrielles)</li><li>leur secteur d\'activitÃ©</li><li>leurs implantations</li><li>et leurs marchÃ©s qui peuvent Ãªtre locaux, rÃ©gionaux, nationaux ou internationaux.</li></ul>\n	<p class=\"normal\">Cette\ncapacitÃ© d\'adaptation rÃ©side dans la souplesse des Ã©quipes, leur\ndiversitÃ© d\'origine, leur pluridisciplinaritÃ©, mais aussi dans la\nnature mÃªme des produits qu\'elles dÃ©veloppent et commercialisent :\nouverts et modulables.</p>\n\n  	<h2>Des rÃ©ponses institutionnelles adaptÃ©es au Service Public</h2>\n	<p class=\"normal\">CollectivitÃ©s\nlocales, mairies, organismes, universitÃ©s, conseils gÃ©nÃ©raux,\nconsultent rÃ©guliÃ¨rement RBS, car les solutions apportÃ©es rÃ©pondent aux\nattentes de leurs publics : simplicitÃ© d\'accÃ¨s Ã  des informations\nsouvent nombreuses et complexes, lisibilitÃ© de leurs offres de\nservices, interactivitÃ©, etc.</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(86,'fr','Moyens','PUBLICATED','2007-06-13 00:00:00',NULL,'Moyens','Moyens',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Moyens</h1><h2>Des Ã©quipes Ã©nergiques</h2>\n	<p class=\"normal\">RBS compte aujourd\'hui un effectif\nde 150 personnes, aux compÃ©tences Ã  la fois pointues et diversifiÃ©es\ndans les domaines administratif, technique, commercial et de\nl\'ingÃ©nierie informatique. Fait exceptionnel pour une entreprise de la\ntaille de RBS, une quinzaine d\'ingÃ©nieurs constituent un pÃ´le recherche\net dÃ©veloppement.</p>\n	<p class=\"normal\">Le dynamisme et l\'Ã©lan des plus jeunes (la\nmoyenne d\'Ã¢ge est de 32 ans), conjuguÃ©s Ã  l\'expÃ©rience et la maturitÃ©\ndes seniors expliquent certainement la croissance particuliÃ¨rement\nrapide de la sociÃ©tÃ©.</p>\n \n	<h2>Un bÃ¢timent dÃ©diÃ© et une adresse accessible</h2>\n	<p class=\"normal\">Depuis\njanvier 2006, RBS dispose d\'un bÃ¢timent spÃ©cialement dÃ©diÃ© Ã  son\nactivitÃ©. Facile d\'accÃ¨s, il est situÃ© en pÃ©riphÃ©rie de Strasbourg,\ndans l\'immÃ©diate proximitÃ© de l\'aÃ©roport international d\'Entzheim.</p>\n	<p class=\"normal\">Cet\nespace de 2000 m2, Ã  la fois cloisonnÃ© et ouvert, offre des conditions\nidÃ©ales de travail sur deux niveaux : le niveau supÃ©rieur est dotÃ©\nd\'une coursive intÃ©rieure, permettant d\'embrasser du regard tout le\nniveau bas. Cette structure architecturale esthÃ©tique et fonctionnelle\npermet de capter toute la lumiÃ¨re naturelle. Ainsi, toutes les zones de\ntravail, de mÃªme que les diverses salles de rÃ©union de toutes tailles\ndisposÃ©es un peu partout dans le bÃ¢timent, sont propices Ã  la\nconcentration.</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,0,NULL),(89,'fr','Mentions lÃ©gales','PUBLICATED','2007-06-13 00:00:00',NULL,'Mentions lÃ©gales','Mentions lÃ©gales',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h2>Editeur</h2>\n		<p class=\"normal\">Vous Ãªtes actuellement connectÃ© au site Internet\nde la sociÃ©tÃ© Ready Business System SA (RBS). Directeur de la\npublication : Daniel Romani, PrÃ©sident - Directeur gÃ©nÃ©ral.</p>\n		\n		<h2>Loi informatique et libertÃ©s</h2>\n		<p class=\"normal\">Vous\ndisposez d\'un droit d\'accÃ¨s, de modification, de rectification et de\nsuppression des donnÃ©es qui vous concernent (art. 34 de la loi\n\"Informatique et LibertÃ©s\" du 6 janvier 1978). Pour exercer ce droit,\nadressez-vous par courrier Ã  :</p>\n		<p class=\"normal\">RBS <br/>\n		AÃ©roparc 1 <br/>\n		11, rue Icare <br/>\n		Strasbourg - Entzheim  <br/>\n		67836 Tanneries Cedex <br/>\n		</p><p class=\"normal\">Ou utilisez notre formulaire <a href=\"http://www.rbs.fr/new/contact.php\" title=\"Contact\">contact</a>.</p>\n		\n		<h2>La protection des donnÃ©es personnelles</h2> \n		<ul><li>Aucune information personnelle n\'est collectÃ©e Ã  votre insu</li><li>Aucune information personnelle n\'est cÃ©dÃ©e Ã  des tiers</li><li>Aucune information personnelle n\'est utilisÃ©e Ã  des fins non prÃ©vues</li></ul>\n\n		<h2>DÃ©claration</h2>\n		<p class=\"normal\">Le prÃ©sent site a fait l\'objet d\'une dÃ©claration Ã  la CNIL, sous le numÃ©ro 833525.</p>\n \n		<h2>Messagerie</h2>\n		<p class=\"normal\">Les\nmessages envoyÃ©s sur le rÃ©seau internet peuvent Ãªtre interceptÃ©s. Ne\ndivulguez pas d\'informations personnelles inutiles ou sensibles. Si\nvous souhaitez nous communiquer de telles informations, utilisez\nimpÃ©rativement la voie postale.</p>\n \n		<h2>Droit dâ€™auteur â€“ Copyright</h2>\n		<p class=\"normal\">L\'ensemble\nde ce site relÃ¨ve de la lÃ©gislation franÃ§aise et internationale sur le\ndroit d\'auteur et la propriÃ©tÃ© intellectuelle. Tous les droits de\nreproduction sont rÃ©servÃ©s, y compris pour les documents\ntÃ©lÃ©chargeables et les reprÃ©sentations iconographiques et\nphotographiques.</p> \n		<p class=\"normal\">La reproduction de tout ou partie de ce\nsite sur un support Ã©lectronique quel qu\'il soit est formellement\ninterdite sauf autorisation expresse du directeur de la publication.</p> \n		<p class=\"normal\">La reproduction des textes de ce site sur un support papier est strictement interdite, sauf autorisation expresse de notre part.</p>\n		<p class=\"normal\">RBS se rÃ©serve le droit de modifier le contenu de ce site Ã  tout moment et sans prÃ©avis.</p>\n		<p class=\"normal\">Les marques citÃ©es sur ce site sont dÃ©posÃ©es par les sociÃ©tÃ©s qui en sont propriÃ©taires.</p> \n		<p class=\"normal\">Ready Business System (RBS) Â®, AgilÃ©o, Partagenda Â®, WebEdit Â® et Moby Â® sont des marques dÃ©posÃ©es par Ready Business System SA.</p>\n		<p class=\"normal\">Ready Business System SA : RCS Strasbourg B 402 777 643</p>]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(95,'fr','Imprimer','PUBLICATED','2007-06-13 00:00:00',NULL,'Imprimer','Imprimer',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[Pour imprimer une page, utilisez le menu \"Imprimer\" de votre navigateur ou le raccourci clavier CTRL+p.]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,0,NULL,NULL),(96,'fr','Ajouter aux favoris','PUBLICATED','2007-06-13 00:00:00',NULL,'Ajouter aux favoris','Ajouter aux favoris',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[Pour ajouter une page de ce site web Ã  vos liens favoris, utilisez le menu \"Ajouter aux favoris\" de votre navigateur ou le raccourci clavier CTRL+d.]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',0,0,NULL,NULL),(120,'fr','RBS Change','PUBLICATED','2007-06-14 00:00:00',NULL,'RBS Change','RBS Change',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<div id=\"contenu-main\">\n  	<h1 id=\"maintarget\">RBS Change</h1>\n  	<p class=\"content-tagline\">Gestion de contenus web</p>\n  	<p class=\"normal\">RBS Change est un progiciel de crÃ©ation et de gestion de contenu normalisÃ© pour le web. Il permet une gestion multi-sites\net multi-langues. Chaque site peut possÃ©der sa propre charte graphique ou partager la mÃªme charte que les autres sites.</p>\n	<p class=\"normal\">Change allie puissance et convivialitÃ© : maÃ®trise de la publication, intÃ©gration des contraintes de <a href=\"http://www.rbs.fr/new/webfactory/referencement.php\" title=\"RÃ©fÃ©rencement \">rÃ©fÃ©rencement</a>, conformitÃ© aux\ncritÃ¨res dâ€™accessibilitÃ©, etc.</p>\n	<p class=\"normal\">Change permet notamment :</p>\n	<ul><li>la gestion de lâ€™arborescence</li><li>lâ€™Ã©dition de texte enrichi</li><li>la gestion des contenus par module</li></ul>\n	<p class=\"normal\">Les contenus dâ€™un site Internet peuvent provenir de plusieurs sources :</p>\n	<ul><li>dÃ©jÃ  existants et structurÃ©s dans des bases de donnÃ©es ERP, <a href=\"http://www.rbs.fr/new/produits/agileo/index.php\" title=\"Intranet\">Intranet</a>, applications mÃ©tiers, etc.</li><li>dÃ©jÃ  existants sous forme de fichiers documents tÃ©lÃ©chargeables, images, animations, etc.</li><li>crÃ©Ã©s pour lâ€™occasion, ils sont dits Â« de contenu libre Â», saisis dans un Ã©diteur de texte par exemple.</li></ul>\n	<p class=\"normal\">Le point fort de Change, câ€™est sa capacitÃ© Ã  gÃ©rer diffÃ©rents types de contenus de sites Internet.</p>\n  </div>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(121,'fr','RBS Change - Technologies et architectures','PUBLICATED','2007-06-14 00:00:00',NULL,'RBS Change - Technologies et architectures','RBS Change - Technologies et architectures',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Technologies et architectures</h1>\n	 <p class=\"content-tagline\">RBS Change repose sur plusieurs fondamentaux</p>\n	 \n	<p class=\"normal\">RBS Change est dÃ©veloppÃ© avec des langages libres et non propriÃ©taires. Les technologies utilisÃ©es sont les suivantes :</p>\n	<ul><li>Langage de programmation <abbr title=\"Hypertext Preprocessor\">PHP</abbr> 5</li><li>Base de donnÃ©es MySQL</li><li>Serveur Apache</li><li>Librairies <abbr title=\"PHP Extension and Application Repository\">PEAR</abbr></li><li>Interface backoffice Mozilla/<abbr title=\"XML User Interface Language\">XUL</abbr> sous Firefox</li></ul>\n	<p class=\"normal\">Les sites gÃ©nÃ©rÃ©s par Change sont consultables sur les navigateurs standards sur PC (Mozilla, Firefox, Netscape et Internet\nExplorer jusquâ€™Ã  version v-1), sur MAC (Mozilla, Firefox, Netscape et Safari jusquâ€™Ã  version -1).</p>\n	<p class=\"normal\">Les sites gÃ©nÃ©rÃ©s par Change sont Ã©laborÃ©s dans l\'optique de respecter :</p>\n	<ul><li>au niveau bronze dâ€™<a href=\"http://www.accessiweb.org/\">Accessiweb</a></li><li>au niveau <strong>A</strong> du <abbr title=\"World Wide Web Consortium\">W3C</abbr>/<abbr title=\"Web Accessibility Initiative\">WAI</abbr>/<abbr title=\"Web Content Accessibility Guidelines\">WCAG</abbr> (<a href=\"http://www.w3.org/WAI/\">www.w3.org/WAI/</a>)</li></ul>\n	\n	<h2>Des composants logiciels OpenSource</h2>\n	<p class=\"normal\">Technologie <abbr title=\"Linux-Apache-MySQL-PHP\">LAMP</abbr>, notamment.\n\n	</p><h2>Des sources de contenu multiples</h2>\n	<p class=\"normal\">RBS\nChange prend en charge tous les types contenus, mÃªme provenant de\nplusieurs sources : contenus dÃ©jÃ  existants et structurÃ©s, contenus\nsous forme de fichiers, Â« contenu libre Â».</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(122,'fr','Produits de Recherche et DÃ©veloppement','PUBLICATED','2007-06-14 00:00:00',NULL,'Produits de Recherche et DÃ©veloppement','Produits de Recherche et DÃ©veloppement',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Produits de Recherche et DÃ©veloppement</h1>\n  	<p class=\"content-tagline\">En 2006, le budget Recherche et DÃ©veloppement de RBS a reprÃ©sentÃ© 7 % du CA de lâ€™entreprise.</p>\n  	<p class=\"normal\">Fait\nrare pour une sociÃ©tÃ© de service, RBS possÃ¨de sa propre Ã©quipe de\nRecherche et DÃ©veloppement composÃ©e dâ€™ingÃ©nieurs qui travaillent\nexclusivement Ã  la conception et au dÃ©veloppement de produits.</p>\n\n	<h2>Lâ€™innovation</h2>\n  	<p class=\"normal\">Pour\nRBS, la recherche est la clef dâ€™un dÃ©veloppement durable et le socle de\nses futurs produits. Ses Ã©quipes Ã©laborent des solutions qui conjuguent\nergonomie et stabilitÃ© sur le long terme.</p>\n  	<p class=\"normal\">Cette politique est encouragÃ©e par lâ€™agence nationale pour lâ€™innovation :</p> \n	<ul><li>Depuis 2002, elle soutient les projets dâ€™innovation de la sociÃ©tÃ©.</li><li>En 2006, RBS a obtenu le label Entreprise Innovante.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(133,'fr','RBS Change - Structure d\'application','PUBLICATED','2007-06-15 00:00:00',NULL,'RBS Change - Structure d\'application','RBS Change - Structure d\'application',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Structure d\'application</h1>\n	<p class=\"normal\">Lâ€™application est structurÃ©e de faÃ§on modulaire : un noyau qui comprend les fonctionnalitÃ©s de base, et des <a href=\"http://www.rbs.fr/new/produits/change/modules-gestion-contenu-cms.php\" title=\"Modules de la solution de gestion de contenu RBS Change\">modules</a> qui sont ajoutÃ©s en fonction des besoins du site.</p>\n	<p class=\"normal\">Le noyau comprend la gestion des composantes suivantes :</p>\n	<ul><li>modÃ¨les de pages</li><li>gestion des utilisateurs de Change</li><li>contenu</li><li>arborescence et pages du site</li><li>rÃ©fÃ©rencement (META, liens simplifiÃ©s)</li><li>mÃ©dias</li><li>documents</li><li>images</li><li>animations Flash</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(134,'fr','RBS Change - FonctionnalitÃ©s','PUBLICATED','2007-06-15 00:00:00',NULL,'RBS Change - FonctionnalitÃ©s','RBS Change - FonctionnalitÃ©s',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - FonctionnalitÃ©s</h1>\n  	<h2>Contenant/contenu</h2>\n	<h3>Lâ€™arborescence et les pages du site</h3>\n	<p class=\"normal\">Change\ngÃ¨re lâ€™arborescence complÃ¨te dâ€™un site (les rubriques et sous-rubriques\ndu site jusquâ€™Ã  Â« n Â» niveaux) et lâ€™emplacement des pages. Change\npermet de crÃ©er, modifier, dÃ©placer, bloquer ou supprimer des pages ou\ndes rubriques. En fonction de ces opÃ©rations, les menus de navigation\net le plan du site sont immÃ©diatement mis Ã  jour.</p>\n\n	<h3>La page et son contenu</h3>\n	<p class=\"normal\">Les\npages crÃ©Ã©es dans Change sont entiÃ¨rement modifiables, tant au niveau\nde la mise en page que du contenu. Tout ou partie de ce contenu peut\ncependant Ãªtre dÃ©clarÃ© non modifiable.</p>\n\n	<p class=\"normal\">Une page est composÃ©e\nde diffÃ©rents blocs, accueillant chacun un contenu prÃ©cis. Un Ã©diteur\npermet dâ€™assembler des blocs sur une page, et de crÃ©er ainsi une\nstructure totalement libre. Certains blocs sont destinÃ©s Ã  accueillir\nun contenu structurÃ©, issu des modules, dâ€™autres blocs Ã  accueillir des\ncontenus libres.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(135,'fr','RBS Change - Modules','PUBLICATED','2007-06-15 00:00:00',NULL,'RBS Change - Modules','RBS Change - Modules',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Modules</h1>\n  	<h2>MÃ©diathÃ¨que</h2>\n\n	<p class=\"normal\">Ce module permet :</p>\n	<ul><li>de gÃ©rer lâ€™affichage de banques dâ€™images,</li><li>dâ€™accÃ©der Ã  ces images en mode lecture,</li><li>de crÃ©er une arborescence spÃ©cifique,</li><li>dâ€™Ã©tablir une liste dâ€™images composÃ©e dâ€™un visuel, dâ€™un copyright, dâ€™un commentaire (facultatif) et dâ€™une extension,</li><li>de\nclasser et regrouper ces images dans des dossiers thÃ©matiques ; chaque\nimage est automatiquement redimensionnÃ©e aux diffÃ©rentes tailles\nsouhaitÃ©es (vignette, zoom, pleine pageâ€¦) et optimisÃ©e pour un site Web.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(136,'fr','RBS Change - Structure d\'application','DEPRECATED','2007-06-15 00:00:00',NULL,'RBS Change - Structure d\'application','RBS Change - Structure d\'application',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS Change - Modules</h1>\n  	<h2>MÃ©diathÃ¨que</h2>\n\n	<p class=\"normal\">Ce module permet :</p>\n	<ul><li>de gÃ©rer lâ€™affichage de banques dâ€™images,</li><li>dâ€™accÃ©der Ã  ces images en mode lecture,</li><li>de crÃ©er une arborescence spÃ©cifique,</li><li>dâ€™Ã©tablir une liste dâ€™images composÃ©e dâ€™un visuel, dâ€™un copyright, dâ€™un commentaire (facultatif) et dâ€™une extension,</li><li>de\nclasser et regrouper ces images dans des dossiers thÃ©matiques ; chaque\nimage est automatiquement redimensionnÃ©e aux diffÃ©rentes tailles\nsouhaitÃ©es (vignette, zoom, pleine pageâ€¦) et optimisÃ©e pour un site Web.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,133),(137,'fr','RBS AgilÃ©o','PUBLICATED','2007-06-15 00:00:00',NULL,'RBS AgilÃ©o','RBS AgilÃ©o',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS AgilÃ©o</h1>\n	<p class=\"content-tagline\">Une suite logicielle collaborative.</p>\n  	<p class=\"normal\">RBS AgilÃ©o est une solution collaborative permettant de canaliser les flux dâ€™informations.</p> \n	<p class=\"normal\">Pour tous les collaborateurs dâ€™une entreprise, elle est un lieu dâ€™Ã©changes et de partage, aisÃ©ment accessible.</p>\n\n	<p class=\"normal\">DotÃ©e\ndâ€™un noyau standard et de nombreux modules paramÃ©trables, RBS AgilÃ©o\npermet de traiter toutes les Ã©tapes dâ€™Ã©laboration, de crÃ©ation, de\nvalidation et de <a href=\"http://www.rbs.fr/new/produits/agileo/flux-documentaires-intranet-extranet-ged.php\" title=\"Gestion des documents\">gestion des documents</a>\ngÃ©nÃ©rÃ©s par une activitÃ©, ainsi que la collecte et le partage de\nressources documentaires, lâ€™informatisation et la gestion de piÃ¨ces\nadministratives.</p>\n\n	<p class=\"normal\">Sur le marchÃ© des applications\ncollaboratives, la force de RBS AgilÃ©o rÃ©side dans la richesse et la\ndiversitÃ© de ses fonctionnalitÃ©s et dans la modularitÃ© des solutions\noffertes.</p>\n\n	<p class=\"normal\">Elle sâ€™appuie Ã©galement sur la polyvalence de RBS qui apporte des solutions globales pour les SystÃ¨mes dâ€™Information.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(138,'fr','RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e','PUBLICATED','2007-06-15 00:00:00',NULL,'RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e','RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RBS AgilÃ©o - Une ergonomie Ã©prouvÃ©e</h1>\n	\n	<h2>Interface RBS AgilÃ©o</h2>\n  	<ul><li>Interface Web intuitive</li><li>Navigation simple</li><li>Recherche multi-critÃ¨res</li><li>Personnalisation de lâ€™affichage</li><li>DÃ©coupage modulaire</li><li>Application multilingue</li></ul>\n\n	<h2>Une charte graphique sur mesure</h2>\n	<ul><li>Feuilles de styles CSS</li><li>Prise en compte multi-enseignes </li><li>BibliothÃ¨que de templates (modÃ¨les)</li><li>Mise Ã  jour facilitÃ©e</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(139,'fr','Solutions','PUBLICATED','2007-06-15 00:00:00',NULL,'Solutions','Solutions',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Solutions</h1>\n  	<p class=\"normal\">RBS a fait le choix de\nsâ€™appuyer sur des Ã©diteurs et des constructeurs de renom. Elle propose\ndes solutions Ã©prouvÃ©es et pÃ©rennes, qui offrent Ã  ses clients de trÃ¨s\nsÃ©rieux gages de technicitÃ© et de souplesse.</p>\n  	<p class=\"normal\">Afin de tirer\nle meilleur parti de ces partenariats, RBS forme et fait certifier trÃ¨s\nrÃ©guliÃ¨rement ses Ã©quipes. Elles sont composÃ©es de personnel stable\nqui, installation aprÃ¨s installation, acquiert un savoir-faire unique\ndans\nlâ€™intÃ©gration de solutions tierces.</p>\n  	<p class=\"normal\">Ses compÃ©tences dans ce\ndomaine de spÃ©cialitÃ© sont prÃ©cieuses pour ses clients, mais Ã©galement\npour les Ã©diteurs et les constructeurs avec lesquels RBS entretient une\nvÃ©ritable interactivitÃ© technologique.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(140,'fr','WebFactory','PUBLICATED','2007-06-15 00:00:00',NULL,'WebFactory','WebFactory',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">WebFactory</h1>\n	<p class=\"content-tagline\">RBS a\nchoisi de regrouper ses activitÃ©s de production et de promotion des\nsites Internet au sein dâ€™une entitÃ© unique, offrant ainsi Ã  ses clients\nune gestion de bout en bout de leur projet web.</p>\n  	<p class=\"normal\">Les\nconsultants web de RBS Ã©tudient ou formalisent la stratÃ©gie et les\nmoyens online/offline Ã  mettre en oeuvre pour atteindre les objectifs\nde leurs clients. Lâ€™Ã©quipe de production crÃ©e, dÃ©veloppe et intÃ¨gre les\nsites Internet, sur la base du <a href=\"http://www.rbs.fr/new/produits/change/index.php\" title=\"Gestion de contenus web RBS Change\">logiciel de gestion de contenus RBS Change</a>. La cellule experte en <a href=\"http://www.rbs.fr/new/webfactory/referencement.php\" title=\"RÃ©fÃ©rencement \">rÃ©fÃ©rencement</a> et <a href=\"http://www.rbs.fr/new/webfactory/webmarketing.php\" title=\"Webmarketing\">webmarketing</a>\nmet en oeuvre les techniques les plus efficaces pour assurer une\nvisibilitÃ© et une rentabilitÃ© maximale aux sites Internet qui lui sont\nconfiÃ©s.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(141,'fr','RÃ©fÃ©rencement','PUBLICATED','2007-06-15 00:00:00',NULL,'RÃ©fÃ©rencement','RÃ©fÃ©rencement',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">RÃ©fÃ©rencement</h1>\n  	<p class=\"normal\">RBS WebFactory fait\nindexer les sites de ses clients sur les principaux outils de recherche\nfrancophones et europÃ©ens et assure le cas Ã©chÃ©ant leur prÃ©sence sur\nles portails locaux et rÃ©gionaux.</p>\n  	\n  	<h2>En rÃ©gion</h2>\n\n		<h3>Un potentiel de trafic qualifiÃ©</h3>\n		<p class=\"normal\">Le rÃ©fÃ©rencement rÃ©gional permet de renforcer la visibilitÃ© du site grÃ¢ce Ã  des outils rÃ©actifs\n		 et peu concurrentiels, a fortiori si le marchÃ© est rÃ©gional.</p>\n\n		<h3>Une visibilitÃ© dans son domaine dâ€™activitÃ©</h3>\n		<p class=\"normal\">Une veille est effectuÃ©e sur le secteur dâ€™activitÃ©s concernÃ©, pour faire figurer le site dans les\n		 annuaires les plus pertinents.</p>\n	\n	<h2>En France</h2>\n\n		<h3>Une prÃ©sence sur les outils leader du marchÃ©</h3>\n		<p class=\"normal\">Le rÃ©fÃ©rencement sur les outils leader de la recherche francophone est complÃ©tÃ© par les outils \n		de recherche secondaires qui dÃ©veloppent ou renforcent le maillage de liens externes pointant vers \n		le site.</p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(142,'fr','Webmarketing','PUBLICATED','2007-06-15 00:00:00',NULL,'Webmarketing','Webmarketing',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Webmarketing</h1>\n	<p class=\"normal\">RBS WebFactory câ€™est aussi\nune cellule de webmarketing qui apprÃ©hende le web comme un outil\nmarketing Ã  part entiÃ¨re au service des objectifs de lâ€™entreprise, de\nsa politique de dÃ©veloppement commercial, de son image et de sa\nnotoriÃ©tÃ©. Cette cellule mÃ¨ne des actions :</p>\n	<ul><li>de visibilitÃ© ponctuelle (campagnes dâ€™e-mailing par exemple)</li><li>dâ€™animation du site</li><li>dâ€™e-commerce</li><li>dans le domaine de la formation aux techniques dâ€™optimisation des sites.</li></ul>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL),(143,'fr','Nos offres d\'emploi','PUBLICATED','2007-06-15 00:00:00',NULL,'Nos offres d\'emploi','Nos offres d\'emploi',NULL,NULL,'<wlayout id=\"mainLayout\" editable=\"true\"><wtemplate id=\"tplFree\"><div orient=\"vertical\"><div id=\"global\" orient=\"vertical\"><div id=\"main\" /><div id=\"center\" orient=\"horizontal\"><div id=\"sidebar\" /><div id=\"content\" orient=\"vertical\"><div id=\"thread\" /><div id=\"content-block\" /></div></div><div id=\"footer\" /></div><div id=\"copyright\" /></div></wtemplate><wlocationset><wlocation target=\"main\" editable=\"false\" /><wlocation target=\"sidebar\" editable=\"false\" /><wlocation target=\"thread\" editable=\"false\" /><wlocation target=\"content-block\" editable=\"false\" /><wlocation target=\"footer\" editable=\"false\" /><wlocation target=\"copyright\" editable=\"false\" /></wlocationset><wblockset><wblock type=\"modules_website_main\" target=\"main\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_sidebar\" target=\"sidebar\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_thread\" target=\"thread\" editable=\"false\" movable=\"false\" /><wblock type=\"free\" target=\"content-block\" editable=\"true\" movable=\"false\"><wlayout id=\"freeLayout4\" type=\"free\" editable=\"true\"><wtemplate id=\"freeTemplate4\"><grid><columns><column /></columns><rows><row id=\"freeContainer5\" /><row id=\"freeContainer4\" /></rows></grid></wtemplate><wlocationset><wlocation target=\"freeContainer5\" label=\"Free5\" editable=\"true\" /><wlocation target=\"freeContainer4\" editable=\"true\" /></wlocationset><wblockset><wblock type=\"richtext\" target=\"freeContainer5\" display=\"class: richtext;\" editable=\"true\" movable=\"true\" resizable=\"true\"><![CDATA[<h1 id=\"maintarget\">Nos Offres d\'Emploi</h1>\n	<p class=\"normal\">Pour postuler aux\nannonces ci-dessous, merci d\'envoyer votre CV et votre lettre de\nmotivation, en prÃ©cisant la rÃ©fÃ©rence exacte du poste, Ã  :</p>\n	<p class=\"normal\"><strong>RBS - Ready Business System<br/>\n	Ressources Humaines<br/>\n	Aeroparc 1, 11 rue Icare<br/>\n	Strasbourg-Entzheim<br/>\n	67836 Tanneries Cedex</strong></p>]]></wblock><wblock type=\"empty\" target=\"freeContainer4\" editable=\"true\" movable=\"true\"><![CDATA[&nbsp;]]></wblock></wblockset></wlayout></wblock><wblock type=\"modules_website_footer\" target=\"footer\" editable=\"false\" movable=\"false\" /><wblock type=\"modules_website_copyright\" target=\"copyright\" editable=\"false\" movable=\"false\" /></wblockset></wlayout>',1,0,NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_page_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_pageexternal`
--

DROP TABLE IF EXISTS `m_website_doc_pageexternal`;
CREATE TABLE `m_website_doc_pageexternal` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `navigationtitle` varchar(80) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  `url` varchar(255) collate utf8_bin default NULL,
  `indexingstatus` tinyint(1) default NULL,
  `navigationvisibility` int(11) default NULL,
  `isindexpage` tinyint(1) default NULL,
  `ishomepage` tinyint(1) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_pageexternal`
--


/*!40000 ALTER TABLE `m_website_doc_pageexternal` DISABLE KEYS */;
LOCK TABLES `m_website_doc_pageexternal` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_pageexternal` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_pageexternal_i18n`
--

DROP TABLE IF EXISTS `m_website_doc_pageexternal_i18n`;
CREATE TABLE `m_website_doc_pageexternal_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `navigationvisibility_i18n` int(11) default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_pageexternal_i18n`
--


/*!40000 ALTER TABLE `m_website_doc_pageexternal_i18n` DISABLE KEYS */;
LOCK TABLES `m_website_doc_pageexternal_i18n` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_pageexternal_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_preferences`
--

DROP TABLE IF EXISTS `m_website_doc_preferences`;
CREATE TABLE `m_website_doc_preferences` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_preferences`
--


/*!40000 ALTER TABLE `m_website_doc_preferences` DISABLE KEYS */;
LOCK TABLES `m_website_doc_preferences` WRITE;
INSERT INTO `m_website_doc_preferences` VALUES (132,'modules_website/preferences','PrÃ©fÃ©rences sites et pages','wwwadmin','2007-06-14 21:57:34','2007-06-14 21:57:34','PUBLICATED','fr','1.0',NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_preferences` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_template`
--

DROP TABLE IF EXISTS `m_website_doc_template`;
CREATE TABLE `m_website_doc_template` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `template` varchar(255) collate utf8_bin default NULL,
  `content` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_template`
--


/*!40000 ALTER TABLE `m_website_doc_template` DISABLE KEYS */;
LOCK TABLES `m_website_doc_template` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_template` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_topic`
--

DROP TABLE IF EXISTS `m_website_doc_topic`;
CREATE TABLE `m_website_doc_topic` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `urllabel` varchar(255) collate utf8_bin default NULL,
  `description` text collate utf8_bin,
  `stylesheet` varchar(255) collate utf8_bin default NULL,
  `indexpage` int(11) default NULL,
  `navigationvisibility` int(11) default NULL,
  `skin` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_topic`
--


/*!40000 ALTER TABLE `m_website_doc_topic` DISABLE KEYS */;
LOCK TABLES `m_website_doc_topic` WRITE;
INSERT INTO `m_website_doc_topic` VALUES (74,'modules_website/topic','Entreprise','wwwadmin','2007-06-13 14:36:09','2007-06-13 14:38:29','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,79,1,0),(75,'modules_website/topic','Solutions+','wwwadmin','2007-06-13 14:36:27','2007-06-15 09:46:56','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,139,1,0),(76,'modules_website/topic','WebFactory','wwwadmin','2007-06-13 14:36:37','2007-06-15 09:48:39','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,140,1,0),(77,'modules_website/topic','Produits','wwwadmin','2007-06-13 14:36:48','2007-06-14 18:53:49','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,122,1,0),(78,'modules_website/topic','Recrutement','wwwadmin','2007-06-13 14:37:17','2007-06-15 09:52:28','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,143,1,0),(83,'modules_website/topic','Outils','wwwadmin','2007-06-13 14:43:30','2007-06-13 14:43:39','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,0,0,0),(118,'modules_website/topic','RBS Change','wwwadmin','2007-06-14 18:49:07','2007-06-14 18:50:26','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,120,1,0),(119,'modules_website/topic','RBS AgilÃ©o','wwwadmin','2007-06-14 18:49:20','2007-06-15 09:42:57','PUBLICATED','fr','1.0',NULL,NULL,NULL,NULL,NULL,137,1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_topic` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_topic_i18n`
--

DROP TABLE IF EXISTS `m_website_doc_topic_i18n`;
CREATE TABLE `m_website_doc_topic_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `urllabel_i18n` varchar(255) collate utf8_bin default NULL,
  `description_i18n` text collate utf8_bin,
  `navigationvisibility_i18n` int(11) default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_topic_i18n`
--


/*!40000 ALTER TABLE `m_website_doc_topic_i18n` DISABLE KEYS */;
LOCK TABLES `m_website_doc_topic_i18n` WRITE;
INSERT INTO `m_website_doc_topic_i18n` VALUES (74,'fr','Entreprise',NULL,NULL,1),(75,'fr','Solutions+',NULL,NULL,1),(76,'fr','WebFactory',NULL,NULL,1),(77,'fr','Produits',NULL,NULL,1),(78,'fr','Recrutement',NULL,NULL,1),(83,'fr','Outils',NULL,NULL,0),(118,'fr','RBS Change',NULL,NULL,1),(119,'fr','RBS AgilÃ©o',NULL,NULL,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_topic_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_website`
--

DROP TABLE IF EXISTS `m_website_doc_website`;
CREATE TABLE `m_website_doc_website` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `url` varchar(255) collate utf8_bin default NULL,
  `indexpage` int(11) default NULL,
  `stylesheet` varchar(255) collate utf8_bin default NULL,
  `skin` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_website`
--


/*!40000 ALTER TABLE `m_website_doc_website` DISABLE KEYS */;
LOCK TABLES `m_website_doc_website` WRITE;
INSERT INTO `m_website_doc_website` VALUES (66,'modules_website/website','rbs.fr','wwwadmin','2007-06-13 14:07:02','2007-06-13 14:38:50','DRAFT','fr','1.0',NULL,NULL,NULL,'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr',73,NULL,0),(144,'modules_website/website','rbs-change.fr','wwwadmin','2007-06-15 14:19:12','2007-06-15 14:19:12','DRAFT','fr','1.0',NULL,NULL,NULL,'http://change.intbonjf.rd-change.devlinux.france.rbs.fr',0,NULL,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_website` ENABLE KEYS */;

--
-- Table structure for table `m_website_doc_website_i18n`
--

DROP TABLE IF EXISTS `m_website_doc_website_i18n`;
CREATE TABLE `m_website_doc_website_i18n` (
  `document_id` int(11) NOT NULL default '0',
  `lang_i18n` varchar(2) collate utf8_bin NOT NULL default 'fr',
  `document_label_i18n` varchar(255) collate utf8_bin default NULL,
  `description_i18n` text collate utf8_bin,
  `url_i18n` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`,`lang_i18n`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_website_doc_website_i18n`
--


/*!40000 ALTER TABLE `m_website_doc_website_i18n` DISABLE KEYS */;
LOCK TABLES `m_website_doc_website_i18n` WRITE;
INSERT INTO `m_website_doc_website_i18n` VALUES (66,'fr','rbs.fr',NULL,'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr'),(144,'fr','rbs-change.fr',NULL,'http://change.intbonjf.rd-change.devlinux.france.rbs.fr');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_doc_website_i18n` ENABLE KEYS */;

--
-- Table structure for table `m_website_urlrewriting_rules`
--

DROP TABLE IF EXISTS `m_website_urlrewriting_rules`;
CREATE TABLE IF NOT EXISTS `m_website_urlrewriting_rules` (
  `document_id` int(11) NOT NULL,
  `document_url` varchar(255) NOT NULL,
  `document_lang` varchar(2) NOT NULL DEFAULT 'fr',
  `document_moved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`document_id`, `document_lang`),
  UNIQUE (`document_url`)
) TYPE=InnoDB CHARACTER SET utf8 COLLATE utf8_bin;

--
-- Dumping data for table `m_website_urlrewriting_rules`
--


/*!40000 ALTER TABLE `m_website_urlrewriting_rules` DISABLE KEYS */;
LOCK TABLES `m_website_urlrewriting_rules` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_website_urlrewriting_rules` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_arc`
--

DROP TABLE IF EXISTS `m_workflow_doc_arc`;
CREATE TABLE `m_workflow_doc_arc` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `transition` int(11) default NULL,
  `place` int(11) default NULL,
  `direction` varchar(3) collate utf8_bin default NULL,
  `arctype` varchar(10) collate utf8_bin default NULL,
  `precondition` varchar(255) collate utf8_bin default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_arc`
--


/*!40000 ALTER TABLE `m_workflow_doc_arc` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_arc` WRITE;
INSERT INTO `m_workflow_doc_arc` VALUES (30,'modules_workflow/arc','Production du contenu -> SÃ©lection du valideur','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,31,32,'IN','SEQ',''),(33,'modules_workflow/arc','SÃ©lection du valideur -> Attente du valideur (OK)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,31,34,'OUT','EX_OR_SP','OK'),(35,'modules_workflow/arc','SÃ©lection du valideur -> Attente d\'annulation (KO)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,31,36,'OUT','EX_OR_SP','KO'),(37,'modules_workflow/arc','Attente du valideur -> Validation du contenu','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,38,34,'IN','IM_OR_SP',''),(39,'modules_workflow/arc','Validation du contenu -> Contenu acceptÃ© (ACCEPTED)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,38,40,'OUT','EX_OR_SP','ACCEPTED'),(41,'modules_workflow/arc','Validation du contenu -> Contenu refusÃ© (REFUSED)','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,38,42,'OUT','EX_OR_SP','REFUSED'),(43,'modules_workflow/arc','Contenu acceptÃ© -> Activation','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,44,40,'IN','SEQ',''),(45,'modules_workflow/arc','Attente d\'annulation -> Annulation du workflow','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,46,36,'IN','SEQ',''),(47,'modules_workflow/arc','Annulation du workflow -> Fin','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,46,48,'OUT','SEQ',''),(49,'modules_workflow/arc','Activation -> Fin','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,44,48,'OUT','SEQ',''),(50,'modules_workflow/arc','Attente du valideur -> Rappel','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,51,34,'IN','IM_OR_SP',''),(52,'modules_workflow/arc','Rappel -> Attente du valideur','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,51,34,'OUT','SEQ',''),(53,'modules_workflow/arc','Contenu refusÃ© -> Retour brouillon','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,54,42,'IN','IM_OR_SP',''),(55,'modules_workflow/arc','Retour brouillon -> Fin','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,54,48,'OUT','SEQ','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_arc` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_case`
--

DROP TABLE IF EXISTS `m_workflow_doc_case`;
CREATE TABLE `m_workflow_doc_case` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `workflow` int(11) default NULL,
  `documentid` int(11) default NULL,
  `parameters` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_case`
--


/*!40000 ALTER TABLE `m_workflow_doc_case` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_case` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_case` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_place`
--

DROP TABLE IF EXISTS `m_workflow_doc_place`;
CREATE TABLE `m_workflow_doc_place` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `placetype` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_place`
--


/*!40000 ALTER TABLE `m_workflow_doc_place` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_place` WRITE;
INSERT INTO `m_workflow_doc_place` VALUES (32,'modules_workflow/place','Production du contenu','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Production du contenu via Change.',1),(34,'modules_workflow/place','Attente du valideur','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Attende de validation ou refus par l\'un des valideurs autorisÃ©s.',5),(36,'modules_workflow/place','Attente d\'annulation','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le workflow est annulÃ© pour cause de refus ou d\'erreur.',5),(40,'modules_workflow/place','Contenu acceptÃ©','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le contenu est acceptÃ© et prÃªt Ã  Ãªtre publiÃ©.',5),(42,'modules_workflow/place','Contenu refusÃ©','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le contenu est refusÃ© et prÃªt Ã  retourner Ã  l\'Ã©tat brouillon.',5),(48,'modules_workflow/place','Fin','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Fin du workflow.',9);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_place` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_token`
--

DROP TABLE IF EXISTS `m_workflow_doc_token`;
CREATE TABLE `m_workflow_doc_token` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `place` int(11) default NULL,
  `documentid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_token`
--


/*!40000 ALTER TABLE `m_workflow_doc_token` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_token` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_token` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_transition`
--

DROP TABLE IF EXISTS `m_workflow_doc_transition`;
CREATE TABLE `m_workflow_doc_transition` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `actionname` varchar(255) collate utf8_bin default NULL,
  `trigger` varchar(10) collate utf8_bin default NULL,
  `timelimit` int(11) default NULL,
  `taskid` varchar(50) collate utf8_bin default NULL,
  `roleid` varchar(50) collate utf8_bin default NULL,
  `creationnotification` int(11) default NULL,
  `terminationnotification` int(11) default NULL,
  `cancellationnotification` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_transition`
--


/*!40000 ALTER TABLE `m_workflow_doc_transition` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_transition` WRITE;
INSERT INTO `m_workflow_doc_transition` VALUES (31,'modules_workflow/transition','SÃ©lection du valideur','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le ou les valideur(s) sont selectionnÃ©s par l\'application.','workflow_SelectValidatorWorkflowaction','AUTO',NULL,'VALIDATION1',NULL,0,0,0),(38,'modules_workflow/transition','Validation du contenu','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Validation ou refus du contenu par l\'un des valideurs autorisÃ©s.','workflow_ValidateWorkflowaction','USER',NULL,'VALIDATION1','validator',23,24,25),(44,'modules_workflow/transition','Activation','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le contenu est validÃ©, le workflow le concernant prend fin.','workflow_ActivateWorkflowaction','AUTO',NULL,'VALIDATION1',NULL,0,0,0),(46,'modules_workflow/transition','Annulation du workflow','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Case d\'annulation du workflow pour cause de refus ou d\'erreur.','workflow_CancelWorkflowaction','AUTO',NULL,'VALIDATION1',NULL,0,0,0),(51,'modules_workflow/transition','Rappel','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Rappel rÃ©gulier aux valideurs qu\'ils doivent valider ce document.','workflow_RecallWorkflowaction','TIME',48,'VALIDATION1',NULL,0,0,0),(54,'modules_workflow/transition','Retour brouillon','system','2007-06-13 14:05:53','2007-06-13 14:05:53','PUBLICATED','fr','1.0',NULL,NULL,'Le contenu est refusÃ© et retourne Ã  l\'Ã©tat brouillon, le workflow le concernant prend fin.','workflow_BackToDraftWorkflowaction','AUTO',NULL,'VALIDATION1',NULL,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_transition` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_workflow`
--

DROP TABLE IF EXISTS `m_workflow_doc_workflow`;
CREATE TABLE `m_workflow_doc_workflow` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `description` text collate utf8_bin,
  `starttaskid` varchar(50) collate utf8_bin default NULL,
  `errors` text collate utf8_bin,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_workflow`
--


/*!40000 ALTER TABLE `m_workflow_doc_workflow` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_workflow` WRITE;
INSERT INTO `m_workflow_doc_workflow` VALUES (29,'modules_workflow/workflow','Validation Ã  un niveau','system','2007-06-13 14:05:53','2007-06-13 14:05:53','DRAFT','fr','1.0',NULL,NULL,NULL,'VALIDATION1',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_workflow` ENABLE KEYS */;

--
-- Table structure for table `m_workflow_doc_workitem`
--

DROP TABLE IF EXISTS `m_workflow_doc_workitem`;
CREATE TABLE `m_workflow_doc_workitem` (
  `document_id` int(11) NOT NULL default '0',
  `document_model` varchar(50) collate utf8_bin NOT NULL default '',
  `document_label` varchar(255) collate utf8_bin default NULL,
  `document_author` varchar(50) collate utf8_bin default NULL,
  `document_creationdate` datetime default NULL,
  `document_modificationdate` datetime default NULL,
  `document_publicationstatus` varchar(50) collate utf8_bin default NULL,
  `document_lang` varchar(2) collate utf8_bin default NULL,
  `document_modelversion` varchar(20) collate utf8_bin default NULL,
  `document_startpublicationdate` datetime default NULL,
  `document_endpublicationdate` datetime default NULL,
  `transition` int(11) default NULL,
  `documentid` int(11) default NULL,
  `deadline` datetime default NULL,
  `userid` int(11) default NULL,
  PRIMARY KEY  (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `m_workflow_doc_workitem`
--


/*!40000 ALTER TABLE `m_workflow_doc_workitem` DISABLE KEYS */;
LOCK TABLES `m_workflow_doc_workitem` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `m_workflow_doc_workitem` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

