CREATE TABLE IF NOT EXISTS `group` (
  `group_id` INTEGER NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(255),
  `group_start` DATE NOT NULL,
  `group_end` DATE NOT NULL,
  `group_count` INTEGER NOT NULL,
  `group_species_id` INTEGER NOT NULL,
  `group_org_id` INTEGER NOT NULL,
  FOREIGN KEY (`group_species_id`) REFERENCES species(`species_id`),
  FOREIGN KEY (`group_org_id`) REFERENCES organization(`org_id`),
  PRIMARY KEY (`group_id`)
);
