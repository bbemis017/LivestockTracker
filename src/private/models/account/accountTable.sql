CREATE TABLE IF NOT EXISTS `account` (
  `account_id` INTEGER PRIMARY KEY,
  `account_username` varchar(255) NOT NULL,
  `account_password` varchar(255) NOT NULL,
  `account_email` varchar(255) NOT NULL,
  `account_active` TINYINT(1) NOT NULL
);

ALTER TABLE `account` ADD CONSTRAINT `U_account_email` UNIQUE(`account_email`);
ALTER TABLE `account` ADD CONSTRAINT `U_account_username` UNIQUE(`account_username`);
