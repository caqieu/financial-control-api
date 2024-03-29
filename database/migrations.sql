-- MySQL Script generated by MySQL Workbench
-- Tue Aug  2 01:04:53 2022
-- Model: Financial Control Entity Relation    Version: 1.0
-- MySQL Workbench Forward Engineering

-- -----------------------------------------------------
-- Schema financial
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `financial` DEFAULT CHARACTER SET utf8 ;
USE `financial` ;

-- -----------------------------------------------------
-- Table `financial`.`receitas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `financial`.`receitas` (
  `id` INT UNSIGNED NOT NULL,
  `descricao` VARCHAR(45) NOT NULL,
  `valor` DOUBLE NOT NULL,
  `data` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `financial`.`despesas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `financial`.`despesas` (
  `id` INT UNSIGNED NOT NULL,
  `descricao` VARCHAR(45) NOT NULL,
  `valor` FLOAT NOT NULL,
  `data` TIMESTAMP NOT NULL,
  `categoria` ENUM ('Alimentação', 'Saúde', 'Moradia', 'Transporte', 'Educação', 'Lazer', 'Imprevistos', 'Outras')
    PRIMARY KEY (`id`))
ENGINE = InnoDB;
