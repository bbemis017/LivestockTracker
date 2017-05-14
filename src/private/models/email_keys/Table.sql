CREATE TABLE IF NOT EXISTS `email_keys` (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`account_id` INTEGER NOT NULL,
	`key` char(32) NOT NULL,
	`type` int(2) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`account_id`) REFERENCES account(`account_id`),
	CONSTRAINT C_account_type UNIQUE (`account_id`,`type`)
);
