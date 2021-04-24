-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema gradely
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema gradely
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `gradely` DEFAULT CHARACTER SET utf8 ;
USE `gradely` ;

-- -----------------------------------------------------
-- Table `gradely`.`benutzer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`benutzer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `admin` BOOLEAN DEFAULT false,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`schule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`schule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `semester` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`benutzerschule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`benutzerschule` (
  `benutzerID` INT NOT NULL,
  `schuleID` INT NOT NULL,
  PRIMARY KEY (`benutzerID`, `schuleID`),
  INDEX `schule_idx` (`schuleID` ASC),
  CONSTRAINT `benutzer`
    FOREIGN KEY (`benutzerID`)
    REFERENCES `gradely`.`benutzer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `schule`
    FOREIGN KEY (`schuleID`)
    REFERENCES `gradely`.`schule` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`fach`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`fach` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `schuleID` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `schule_idx` (`schuleID` ASC),
  CONSTRAINT `schule-fach`
    FOREIGN KEY (`schuleID`)
    REFERENCES `gradely`.`schule` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`fach-semester`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`fachsemester` (
  `fachID` INT NOT NULL,
  `semester` INT NOT NULL,
  PRIMARY KEY (`fachID`, `semester`),
  CONSTRAINT `fach`
    FOREIGN KEY (`fachID`)
    REFERENCES `gradely`.`fach` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`typ`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`typ` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`gewichtung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`gewichtung` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `wert` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gradely`.`note`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gradely`.`note` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `wert` DOUBLE NOT NULL,
  `semester` INT NOT NULL,
  `bemerkung` VARCHAR(30),
  `fachID` INT NOT NULL,
  `gewichtungID` INT NOT NULL,
  `typID` INT NOT NULL,
  `benutzerID` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `typ_idx` (`typID` ASC),
  INDEX `gewichtung_idx` (`gewichtungID` ASC),
  INDEX `benutzer_idx` (`benutzerID` ASC),
  INDEX `fach_idx` (`fachID` ASC),
  CONSTRAINT `typ-note`
    FOREIGN KEY (`typID`)
    REFERENCES `gradely`.`typ` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gewichtung-note`
    FOREIGN KEY (`gewichtungID`)
    REFERENCES `gradely`.`gewichtung` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `benutzer-note`
    FOREIGN KEY (`benutzerID`)
    REFERENCES `gradely`.`benutzer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fach-note`
    FOREIGN KEY (`fachID`)
    REFERENCES `gradely`.`fach` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `gradely`.`typ`
-- -----------------------------------------------------
START TRANSACTION;
USE `gradely`;
INSERT INTO `gradely`.`typ` (`name`) VALUES
('Präsentation'),
('Grammatik'),
('Praktische Arbeit'),
('Theorie'),
('Projekt'),
('Vokabular'),
('Hörverstehen'),
('Leseverstehen');

COMMIT;


-- -----------------------------------------------------
-- Data for table `gradely`.`gewichtung`
-- -----------------------------------------------------
START TRANSACTION;
USE `gradely`;
INSERT INTO `gradely`.`gewichtung` (`wert`) VALUES
(0),
(0.2),
(0.25),
(0.33),
(0.4),
(0.5),
(0.6),
(0.66),
(0.75),
(0.8),
(1),
(2),
(3);

COMMIT;

-- -----------------------------------------------------
-- User for database `gradely`
-- -----------------------------------------------------
CREATE USER 'gradely'@'localhost' IDENTIFIED BY 'Absolute!Sicherheit4Gradely';
GRANT SELECT, INSERT, UPDATE, DELETE ON `gradely`.* TO 'gradely'@'localhost';