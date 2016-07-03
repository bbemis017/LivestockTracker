CREATE TABLE IF NOT EXISTS `role` (
  `role_org_id` INTEGER NOT NULL,
  `role_account_id` INTEGER NOT NULL,
  `role_auth` INTEGER NOT NULL,
  FOREIGN KEY (`role_org_id`) REFERENCES organization(`org_id`),
  FOREIGN KEY (`role_account_id`) REFERENCES account(`account_id`),
  PRIMARY KEY(`role_org_id`,`role_account_id`)
);
