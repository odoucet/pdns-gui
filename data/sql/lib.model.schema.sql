
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- domains
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `domains`;


CREATE TABLE `domains`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255)  NOT NULL,
	`master` VARCHAR(20),
	`last_check` INTEGER,
	`type` VARCHAR(6)  NOT NULL,
	`notified_serial` INTEGER,
	`account` VARCHAR(40),
	PRIMARY KEY (`id`),
	UNIQUE KEY `name_index` (`name`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- records
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `records`;


CREATE TABLE `records`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`domain_id` INTEGER,
	`name` VARCHAR(255),
	`type` VARCHAR(6),
	`content` VARCHAR(255),
	`ttl` INTEGER,
	`prio` INTEGER,
	`change_date` INTEGER,
	PRIMARY KEY (`id`),
	KEY `rec_name_index`(`name`),
	KEY `nametype_index`(`name`, `type`),
	KEY `domain_id`(`domain_id`),
	CONSTRAINT `records_FK_1`
		FOREIGN KEY (`domain_id`)
		REFERENCES `domains` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- supermasters
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `supermasters`;


CREATE TABLE `supermasters`
(
	`ip` VARCHAR(25)  NOT NULL,
	`nameserver` VARCHAR(255)  NOT NULL,
	`account` VARCHAR(40),
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- template
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `template`;


CREATE TABLE `template`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`type` VARCHAR(45),
	PRIMARY KEY (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- template_record
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `template_record`;


CREATE TABLE `template_record`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`template_id` INTEGER,
	`name` VARCHAR(255),
	`type` VARCHAR(6),
	`content` VARCHAR(255),
	`ttl` INTEGER,
	`prio` INTEGER default null,
	PRIMARY KEY (`id`),
	INDEX `template_record_FI_1` (`template_id`),
	CONSTRAINT `template_record_FK_1`
		FOREIGN KEY (`template_id`)
		REFERENCES `template` (`id`)
)Type=MyISAM;

#-----------------------------------------------------------------------------
#-- setting
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `setting`;


CREATE TABLE `setting`
(
	`name` VARCHAR(255)  NOT NULL,
	`value` TEXT,
	PRIMARY KEY (`name`)
)Type=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
