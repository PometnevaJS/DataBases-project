CREATE TABLE `user` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(50) NOT NULL,
	`email` VARCHAR(50) NOT NULL,
	`password` VARCHAR(50) NOT NULL,
	`role` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `username` (`username`),
	UNIQUE INDEX `email` (`email`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
