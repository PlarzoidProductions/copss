SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `iron_arena` DEFAULT CHARACTER SET latin1 ;
USE `iron_arena` ;

-- -----------------------------------------------------
-- Table `iron_arena`.`game_systems`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_systems` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_sizes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_sizes` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_game_system` INT(10) UNSIGNED NOT NULL,
  `size` INT(11) NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_game_system_id` (`parent_game_system` ASC),
  CONSTRAINT `fk_game_system_id`
    FOREIGN KEY (`parent_game_system`)
    REFERENCES `iron_arena`.`game_systems` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_system_factions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_system_factions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_game_system` INT(10) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `acronym` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_game_system_id` (`parent_game_system` ASC),
  CONSTRAINT `fk_faction_parent_game_system_id`
    FOREIGN KEY (`parent_game_system`)
    REFERENCES `iron_arena`.`game_systems` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 21
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`events` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`achievements`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`achievements` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `points` INT(11) NOT NULL,
  `per_game` TINYINT(1) NOT NULL DEFAULT '0',
  `is_meta` TINYINT(1) NOT NULL DEFAULT '0',
  `game_count` INT(11) NULL DEFAULT NULL,
  `game_system_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `game_size_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `faction_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `unique_opponent` TINYINT(1) NULL DEFAULT NULL,
  `unique_opponent_locations` TINYINT(1) NULL DEFAULT NULL,
  `played_theme_force` TINYINT(1) NULL DEFAULT NULL,
  `fully_painted` TINYINT(1) NULL DEFAULT NULL,
  `fully_painted_battle` TINYINT(1) NULL DEFAULT NULL,
  `played_scenario` TINYINT(1) NULL DEFAULT NULL,
  `multiplayer` TINYINT(1) NULL DEFAULT NULL,
  `vs_vip` TINYINT(1) NULL DEFAULT NULL,
  `event_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ach_game_system_id` (`game_system_id` ASC),
  INDEX `fk_ach_game_size_id` (`game_size_id` ASC),
  INDEX `fk_ach_faction_id` (`faction_id` ASC),
  INDEX `fk_ach_event_id` (`event_id` ASC),
  CONSTRAINT `fk_ach_game_system_id`
    FOREIGN KEY (`game_system_id`)
    REFERENCES `iron_arena`.`game_systems` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_game_size_id`
    FOREIGN KEY (`game_size_id`)
    REFERENCES `iron_arena`.`game_sizes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_faction_id`
    FOREIGN KEY (`faction_id`)
    REFERENCES `iron_arena`.`game_system_factions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_event_id`
    FOREIGN KEY (`event_id`)
    REFERENCES `iron_arena`.`events` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`countries`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`countries` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 258
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`states`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`states` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `parent` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_parent_id` (`parent` ASC),
  CONSTRAINT `fk_parent_id`
    FOREIGN KEY (`parent`)
    REFERENCES `iron_arena`.`countries` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 79
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`players`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `country` INT(10) UNSIGNED NOT NULL,
  `state` INT(10) UNSIGNED NULL DEFAULT NULL,
  `vip` TINYINT(1) NULL DEFAULT '0',
  `creation_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_country_id` (`country` ASC),
  INDEX `fk_state_id` (`state` ASC),
  CONSTRAINT `fk_country_id`
    FOREIGN KEY (`country`)
    REFERENCES `iron_arena`.`countries` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_state_id`
    FOREIGN KEY (`state`)
    REFERENCES `iron_arena`.`states` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`games`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`games` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `creation_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `game_system` INT(10) UNSIGNED NOT NULL,
  `scenario` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_parent_game_system_id` (`game_system` ASC),
  CONSTRAINT `fk_parent_game_system_id`
    FOREIGN KEY (`game_system`)
    REFERENCES `iron_arena`.`game_systems` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`achievements_earned`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`achievements_earned` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `player_id` INT(10) UNSIGNED NOT NULL,
  `achievement_id` INT(10) UNSIGNED NOT NULL,
  `game_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_ach_earned_player_id` (`player_id` ASC),
  INDEX `fk_ach_earned_achievement_id` (`achievement_id` ASC),
  INDEX `fk_ach_earned_game_id` (`game_id` ASC),
  CONSTRAINT `fk_ach_earned_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `iron_arena`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_achievement_id`
    FOREIGN KEY (`achievement_id`)
    REFERENCES `iron_arena`.`achievements` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ach_earned_game_id`
    FOREIGN KEY (`game_id`)
    REFERENCES `iron_arena`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`feedback`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`feedback` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `comment` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`game_players`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_players` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id` INT(10) UNSIGNED NOT NULL,
  `player_id` INT(10) UNSIGNED NOT NULL,
  `faction_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `game_size` INT(10) UNSIGNED NULL DEFAULT NULL,
  `theme_force` TINYINT(1) NOT NULL DEFAULT '0',
  `fully_painted` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `fk_game_players_game_id` (`game_id` ASC),
  INDEX `fk_game_players_player_id` (`player_id` ASC),
  INDEX `fk_game_players_faction_id` (`faction_id` ASC),
  INDEX `fk_game_players_game_size_id` (`game_size` ASC),
  CONSTRAINT `fk_game_players_game_id`
    FOREIGN KEY (`game_id`)
    REFERENCES `iron_arena`.`games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `iron_arena`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_faction_id`
    FOREIGN KEY (`faction_id`)
    REFERENCES `iron_arena`.`game_system_factions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_players_game_size_id`
    FOREIGN KEY (`game_size`)
    REFERENCES `iron_arena`.`game_sizes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`meta_achievement_criteria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`meta_achievement_criteria` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_achievement` INT(10) UNSIGNED NOT NULL,
  `child_achievement` INT(10) UNSIGNED NOT NULL,
  `count` INT(10) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `fk_parent_ach_id` (`parent_achievement` ASC),
  INDEX `fk_child_ach_id` (`child_achievement` ASC),
  CONSTRAINT `fk_parent_ach_id`
    FOREIGN KEY (`parent_achievement`)
    REFERENCES `iron_arena`.`achievements` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_child_ach_id`
    FOREIGN KEY (`child_achievement`)
    REFERENCES `iron_arena`.`achievements` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`prize_redemptions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`prize_redemptions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `player_id` INT(10) UNSIGNED NOT NULL,
  `cost` INT(11) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  `creation_time` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_prize_redemptions_player_id` (`player_id` ASC),
  CONSTRAINT `fk_prize_redemptions_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `iron_arena`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `username` VARCHAR(20) NOT NULL,
  `password` CHAR(80) NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `admin` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `iron_arena`.`tournaments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`tournaments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `game_system_id` INT UNSIGNED NOT NULL,
  `max_num_players` INT UNSIGNED NOT NULL DEFAULT 32,
  `max_num_rounds` INT UNSIGNED NULL,
  `num_lists_required` INT UNSIGNED NOT NULL DEFAULT 1,
  `divide_and_conquer` INT UNSIGNED NOT NULL DEFAULT 0,
  `standings_type` VARCHAR(45) NOT NULL DEFAULT 'STANDARD',
  `final_tables` TINYINT NOT NULL DEFAULT 0,
  `large_event_scoring` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_tournament_game_system_id_idx` (`game_system_id` ASC),
  CONSTRAINT `fk_tournament_game_system_id`
    FOREIGN KEY (`game_system_id`)
    REFERENCES `iron_arena`.`game_systems` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`tournament_registrations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`tournament_registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `player_id` INT UNSIGNED NOT NULL,
  `tournament_id` INT UNSIGNED NOT NULL,
  `faction_id` INT UNSIGNED NOT NULL,
  `has_dropped` TINYINT NOT NULL DEFAULT 0,
  `had_buy` TINYINT NOT NULL DEFAULT 0,
  `num_lists` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_reg_player_id_idx` (`player_id` ASC),
  INDEX `fk_reg_tournament_id_idx` (`tournament_id` ASC),
  INDEX `fk_reg_faction_id_idx` (`faction_id` ASC),
  CONSTRAINT `fk_reg_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `iron_arena`.`players` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reg_tournament_id`
    FOREIGN KEY (`tournament_id`)
    REFERENCES `iron_arena`.`tournaments` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reg_faction_id`
    FOREIGN KEY (`faction_id`)
    REFERENCES `iron_arena`.`game_system_factions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`tournament_games`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`tournament_games` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tournament_id` INT UNSIGNED NOT NULL,
  `round` INT UNSIGNED NOT NULL DEFAULT 1,
  `winner_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_parent_tournament_id_idx` (`tournament_id` ASC),
  INDEX `fk_winner_id_idx` (`winner_id` ASC),
  CONSTRAINT `fk_parent_tournament_id`
    FOREIGN KEY (`tournament_id`)
    REFERENCES `iron_arena`.`tournaments` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_winner_id`
    FOREIGN KEY (`winner_id`)
    REFERENCES `iron_arena`.`tournament_registrations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `iron_arena`.`tournament_game_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`tournament_game_details` (
  `id` INT UNSIGNED NOT NULL,
  `game_id` INT UNSIGNED NOT NULL,
  `player_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `list_played` INT UNSIGNED NOT NULL DEFAULT 1,
  `control_points` INT UNSIGNED NOT NULL DEFAULT 0,
  `destruction_points` INT UNSIGNED NOT NULL DEFAULT 0,
  `assassination_efficiency` INT UNSIGNED NULL,
  `timed_out` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_parent_tourny_game_id_idx` (`game_id` ASC),
  INDEX `fk_tourny_game_player_id_idx` (`player_id` ASC),
  CONSTRAINT `fk_parent_tourny_game_id`
    FOREIGN KEY (`game_id`)
    REFERENCES `iron_arena`.`tournament_games` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tourny_game_player_id`
    FOREIGN KEY (`player_id`)
    REFERENCES `iron_arena`.`tournament_registrations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `iron_arena` ;

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`earned`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`earned` (`player_id` INT, `earned` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`game_counter`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`game_counter` (`player_id` INT, `game_count` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`leaderboard`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`leaderboard` (`player_id` INT, `last_name` INT, `first_name` INT, `game_count` INT, `earned` INT, `spent` INT, `points` INT);

-- -----------------------------------------------------
-- Placeholder table for view `iron_arena`.`spent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `iron_arena`.`spent` (`player_id` INT, `spent` INT);

-- -----------------------------------------------------
-- View `iron_arena`.`earned`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`earned`;
USE `iron_arena`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `iron_arena`.`earned` AS select `ae`.`player_id` AS `player_id`,sum(`a`.`points`) AS `earned` from (`iron_arena`.`achievements_earned` `ae` join `iron_arena`.`achievements` `a`) where (`ae`.`achievement_id` = `a`.`id`) group by `ae`.`player_id`;

-- -----------------------------------------------------
-- View `iron_arena`.`game_counter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`game_counter`;
USE `iron_arena`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `iron_arena`.`game_counter` AS select `iron_arena`.`game_players`.`player_id` AS `player_id`,count(1) AS `game_count` from `iron_arena`.`game_players` group by `iron_arena`.`game_players`.`player_id`;

-- -----------------------------------------------------
-- View `iron_arena`.`leaderboard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`leaderboard`;
USE `iron_arena`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `iron_arena`.`leaderboard` AS select `iron_arena`.`players`.`id` AS `player_id`,`iron_arena`.`players`.`last_name` AS `last_name`,`iron_arena`.`players`.`first_name` AS `first_name`,`game_counter`.`game_count` AS `game_count`,`earned`.`earned` AS `earned`,`spent`.`spent` AS `spent`,(`earned`.`earned` - `spent`.`spent`) AS `points` from (((`iron_arena`.`players` left join `iron_arena`.`game_counter` on((`game_counter`.`player_id` = `iron_arena`.`players`.`id`))) left join `iron_arena`.`earned` on((`earned`.`player_id` = `iron_arena`.`players`.`id`))) left join `iron_arena`.`spent` on((`spent`.`player_id` = `iron_arena`.`players`.`id`))) order by (`earned`.`earned` - `spent`.`spent`) desc,`iron_arena`.`players`.`last_name`;

-- -----------------------------------------------------
-- View `iron_arena`.`spent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `iron_arena`.`spent`;
USE `iron_arena`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `iron_arena`.`spent` AS select `pr`.`player_id` AS `player_id`,sum(`pr`.`cost`) AS `spent` from `iron_arena`.`prize_redemptions` `pr` group by `pr`.`player_id`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
