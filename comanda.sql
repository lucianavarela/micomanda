-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2018 a las 19:00:49
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
(3, 'Familia Gonzales', '85w5f', NULL, 'o8ru0', ''),
(4, 'Despedida Sol', '85yqy', NULL, 'g8sve', ''),
(5, 'Familia Gentil', '9x7tx', NULL, 'dyao5', ''),
(6, 'Las Chichis', 'ow1ee', NULL, 'mch6i', '');

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
(2, 'omar', '123', 'barra', 'activo', 2356),
(3, 'adri', '123', 'management', 'activo', 15348),
(4, 'moni', '123', 'cerveza', 'activo', 1500),
(5, 'agus', '123', 'mozo', 'activo', 8050),
(7, 'cami', '123', 'candy', 'activo', 350);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `idComanda` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `puntosMozo` int(11) NOT NULL,
  `puntosMesa` int(11) NOT NULL,
  `puntosRestaurante` int(11) NOT NULL,
  `puntosCocinero` int(11) NOT NULL,
  `comentario` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
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
(3, 'o8ru0', 'con clientes comiendo'),
(4, 'g8sve', 'con clientes comiendo'),
(5, 'dyao5', 'con clientes comiendo'),
(6, 'lsjuh', 'cerrada'),
(7, 'mch6i', 'con cliente esperando pedido'),
(8, 'pybvs', 'cerrada');

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
  `estimacion` datetime DEFAULT NULL,
  `fechaIngresado` datetime DEFAULT NULL,
  `fechaEntregado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `idComanda`, `sector`, `idEmpleado`, `descripcion`, `estado`, `estimacion`, `fechaIngresado`, `fechaEntregado`) VALUES
(10, '85yqy', 'cocina', 1, 'picada para 4', 'entregado', '0000-00-00 00:00:00', '2018-06-23 23:45:31', '0000-00-00 00:00:00'),
(11, '9x7tx', 'barra', 2, '1 daikiri, 1 agua', 'entregado', '2018-06-25 17:20:02', '2018-06-24 16:00:35', '0000-00-00 00:00:00'),
(13, '9x7tx', 'cocina', 1, '5 rissottos al limon', 'entregado', '2018-06-25 19:08:57', '2018-06-24 16:00:35', '2018-06-25 18:09:16'),
(14, 'ow1ee', 'barra', 2, '3 caipis', 'listo para servir', '2018-06-25 18:46:27', '2018-06-25 18:05:06', '2018-06-25 18:15:13'),
(15, '85w5f', 'cerveza', 4, '2 rubias', 'entregado', '2018-06-25 18:35:57', '2018-06-25 18:05:06', '2018-06-25 18:16:04'),
(16, 'ow1ee', 'cocina', 1, 'mani y papitas', 'listo para servir', '2018-06-25 22:04:54', '2018-06-25 18:05:06', '2018-06-25 18:11:18');

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
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
