CREATE TABLE `customfields_definitions` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `target_module` varchar(50) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_title` varchar(255) NOT NULL,
  `field_description` text,
  `field_type` enum('text','textarea','editor','image','file','select','checkbox','radio','date') NOT NULL DEFAULT 'text',
  `field_options` text,
  `field_order` int(5) DEFAULT 0,
  `required` tinyint(1) DEFAULT 0,
  `show_in_form` tinyint(1) DEFAULT 1,
  `validation_rules` text,
  `created` int(10) NOT NULL DEFAULT 0,
  `modified` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`field_id`),
  KEY `target_module` (`target_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `customfields_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `target_module` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `field_value` text,
  `created` int(10) NOT NULL DEFAULT 0,
  `modified` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`data_id`),
  KEY `field_id` (`field_id`),
  KEY `target_item` (`target_module`, `item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
