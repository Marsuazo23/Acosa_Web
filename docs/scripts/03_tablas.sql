-- Active: 1753393130641@@127.0.0.1@3306@acosa

--Tabla de Productos
  CREATE TABLE `products` (
    `productId` INT(11) NOT NULL AUTO_INCREMENT,
    `productName` VARCHAR(255) NOT NULL,
    `productDescription` TEXT NOT NULL,
    `productPrice` DECIMAL(10,2) NOT NULL,
    `productImgUrl` VARCHAR(255) NOT NULL,
    `productStatus` CHAR(3) NOT NULL,
    `categoryId` INT(11) NOT NULL,
    `productStock` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`productId`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


--Tabla de Categorias
  CREATE TABLE `categories` (
    `categoryId` int(11) NOT NULL AUTO_INCREMENT,
    `categoryName` varchar(100) NOT NULL,
    PRIMARY KEY (`categoryId`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--Tabla de Ventas
  CREATE TABLE `sales` (
    `saleId` INT(11) NOT NULL AUTO_INCREMENT,
    `productId` INT(11) NOT NULL,
    `discountPercent` DECIMAL(5,2) NOT NULL COMMENT 'Porcentaje de descuento (ej: 20.00 para 20%)',
    `saleStart` DATETIME NOT NULL,
    `saleEnd` DATETIME NOT NULL,
    PRIMARY KEY (`saleId`),
    KEY `fk_sales_products_idx` (`productId`),
    CONSTRAINT `fk_sales_products` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


