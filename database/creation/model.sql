SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `copss` ;
CREATE SCHEMA IF NOT EXISTS `copss` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `copss` ;

-- -----------------------------------------------------
-- Table `copss`.`countries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`countries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `copss`.`states`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`states` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `parent` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_id` (`parent` ASC) ,
  CONSTRAINT `fk_parent_id`
    FOREIGN KEY (`parent` )
    REFERENCES `copss`.`countries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `copss`.`players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(255) NOT NULL ,
  `last_name` VARCHAR(255) NOT NULL ,
  `country` INT(10) UNSIGNED NOT NULL ,
  `state` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `vip` TINYINT(1) NULL DEFAULT '0' ,
  `creation_date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_country_id` (`country` ASC) ,
  INDEX `fk_state_id` (`state` ASC) ,
  CONSTRAINT `fk_country_id`
    FOREIGN KEY (`country` )
    REFERENCES `copss`.`countries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_state_id`
    FOREIGN KEY (`state` )
    REFERENCES `copss`.`states` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`game_systems`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`game_systems` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `copss`.`game_sizes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`game_sizes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT(10) UNSIGNED NOT NULL ,
  `size` INT(11) NOT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `copss`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`game_system_factions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`game_system_factions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT(10) UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `acronym` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_faction_parent_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `copss`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`games`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`games` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `creation_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `game_system` INT(10) UNSIGNED NOT NULL ,
  `scenario` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_game_system_id` (`game_system` ASC) ,
  CONSTRAINT `fk_parent_game_system_id`
    FOREIGN KEY (`game_system` )
    REFERENCES `copss`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`game_players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`game_players` (
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
    REFERENCES `copss`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `copss`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `copss`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_game_size_id`
    FOREIGN KEY (`game_size` )
    REFERENCES `copss`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `copss`.`achievements`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`achievements` (
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
    REFERENCES `copss`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `copss`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `copss`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_event_id`
    FOREIGN KEY (`event_id` )
    REFERENCES `copss`.`events` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`achievements_earned`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`achievements_earned` (
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
    REFERENCES `copss`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_achievement_id`
    FOREIGN KEY (`achievement_id` )
    REFERENCES `copss`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `copss`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`meta_achievement_criteria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`meta_achievement_criteria` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_achievement` INT(10) UNSIGNED NOT NULL ,
  `child_achievement` INT(10) UNSIGNED NOT NULL ,
  `count` INT(10) UNSIGNED NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_ach_id` (`parent_achievement` ASC) ,
  INDEX `fk_child_ach_id` (`child_achievement` ASC) ,
  CONSTRAINT `fk_parent_ach_id`
    FOREIGN KEY (`parent_achievement` )
    REFERENCES `copss`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_child_ach_id`
    FOREIGN KEY (`child_achievement` )
    REFERENCES `copss`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `copss`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`users` (
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
-- Table `copss`.`prize_redemptions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`prize_redemptions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `cost` INT NOT NULL ,
  `description` VARCHAR(45) NOT NULL ,
  `creation_time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_prize_redemptions_player_id` (`player_id` ASC) ,
  CONSTRAINT `fk_prize_redemptions_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `copss`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `copss`.`feedback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `copss`.`feedback` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(45) NOT NULL ,
  `comment` LONGTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `copss`.`earned`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `copss`.`earned` (`player_id` INT, `earned` INT);

-- -----------------------------------------------------
-- Placeholder table for view `copss`.`spent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `copss`.`spent` (`player_id` INT, `spent` INT);

-- -----------------------------------------------------
-- Placeholder table for view `copss`.`game_counter`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `copss`.`game_counter` (`player_id` INT, `game_count` INT);

-- -----------------------------------------------------
-- Placeholder table for view `copss`.`leaderboard`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `copss`.`leaderboard` (`player_id` INT, `last_name` INT, `first_name` INT, `game_count` INT, `earned` INT, `spent` INT, `points` INT);

-- -----------------------------------------------------
-- View `copss`.`earned`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `copss`.`earned`;
USE `copss`;
CREATE  OR REPLACE VIEW `copss`.`earned` AS 
SELECT ae.player_id as `player_id`, sum(a.points) as earned
FROM `copss`.`achievements_earned` ae, `copss`.`achievements` a
WHERE ae.achievement_id=a.id
GROUP BY ae.player_id;

-- -----------------------------------------------------
-- View `copss`.`spent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `copss`.`spent`;
USE `copss`;
CREATE  OR REPLACE VIEW `copss`.`spent` AS
SELECT pr.player_id as `player_id`, sum(pr.cost) as `spent`
FROM prize_redemptions pr
GROUP BY `player_id`
;

-- -----------------------------------------------------
-- View `copss`.`game_counter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `copss`.`game_counter`;
USE `copss`;
CREATE  OR REPLACE VIEW `copss`.`game_counter` AS
SELECT player_id, count(1) AS game_count
FROM game_players
GROUP BY player_id;

-- -----------------------------------------------------
-- View `copss`.`leaderboard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `copss`.`leaderboard`;
USE `copss`;
CREATE  OR REPLACE VIEW `copss`.`leaderboard` AS
SELECT players.id AS `player_id`,
players.last_name AS last_name,
players.first_name AS first_name,
game_counter.game_count AS game_count,
earned.earned AS earned,
spent.spent AS spent,
earned - spent as points
FROM `copss`.`players`
LEFT OUTER JOIN `copss`.`game_counter` 
        ON game_counter.player_id=players.id
LEFT OUTER JOIN `copss`.`earned` 
        ON earned.player_id=players.id
LEFT OUTER JOIN `copss`.`spent` 
        ON spent.player_id=players.id
ORDER BY points DESC, last_name ASC;
;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
