-- İlave Alanlar Modülü - Kurulum SQL
-- XOOPS veritabanı prefix'i ile kullanılacak

CREATE TABLE IF NOT EXISTS `{PREFIX}_customfields_definitions` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `target_module` varchar(50) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_title` varchar(255) NOT NULL,
  `field_description` text,
  `field_type` enum('text','textarea','editor','image','file','select','checkbox','radio','date') NOT NULL,
  `field_options` text COMMENT 'JSON format for select/radio/checkbox options',
  `field_order` int(5) DEFAULT 0,
  `required` tinyint(1) DEFAULT 0,
  `show_in_form` tinyint(1) DEFAULT 1,
  `validation_rules` text,
  `created` int(10) NOT NULL,
  `modified` int(10) NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `target_module` (`target_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `{PREFIX}_customfields_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `target_module` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL COMMENT 'ID of the item in target module',
  `field_value` text,
  `created` int(10) NOT NULL,
  `modified` int(10) NOT NULL,
  PRIMARY KEY (`data_id`),
  KEY `field_id` (`field_id`),
  KEY `target_item` (`target_module`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Not: Foreign key constraint XOOPS kurulumunda sorun çıkarabilir, gerekirse manuel ekleyin
-- ALTER TABLE `{PREFIX}_customfields_data` ADD FOREIGN KEY (`field_id`) REFERENCES `{PREFIX}_customfields_definitions`(`field_id`) ON DELETE CASCADE;
