CREATE TABLE IF NOT EXISTS `module` (
  `module_id` INTEGER NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) NOT NULL,
  `module_description` varchar(500) NOT NULL,
  `module_active` TINYINT(1) NOT NULL,
   PRIMARY KEY (`module_id`)
);
