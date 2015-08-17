-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 06, 2015 at 01:55 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `timmyrevenue`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `assignThemeAdUnitId`(IN _theme_ad_id INTEGER)
MAIN_BLOCK: BEGIN
    
    DELETE FROM ts__theme_active_ads WHERE theme_ad_id IN (
        SELECT 
            ta1.id 
        FROM ts__theme_ads ta 
        INNER JOIN ts__ads a ON a.id = ta.ad_id 
        INNER JOIN ts__ad_accounts aa ON aa.id = a.account_id 
        INNER JOIN ts__theme_ads ta1 ON ta1.theme_id = ta.theme_id 
        INNER JOIN ts__ads a1 ON a1.id = ta1.ad_id AND a1.type = a.type AND a1.id <> a.id 
        INNER JOIN ts__ad_accounts aa1 ON aa1.provider = aa.provider AND aa1.id = a1.account_id 
        WHERE ta.id = _theme_ad_id
    );

    INSERT INTO ts__theme_active_ads(theme_ad_id) VALUES (_theme_ad_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `autoAssignThemeAdmobId`(IN _theme_id INTEGER, IN _admob_id VARCHAR(100), IN _admob_type VARCHAR(50))
MAIN_BLOCK: BEGIN
    DECLARE _ad_id INTEGER DEFAULT NULL;
    DECLARE _existing_admob_type VARCHAR(50);
    DECLARE _exists INTEGER DEFAULT 0;
    DECLARE _account_admob_banner_id VARCHAR(50);
    DECLARE _account_admob_interstitial_id VARCHAR(50);

    IF _admob_id IS NULL OR _admob_id = '' OR _admob_id = _admob_type OR _admob_id = 'pending' THEN
        LEAVE MAIN_BLOCK;
    END IF;

    
    
    SELECT 
        d.admob_banner_id, d.admob_interstitial_id INTO 
        _account_admob_banner_id, _account_admob_interstitial_id
    FROM ts__developer_accounts d
    INNER JOIN ts__themes t ON t.developer_account_id = d.id
    WHERE t.id = _theme_id;

    IF _account_admob_banner_id = _admob_id OR _account_admob_interstitial_id = _admob_id THEN
        LEAVE MAIN_BLOCK;
    END IF;
    
    SELECT id, `type` INTO _ad_id, _existing_admob_type FROM ts__ads WHERE identifier = _admob_id;

    IF _ad_id IS NULL THEN
        INSERT INTO ts__ads (identifier, `type`) VALUES (_admob_id, _admob_type);
        SET _ad_id = LAST_INSERT_ID();
    ELSE
        IF _existing_admob_type IS NULL OR _existing_admob_type <> _admob_type THEN
            UPDATE ts__ads SET `type` = _admob_type WHERE id = _ad_id;
        END IF;
    END IF;

    SELECT COUNT(*) INTO _exists FROM ts__theme_ads WHERE theme_id = _theme_id AND ad_id = _ad_id;

    IF _exists = 0 THEN
        INSERT INTO ts__theme_ads (theme_id, ad_id) VALUES (_theme_id, _ad_id);
    END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `invalidateLauncherTemplate`(IN _launcher_template_id INTEGER)
MAIN_BLOCK: BEGIN
    UPDATE ts__launcher_template_tests SET passed = NULL, last_update = NOW(), user_id = NULL WHERE launcher_template_id = _launcher_template_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `invalidateLauncherTemplateForDependency`(IN _dependency_id INTEGER)
MAIN_BLOCK: BEGIN
    UPDATE ts__launcher_template_tests 
            SET passed = NULL 
        WHERE launcher_template_id IN (
            SELECT launcher_template_id 
            FROM ts__launcher_template_dependencies 
            WHERE dependency_id = _dependency_id
        );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `invalidateLauncherTemplateForFolder`(IN _folder_id INTEGER)
MAIN_BLOCK: BEGIN

    
    UPDATE ts__launcher_templates SET test_passed = 0 WHERE folder_id = _folder_id;

    UPDATE ts__launcher_template_tests t
        INNER JOIN ts__launcher_templates l  ON l.id = t.launcher_template_id
        SET passed = NULL 
        WHERE l.folder_id = _folder_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateThemeResource`(IN _theme_id INTEGER, IN _resource_id VARCHAR(100), IN _resource_value VARCHAR(100), IN _parent VARCHAR(100))
MAIN_BLOCK: BEGIN
    DECLARE _id INTEGER DEFAULT NULL;
    DECLARE _parent_id INTEGER DEFAULT NULL;
    DECLARE _existing_value VARCHAR(100) DEFAULT NULL;
    
    SELECT id, `value` INTO _id, _existing_value FROM ts__theme_assets WHERE theme_id = _theme_id AND resource_id = _resource_id;

    IF _parent IS NOT NULL THEN 
        SELECT id INTO _parent_id FROM ts__theme_assets WHERE theme_id = _theme_id AND resource_id = _parent;
    END IF;

    IF _id IS NULL THEN
        INSERT INTO ts__theme_assets (theme_id, resource_id, `value`, last_updated, parent_id) VALUES (_theme_id, _resource_id, _resource_value, NOW(), _parent_id);
    ELSE
        IF _existing_value <> _resource_value 
            OR (_resource_value IS NULL AND _existing_value IS NOT NULL) 
            OR (_existing_value IS NULL AND _resource_value IS NOT NULL) 
        THEN
            UPDATE ts__theme_assets SET `value` = _resource_value, last_updated = NOW() WHERE id = _id;
        END IF;
    END IF;
    
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getAvailableBuildersCount`(_batch_theme_id INT ) RETURNS int(11)
MAIN_BLOCK: BEGIN
    DECLARE _builders_count INT DEFAULT 0;

    SELECT COUNT(*) INTO _builders_count FROM (
        SELECT builder_id FROM (
            SELECT 
                bt.builder_id, TIME_TO_SEC(TIMEDIFF(NOW(), bb.last_seen_online)) as last_seen_online
            FROM 
                ts__download_batch_themes bt
            INNER JOIN ts__download_batches b ON b.id = bt.batch_id
            INNER JOIN ts__download_batches b2 ON b2.test_only = b.test_only
            INNER JOIN ts__download_batch_themes bt2 ON b2.id = bt2.batch_id
            INNER JOIN ts__batch_builders bb ON bb.id = bt.builder_id
            WHERE 
                bt.builder_id IS NOT NULL AND
                bt2.id = _batch_theme_id
            ORDER BY bt.last_update DESC
            LIMIT 10
        ) foo 
        WHERE last_seen_online < 3600
        GROUP BY builder_id
    ) foo;

    IF _builders_count = 0 THEN
        SET _builders_count = 1;
    END IF;
    
    RETURN _builders_count;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getBatchThemeEta`(_batch_theme_id VARCHAR(50)) RETURNS varchar(100) CHARSET latin1
MAIN_BLOCK: BEGIN
    DECLARE _build_duration INT DEFAULT 0;
    DECLARE _build_queue_size INT DEFAULT 0;
    DECLARE _build_running_duration INT DEFAULT 0;
    DECLARE _available_builders INT DEFAULT 0;
    DECLARE _row_batch_theme_id INT DEFAULT 0;
    DECLARE _row_build_duration INT DEFAULT 0;
    DECLARE _row_elapsed_time INT DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    
    DECLARE cur1 CURSOR FOR SELECT 
        bt.id as batch_theme_id, 
        
        COALESCE(lbd.build_duration, 45) as build_duration, 
        
        IF(  bt.build_status = 'running', TIME_TO_SEC(TIMEDIFF(NOW(), bt.last_update)), 0 )  as elapsed_time
    FROM ts__download_batches b 
    INNER JOIN ts__download_batch_themes bt ON bt.batch_id = b.id 
    LEFT JOIN (
        SELECT 
            b1.launcher_template_id, MAX(build_duration) as build_duration
        FROM 
            ts__download_batches b1
        INNER JOIN ts__download_batch_themes t1 ON t1.batch_id = b1.id
        INNER JOIN (
            SELECT 
                launcher_template_id, MAX(t2.last_update) as last_update
            FROM ts__download_batch_themes t2
            INNER JOIN ts__download_batches b2 ON b2.id = t2.batch_id
            WHERE t2.build_duration > 0 AND t2.build_status = 'completed'
            GROUP BY launcher_template_id
        ) lt ON lt.launcher_template_id = b1.launcher_template_id AND lt.last_update = t1.last_update
        GROUP BY launcher_template_id
    ) lbd ON lbd.launcher_template_id = b.launcher_template_id
    INNER JOIN ts__download_batches b2 ON b2.test_only = b.test_only
    INNER JOIN ts__download_batch_themes bt2 ON bt2.batch_id = b2.id
    WHERE 
        bt2.id = _batch_theme_id AND 
        bt2.id >= bt.id AND
        b.build_status != 'completed' AND 
        bt.build_status NOT IN ('completed', 'errors')
        AND b.archived = '0'
    ORDER BY (bt.build_status = 'running') DESC, b.priority DESC, bt.id ASC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    SELECT getAvailableBuildersCount(_batch_theme_id) INTO _available_builders;

    OPEN cur1;

    read_loop: LOOP
        FETCH cur1 INTO _row_batch_theme_id, _row_build_duration, _row_elapsed_time;
        IF done THEN
          LEAVE read_loop;
        END IF;

        
        SET _row_build_duration = _row_build_duration - _row_elapsed_time;

        IF _row_build_duration < 0 THEN 
            SET _row_build_duration = 1;
        END IF;

        IF _row_elapsed_time > 0 THEN 
            IF _row_build_duration > _build_running_duration THEN 
                
                
                SET _build_running_duration = _row_build_duration;
            END IF;
        ELSE
            
            SET _build_queue_size = _build_queue_size + 1;
            SET _build_duration = _build_duration + _row_build_duration;
        END IF;

    END LOOP;

    
    
    
    

      
    
    RETURN _build_duration / _available_builders + _build_running_duration;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getLauncherTemplateCurrentVersionId`(launcher_id INTEGER) RETURNS int(11)
MAIN_BLOCK: BEGIN
    DECLARE version_id INTEGER DEFAULT NULL;

    SELECT v.id INTO version_id 
        FROM ts__launcher_template_versions v
        INNER JOIN ts__launcher_template_folders f ON f.id = v.folder_id
        INNER JOIN ts__launcher_templates t ON t.folder_id = v.folder_id
        WHERE t.id = launcher_id
        ORDER BY version DESC LIMIT 1;
    RETURN version_id;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getSettings`(_identifier VARCHAR(50)) RETURNS varchar(100) CHARSET latin1
MAIN_BLOCK: BEGIN
    DECLARE _return VARCHAR(100) DEFAULT NULL;

    SELECT `value` INTO _return FROM ts__global_settings WHERE identifier = _identifier;

    RETURN _return;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `isLauncherTemplateValid`(_launcher_template_id INTEGER) RETURNS tinyint(1)
MAIN_BLOCK: BEGIN
    DECLARE _count INTEGER DEFAULT NULL;

    SELECT COUNT(*) INTO _count FROM ts__launcher_template_tests WHERE launcher_template_id = _launcher_template_id AND COALESCE(passed,0) <> 1;

    RETURN _count = 0;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ts__permissions`
--

CREATE TABLE IF NOT EXISTS `ts__permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ts__permissions`
--

INSERT INTO `ts__permissions` (`id`, `permission`) VALUES
(1, 'revenue.edit');

-- --------------------------------------------------------

--
-- Table structure for table `ts__roles`
--

CREATE TABLE IF NOT EXISTS `ts__roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ts__roles`
--

INSERT INTO `ts__roles` (`id`, `name`) VALUES
(1, 'timmy'),
(2, 'client');

-- --------------------------------------------------------

--
-- Table structure for table `ts__role_permissions`
--

CREATE TABLE IF NOT EXISTS `ts__role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id_2` (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `ts__users`
--

CREATE TABLE IF NOT EXISTS `ts__users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `hash` varchar(40) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `profile_pic` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=267 ;

--
-- Dumping data for table `ts__users`
--

INSERT INTO `ts__users` (`id`, `email`, `date_created`, `username`, `password`, `name`, `is_admin`, `last_login`, `company_id`, `hash`, `confirmed`, `profile_pic`) VALUES
(3, '-', '2013-08-09 13:36:10', 'admin', 'c4f0ad1a3432748a8f13a9a810c43f7882ec0813', 'Admin', 1, '2015-08-06 10:37:48', NULL, '', 0, '39d37.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ts__user_notifications`
--

CREATE TABLE IF NOT EXISTS `ts__user_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `action` text NOT NULL,
  `status` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ts__user_permissions`
--

CREATE TABLE IF NOT EXISTS `ts__user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`permission_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `ts__user_roles`
--

CREATE TABLE IF NOT EXISTS `ts__user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `ts__user_roles`
--

INSERT INTO `ts__user_roles` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 2),
(2, 6, 3),
(3, 109, 2),
(4, 9, 2),
(5, 238, 2),
(6, 242, 4),
(7, 3, 5),
(8, 3, 6),
(9, 248, 6),
(10, 3, 1),
(11, 3, 4),
(12, 3, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ts__role_permissions`
--
ALTER TABLE `ts__role_permissions`
  ADD CONSTRAINT `ts__role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `ts__permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ts__role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `ts__roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ts__user_notifications`
--
ALTER TABLE `ts__user_notifications`
  ADD CONSTRAINT `ts__user_notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `ts__users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ts__user_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ts__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ts__user_permissions`
--
ALTER TABLE `ts__user_permissions`
  ADD CONSTRAINT `ts__user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `ts__permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ts__user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ts__users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
