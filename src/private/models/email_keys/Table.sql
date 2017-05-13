CREATE TABLE IF NOT EXISTS `email_keys` (
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`account_id` INTEGER NOT NULL,
	`key` char(32) NOT NULL,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`account_id`) REFERENCES account(`account_id`)
);
