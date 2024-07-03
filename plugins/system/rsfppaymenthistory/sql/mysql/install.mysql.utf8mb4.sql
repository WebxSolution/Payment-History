CREATE TABLE IF NOT EXISTS `#__rsform_paymentHistory`
(
	`id` INT NOT NULL AUTO_INCREMENT,
	`SubmissionId` INT NOT NULL, 
	`UserId` INT NOT NULL,
	`customer_name` VARCHAR(100),
	`checkstorun` TEXT,
	`payment_method` VARCHAR(50),
	`amount` VARCHAR(50),
	`created` DATETIME,
	`modified` DATETIME ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

