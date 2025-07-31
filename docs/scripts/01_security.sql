-- Active: 1753935408644@@127.0.0.1@3306@acosa
CREATE TABLE
    `usuario` (
        `usercod` bigint(10) NOT NULL AUTO_INCREMENT,
        `useremail` varchar(80) DEFAULT NULL,
        `username` varchar(80) DEFAULT NULL,
        `userpswd` varchar(128) DEFAULT NULL,
        `userfching` datetime DEFAULT NULL,
        `userpswdest` char(3) DEFAULT NULL,
        `userpswdexp` datetime DEFAULT NULL,
        `userest` char(3) DEFAULT NULL,
        `useractcod` varchar(128) DEFAULT NULL,
        `userpswdchg` varchar(128) DEFAULT NULL,
        `usertipo` char(3) DEFAULT NULL COMMENT 'Tipo de Usuario, Normal, Consultor o Cliente',
        PRIMARY KEY (`usercod`),
        UNIQUE KEY `useremail_UNIQUE` (`useremail`),
        KEY `usertipo` (
            `usertipo`,
            `useremail`,
            `usercod`,
            `userest`
        )
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;

CREATE TABLE 
    `roles` (
        `roleid` INT AUTO_INCREMENT PRIMARY KEY,
        `rolescod` VARCHAR(128) NOT NULL,
        `rolesdsc` VARCHAR(45) DEFAULT NULL,
        `rolesest` CHAR(3) DEFAULT NULL,
        UNIQUE KEY `uk_rolescod` (`rolescod`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE 
    `roles_usuarios` (
        `roleuserid` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `usercod` BIGINT(10) NOT NULL,
        `rolescod` VARCHAR(128) NOT NULL,
        `roleuserest` CHAR(3) DEFAULT NULL,
        `roleuserfch` DATETIME DEFAULT NULL,
        `roleuserexp` DATETIME DEFAULT NULL,
        KEY `idx_rolescod` (`rolescod`),
        KEY `idx_usercod` (`usercod`),
        CONSTRAINT `rol_usuario_key` FOREIGN KEY (`rolescod`) REFERENCES `roles` (`rolescod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `usuario_rol_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE 
    `funciones` (
        `fnid` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `fncod` VARCHAR(255) NOT NULL,
        `fndsc` VARCHAR(255) DEFAULT NULL,
        `fnest` CHAR(3) DEFAULT NULL,
        `fntyp` CHAR(3) DEFAULT NULL,
        UNIQUE KEY `uk_fncod` (`fncod`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE 
    `funciones_roles` (
        `fnrolid` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `rolescod` VARCHAR(128) NOT NULL,
        `fncod` VARCHAR(255) NOT NULL,
        `fnrolest` CHAR(3) DEFAULT NULL,
        `fnexp` DATETIME DEFAULT NULL,
        KEY `idx_rolescod` (`rolescod`),
        KEY `idx_fncod` (`fncod`),
        CONSTRAINT `funcion_rol_key` FOREIGN KEY (`rolescod`) REFERENCES `roles` (`rolescod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `rol_funcion_key` FOREIGN KEY (`fncod`) REFERENCES `funciones` (`fncod`) ON DELETE NO ACTION ON UPDATE NO ACTION
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
