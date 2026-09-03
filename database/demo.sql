-- Base de demostración sin datos personales reales.
CREATE DATABASE IF NOT EXISTS `basededatosproyecto`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `basededatosproyecto`;

SET FOREIGN_KEY_CHECKS=0;
DROP VIEW IF EXISTS `vista_usuarios`;
DROP TABLE IF EXISTS `temporal`, `supervisores`, `registro_respaldo`, `registro`,
  `empleados`, `usuario`, `puestos`, `departamento`, `conceptos`, `rol`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `conceptos` (
  `concepto` varchar(50) NOT NULL,
  `tipoConcepto` varchar(50) NOT NULL,
  PRIMARY KEY (`concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `departamento` (
  `id_departamento` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `puestos` (
  `id_puestos` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_departamento` int NOT NULL,
  PRIMARY KEY (`id_puestos`),
  CONSTRAINT `puestos_departamento_fk` FOREIGN KEY (`id_departamento`)
    REFERENCES `departamento` (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rol` (
  `codigo_rol` int NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `usuario` (
  `id_usuario` varchar(50) NOT NULL,
  `DNI` int NOT NULL,
  `claveIngreso` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `codigo_rol` int NOT NULL,
  PRIMARY KEY (`id_usuario`),
  CONSTRAINT `usuario_rol_fk` FOREIGN KEY (`codigo_rol`) REFERENCES `rol` (`codigo_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `empleados` (
  `DNI` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `celular` varchar(50) NOT NULL DEFAULT '0',
  `mail` varchar(100) NOT NULL,
  `id_puestos` int NOT NULL,
  `salarioBruto` double NOT NULL DEFAULT 0,
  `id_usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`DNI`),
  CONSTRAINT `empleados_puesto_fk` FOREIGN KEY (`id_puestos`) REFERENCES `puestos` (`id_puestos`),
  CONSTRAINT `empleados_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `registro` (
  `id_registro` int NOT NULL AUTO_INCREMENT,
  `DNI` int NOT NULL,
  `periodo` varchar(7) NOT NULL,
  `salario` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_registro`),
  CONSTRAINT `registro_empleado_fk` FOREIGN KEY (`DNI`) REFERENCES `empleados` (`DNI`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `registro_respaldo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `DNI` int NOT NULL,
  `oldSueldo` decimal(10,2) NOT NULL,
  `newSueldo` decimal(10,2) NOT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `respaldo_empleado_fk` FOREIGN KEY (`DNI`) REFERENCES `empleados` (`DNI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `supervisores` (
  `id_supervisor` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `id_departamento` int NOT NULL,
  `id_usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_supervisor`),
  CONSTRAINT `supervisores_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `supervisores_departamento_fk` FOREIGN KEY (`id_departamento`)
    REFERENCES `departamento` (`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `temporal` (
  `id_temporal` int NOT NULL AUTO_INCREMENT,
  `concepto` varchar(50) NOT NULL,
  `DNI` int NOT NULL,
  `cantidadEnValor` double NOT NULL DEFAULT 0,
  `periodo` varchar(7) NOT NULL,
  PRIMARY KEY (`id_temporal`),
  CONSTRAINT `temporal_concepto_fk` FOREIGN KEY (`concepto`) REFERENCES `conceptos` (`concepto`),
  CONSTRAINT `temporal_empleado_fk` FOREIGN KEY (`DNI`) REFERENCES `empleados` (`DNI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `conceptos` VALUES
  ('C1', 'ausencia remunerada'), ('C2', 'ausencia no remunerada'),
  ('C3', 'horas extra feriado'), ('C4', 'horas extra'), ('C5', 'sin concepto');
INSERT INTO `departamento` VALUES
  (1, 'Atención al cliente'), (2, 'Depósito'), (3, 'Marketing'), (4, 'Contabilidad');
INSERT INTO `puestos` VALUES
  (100, 'Vendedor', 1), (200, 'Repositor', 2),
  (300, 'Community manager', 3), (400, 'Contador', 4);
INSERT INTO `rol` VALUES (0, 'supervisor'), (1, 'empleado');

-- Credenciales de demo: usuario demo / contraseña demo1234 / DNI 10000000
INSERT INTO `usuario` VALUES
  ('demo', 10000000, '6e9bece1914809fb8493146417e722f6', 'MD5', 0),
  ('empleado.demo', 20000000, '6e9bece1914809fb8493146417e722f6', 'MD5', 1);
INSERT INTO `empleados` VALUES
  (20000000, 'Ana', 'Ejemplo', '0000000000', 'ana@example.com', 100, 650000, 'empleado.demo');
INSERT INTO `supervisores` VALUES
  ('10000000', 'Alex', 'Demo', 4, 'demo');
INSERT INTO `registro` (`DNI`, `periodo`, `salario`) VALUES
  (20000000, '2026-01', 540000), (20000000, '2026-02', 552000);

CREATE VIEW `vista_usuarios` AS
SELECT u.id_usuario, u.DNI, u.claveIngreso, u.tipo, u.codigo_rol,
       COALESCE(e.nombre, s.nombre) AS nombre,
       COALESCE(e.apellido, s.apellido) AS apellido
FROM usuario u
LEFT JOIN empleados e ON u.id_usuario = e.id_usuario
LEFT JOIN supervisores s ON u.id_usuario = s.id_usuario;
