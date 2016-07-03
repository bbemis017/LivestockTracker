CREATE TABLE IF NOT EXISTS `stage_order` (
  `stage_order_species_id` INTEGER NOT NULL,
  `stage_order_stage_id` INTEGER NOT NULL,
  `stage_order_rank` INTEGER NOT NULL,
  `stage_order_org_id` INTEGER NOT NULL,
  FOREIGN KEY (`stage_order_org_id`) REFERENCES organization(`org_id`),
  PRIMARY KEY (`stage_order_species_id`,`stage_order_stage_id`)
)
