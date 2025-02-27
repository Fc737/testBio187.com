CREATE TABLE IF NOT EXISTS `Address`(
    `id` INT AUTO_INCREMENT,
    `users_id` INT,
    `street` VARCHAR(60) NOT NULL,
    `city` VARCHAR(20) NOT NULL,
    `state` VARCHAR(20) NOT NULL,
    `zip_code` MEDIUMINT(9) NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`users_id`) REFERENCES Users(`id`)
)