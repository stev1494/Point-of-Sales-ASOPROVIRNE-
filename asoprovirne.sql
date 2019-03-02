-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2018 a las 01:03:50
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asoprovirne`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE `articulo` (
  `idArt` int(10) NOT NULL,
  `nombreArticulo` varchar(30) NOT NULL,
  `precio` decimal(5,2) NOT NULL,
  `cantidad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `idCargo` int(10) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `sueldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `idComCab` int(10) NOT NULL,
  `idEmpleado` int(10) NOT NULL,
  `idProductor` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `idArt` int(10) NOT NULL,
  `precio` float NOT NULL,
  `cantidad` int(10) NOT NULL,
  `total` float NOT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `compra`
--
DELIMITER $$
CREATE TRIGGER `aumentar_cantidad_articulo` AFTER INSERT ON `compra` FOR EACH ROW BEGIN
	UPDATE articulo SET cantidad = cantidad + NEW.cantidad WHERE idArt = NEW.idArt;
END
$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER `disminuir_cantidad_articulo` AFTER UPDATE ON `compra` FOR EACH ROW BEGIN
	UPDATE articulo SET cantidad = cantidad - NEW.cantidad WHERE idArt = NEW.idArt;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destino_exporta`
--

CREATE TABLE `destino_exporta` (
  `idDestExp` int(10) NOT NULL,
  `destino` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `idEmpleado` int(10) NOT NULL,
  `nombre` char(30) NOT NULL,
  `apellido` char(30) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `estadoCivil` char(10) NOT NULL,
  `genero` char(10) NOT NULL,
  `idCargo` int(10) NOT NULL,
  `cedula` char(10) NOT NULL,
  `password` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exportador`
--

CREATE TABLE `exportador` (
  `idExportador` int(10) NOT NULL,
  `ruc` char(13) NOT NULL,
  `razonSocial` varchar(30) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_despacho_cabecera`
--

CREATE TABLE `orden_despacho_cabecera` (
  `idOrden` int(10) NOT NULL,
  `idEmpleado` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '2',
  `vendida` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_despacho_detalle`
--

CREATE TABLE `orden_despacho_detalle` (
  `idOrdenDet` int(10) NOT NULL,
  `idOrden` int(10) NOT NULL,
  `idArt` int(10) NOT NULL,
  `precio` float NOT NULL,
  `cantidad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productor`
--

CREATE TABLE `productor` (
  `idProductor` int(10) NOT NULL,
  `cedula` char(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono`
--

CREATE TABLE `telefono` (
  `idTelefono` int(10) NOT NULL,
  `idEmpleado` int(10) NOT NULL,
  `numeroTelefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idVenta` int(10) NOT NULL,
  `idOrden` int(10) NOT NULL,
  `idEmpleado` int(10) NOT NULL,
  `idExportador` int(10) NOT NULL,
  `idDestExp` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `total` float NOT NULL,
  `observacion` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `venta`
--
DELIMITER $$
CREATE TRIGGER `anular` AFTER UPDATE ON `venta` FOR EACH ROW BEGIN 

	DECLARE fin BOOL DEFAULT FALSE;
	DECLARE id_art INT(10) DEFAULT 0;
	DECLARE cant_art INT(10) DEFAULT 0;

	DECLARE articulos CURSOR FOR SELECT idArt, cantidad FROM orden_despacho_detalle WHERE idOrden = NEW.idOrden;
          
	DECLARE CONTINUE HANDLER 
	FOR SQLSTATE '02000'
	SET fin = TRUE;

	OPEN articulos;
		Recorre_Cursor: LOOP
		FETCH articulos INTO id_art, cant_art;

			IF fin THEN
				LEAVE Recorre_Cursor;
			END IF;

			UPDATE articulo SET cantidad = cantidad + cant_art WHERE idArt = id_art;

		END LOOP;
	CLOSE articulos;
    
	UPDATE orden_despacho_cabecera SET vendida = 0 WHERE idOrden = NEW.idOrden;
        
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `vender` AFTER INSERT ON `venta` FOR EACH ROW BEGIN 

	DECLARE fin BOOL DEFAULT FALSE;
	DECLARE id_art INT(10) DEFAULT 0;
	DECLARE cant_art INT(10) DEFAULT 0;

	DECLARE articulos CURSOR FOR SELECT idArt, cantidad FROM orden_despacho_detalle WHERE idOrden = NEW.idOrden;
          
	DECLARE CONTINUE HANDLER 
	FOR SQLSTATE '02000'
	SET fin = TRUE;

	OPEN articulos;
		Recorre_Cursor: LOOP
		FETCH articulos INTO id_art, cant_art;

			IF fin THEN
				LEAVE Recorre_Cursor;
			END IF;

			UPDATE articulo SET cantidad = cantidad - cant_art WHERE idArt = id_art;

		END LOOP;
	CLOSE articulos;
    
	UPDATE orden_despacho_cabecera SET vendida = 1 WHERE idOrden = NEW.idOrden;
        
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_compra`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_compra` (
`idComCab` int(10)
,`idEmpleado` int(10)
,`empleado` varchar(61)
,`idProductor` int(10)
,`productor` varchar(61)
,`fecha` date
,`hora` time
,`idArt` int(10)
,`precio` float
,`nombreArticulo` varchar(30)
,`cantidad` int(10)
,`total` float
,`observacion` varchar(100)
,`estado` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_empleado_cargo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_empleado_cargo` (
`idEmpleado` int(10)
,`nombre` char(30)
,`apellido` char(30)
,`fechaNacimiento` date
,`estadoCivil` char(10)
,`genero` char(10)
,`cedula` char(10)
,`idCargo` int(10)
,`nomCargo` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_orden`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_orden` (
`idOrden` int(10)
,`idEmpleado` int(10)
,`empleado` varchar(61)
,`fecha` date
,`hora` time
,`observacion` varchar(100)
,`estado` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_orden_detalle`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_orden_detalle` (
`idOrdenDet` int(10)
,`idOrden` int(10)
,`idArt` int(10)
,`nombreArticulo` varchar(30)
,`precio` float
,`cantidad` int(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_venta`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_venta` (
`idVenta` int(10)
,`idOrden` int(10)
,`idEmpleado` int(10)
,`empleado` varchar(61)
,`idExportador` int(10)
,`exportador` varchar(30)
,`idDestExp` int(10)
,`destino` varchar(30)
,`fecha` date
,`hora` time
,`total` float
,`observacion` varchar(100)
,`estado` tinyint(4)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_compra`
--
DROP TABLE IF EXISTS `vista_compra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_compra`  AS  select `com`.`idComCab` AS `idComCab`,`com`.`idEmpleado` AS `idEmpleado`,concat(`emp`.`nombre`,' ',`emp`.`apellido`) AS `empleado`,`com`.`idProductor` AS `idProductor`,concat(`pro`.`nombre`,' ',`pro`.`apellido`) AS `productor`,`com`.`fecha` AS `fecha`,`com`.`hora` AS `hora`,`com`.`idArt` AS `idArt`,`com`.`precio` AS `precio`,`art`.`nombreArticulo` AS `nombreArticulo`,`com`.`cantidad` AS `cantidad`,`com`.`total` AS `total`,`com`.`observacion` AS `observacion`,`com`.`estado` AS `estado` from (((`compra` `com` join `empleado` `emp` on((`emp`.`idEmpleado` = `com`.`idEmpleado`))) join `productor` `pro` on((`pro`.`idProductor` = `com`.`idProductor`))) join `articulo` `art` on((`art`.`idArt` = `com`.`idArt`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_empleado_cargo`
--
DROP TABLE IF EXISTS `vista_empleado_cargo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_empleado_cargo`  AS  select `e`.`idEmpleado` AS `idEmpleado`,`e`.`nombre` AS `nombre`,`e`.`apellido` AS `apellido`,`e`.`fechaNacimiento` AS `fechaNacimiento`,`e`.`estadoCivil` AS `estadoCivil`,`e`.`genero` AS `genero`,`e`.`cedula` AS `cedula`,`e`.`idCargo` AS `idCargo`,`c`.`nombre` AS `nomCargo` from (`empleado` `e` join `cargo` `c` on((`e`.`idCargo` = `c`.`idCargo`))) order by `e`.`idEmpleado` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_orden`
--
DROP TABLE IF EXISTS `vista_orden`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_orden`  AS  select `o`.`idOrden` AS `idOrden`,`o`.`idEmpleado` AS `idEmpleado`,concat(`e`.`nombre`,' ',`e`.`apellido`) AS `empleado`,`o`.`fecha` AS `fecha`,`o`.`hora` AS `hora`,`o`.`observacion` AS `observacion`,`o`.`estado` AS `estado` from (`orden_despacho_cabecera` `o` join `empleado` `e` on((`e`.`idEmpleado` = `o`.`idEmpleado`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_orden_detalle`
--
DROP TABLE IF EXISTS `vista_orden_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_orden_detalle`  AS  select `od`.`idOrdenDet` AS `idOrdenDet`,`od`.`idOrden` AS `idOrden`,`od`.`idArt` AS `idArt`,`a`.`nombreArticulo` AS `nombreArticulo`,`od`.`precio` AS `precio`,`od`.`cantidad` AS `cantidad` from (`orden_despacho_detalle` `od` join `articulo` `a` on((`od`.`idArt` = `a`.`idArt`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_venta`
--
DROP TABLE IF EXISTS `vista_venta`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_venta`  AS  select `v`.`idVenta` AS `idVenta`,`v`.`idOrden` AS `idOrden`,`v`.`idEmpleado` AS `idEmpleado`,concat(`e`.`nombre`,' ',`e`.`apellido`) AS `empleado`,`v`.`idExportador` AS `idExportador`,`ex`.`razonSocial` AS `exportador`,`v`.`idDestExp` AS `idDestExp`,`de`.`destino` AS `destino`,`v`.`fecha` AS `fecha`,`v`.`hora` AS `hora`,`v`.`total` AS `total`,`v`.`observacion` AS `observacion`,`v`.`estado` AS `estado` from (((`venta` `v` join `empleado` `e` on((`v`.`idEmpleado` = `e`.`idEmpleado`))) join `exportador` `ex` on((`v`.`idExportador` = `ex`.`idExportador`))) join `destino_exporta` `de` on((`v`.`idDestExp` = `de`.`idDestExp`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`idArt`),
  ADD UNIQUE KEY `nombreArticulo` (`nombreArticulo`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`idCargo`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`idComCab`),
  ADD KEY `fk_idEmpleado_4` (`idEmpleado`),
  ADD KEY `fk_idProductor` (`idProductor`),
  ADD KEY `fk_idArt_2` (`idArt`);

--
-- Indices de la tabla `destino_exporta`
--
ALTER TABLE `destino_exporta`
  ADD PRIMARY KEY (`idDestExp`),
  ADD UNIQUE KEY `destino` (`destino`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`idEmpleado`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_idCargo` (`idCargo`);

--
-- Indices de la tabla `exportador`
--
ALTER TABLE `exportador`
  ADD PRIMARY KEY (`idExportador`),
  ADD UNIQUE KEY `ruc` (`ruc`),
  ADD UNIQUE KEY `razonSocial` (`razonSocial`);

--
-- Indices de la tabla `orden_despacho_cabecera`
--
ALTER TABLE `orden_despacho_cabecera`
  ADD PRIMARY KEY (`idOrden`),
  ADD KEY `fk_idEmpleado_2` (`idEmpleado`);

--
-- Indices de la tabla `orden_despacho_detalle`
--
ALTER TABLE `orden_despacho_detalle`
  ADD PRIMARY KEY (`idOrdenDet`,`idOrden`),
  ADD KEY `fk_idOrden_1` (`idOrden`),
  ADD KEY `fk_idArt_1` (`idArt`);

--
-- Indices de la tabla `productor`
--
ALTER TABLE `productor`
  ADD PRIMARY KEY (`idProductor`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD PRIMARY KEY (`idTelefono`),
  ADD KEY `fk_idEmpleado_1` (`idEmpleado`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idVenta`),
  ADD KEY `fk_idOrden_2` (`idOrden`),
  ADD KEY `fk_idEmpleado_3` (`idEmpleado`),
  ADD KEY `fk_idExportador` (`idExportador`),
  ADD KEY `fk_idDestExp` (`idDestExp`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `idArt` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `idCargo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `idComCab` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `destino_exporta`
--
ALTER TABLE `destino_exporta`
  MODIFY `idDestExp` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `idEmpleado` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `exportador`
--
ALTER TABLE `exportador`
  MODIFY `idExportador` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `orden_despacho_cabecera`
--
ALTER TABLE `orden_despacho_cabecera`
  MODIFY `idOrden` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `orden_despacho_detalle`
--
ALTER TABLE `orden_despacho_detalle`
  MODIFY `idOrdenDet` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `productor`
--
ALTER TABLE `productor`
  MODIFY `idProductor` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `telefono`
--
ALTER TABLE `telefono`
  MODIFY `idTelefono` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `fk_idArt_2` FOREIGN KEY (`idArt`) REFERENCES `articulo` (`idArt`),
  ADD CONSTRAINT `fk_idEmpleado_4` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  ADD CONSTRAINT `fk_idProductor` FOREIGN KEY (`idProductor`) REFERENCES `productor` (`idProductor`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_idCargo` FOREIGN KEY (`idCargo`) REFERENCES `cargo` (`idCargo`);

--
-- Filtros para la tabla `orden_despacho_cabecera`
--
ALTER TABLE `orden_despacho_cabecera`
  ADD CONSTRAINT `fk_idEmpleado_2` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`);

--
-- Filtros para la tabla `orden_despacho_detalle`
--
ALTER TABLE `orden_despacho_detalle`
  ADD CONSTRAINT `fk_idArt_1` FOREIGN KEY (`idArt`) REFERENCES `articulo` (`idArt`),
  ADD CONSTRAINT `fk_idOrden_1` FOREIGN KEY (`idOrden`) REFERENCES `orden_despacho_cabecera` (`idOrden`);

--
-- Filtros para la tabla `telefono`
--
ALTER TABLE `telefono`
  ADD CONSTRAINT `fk_idEmpleado_1` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_idDestExp` FOREIGN KEY (`idDestExp`) REFERENCES `destino_exporta` (`idDestExp`),
  ADD CONSTRAINT `fk_idEmpleado_3` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`idEmpleado`),
  ADD CONSTRAINT `fk_idExportador` FOREIGN KEY (`idExportador`) REFERENCES `exportador` (`idExportador`),
  ADD CONSTRAINT `fk_idOrden_2` FOREIGN KEY (`idOrden`) REFERENCES `orden_despacho_cabecera` (`idOrden`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



show index FROM asoprovirne.venta;

use asoprovirne;
DELIMITER //

CREATE PROCEDURE insert_empleado(
	IN _apellido varchar(30),
       _cedula varchar(10),
       _estadocivil varchar(10),
       _fechaNacimiento date,
       _genero varchar (10),
       _nombre varchar (30)

) BEGIN
INSERT INTO users (apellido,cedula,estadocivil,fechaNacimiento,genero,nombre) 
VALUES (_apellido,_cedula,_estadocivil,_fechaNacimiento,_genero,_nombre);
END //
DELIMITER ;



DELIMITER //
create procedure get_all_art(



)begin
 
 select * from articulos;

end //
DELIMITER ;