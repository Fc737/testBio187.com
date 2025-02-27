ALTER TABLE `Users` ADD COLUMN isActive TINYINT(1)
DEFAULT 1
COMMENT "A boolean value for for active accounts";

ALTER TABLE `Employees`ADD COLUMN isActive TINYINT(1)
DEFAULT 1
COMMENT "A boolean value for for active accounts" ; 
