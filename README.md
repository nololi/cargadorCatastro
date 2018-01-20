# cargadorCatastro
Cargador de provincias y poblaciones en una base de datos mysql/mariadb desde los servicios web libres del catastro
Importante modificar el time limit de ejecución de los scripts, y habilitar la extensión  php_soap.dll en php.ini 

Las tablas poblaciones y provincias se relacionan por su código postal.
Ejemplo script bd en mariadb: 


CREATE TABLE `provincias` (
  `idprovincia` int(11) NOT NULL,
  `provincia` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cp` varchar(2) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

ALTER TABLE `provincias`
  ADD PRIMARY KEY (`idprovincia`),
  ADD UNIQUE KEY `provincia` (`provincia`),
  ADD UNIQUE KEY `codigopostal` (`cp`);


CREATE TABLE `poblaciones` (
  `idpoblacion` int(11) NOT NULL,
  `poblacion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `cp` varchar(2) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

ALTER TABLE `poblaciones`
  ADD PRIMARY KEY (`idpoblacion`),
  ADD KEY `cp` (`cp`);
    
  
  ALTER TABLE `poblaciones`
  ADD CONSTRAINT `poblaciones_ibfk_1` FOREIGN KEY (`cp`) REFERENCES `provincias` (`cp`);
