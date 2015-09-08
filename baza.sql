CREATE TABLE `brain_base` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ProductID` INT(11) NULL DEFAULT NULL,
	`NameRu` TEXT NULL COLLATE 'utf8_unicode_ci',
	`NameUa` TEXT NULL COLLATE 'utf8_unicode_ci',
	`FullDescription` TEXT NULL COLLATE 'utf8_unicode_ci',
	`Slug` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`SlugUa` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`BriefDescriptionRu` TEXT NULL COLLATE 'utf8_unicode_ci',
	`BriefDescriptionUa` TEXT NULL COLLATE 'utf8_unicode_ci',
	`ProductGroupID` INT(11) NULL DEFAULT NULL,
	`ProductCode` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`i` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `ProductID` (`ProductID`),
	INDEX `ProductCode` (`ProductCode`),
	INDEX `i` (`i`),
	INDEX `GID` (`ProductGroupID`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `brain_attributes` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_base` INT(11) NULL DEFAULT NULL,
	`attribute_name` VARCHAR(500) NOT NULL COLLATE 'utf8_unicode_ci',
	`attribute_value` TEXT NOT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`),
	INDEX `id_base` (`id_base`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

CREATE TABLE `brain_images` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_base` INT(11) NULL DEFAULT NULL,
	`images_brain_path` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`images_local_path` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`),
	INDEX `id_base` (`id_base`)
)
COLLATE='utf8_unicode_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;
