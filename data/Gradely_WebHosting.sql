-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bbuernsql1
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bbuernsql1
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bbuernsql1` DEFAULT CHARACTER SET utf8 ;
USE `bbuernsql1` ;

-- -----------------------------------------------------
-- Table `bbuernsql1`.`benutzer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`benutzer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `admin` BOOLEAN DEFAULT false,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`schule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`schule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `semester` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`benutzerschule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`benutzerschule` (
  `benutzerID` INT NOT NULL,
  `schuleID` INT NOT NULL,
  PRIMARY KEY (`benutzerID`, `schuleID`),
  INDEX `schule_idx` (`schuleID` ASC),
  CONSTRAINT `benutzer`
    FOREIGN KEY (`benutzerID`)
    REFERENCES `bbuernsql1`.`benutzer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `schule`
    FOREIGN KEY (`schuleID`)
    REFERENCES `bbuernsql1`.`schule` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`fach`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`fach` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `schuleID` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `schule_idx` (`schuleID` ASC),
  CONSTRAINT `schule-fach`
    FOREIGN KEY (`schuleID`)
    REFERENCES `bbuernsql1`.`schule` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`fach-semester`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`fachsemester` (
  `fachID` INT NOT NULL,
  `semester` INT NOT NULL,
  PRIMARY KEY (`fachID`, `semester`),
  CONSTRAINT `fach`
    FOREIGN KEY (`fachID`)
    REFERENCES `bbuernsql1`.`fach` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`typ`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`typ` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`gewichtung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`gewichtung` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `wert` DOUBLE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bbuernsql1`.`note`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bbuernsql1`.`note` (
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
    REFERENCES `bbuernsql1`.`typ` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `gewichtung-note`
    FOREIGN KEY (`gewichtungID`)
    REFERENCES `bbuernsql1`.`gewichtung` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `benutzer-note`
    FOREIGN KEY (`benutzerID`)
    REFERENCES `bbuernsql1`.`benutzer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fach-note`
    FOREIGN KEY (`fachID`)
    REFERENCES `bbuernsql1`.`fach` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `bbuernsql1`.`schule`
-- -----------------------------------------------------
START TRANSACTION;
USE `bbuernsql1`;
INSERT INTO `bbuernsql1`.`schule` (`name`, `semester`) VALUES
('Gibb IET', 8),
('Gibb BMS', 8),
('Gibb Grundbildung', 8);


COMMIT;


-- -----------------------------------------------------
-- Data for table `bbuernsql1`.`fach`
-- -----------------------------------------------------
START TRANSACTION;
USE `bbuernsql1`;
INSERT INTO `bbuernsql1`.`fach` (`name`, `schuleID`) VALUES
-- Gibb IET semester 1
('Modul 100', 1),
('Modul 117', 1),
('Modul 403', 1),
('Modul 431', 1),
-- Gibb IET semester 2
('Modul 104', 1),
('Modul 114', 1),
('Modul 123', 1),
('Modul 404', 1),
-- Gibb IET semester 3
('Modul 122', 1),
('Modul 126', 1),
('Modul 129', 1),
('Modul 133', 1),
('Modul 141', 1),
('Modul 226A', 1),
-- Gibb IET semester 4
('Modul 120', 1),
('Modul 143', 1),
('Modul 145', 1),
('Modul 151', 1),
('Modul 214', 1),
('Modul 226B', 1),
('Modul 239', 1),
('Modul 411', 1),
('Modul 437', 1),
-- Gibb IET semester 5
('Modul 146', 1),
('Modul 157', 1),
('Modul 213', 1),
('Modul 239', 1),
('Modul 306', 1),
('Modul 326', 1),
('Modul 426', 1),
-- Gibb IET semester 6
('Modul 121', 1),
('Modul 145', 1),
('Modul 152', 1),
('Modul 153', 1),
('Modul 156', 1),
('Modul 254', 1),
('Modul 300', 1),
-- Gibb IET semester 7
('Modul 150', 1),
('Modul 159', 1),
('Modul 182', 1),
('Modul 183', 1),
-- Gibb IET semester 8
('Modul 914', 1),
('Modul 932', 1),
-- Gibb BMS
('Englisch', 2),
('Französisch', 2),
('IDAF', 2),
('Mathematik', 2),
('Physik', 2),
('Chemie', 2),
('Deutsch', 2),
('Geschichte & Politik', 2),
('Wirtschaft & Recht', 2),
('Projektarbeit', 2),
-- Gibb ALG
('Englisch', 3),
('Gesellschaft', 3),
('Naturwissenschaft', 3),
('Sport', 3),
('Sprache & Kommunikation', 3),
('Wirtschaft & Recht', 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `bbuernsql1`.`fach-semester`
-- -----------------------------------------------------
START TRANSACTION;
USE `bbuernsql1`;
INSERT INTO `bbuernsql1`.`fachsemester` (`fachID`, `semester`) VALUES
-- Gibb semester 1
(1, 1),
(2, 1),
(3, 1),
(4, 1),
-- Gibb IET semester 2
(5, 2),
(6, 2),
(7, 2),
(8, 2),
-- Gibb IET semester 3
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
-- Gibb IET semester 4
(15, 4),
(16, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
-- Gibb IET semester 5
(24, 5),
(25, 5),
(26, 5),
(27, 5),
(28, 5),
(29, 5),
(30, 5),
-- Gibb IET semester 6
(31, 6),
(32, 6),
(33, 6),
(34, 6),
(35, 6),
(36, 6),
(37, 6),
-- Gibb IET semester 7
(38, 7),
(39, 7),
(40, 7),
(41, 7),
-- Gibb IET semester 8
(42, 8),
(43, 8),
-- Gibb ALG semester 1
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
-- Gibb ALG semester 2
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
-- Gibb ALG semester 3
(54, 3),
(55, 3),
(56, 3),
(57, 3),
(58, 3),
-- Gibb ALG semester 4
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
-- Gibb ALG semester 5
(54, 5),
(55, 5),
(56, 5),
(59, 5),
(58, 5),
-- Gibb ALG semester 6
(54, 6),
(55, 6),
(56, 6),
(59, 6),
(58, 6),
-- Gibb ALG semester 7
(54, 7),
(55, 7),
(56, 7),
(59, 7),
(58, 7),
-- Gibb ALG semester 8
(54, 8),
(55, 8),
(56, 8),
(59, 8),
(58, 8),
-- Gibb BMS semester 1
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
-- Gibb BMS semester 2
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
-- Gibb BMS semester 3
(50, 3),
(44, 3),
(45, 3),
(47, 3),
(49, 3),
-- Gibb BMS semester 4
(50, 4),
(44, 4),
(45, 4),
(47, 4),
(49, 4),
-- Gibb BMS semester 5
(50, 5),
(51, 5),
(46, 5),
(47, 5),
(52, 5),
-- Gibb BMS semester 6
(50, 6),
(51, 6),
(46, 6),
(47, 6),
(52, 6),
-- Gibb BMS semester 7
(50, 7),
(53, 7),
(47, 7),
(48, 7),
(52, 7),
-- Gibb BMS semester 8
(50, 8),
(53, 8),
(47, 8),
(48, 8),
(52, 8);


COMMIT;


-- -----------------------------------------------------
-- Data for table `bbuernsql1`.`typ`
-- -----------------------------------------------------
START TRANSACTION;
USE `bbuernsql1`;
INSERT INTO `bbuernsql1`.`typ` (`name`) VALUES
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
-- Data for table `bbuernsql1`.`gewichtung`
-- -----------------------------------------------------
START TRANSACTION;
USE `bbuernsql1`;
INSERT INTO `bbuernsql1`.`gewichtung` (`wert`) VALUES
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

