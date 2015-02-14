-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-09-2013 a las 19:35:41
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pollcrear`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `batalla`
--

CREATE TABLE IF NOT EXISTS `batalla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `ronda` varchar(20) NOT NULL,
  `grupo` varchar(8) NOT NULL,
  `idtorneo` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `numerovotos` int(11) NOT NULL,
  `ganador` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

CREATE TABLE IF NOT EXISTS `calendario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accion` varchar(5) NOT NULL,
  `fecha` datetime NOT NULL,
  `hecho` int(11) NOT NULL,
  `targetstring` varchar(20) NOT NULL,
  `targetdate` date NOT NULL,
  `targetint` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `idtorneo` int(11) NOT NULL,
  `numerogrupos` int(11) NOT NULL,
  `tipo` varchar(5) NOT NULL,
  `segundo` int(11) NOT NULL,
  `primclas` int(11) NOT NULL,
  `primproxronda` varchar(20) NOT NULL,
  `segclas` int(11) NOT NULL,
  `segproxronda` varchar(20) NOT NULL,
  `sorteo` int(11) NOT NULL,
  `limitevotos` int(11) NOT NULL,
  `extra` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadistica`
--

CREATE TABLE IF NOT EXISTS `estadistica` (
  `idpersonaje` int(11) NOT NULL,
  `idbatalla` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `votos` int(11) NOT NULL,
  PRIMARY KEY (`idpersonaje`,`idbatalla`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` int(11) NOT NULL,
  `idtorneo` int(11) NOT NULL,
  `fechainicio` datetime NOT NULL,
  `fechatermino` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ip`
--

CREATE TABLE IF NOT EXISTS `ip` (
  `fecha` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `codepass` varchar(32) NOT NULL,
  `forumcode` varchar(32) NOT NULL,
  `user` int(11) NOT NULL,
  `idevento` int(11) NOT NULL,
  `tiempo` int(11) NOT NULL,
  `usada` int(11) NOT NULL,
  `uniquecode` varchar(40) NOT NULL,
  `mastercode` varchar(32) NOT NULL,
  `masterip` varchar(32) NOT NULL,
  `info` varchar(80) NOT NULL,
  PRIMARY KEY (`uniquecode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `iduser` int(11) NOT NULL,
  `accion` varchar(5) NOT NULL,
  `fecha` datetime NOT NULL,
  `estado` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `accioncompleta` varchar(40) NOT NULL,
  PRIMARY KEY (`iduser`,`accion`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dependencia` int(11) NOT NULL,
  `titulo` varchar(20) NOT NULL,
  `tituloingles` varchar(20) NOT NULL,
  `url` varchar(50) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion`
--

CREATE TABLE IF NOT EXISTS `participacion` (
  `idpersonaje` int(11) NOT NULL,
  `idbatalla` int(11) NOT NULL,
  PRIMARY KEY (`idpersonaje`,`idbatalla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pelea`
--

CREATE TABLE IF NOT EXISTS `pelea` (
  `idpersonaje` int(11) NOT NULL,
  `idbatalla` int(11) NOT NULL,
  `votos` int(11) NOT NULL,
  PRIMARY KEY (`idpersonaje`,`idbatalla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaje`
--

CREATE TABLE IF NOT EXISTS `personaje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `serie` varchar(80) NOT NULL,
  `imagen` varchar(40) NOT NULL,
  `idserie` int(11) NOT NULL,
  `nparticipaciones` int(11) NOT NULL,
  `mejorpos` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personajepar`
--

CREATE TABLE IF NOT EXISTS `personajepar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `serie` varchar(80) NOT NULL,
  `idpersonaje` int(11) NOT NULL,
  `idserie` int(11) NOT NULL,
  `imagenpeq` varchar(40) NOT NULL,
  `imagen` varchar(40) NOT NULL,
  `idtorneo` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `grupo` varchar(8) NOT NULL,
  `ronda` varchar(20) NOT NULL,
  `seiyuu` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serie`
--

CREATE TABLE IF NOT EXISTS `serie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `imagen` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneo`
--

CREATE TABLE IF NOT EXISTS `torneo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ano` int(11) NOT NULL,
  `version` varchar(8) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `activo` int(11) NOT NULL,
  `estado` int(11) NOT NULL,
  `duracionbatalla` int(11) NOT NULL,
  `extraconteo` int(11) NOT NULL,
  `nominaciones` int(11) NOT NULL,
  `intervalo` int(11) NOT NULL,
  `horainicio` varchar(6) NOT NULL,
  `duracionlive` int(11) NOT NULL,
  `maxmiembrosgraf` int(11) NOT NULL,
  `opcionpartida` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` int(11) NOT NULL,
  `poder` int(11) NOT NULL,
  `facecode` varchar(50) NOT NULL,
  `facecodeex` varchar(50) NOT NULL,
  `twittercode` varchar(50) NOT NULL,
  `twittercodeex` varchar(50) NOT NULL,
  `extracode` varchar(50) NOT NULL,
  `extracodeex` varchar(50) NOT NULL,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voto`
--

CREATE TABLE IF NOT EXISTS `voto` (
  `fecha` datetime NOT NULL,
  `idbatalla` int(11) NOT NULL,
  `idpersonaje` int(11) NOT NULL,
  `uniquecode` varchar(30) NOT NULL,
  `codepass` varchar(30) NOT NULL,
  PRIMARY KEY (`fecha`,`idbatalla`,`idpersonaje`,`uniquecode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votouser`
--

CREATE TABLE IF NOT EXISTS `votouser` (
  `iduser` int(11) NOT NULL,
  `idbatalla` int(11) NOT NULL,
  `idpersonaje` int(11) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`iduser`,`idbatalla`,`idpersonaje`,`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
