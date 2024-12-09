CREATE TABLE IF NOT EXISTS `shop_notebook_brand` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  INDEX `idx_name` (`NAME`)
);

CREATE TABLE IF NOT EXISTS `shop_notebook_model` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(50) DEFAULT NULL,
  `BRAND_ID` int(18) NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `idx_brand` (`BRAND_ID`)
);

CREATE TABLE IF NOT EXISTS `shop_notebook_product` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(50) DEFAULT NULL,
  `YEAR` int(4) NOT NULL,
  `PRICE` DECIMAL(8,2) NOT NULL,
  `MODEL_ID` int(18) NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `idx_model` (`MODEL_ID`)
);

CREATE TABLE IF NOT EXISTS `shop_notebook_option` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE IF NOT EXISTS `shop_notebook_product_option` (
  `PRODUCT_ID` int(18) NOT NULL,
  `OPTION_ID` int(18) NOT NULL
);