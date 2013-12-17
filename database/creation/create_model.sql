SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

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
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(255) NOT NULL ,
  `last_name` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NULL ,
  `country` INT UNSIGNED NOT NULL ,
  `state` INT UNSIGNED NULL ,
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
ENGINE = InnoDB;


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
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT UNSIGNED NOT NULL ,
  `size` INT NOT NULL ,
  `name` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_system_factions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_system_factions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_game_system` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `acronym` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`parent_game_system` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`parent_game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`games`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `time` TIMESTAMP NOT NULL ,
  `game_system` INT UNSIGNED NOT NULL ,
  `scenario` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`game_players` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `game_id` INT UNSIGNED NOT NULL ,
  `player_id` INT UNSIGNED NOT NULL ,
  `faction_id` INT UNSIGNED NULL ,
  `game_size` INT UNSIGNED NULL ,
  `theme_force` TINYINT(1) NOT NULL DEFAULT false ,
  `fully_painted` TINYINT(1) NOT NULL DEFAULT false ,
  `winner` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_id` (`game_id` ASC) ,
  INDEX `fk_player_id` (`player_id` ASC) ,
  INDEX `fk_faction_id` (`faction_id` ASC) ,
  INDEX `fk_game_size_id` (`game_size` ASC) ,
  CONSTRAINT `fk_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `iron_arena`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `iron_arena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `iron_arena`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_size_id`
    FOREIGN KEY (`game_size` )
    REFERENCES `iron_arena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


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
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `points` INT NOT NULL ,
  `per_game` TINYINT(1) NOT NULL DEFAULT false ,
  `is_meta` TINYINT(1) NOT NULL DEFAULT false ,
  `game_count` INT NULL DEFAULT NULL ,
  `game_system_id` INT UNSIGNED NULL DEFAULT NULL ,
  `game_size_id` INT UNSIGNED NULL DEFAULT NULL ,
  `faction_id` INT UNSIGNED NULL DEFAULT NULL ,
  `unique_opponent` TINYINT(1) NULL DEFAULT NULL ,
  `unique_opponent_locations` TINYINT(1) NULL DEFAULT NULL ,
  `played_theme_force` TINYINT(1) NULL DEFAULT NULL ,
  `fully_painted` TINYINT(1) NULL DEFAULT NULL ,
  `fully_painted_battle` TINYINT(1) NULL DEFAULT NULL ,
  `event_id` INT UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  INDEX `fk_game_size_id` (`game_size_id` ASC) ,
  INDEX `fk_faction_id` (`faction_id` ASC) ,
  INDEX `fk_event_id` (`event_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `iron_arena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `iron_arena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `iron_arena`.`game_system_factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_id`
    FOREIGN KEY (`event_id` )
    REFERENCES `iron_arena`.`events` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`achievements_earned`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`achievements_earned` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `achievement_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_player_id` (`player_id` ASC) ,
  INDEX `fk_achievement_id` (`achievement_id` ASC) ,
  CONSTRAINT `fk_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `iron_arena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_achievement_id`
    FOREIGN KEY (`achievement_id` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`meta_achievement_criteria`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`meta_achievement_criteria` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `parent_achievement` INT UNSIGNED NOT NULL ,
  `child_achievement` INT UNSIGNED NOT NULL ,
  `count` INT UNSIGNED NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_parent_id` (`parent_achievement` ASC) ,
  INDEX `fk_child_id` (`child_achievement` ASC) ,
  CONSTRAINT `fk_parent_id`
    FOREIGN KEY (`parent_achievement` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_child_id`
    FOREIGN KEY (`child_achievement` )
    REFERENCES `iron_arena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(20) NOT NULL ,
  `password` CHAR(80) NOT NULL ,
  `creation_date` DATETIME NOT NULL ,
  `last_login` TIMESTAMP NULL ,
  `admin` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`shifts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`shifts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `start` DATETIME NOT NULL ,
  `stop` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`user_shifts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `iron_arena`.`user_shifts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `shift_id` INT UNSIGNED NOT NULL ,
  `checked_in` TINYINT(1) NOT NULL DEFAULT 0 ,
  `completed` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_user_shifts_user_id` (`user_id` ASC) ,
  INDEX `fk_user_shifts_shift_id` (`shift_id` ASC) ,
  CONSTRAINT `fk_user_shifts_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `iron_arena`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_shifts_shift_id`
    FOREIGN KEY (`shift_id` )
    REFERENCES `iron_arena`.`shifts` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
