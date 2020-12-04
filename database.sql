-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema symfony_master
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema symfony_master
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `symfony_master` DEFAULT CHARACTER SET utf8 ;
USE `symfony_master` ;

-- -----------------------------------------------------
-- Table `symfony_master`.`USERS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony_master`.`USERS` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` VARCHAR(255) NULL,
  `name` VARCHAR(255) NULL,
  `surname` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  `password` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony_master`.`TASK`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony_master`.`TASK` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `title` VARCHAR(255) NULL,
  `content` TEXT NULL,
  `priority` VARCHAR(255) NULL,
  `hours` INT NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_TASK_USERS_idx` (`user_id` ASC),
  CONSTRAINT `fk_TASK_USERS`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony_master`.`USERS` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
