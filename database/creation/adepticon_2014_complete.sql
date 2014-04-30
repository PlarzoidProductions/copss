SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `iron_arena` ;
CREATE SCHEMA IF NOT EXISTS `iron_arena` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `iron_arena` ;

-- -----------------------------------------------------
-- Table `iron_arena`.`countries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`countries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`states`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`states` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `parent` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_id` (`parent` ASC) ,
  CONSTRAINT `fk_parent_id`
    FOREIGN KEY (`parent` )
    REFERENCES `iron_arena`.`countries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(255) NOT NULL ,
  `last_name` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `country` INT(10) UNSIGNED NOT NULL ,
  `state` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `vip` TINYINT(1) NULL DEFAULT '0' ,
  `creation_date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_country_id` (`country` ASC) ,
  INDEX `fk_state_id` (`state` ASC) ,
  CONSTRAINT `fk_country_id`
    FOREIGN KEY (`country` )
    REFERENCES `iron_arena`.`countries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_state_id`
    FOREIGN KEY (`state` )
    REFERENCES `iron_arena`.`states` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_systems`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_systems` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_sizes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_sizes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT(10) UNSIGNED NOT NULL ,
  `size` INT(11) NOT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_system_factions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_system_factions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT(10) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `acronym` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_faction_parent_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`games`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`games` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `creation_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `game_system` INT(10) UNSIGNED NOT NULL ,
  `scenario` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_game_system_id` (`game_system` ASC) ,
  CONSTRAINT `fk_parent_game_system_id`
    FOREIGN KEY (`game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `game_id` INT(10) UNSIGNED NOT NULL ,
  `player_id` INT(10) UNSIGNED NOT NULL ,
  `faction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `game_size` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `theme_force` TINYINT(1) NOT NULL DEFAULT '0' ,
  `fully_painted` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_players_game_id` (`game_id` ASC) ,
  INDEX `fk_game_players_player_id` (`player_id` ASC) ,
  INDEX `fk_game_players_faction_id` (`faction_id` ASC) ,
  INDEX `fk_game_players_game_size_id` (`game_size` ASC) ,
  CONSTRAINT `fk_game_players_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `iron_arena`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `iron_arena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `iron_arena`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_game_size_id`
    FOREIGN KEY (`game_size` )
    REFERENCES `iron_arena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`achievements`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`achievements` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `points` INT(11) NOT NULL ,
  `per_game` TINYINT(1) NOT NULL DEFAULT '0' ,
  `is_meta` TINYINT(1) NOT NULL DEFAULT '0' ,
  `game_count` INT(11) NULL DEFAULT NULL ,
  `game_system_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `game_size_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `faction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `unique_opponent` TINYINT(1) NULL DEFAULT NULL ,
  `unique_opponent_locations` TINYINT(1) NULL DEFAULT NULL ,
  `played_theme_force` TINYINT(1) NULL DEFAULT NULL ,
  `fully_painted` TINYINT(1) NULL DEFAULT NULL ,
  `fully_painted_battle` TINYINT(1) NULL DEFAULT NULL ,
  `played_scenario` TINYINT(1) NULL DEFAULT NULL ,
  `multiplayer` TINYINT(1) NULL DEFAULT NULL ,
  `vs_vip` TINYINT(1) NULL DEFAULT NULL ,
  `event_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ach_game_system_id` (`game_system_id` ASC) ,
  INDEX `fk_ach_game_size_id` (`game_size_id` ASC) ,
  INDEX `fk_ach_faction_id` (`faction_id` ASC) ,
  INDEX `fk_ach_event_id` (`event_id` ASC) ,
  CONSTRAINT `fk_ach_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `iron_arena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `iron_arena`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_event_id`
    FOREIGN KEY (`event_id` )
    REFERENCES `iron_arena`.`events` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`achievements_earned`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`achievements_earned` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT(10) UNSIGNED NOT NULL ,
  `achievement_id` INT(10) UNSIGNED NOT NULL ,
  `game_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ach_earned_player_id` (`player_id` ASC) ,
  INDEX `fk_ach_earned_achievement_id` (`achievement_id` ASC) ,
  INDEX `fk_ach_earned_game_id` (`game_id` ASC) ,
  CONSTRAINT `fk_ach_earned_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `iron_arena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_achievement_id`
    FOREIGN KEY (`achievement_id` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `iron_arena`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`meta_achievement_criteria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`meta_achievement_criteria` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_achievement` INT(10) UNSIGNED NOT NULL ,
  `child_achievement` INT(10) UNSIGNED NOT NULL ,
  `count` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_ach_id` (`parent_achievement` ASC) ,
  INDEX `fk_child_ach_id` (`child_achievement` ASC) ,
  CONSTRAINT `fk_parent_ach_id`
    FOREIGN KEY (`parent_achievement` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_child_ach_id`
    FOREIGN KEY (`child_achievement` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `iron_arena`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `username` VARCHAR(20) NOT NULL ,
  `password` CHAR(80) NOT NULL ,
  `creation_date` DATETIME NOT NULL ,
  `last_login` TIMESTAMP NULL ,
  `admin` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`prize_redemptions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`prize_redemptions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `cost` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_prize_redemptions_player_id` (`player_id` ASC) ,
  CONSTRAINT `fk_prize_redemptions_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `iron_arena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`feedback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`feedback` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(45) NOT NULL ,
  `comment` LONGTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`earned`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`earned` (`player_id` INT, `earned` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`spent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`spent` (`player_id` INT, `spent` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`game_counter`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_counter` (`player_id` INT, `game_count` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`leaderboard`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`leaderboard` (`player_id` INT, `last_name` INT, `first_name` INT, `game_count` INT, `earned` INT, `spent` INT, `points` INT);

-- -----------------------------------------------------
-- View `iron_arena`.`earned`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`earned`;
USE `iron_arena`;
CREATE  OR REPLACE VIEW `iron_arena`.`earned` AS 
SELECT ae.player_id as `player_id`, sum(a.points) as earned
FROM `iron_arena`.`achievements_earned` ae, `iron_arena`.`achievements` a
WHERE ae.achievement_id=a.id
GROUP BY ae.player_id;

-- -----------------------------------------------------
-- View `iron_arena`.`spent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`spent`;
USE `iron_arena`;
CREATE  OR REPLACE VIEW `iron_arena`.`spent` AS
SELECT pr.player_id as `player_id`, sum(pr.cost) as `spent`
FROM prize_redemptions pr
GROUP BY `player_id`
;

-- -----------------------------------------------------
-- View `iron_arena`.`game_counter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`game_counter`;
USE `iron_arena`;
CREATE  OR REPLACE VIEW `iron_arena`.`game_counter` AS
SELECT player_id, count(1) AS game_count
FROM game_players
GROUP BY player_id;

-- -----------------------------------------------------
-- View `iron_arena`.`leaderboard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`leaderboard`;
USE `iron_arena`;
CREATE  OR REPLACE VIEW `iron_arena`.`leaderboard` AS
SELECT players.id AS `player_id`,
players.last_name AS last_name,
players.first_name AS first_name,
game_counter.game_count AS game_count,
earned.earned AS earned,
spent.spent AS spent,
earned - spent as points
FROM `iron_arena`.`players`
LEFT OUTER JOIN `iron_arena`.`game_counter` 
        ON game_counter.player_id=players.id
LEFT OUTER JOIN `iron_arena`.`earned` 
        ON earned.player_id=players.id
LEFT OUTER JOIN `iron_arena`.`spent` 
        ON spent.player_id=players.id
ORDER BY points DESC, last_name ASC;
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
USE iron_arena;
INSERT INTO users (username, password, creation_date, admin) VALUES ('Admin', '5f4dcc3b5aa765d61d8327deb882cf99', NOW(), 1);
DROP USER 'ironarena'@'localhost';
CREATE USER 'ironarena'@'localhost' identified by 'iwantskullz';
GRANT ALL ON iron_arena.* to 'ironarena'@'localhost';
USE iron_arena;

INSERT INTO countries (name) VALUES ('Afghanistan');
INSERT INTO countries (name) VALUES ('Akrotiri');
INSERT INTO countries (name) VALUES ('Albania');
INSERT INTO countries (name) VALUES ('Algeria');
INSERT INTO countries (name) VALUES ('American Samoa');
INSERT INTO countries (name) VALUES ('Andorra');
INSERT INTO countries (name) VALUES ('Angola');
INSERT INTO countries (name) VALUES ('Anguilla');
INSERT INTO countries (name) VALUES ('Antarctica');
INSERT INTO countries (name) VALUES ('Antigua and Barbuda');
INSERT INTO countries (name) VALUES ('Argentina');
INSERT INTO countries (name) VALUES ('Armenia');
INSERT INTO countries (name) VALUES ('Aruba');
INSERT INTO countries (name) VALUES ('Ashmore and Cartier Islands');
INSERT INTO countries (name) VALUES ('Australia');
INSERT INTO countries (name) VALUES ('Austria');
INSERT INTO countries (name) VALUES ('Azerbaijan');
INSERT INTO countries (name) VALUES ('Bahamas, The');
INSERT INTO countries (name) VALUES ('Bahrain');
INSERT INTO countries (name) VALUES ('Bangladesh');
INSERT INTO countries (name) VALUES ('Barbados');
INSERT INTO countries (name) VALUES ('Bassas da India');
INSERT INTO countries (name) VALUES ('Belarus');
INSERT INTO countries (name) VALUES ('Belgium');
INSERT INTO countries (name) VALUES ('Belize');
INSERT INTO countries (name) VALUES ('Benin');
INSERT INTO countries (name) VALUES ('Bermuda');
INSERT INTO countries (name) VALUES ('Bhutan');
INSERT INTO countries (name) VALUES ('Bolivia');
INSERT INTO countries (name) VALUES ('Bosnia and Herzegovina');
INSERT INTO countries (name) VALUES ('Botswana');
INSERT INTO countries (name) VALUES ('Bouvet Island');
INSERT INTO countries (name) VALUES ('Brazil');
INSERT INTO countries (name) VALUES ('British Indian Ocean Territory');
INSERT INTO countries (name) VALUES ('British Virgin Islands');
INSERT INTO countries (name) VALUES ('Brunei');
INSERT INTO countries (name) VALUES ('Bulgaria');
INSERT INTO countries (name) VALUES ('Burkina Faso');
INSERT INTO countries (name) VALUES ('Burma');
INSERT INTO countries (name) VALUES ('Burundi');
INSERT INTO countries (name) VALUES ('Cambodia');
INSERT INTO countries (name) VALUES ('Cameroon');
INSERT INTO countries (name) VALUES ('Canada');
INSERT INTO countries (name) VALUES ('Cape Verde');
INSERT INTO countries (name) VALUES ('Cayman Islands');
INSERT INTO countries (name) VALUES ('Central African Republic');
INSERT INTO countries (name) VALUES ('Chad');
INSERT INTO countries (name) VALUES ('Chile');
INSERT INTO countries (name) VALUES ('China');
INSERT INTO countries (name) VALUES ('Christmas Island');
INSERT INTO countries (name) VALUES ('Clipperton Island');
INSERT INTO countries (name) VALUES ('Cocos (Keeling) Islands');
INSERT INTO countries (name) VALUES ('Colombia');
INSERT INTO countries (name) VALUES ('Comoros');
INSERT INTO countries (name) VALUES ('Congo, Democratic Republic of the');
INSERT INTO countries (name) VALUES ('Congo, Republic of the');
INSERT INTO countries (name) VALUES ('Cook Islands');
INSERT INTO countries (name) VALUES ('Coral Sea Islands');
INSERT INTO countries (name) VALUES ('Costa Rica');
INSERT INTO countries (name) VALUES ('Cote d\'Ivoire');
INSERT INTO countries (name) VALUES ('Croatia');
INSERT INTO countries (name) VALUES ('Cuba');
INSERT INTO countries (name) VALUES ('Cyprus');
INSERT INTO countries (name) VALUES ('Czech Republic');
INSERT INTO countries (name) VALUES ('Denmark');
INSERT INTO countries (name) VALUES ('Dhekelia');
INSERT INTO countries (name) VALUES ('Djibouti');
INSERT INTO countries (name) VALUES ('Dominica');
INSERT INTO countries (name) VALUES ('Dominican Republic');
INSERT INTO countries (name) VALUES ('Ecuador');
INSERT INTO countries (name) VALUES ('Egypt');
INSERT INTO countries (name) VALUES ('El Salvador');
INSERT INTO countries (name) VALUES ('Equatorial Guinea');
INSERT INTO countries (name) VALUES ('Eritrea');
INSERT INTO countries (name) VALUES ('Estonia');
INSERT INTO countries (name) VALUES ('Ethiopia');
INSERT INTO countries (name) VALUES ('Europa Island');
INSERT INTO countries (name) VALUES ('Falkland Islands (Islas Malvinas)');
INSERT INTO countries (name) VALUES ('Faroe Islands');
INSERT INTO countries (name) VALUES ('Fiji');
INSERT INTO countries (name) VALUES ('Finland');
INSERT INTO countries (name) VALUES ('France');
INSERT INTO countries (name) VALUES ('French Guiana');
INSERT INTO countries (name) VALUES ('French Polynesia');
INSERT INTO countries (name) VALUES ('French Southern and Antarctic Lands');
INSERT INTO countries (name) VALUES ('Gabon');
INSERT INTO countries (name) VALUES ('Gambia, The');
INSERT INTO countries (name) VALUES ('Gaza Strip');
INSERT INTO countries (name) VALUES ('Georgia');
INSERT INTO countries (name) VALUES ('Germany');
INSERT INTO countries (name) VALUES ('Ghana');
INSERT INTO countries (name) VALUES ('Gibraltar');
INSERT INTO countries (name) VALUES ('Glorioso Islands');
INSERT INTO countries (name) VALUES ('Greece');
INSERT INTO countries (name) VALUES ('Greenland');
INSERT INTO countries (name) VALUES ('Grenada');
INSERT INTO countries (name) VALUES ('Guadeloupe');
INSERT INTO countries (name) VALUES ('Guam');
INSERT INTO countries (name) VALUES ('Guatemala');
INSERT INTO countries (name) VALUES ('Guernsey');
INSERT INTO countries (name) VALUES ('Guinea');
INSERT INTO countries (name) VALUES ('Guinea-Bissau');
INSERT INTO countries (name) VALUES ('Guyana');
INSERT INTO countries (name) VALUES ('Haiti');
INSERT INTO countries (name) VALUES ('Heard Island and McDonald Islands');
INSERT INTO countries (name) VALUES ('Holy See (Vatican City)');
INSERT INTO countries (name) VALUES ('Honduras');
INSERT INTO countries (name) VALUES ('Hong Kong');
INSERT INTO countries (name) VALUES ('Hungary');
INSERT INTO countries (name) VALUES ('Iceland');
INSERT INTO countries (name) VALUES ('India');
INSERT INTO countries (name) VALUES ('Indonesia');
INSERT INTO countries (name) VALUES ('Iran');
INSERT INTO countries (name) VALUES ('Iraq');
INSERT INTO countries (name) VALUES ('Ireland');
INSERT INTO countries (name) VALUES ('Isle of Man');
INSERT INTO countries (name) VALUES ('Israel');
INSERT INTO countries (name) VALUES ('Italy');
INSERT INTO countries (name) VALUES ('Jamaica');
INSERT INTO countries (name) VALUES ('Jan Mayen');
INSERT INTO countries (name) VALUES ('Japan');
INSERT INTO countries (name) VALUES ('Jersey');
INSERT INTO countries (name) VALUES ('Jordan');
INSERT INTO countries (name) VALUES ('Juan de Nova Island');
INSERT INTO countries (name) VALUES ('Kazakhstan');
INSERT INTO countries (name) VALUES ('Kenya');
INSERT INTO countries (name) VALUES ('Kiribati');
INSERT INTO countries (name) VALUES ('Korea, North');
INSERT INTO countries (name) VALUES ('Korea, South');
INSERT INTO countries (name) VALUES ('Kuwait');
INSERT INTO countries (name) VALUES ('Kyrgyzstan');
INSERT INTO countries (name) VALUES ('Laos');
INSERT INTO countries (name) VALUES ('Latvia');
INSERT INTO countries (name) VALUES ('Lebanon');
INSERT INTO countries (name) VALUES ('Lesotho');
INSERT INTO countries (name) VALUES ('Liberia');
INSERT INTO countries (name) VALUES ('Libya');
INSERT INTO countries (name) VALUES ('Liechtenstein');
INSERT INTO countries (name) VALUES ('Lithuania');
INSERT INTO countries (name) VALUES ('Luxembourg');
INSERT INTO countries (name) VALUES ('Macau');
INSERT INTO countries (name) VALUES ('Macedonia');
INSERT INTO countries (name) VALUES ('Madagascar');
INSERT INTO countries (name) VALUES ('Malawi');
INSERT INTO countries (name) VALUES ('Malaysia');
INSERT INTO countries (name) VALUES ('Maldives');
INSERT INTO countries (name) VALUES ('Mali');
INSERT INTO countries (name) VALUES ('Malta');
INSERT INTO countries (name) VALUES ('Marshall Islands');
INSERT INTO countries (name) VALUES ('Martinique');
INSERT INTO countries (name) VALUES ('Mauritania');
INSERT INTO countries (name) VALUES ('Mauritius');
INSERT INTO countries (name) VALUES ('Mayotte');
INSERT INTO countries (name) VALUES ('Mexico');
INSERT INTO countries (name) VALUES ('Micronesia, Federated States of');
INSERT INTO countries (name) VALUES ('Moldova');
INSERT INTO countries (name) VALUES ('Monaco');
INSERT INTO countries (name) VALUES ('Mongolia');
INSERT INTO countries (name) VALUES ('Montserrat');
INSERT INTO countries (name) VALUES ('Morocco');
INSERT INTO countries (name) VALUES ('Mozambique');
INSERT INTO countries (name) VALUES ('Namibia');
INSERT INTO countries (name) VALUES ('Nauru');
INSERT INTO countries (name) VALUES ('Navassa Island');
INSERT INTO countries (name) VALUES ('Nepal');
INSERT INTO countries (name) VALUES ('Netherlands');
INSERT INTO countries (name) VALUES ('Netherlands Antilles');
INSERT INTO countries (name) VALUES ('New Caledonia');
INSERT INTO countries (name) VALUES ('New Zealand');
INSERT INTO countries (name) VALUES ('Nicaragua');
INSERT INTO countries (name) VALUES ('Niger');
INSERT INTO countries (name) VALUES ('Nigeria');
INSERT INTO countries (name) VALUES ('Niue');
INSERT INTO countries (name) VALUES ('Norfolk Island');
INSERT INTO countries (name) VALUES ('Northern Mariana Islands');
INSERT INTO countries (name) VALUES ('Norway');
INSERT INTO countries (name) VALUES ('Oman');
INSERT INTO countries (name) VALUES ('Pakistan');
INSERT INTO countries (name) VALUES ('Palau');
INSERT INTO countries (name) VALUES ('Panama');
INSERT INTO countries (name) VALUES ('Papua New Guinea');
INSERT INTO countries (name) VALUES ('Paracel Islands');
INSERT INTO countries (name) VALUES ('Paraguay');
INSERT INTO countries (name) VALUES ('Peru');
INSERT INTO countries (name) VALUES ('Philippines');
INSERT INTO countries (name) VALUES ('Pitcairn Islands');
INSERT INTO countries (name) VALUES ('Poland');
INSERT INTO countries (name) VALUES ('Portugal');
INSERT INTO countries (name) VALUES ('Puerto Rico');
INSERT INTO countries (name) VALUES ('Qatar');
INSERT INTO countries (name) VALUES ('Reunion');
INSERT INTO countries (name) VALUES ('Romania');
INSERT INTO countries (name) VALUES ('Russia');
INSERT INTO countries (name) VALUES ('Rwanda');
INSERT INTO countries (name) VALUES ('Saint Helena');
INSERT INTO countries (name) VALUES ('Saint Kitts and Nevis');
INSERT INTO countries (name) VALUES ('Saint Lucia');
INSERT INTO countries (name) VALUES ('Saint Pierre and Miquelon');
INSERT INTO countries (name) VALUES ('Saint Vincent and the Grenadines');
INSERT INTO countries (name) VALUES ('Samoa');
INSERT INTO countries (name) VALUES ('San Marino');
INSERT INTO countries (name) VALUES ('Sao Tome and Principe');
INSERT INTO countries (name) VALUES ('Saudi Arabia');
INSERT INTO countries (name) VALUES ('Senegal');
INSERT INTO countries (name) VALUES ('Serbia and Montenegro');
INSERT INTO countries (name) VALUES ('Seychelles');
INSERT INTO countries (name) VALUES ('Sierra Leone');
INSERT INTO countries (name) VALUES ('Singapore');
INSERT INTO countries (name) VALUES ('Slovakia');
INSERT INTO countries (name) VALUES ('Slovenia');
INSERT INTO countries (name) VALUES ('Solomon Islands');
INSERT INTO countries (name) VALUES ('Somalia');
INSERT INTO countries (name) VALUES ('South Africa');
INSERT INTO countries (name) VALUES ('South Georgia and the South Sandwich Islands');
INSERT INTO countries (name) VALUES ('Spain');
INSERT INTO countries (name) VALUES ('Spratly Islands');
INSERT INTO countries (name) VALUES ('Sri Lanka');
INSERT INTO countries (name) VALUES ('Sudan');
INSERT INTO countries (name) VALUES ('Suriname');
INSERT INTO countries (name) VALUES ('Svalbard');
INSERT INTO countries (name) VALUES ('Swaziland');
INSERT INTO countries (name) VALUES ('Sweden');
INSERT INTO countries (name) VALUES ('Switzerland');
INSERT INTO countries (name) VALUES ('Syria');
INSERT INTO countries (name) VALUES ('Taiwan');
INSERT INTO countries (name) VALUES ('Tajikistan');
INSERT INTO countries (name) VALUES ('Tanzania');
INSERT INTO countries (name) VALUES ('Thailand');
INSERT INTO countries (name) VALUES ('Timor-Leste');
INSERT INTO countries (name) VALUES ('Togo');
INSERT INTO countries (name) VALUES ('Tokelau');
INSERT INTO countries (name) VALUES ('Tonga');
INSERT INTO countries (name) VALUES ('Trinidad and Tobago');
INSERT INTO countries (name) VALUES ('Tromelin Island');
INSERT INTO countries (name) VALUES ('Tunisia');
INSERT INTO countries (name) VALUES ('Turkey');
INSERT INTO countries (name) VALUES ('Turkmenistan');
INSERT INTO countries (name) VALUES ('Turks and Caicos Islands');
INSERT INTO countries (name) VALUES ('Tuvalu');
INSERT INTO countries (name) VALUES ('Uganda');
INSERT INTO countries (name) VALUES ('Ukraine');
INSERT INTO countries (name) VALUES ('United Arab Emirates');
INSERT INTO countries (name) VALUES ('United Kingdom');
INSERT INTO countries (name) VALUES ('United States');
INSERT INTO countries (name) VALUES ('Uruguay');
INSERT INTO countries (name) VALUES ('Uzbekistan');
INSERT INTO countries (name) VALUES ('Vanuatu');
INSERT INTO countries (name) VALUES ('Venezuela');
INSERT INTO countries (name) VALUES ('Vietnam');
INSERT INTO countries (name) VALUES ('Virgin Islands');
INSERT INTO countries (name) VALUES ('Wake Island');
INSERT INTO countries (name) VALUES ('Wallis and Futuna');
INSERT INTO countries (name) VALUES ('West Bank');
INSERT INTO countries (name) VALUES ('Western Sahara');
INSERT INTO countries (name) VALUES ('Yemen');
INSERT INTO countries (name) VALUES ('Zambia');
INSERT INTO countries (name) VALUES ('Zimbabwe');

INSERT INTO states (parent, name) VALUES (244, 'Alabama');
INSERT INTO states (parent, name) VALUES (244, 'Alaska');
INSERT INTO states (parent, name) VALUES (244, 'Arizona');
INSERT INTO states (parent, name) VALUES (244, 'Arkansas');
INSERT INTO states (parent, name) VALUES (244, 'California');
INSERT INTO states (parent, name) VALUES (244, 'Colorado');
INSERT INTO states (parent, name) VALUES (244, 'Connecticut');
INSERT INTO states (parent, name) VALUES (244, 'Delaware');
INSERT INTO states (parent, name) VALUES (244, 'Florida');
INSERT INTO states (parent, name) VALUES (244, 'Georgia');
INSERT INTO states (parent, name) VALUES (244, 'Hawaii');
INSERT INTO states (parent, name) VALUES (244, 'Idaho');
INSERT INTO states (parent, name) VALUES (244, 'Illinois');
INSERT INTO states (parent, name) VALUES (244, 'Indiana');
INSERT INTO states (parent, name) VALUES (244, 'Iowa');
INSERT INTO states (parent, name) VALUES (244, 'Kansas');
INSERT INTO states (parent, name) VALUES (244, 'Kentucky');
INSERT INTO states (parent, name) VALUES (244, 'Louisiana');
INSERT INTO states (parent, name) VALUES (244, 'Maine');
INSERT INTO states (parent, name) VALUES (244, 'Maryland');
INSERT INTO states (parent, name) VALUES (244, 'Massachusetts');
INSERT INTO states (parent, name) VALUES (244, 'Michigan');
INSERT INTO states (parent, name) VALUES (244, 'Minnesota');
INSERT INTO states (parent, name) VALUES (244, 'Mississippi');
INSERT INTO states (parent, name) VALUES (244, 'Missouri');
INSERT INTO states (parent, name) VALUES (244, 'Montana');
INSERT INTO states (parent, name) VALUES (244, 'Nebraska');
INSERT INTO states (parent, name) VALUES (244, 'Nevada');
INSERT INTO states (parent, name) VALUES (244, 'New Hampshire');
INSERT INTO states (parent, name) VALUES (244, 'New Jersey');
INSERT INTO states (parent, name) VALUES (244, 'New Mexico');
INSERT INTO states (parent, name) VALUES (244, 'New York');
INSERT INTO states (parent, name) VALUES (244, 'North Carolina');
INSERT INTO states (parent, name) VALUES (244, 'North Dakota');
INSERT INTO states (parent, name) VALUES (244, 'Ohio');
INSERT INTO states (parent, name) VALUES (244, 'Oklahoma');
INSERT INTO states (parent, name) VALUES (244, 'Oregon');
INSERT INTO states (parent, name) VALUES (244, 'Pennsylvania');
INSERT INTO states (parent, name) VALUES (244, 'Rhode Island');
INSERT INTO states (parent, name) VALUES (244, 'South Carolina');
INSERT INTO states (parent, name) VALUES (244, 'South Dakota');
INSERT INTO states (parent, name) VALUES (244, 'Tennessee');
INSERT INTO states (parent, name) VALUES (244, 'Texas');
INSERT INTO states (parent, name) VALUES (244, 'Utah');
INSERT INTO states (parent, name) VALUES (244, 'Vermont');
INSERT INTO states (parent, name) VALUES (244, 'Virginia');
INSERT INTO states (parent, name) VALUES (244, 'Washington');
INSERT INTO states (parent, name) VALUES (244, 'West Virginia');
INSERT INTO states (parent, name) VALUES (244, 'Wisconsin');
INSERT INTO states (parent, name) VALUES (244, 'Wyoming');
INSERT INTO states (parent, name) VALUES (244, 'District of Columbia');
INSERT INTO states (parent, name) VALUES (244, 'American Samoa');
INSERT INTO states (parent, name) VALUES (244, 'Guam');
INSERT INTO states (parent, name) VALUES (244, 'Northern Mariana Islands');
INSERT INTO states (parent, name) VALUES (244, 'Puerto Rico');
INSERT INTO states (parent, name) VALUES (244, 'U.S. Virgin Islands');
INSERT INTO states (parent, name) VALUES (244, 'Baker Island');
INSERT INTO states (parent, name) VALUES (244, 'Howland Island');
INSERT INTO states (parent, name) VALUES (244, 'Jarvis Island');
INSERT INTO states (parent, name) VALUES (244, 'Johnston Atoll');
INSERT INTO states (parent, name) VALUES (244, 'Kingman Reef');
INSERT INTO states (parent, name) VALUES (244, 'Midway Atoll');
INSERT INTO states (parent, name) VALUES (244, 'Navassa Island');
INSERT INTO states (parent, name) VALUES (244, 'Palmyra Atoll');
INSERT INTO states (parent, name) VALUES (244, 'Wake Island');

INSERT INTO states (parent, name) VALUES (43, 'Alberta');
INSERT INTO states (parent, name) VALUES (43, 'British Columbia');
INSERT INTO states (parent, name) VALUES (43, 'Manitoba');
INSERT INTO states (parent, name) VALUES (43, 'New Brunswick');
INSERT INTO states (parent, name) VALUES (43, 'Newfoundland and Labrador');
INSERT INTO states (parent, name) VALUES (43, 'Nova Scotia');
INSERT INTO states (parent, name) VALUES (43, 'Ontario');
INSERT INTO states (parent, name) VALUES (43, 'Prince Edward Island');
INSERT INTO states (parent, name) VALUES (43, 'Quebec');
INSERT INTO states (parent, name) VALUES (43, 'Saskatchewan');
INSERT INTO states (parent, name) VALUES (43, 'Northwest Territories');
INSERT INTO states (parent, name) VALUES (43, 'Nunavut');
INSERT INTO states (parent, name) VALUES (43, 'Yukon');
USE iron_arena;

INSERT INTO game_systems (name) VALUES ('Warmachine / Hordes');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Cygnar', 'Cyg');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Khador', 'Kha');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Protectorate', 'PoM');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Cryx', 'Crx');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Mercenaries', 'Mer');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Retribution', 'Ret');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Convergence', 'Con');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Legion', 'LoE');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Circle', 'CoO');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Skorne', 'Sko');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Trollbloods', 'Tro');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Minions', 'Min');


INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 25, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 35, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 50, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 75, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 100, 'Unbound');
USE iron_arena;

INSERT INTO game_systems (name) VALUES ('High Command');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Cygnar', 'Cyg');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Khador', 'Kha');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Protectorate', 'PoM');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Cryx', 'Crx');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Legion', 'LoE');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Circle', 'CoO');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Skorne', 'Sko');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (2, 'Trollbloods', 'Tro');

USE iron_arena;

INSERT INTO game_systems (name) VALUES ('LEVEL 7 [ESCAPE]');
INSERT INTO game_systems (name) VALUES ('LEVEL 7 [OMEGA PROTOCOL]');
INSERT INTO game_systems (name) VALUES ('Infernal Contraption');
INSERT INTO game_systems (name) VALUES ('Heap');
INSERT INTO game_systems (name) VALUES ('Bodgermania');
INSERT INTO game_systems (name) VALUES ('Zombies Keep Out!');
INSERT INTO `achievements` VALUES (1,'Play a 25-point game',1,1,0,0,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'Play a 35-point game',1,1,0,0,1,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'Play a 50-point game',2,1,0,0,1,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'Play a 75-point game',3,1,0,0,1,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'Play an Unbound game',5,1,0,0,1,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,'Play a game with 3+ players',1,1,0,0,NULL,NULL,NULL,0,0,0,0,0,0,1,0,NULL),(7,'Play a Scenario Table',1,1,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL),(8,'Played Fully Painted',1,1,0,0,1,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(9,'Play High Command',2,1,0,0,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,'Play Level 7 [Escape]',2,1,0,0,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,'Play Level 7 [Omega Protocol]',2,1,0,0,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,'Play Infernal Contraption',1,1,0,0,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(13,'Play Heap',1,1,0,0,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,'Play Bodgermania',1,1,0,0,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(15,'Play Zombies Keep Out!',1,1,0,0,8,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,'Play Different Player',0,1,0,0,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,'Play opponent from different location',0,1,0,0,NULL,NULL,NULL,0,1,0,0,0,0,0,0,NULL),(18,'Play 5 different players',2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,'Play 10 different players',5,0,1,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,NULL),(20,'Play 15 different players',5,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(21,'Play 20 different players',10,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(22,'Play opponents from 5 different locations',5,0,1,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,NULL),(23,'Play opponents from 10 different locations',5,0,1,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,NULL),(24,'Play opponents from 15 different locations',10,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,'Play 5th game',1,0,0,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,'Play 10th game',2,0,0,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,'Play 15th game',2,0,0,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,'Play 20th game',2,0,0,20,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,'Play all four Bodgers games',5,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,'Play both Level 7 games',5,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,'Completed Thurs HC Kingmaker',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,1),(32,'Completed Fri HC Kingmaker',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,5),(33,'Completed Sat HC Kingmaker',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,11),(34,'Completed Sun HC Kingmaker',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,15),(35,'Completed Masters Qualifier 1',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,3),(36,'Completed Masters Qualifier 2',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,4),(37,'Completed Masters Finale',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10),(38,'Completed Iron Gauntlet Qualifier 1',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,6),(39,'Completed Iron Gauntlet Qualifier 2',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,8),(40,'Completed Iron Gauntlet Finale',10,0,0,0,NULL,NULL,NULL,0,0,0,0,0,0,0,0,13),(41,'Completed Blood, Sweat & tiers',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2),(42,'Completed Who\'s the Boss?',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7),(43,'Completed Team Tournament',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9),(44,'Completed Hardcore!',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12),(45,'Completed Commander\'s Crucible',10,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14);

INSERT INTO `events` VALUES (1,'Thurs: HC - Kingmaker'),(2,'Thurs: WM - Blood, Sweat & Tiers'),(3,'Thurs: WM - Masters Qualifier 1'),(4,'Thurs: WM - Masters Qualifier 2'),(5,'Fri: HC - Kingmaker'),(6,'Fri: WM - Iron Gauntlet Qualifier 1'),(7,'Fri: WM - Who\'s the Boss?'),(8,'Fri: WM - Iron Gauntlet Qualifier 2'),(9,'Sat: WM - Team Tournament'),(10,'Sat: WM - Masters Finale'),(11,'Sat: HC - Kingmaker'),(12,'Sat: WM - Hardcore!'),(13,'Sun: WM - Iron Gauntlet Finale'),(14,'Sun: WM - Commander\'s Crucible'),(15,'Sun: HC - Kingmaker');

INSERT INTO `meta_achievement_criteria` VALUES (1,18,16,5),(2,19,16,10),(3,20,16,15),(4,21,16,20),(5,22,17,5),(6,23,17,10),(7,24,17,15),(8,29,12,1),(9,29,13,1),(10,29,14,1),(11,29,15,1),(12,30,10,1),(13,30,11,1);

