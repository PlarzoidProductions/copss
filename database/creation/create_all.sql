SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ironarena` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `ironarena` ;

-- -----------------------------------------------------
-- Table `ironarena`.`countries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`countries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`states`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`states` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `country_id` TINYINT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_country_id` (`country_id` ASC) ,
  CONSTRAINT `fk_country_id`
    FOREIGN KEY (`country_id` )
    REFERENCES `ironarena`.`countries` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`players` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `first_name` VARCHAR(255) NULL ,
  `last_name` VARCHAR(255) NULL ,
  `country_id` INT UNSIGNED NOT NULL ,
  `state_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_state_id` (`state_id` ASC) ,
  INDEX `fk_country_id` (`country_id` ASC) ,
  CONSTRAINT `fk_state_id`
    FOREIGN KEY (`state_id` )
    REFERENCES `ironarena`.`states` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_country_id`
    FOREIGN KEY (`country_id` )
    REFERENCES `ironarena`.`countries` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`game_systems`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`game_systems` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `max_num_players` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`factions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`factions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `game_system_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `acronym` VARCHAR(3) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `ironarena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`game_sizes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`game_sizes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `size` INT NOT NULL ,
  `game_system_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `ironarena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`games`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `time` TIMESTAMP NOT NULL ,
  `game_system_id` INT UNSIGNED NOT NULL ,
  `game_size_id` INT UNSIGNED NOT NULL ,
  `scenario` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  INDEX `fk_game_size_id` (`game_size_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `ironarena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `ironarena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`game_players`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`game_players` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `game_id` INT UNSIGNED NOT NULL ,
  `faction_id` INT UNSIGNED NOT NULL ,
  `theme_force` TINYINT(1) NOT NULL DEFAULT false ,
  `fully_painted` TINYINT(1) NOT NULL DEFAULT false ,
  `winner` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_player_id` (`player_id` ASC) ,
  INDEX `fk_game_id` (`game_id` ASC) ,
  INDEX `fk_faction_id` (`faction_id` ASC) ,
  CONSTRAINT `fk_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `ironarena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `ironarena`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `ironarena`.`factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`tournament`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`tournament` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `game_system_id` INT UNSIGNED NOT NULL ,
  `game_size_id` INT UNSIGNED NOT NULL ,
  `max_num_players` INT NOT NULL DEFAULT 32 ,
  `num_lists` INT NOT NULL DEFAULT 2 ,
  `divide_and_conquer` INT NOT NULL DEFAULT false ,
  `scoring_mode` ENUM('baseline','assassin','control','destruction') NOT NULL DEFAULT 'baseline' ,
  `has_time_extensions` TINYINT(1) NOT NULL DEFAULT false ,
  `finals_tables` TINYINT(1) NOT NULL DEFAULT false ,
  `large_event` TINYINT(1) NOT NULL DEFAULT false ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  INDEX `fk_game_size_id` (`game_size_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `ironarena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `ironarena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`achievements`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`achievements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `points` TINYINT NOT NULL DEFAULT 0 ,
  `per_game` TINYINT(1) NOT NULL DEFAULT false ,
  `is_meta` TINYINT(1) NOT NULL DEFAULT false ,
  `game_count` INT NULL ,
  `game_system_id` INT UNSIGNED NULL ,
  `game_size_id` INT UNSIGNED NULL ,
  `tournament_id` INT UNSIGNED NULL ,
  `event_id` INT UNSIGNED NULL ,
  `unique_opponent` TINYINT(1) NULL ,
  `unique_opponent_location` TINYINT(1) NULL ,
  `played_theme_force` TINYINT(1) NULL ,
  `fully_painted` TINYINT(1) NULL ,
  `fully_painted_battle` TINYINT(1) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_game_system_id` (`game_system_id` ASC) ,
  INDEX `fk_game_size_id` (`game_size_id` ASC) ,
  INDEX `fk_tournament_id` (`tournament_id` ASC) ,
  INDEX `fk_event_id` (`event_id` ASC) ,
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`game_system_id` )
    REFERENCES `ironarena`.`game_systems` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_size_id`
    FOREIGN KEY (`game_size_id` )
    REFERENCES `ironarena`.`game_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tournament_id`
    FOREIGN KEY (`tournament_id` )
    REFERENCES `ironarena`.`tournament` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_id`
    FOREIGN KEY (`event_id` )
    REFERENCES `ironarena`.`events` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`achievements_earned`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`achievements_earned` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `game_id` INT UNSIGNED NULL ,
  `achievement_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_player_id` (`player_id` ASC) ,
  INDEX `fk_game_id` (`game_id` ASC) ,
  INDEX `fk_achievement_id` (`achievement_id` ASC) ,
  CONSTRAINT `fk_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `ironarena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_id`
    FOREIGN KEY (`game_id` )
    REFERENCES `ironarena`.`games` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_achievement_id`
    FOREIGN KEY (`achievement_id` )
    REFERENCES `ironarena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`meta_achievements`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`meta_achievements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `child_id` INT UNSIGNED NOT NULL ,
  `parent_id` INT UNSIGNED NOT NULL ,
  `count` INT UNSIGNED NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_child_id` (`child_id` ASC) ,
  INDEX `fk_parent_id` (`parent_id` ASC) ,
  CONSTRAINT `fk_child_id`
    FOREIGN KEY (`child_id` )
    REFERENCES `ironarena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_parent_id`
    FOREIGN KEY (`parent_id` )
    REFERENCES `ironarena`.`achievements` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`tournament_matches`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`tournament_matches` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `tournament_id` INT UNSIGNED NOT NULL ,
  `round` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tournament_id` (`tournament_id` ASC) ,
  CONSTRAINT `fk_tournament_id`
    FOREIGN KEY (`tournament_id` )
    REFERENCES `ironarena`.`tournament` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`registrants`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`registrants` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `player_id` INT UNSIGNED NOT NULL ,
  `tournament_id` INT UNSIGNED NOT NULL ,
  `wait_listed` TINYINT(1) NOT NULL DEFAULT false ,
  `dropped` TINYINT(1) NOT NULL DEFAULT false ,
  `faction` INT UNSIGNED NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_player_id` (`player_id` ASC) ,
  INDEX `fk_tournament_id` (`tournament_id` ASC) ,
  INDEX `fk_faction_id` (`faction` ASC) ,
  CONSTRAINT `fk_player_id`
    FOREIGN KEY (`player_id` )
    REFERENCES `ironarena`.`players` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tournament_id`
    FOREIGN KEY (`tournament_id` )
    REFERENCES `ironarena`.`tournament` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_faction_id`
    FOREIGN KEY (`faction` )
    REFERENCES `ironarena`.`factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ironarena`.`tournament_results`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ironarena`.`tournament_results` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `registrant` INT UNSIGNED NOT NULL ,
  `tournament_match_id` INT UNSIGNED NOT NULL ,
  `winner` TINYINT(1) NOT NULL DEFAULT false ,
  `control_points` TINYINT NOT NULL ,
  `army_points` TINYINT NOT NULL ,
  `models_destroyed` TINYINT NULL DEFAULT 0 ,
  `used_extension` TINYINT(1) NOT NULL DEFAULT false ,
  `caster_kill` TINYINT(1) NOT NULL DEFAULT false ,
  `scenario_victory` TINYINT(1) NOT NULL DEFAULT false ,
  `timed_out` TINYINT(1) NOT NULL DEFAULT false ,
  `list_used` INT NOT NULL DEFAULT 1 ,
  `faction_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tournament_match_id` (`tournament_match_id` ASC) ,
  INDEX `fk_registrant_id` (`registrant` ASC) ,
  INDEX `fk_faction_id` (`faction_id` ASC) ,
  CONSTRAINT `fk_tournament_match_id`
    FOREIGN KEY (`tournament_match_id` )
    REFERENCES `ironarena`.`tournament_matches` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_registrant_id`
    FOREIGN KEY (`registrant` )
    REFERENCES `ironarena`.`registrants` (`player_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_faction_id`
    FOREIGN KEY (`faction_id` )
    REFERENCES `ironarena`.`factions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
