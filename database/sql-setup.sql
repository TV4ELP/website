CREATE DATABASE `website` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;

CREATE TABLE `website`.`paths` (
  `requestPath` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `serverPath` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`requestPath`),
  UNIQUE KEY `pathName_UNIQUE` (`requestPath`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


INSERT INTO `website`.`paths` (`requestPath`, `serverPath`) VALUES ('/', '/sites/home');

CREATE TABLE `website`.`template_variables` (
  `var` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `replacement` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `localFile` int NOT NULL,
  PRIMARY KEY (`var`),
  UNIQUE KEY `var_UNIQUE` (`var`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `website`.`template_variables` (`var`, `replacement`, `localFile`) VALUES ('default_header', '/blocks/header.html', '1');
INSERT INTO `website`.`template_variables` (`var`, `replacement`, `localFile`) VALUES ('main-menu', '/blocks/main-menu.html', '1');
INSERT INTO `website`.`template_variables` (`var`, `replacement`, `localFile`) VALUES ('default-css', '/style.css', '1');
