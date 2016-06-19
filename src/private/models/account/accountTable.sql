CREATE TABLE IF NOT EXISTS `account` (
        `account_id` int NOT NULL AUTO_INCREMENT,
        `account_username` varchar(255) NOT NULL,
	`account_password` varchar(255) NOT NULL,
	`account_email` varchar(255) NOT NULL,
	`account_active` TINYINT(1) NOT NULL,
        PRIMARY KEY (account_id)
);

ALTER TABLE `account` ADD CONSTRAINT `U_account_email` UNIQUE(`account_email`);
ALTER TABLE `account` ADD CONSTRAINT `U_account_username` UNIQUE(`account_username`);
