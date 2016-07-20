CREATE TABLE IF NOT EXISTS `stage` (
  `stage_id` INTEGER NOT NULL AUTO_INCREMENT,
  `stage_name` VARCHAR(255) NOT NULL,
  `stage_length` INTEGER NOT NULL,
  `stage_org_id` INTEGER NOT NULL,
  FOREIGN KEY (`stage_org_id`) REFERENCES organization(`org_id`),
  PRIMARY KEY (`stage_id`)
);
