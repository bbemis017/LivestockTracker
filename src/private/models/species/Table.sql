CREATE TABLE IF NOT EXISTS `species` (
  `species_id` INTEGER NOT NULL AUTO_INCREMENT,
  `species_name` VARCHAR(255) NOT NULL,
  `species_org_id` INTEGER NOT NULL,
  FOREIGN KEY (`species_org_id`) REFERENCES organization(`org_id`),
  PRIMARY KEY (`species_id`)
);
