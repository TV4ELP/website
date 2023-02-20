CREATE DATABASE `website` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE `website`.`paths` (
  `requestPath` VARCHAR(256) NOT NULL,
  `serverPath` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`requestPath`),
  UNIQUE INDEX `pathName_UNIQUE` (`requestPath` ASC) VISIBLE)
ENGINE = InnoDB;


INSERT INTO `website`.`paths` (`requestPath`, `serverPath`) VALUES ('/', '/sites/home');