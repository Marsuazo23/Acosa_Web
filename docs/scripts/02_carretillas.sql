-- Active: 1753393130641@@127.0.0.1@3306@acosa

--Tabla de Carretilla - Usuario logueado
CREATE TABLE
    `carretilla` (
        `usercod` BIGINT(10) NOT NULL,
        `productId` int(11) NOT NULL,
        `crrctd` INT(5) NOT NULL,
        `crrprc` DECIMAL(12, 2) NOT NULL,
        `crrfching` DATETIME NOT NULL,
        PRIMARY KEY (`usercod`, `productId`),
        INDEX `productId_idx` (`productId` ASC),
        CONSTRAINT `carretilla_user_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `carretilla_prd_key` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );


--Tabla de Carretilla - Usuario Anónimo  
CREATE TABLE
    `carretillaanon` (
        `anoncod` varchar(128) NOT NULL,
        `productId` int(11) NOT NULL,
        `crrctd` int(5) NOT NULL,
        `crrprc` decimal(12, 2) NOT NULL,
        `crrfching` datetime NOT NULL,
        PRIMARY KEY (`anoncod`, `productId`),
        KEY `productId_idx` (`productId`)
        CONSTRAINT `carretillaanon_prd_key` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );


--Tabla de Transacciones
CREATE TABLE `paypal_transactions` (
    `id_transaction` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(50) NOT NULL,           
    `capture_id` VARCHAR(50) DEFAULT NULL,     
    `status` VARCHAR(20) NOT NULL,             
    `amount` DECIMAL(12,2) NOT NULL,           
    `currency` VARCHAR(10) NOT NULL,           
    `paypal_fee` DECIMAL(12,2) DEFAULT NULL,  
    `net_amount` DECIMAL(12,2) DEFAULT NULL,  
    `payer_email` VARCHAR(100) NOT NULL,       
    `payer_name` VARCHAR(100) DEFAULT NULL,    
    `payer_id` VARCHAR(50) DEFAULT NULL,       
    `payer_country` VARCHAR(5) DEFAULT NULL,   
    `shipping_address` TEXT DEFAULT NULL,      
    `usercod` BIGINT(10) DEFAULT NULL,         
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

--Tabla de Ordenes
CREATE TABLE `orders` (
    `orderId` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `usercod` BIGINT(10) NOT NULL,
    `transaction_id` BIGINT,
    `order_status` VARCHAR(50) NOT NULL DEFAULT 'Pendiente', -- Estado del pago (Pagado, Pendiente)
    `shipping_status` VARCHAR(50) NOT NULL DEFAULT 'En preparación', -- Estado del envío (En camino, En tienda, etc.)
    `order_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`),
    FOREIGN KEY (`transaction_id`) REFERENCES `paypal_transactions` (`id_transaction`)
);

--Tabla de Detalles de Ordenes
CREATE TABLE `order_items` (
    `orderItemId` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `orderId` BIGINT NOT NULL,
    `productId` INT(11) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`) ON DELETE CASCADE,
    FOREIGN KEY (`productId`) REFERENCES `products` (`productId`)
);


    