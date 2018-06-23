-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2018 a las 00:12:11
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `nombreCliente` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `importe` float DEFAULT NULL,
  `idMesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `nombreCliente`, `codigo`, `importe`, `idMesa`, `foto`) VALUES
(1, 'Luciana', 'hoh7h', 0, 'l2vqc', ''),
(2, 'Luciana', 'tghnp', NULL, 'q2mi6', ''),
(3, 'Familia Gonzales', '85w5f', NULL, 'o8ru0', ''),
(4, 'Despedida Sol', '85yqy', NULL, 'g8sve', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `sector` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `sueldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario`, `clave`, `sector`, `estado`, `sueldo`) VALUES
(1, 'luli', '123', 'cocina', 'activo', 1635),
(2, 'omar', '123', 'barra', 'ocupado', 2356),
(3, 'adri', '123', 'management', 'activo', 15348),
(4, 'moni', '123', 'candy', 'activo', 1500),
(5, 'agus', '123', 'mozo', 'activo', 8050);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `idComanda` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `puntosMozo` int(11) NOT NULL,
  `puntosMesa` int(11) NOT NULL,
  `puntosRestaurante` int(11) NOT NULL,
  `puntosCocinero` int(11) NOT NULL,
  `comentario` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `accion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado`) VALUES
(1, 'l2vqc', 'con cliente esperando pedido'),
(2, 'q2mi6', 'con cliente esperando pedido'),
(3, 'o8ru0', 'con cliente esperando pedido'),
(4, 'g8sve', 'con cliente esperando pedido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `idComanda` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `sector` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `descripcion` varchar(500) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `estimacion` int(11) DEFAULT NULL,
  `fechaIngresado` datetime DEFAULT NULL,
  `fechaEntregado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `idComanda`, `sector`, `idEmpleado`, `descripcion`, `estado`, `estimacion`, `fechaIngresado`, `fechaEntregado`) VALUES
(3, 'hoh7h', 'cerveza', NULL, '1 antares', 'pendiente', NULL, '2018-06-18 12:39:45', NULL),
(4, 'hoh7h', 'cocina', 1, '3 pizzas', 'entregado', NULL, '2018-06-18 12:39:45', NULL),
(5, 'tghnp', 'cerveza', 2, '1 cerveza', 'en preparación', NULL, '2018-06-18 12:39:45', NULL),
(6, 'tghnp', 'cocina', 1, '2 empanadas', 'entregado', NULL, '2018-06-18 12:39:45', NULL),
(9, '85yqy', 'barra', NULL, '4 daikiris', 'pendiente', NULL, '2018-06-23 23:45:31', NULL),
(10, '85yqy', 'cocina', NULL, 'picada para 4', 'pendiente', NULL, '2018-06-23 23:45:31', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
