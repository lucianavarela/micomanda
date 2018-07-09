-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2018 a las 20:47:17
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
(3, 'Familia Gonzales', '85w5f', 1000, 'o8ru0', ''),
(4, 'Despedida Sol', '85yqy', 5260, 'g8sve', '85yqy.png'),
(5, 'Familia Gentil', '9x7tx', 60, 'lsjuh', ''),
(6, 'Las Chichis', 'ow1ee', 5200, 'g8sve', 'ow1ee.jpg'),
(7, 'Varelas', 'mvugy', 250, 'lsjuh', ''),
(8, 'Super salida', 'f416v', 9000, 'bd7d6', ''),
(9, 'La Zulema', 'oa137', 6285, 'pybvs', ''),
(10, 'Sara reunion', '74iey', 5165, 'g8sve', ''),
(11, 'Los Sanchez', '1z714', NULL, 'mch6i', '');

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

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `idComanda`, `puntosMozo`, `puntosMesa`, `puntosRestaurante`, `puntosCocinero`, `comentario`) VALUES
(3, '85w5f', 7, 9, 6, 6, 'Todo genial!'),
(4, '85yqy', 1, 4, 2, 8, 'El mozo medio mala onda, pero todo riquisimo'),
(5, 'ow1ee', 3, 2, 2, 5, 'Todo super sucio!');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `accion` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `idEmpleado`, `fecha`, `accion`) VALUES
(1, 1, '2018-06-26 18:59:50', 'Ver pedidos'),
(2, 1, '2018-06-26 19:07:28', 'Ver comandas'),
(3, 1, '2018-06-26 19:07:33', 'Ver empleados'),
(4, 1, '2018-06-26 19:07:36', 'Ver encuestas'),
(5, 3, '2018-06-26 19:07:42', 'Ver pedidos'),
(6, 3, '2018-06-26 19:07:47', 'Ver mesas'),
(7, 5, '2018-07-05 03:32:59', 'Cargar comanda'),
(8, 1, '2018-07-05 03:40:51', 'Entregar pedido listo para servir'),
(9, 1, '2018-07-05 03:46:11', 'Tomar un pedido'),
(10, 2, '2018-07-05 03:47:52', 'Tomar un pedido'),
(11, 7, '2018-07-09 14:54:44', 'Tomar un pedido'),
(12, 7, '2018-07-09 14:57:41', 'Tomar un pedido'),
(13, 7, '2018-07-09 15:01:25', 'Tomar un pedido'),
(14, 7, '2018-07-09 15:02:06', 'Tomar un pedido'),
(15, 7, '2018-07-09 15:05:29', 'Tomar un pedido'),
(16, 7, '2018-07-09 15:06:22', 'Entregar pedido listo para servir'),
(17, 5, '2018-07-09 15:24:08', 'Cargar comanda'),
(18, 5, '2018-07-09 15:24:51', 'Entregar pedido a cliente'),
(19, 5, '2018-07-09 15:24:53', 'Entregar pedido a cliente'),
(20, 5, '2018-07-09 15:24:56', 'Entregar pedido a cliente'),
(21, 5, '2018-07-09 15:25:08', 'Cobrar comanda'),
(22, 5, '2018-07-09 15:25:13', 'Cobrar comanda'),
(23, 5, '2018-07-09 15:25:28', 'Cobrar comanda'),
(24, 5, '2018-07-09 15:25:32', 'Cobrar comanda'),
(25, 3, '2018-07-09 15:25:46', 'Cerrar mesa'),
(26, 3, '2018-07-09 15:25:48', 'Cerrar mesa'),
(27, 3, '2018-07-09 15:25:50', 'Cerrar mesa'),
(28, 5, '2018-07-09 15:34:12', 'Cargar comanda'),
(29, 5, '2018-07-09 15:51:15', 'Entregar pedido a cliente'),
(30, 1, '2018-07-09 15:51:26', 'Entregar pedido listo para servir'),
(31, 1, '2018-07-09 15:52:18', 'Tomar un pedido'),
(32, 1, '2018-07-09 15:52:43', 'Entregar pedido listo para servir'),
(33, 2, '2018-07-09 15:52:53', 'Entregar pedido listo para servir'),
(34, 2, '2018-07-09 15:52:57', 'Tomar un pedido'),
(35, 2, '2018-07-09 15:53:04', 'Entregar pedido listo para servir'),
(36, 2, '2018-07-09 15:53:07', 'Tomar un pedido'),
(37, 2, '2018-07-09 15:53:10', 'Entregar pedido listo para servir'),
(38, 2, '2018-07-09 15:53:12', 'Tomar un pedido'),
(39, 2, '2018-07-09 15:53:14', 'Entregar pedido listo para servir'),
(40, 4, '2018-07-09 15:53:21', 'Tomar un pedido'),
(41, 4, '2018-07-09 15:53:23', 'Entregar pedido listo para servir'),
(42, 4, '2018-07-09 15:53:26', 'Tomar un pedido'),
(43, 4, '2018-07-09 15:53:27', 'Entregar pedido listo para servir'),
(44, 4, '2018-07-09 15:53:30', 'Tomar un pedido'),
(45, 4, '2018-07-09 15:53:31', 'Entregar pedido listo para servir'),
(46, 7, '2018-07-09 15:53:46', 'Tomar un pedido'),
(47, 7, '2018-07-09 15:53:48', 'Entregar pedido listo para servir'),
(48, 5, '2018-07-09 15:53:56', 'Entregar pedido a cliente'),
(49, 5, '2018-07-09 15:53:57', 'Entregar pedido a cliente'),
(50, 5, '2018-07-09 15:53:58', 'Entregar pedido a cliente'),
(51, 5, '2018-07-09 15:53:58', 'Entregar pedido a cliente'),
(52, 5, '2018-07-09 15:53:59', 'Entregar pedido a cliente'),
(53, 5, '2018-07-09 15:54:00', 'Entregar pedido a cliente'),
(54, 5, '2018-07-09 15:54:00', 'Entregar pedido a cliente'),
(55, 5, '2018-07-09 15:54:01', 'Entregar pedido a cliente'),
(56, 5, '2018-07-09 15:54:02', 'Entregar pedido a cliente'),
(57, 5, '2018-07-09 15:54:03', 'Entregar pedido a cliente'),
(58, 5, '2018-07-09 15:54:12', 'Cobrar comanda'),
(59, 5, '2018-07-09 15:54:16', 'Cobrar comanda'),
(60, 5, '2018-07-09 15:54:21', 'Cobrar comanda'),
(61, 5, '2018-07-09 15:54:26', 'Cobrar comanda'),
(62, 5, '2018-07-09 17:55:53', 'Cargar comanda'),
(63, 1, '2018-07-09 17:56:24', 'Tomar un pedido'),
(64, 3, '2018-07-09 17:57:59', 'Cancelar pedidos'),
(65, 3, '2018-07-09 17:59:50', 'Cancelar pedidos'),
(66, 3, '2018-07-09 18:01:10', 'Cancelar pedidos'),
(67, 3, '2018-07-09 18:02:51', 'Cancelar pedidos'),
(68, 3, '2018-07-09 18:02:54', 'Cancelar pedidos'),
(69, 3, '2018-07-09 18:03:48', 'Cancelar pedidos'),
(70, 3, '2018-07-09 18:04:39', 'Cancelar pedidos'),
(71, 3, '2018-07-09 18:09:07', 'Cancelar pedidos'),
(72, 4, '2018-07-09 18:10:09', 'Tomar un pedido'),
(73, 3, '2018-07-09 18:10:20', 'Cancelar pedidos'),
(74, 3, '2018-07-09 18:10:45', 'Cancelar pedidos'),
(75, 3, '2018-07-09 18:15:23', 'Cancelar pedidos'),
(76, 3, '2018-07-09 18:18:33', 'Cancelar pedidos'),
(77, 3, '2018-07-09 18:18:42', 'Cancelar pedidos'),
(78, 3, '2018-07-09 18:19:17', 'Cancelar pedidos');

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
(3, 'o8ru0', 'con clientes pagando'),
(4, 'g8sve', 'con clientes pagando'),
(5, 'dyao5', 'cerrada'),
(6, 'lsjuh', 'con clientes pagando'),
(7, 'mch6i', 'con clientes comiendo'),
(8, 'pybvs', 'con clientes pagando'),
(9, 'bd7d6', 'con clientes pagando');

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
(10, '85yqy', 'cocina', 1, 'Empanada de carne', 'entregado', '2018-06-23 16:00:31', '2018-06-23 15:45:31', '2018-06-23 16:05:31'),
(11, '9x7tx', 'barra', 2, 'Fernet con coca', 'entregado', '2018-06-25 17:20:02', '2018-06-24 16:00:35', '0000-00-00 00:00:00'),
(13, '9x7tx', 'cocina', 1, 'Pizza de muzzarella', 'cancelado', '2018-06-25 19:08:57', '2018-06-24 16:00:35', '2018-06-25 18:09:16'),
(14, 'ow1ee', 'barra', 2, 'Destornillador', 'entregado', '2018-06-25 18:46:27', '2018-06-25 18:05:06', '2018-06-25 18:15:13'),
(15, '85w5f', 'cerveza', 4, 'Corona', 'entregado', '2018-06-25 18:35:57', '2018-06-25 18:05:06', '2018-06-25 18:16:04'),
(16, 'ow1ee', 'cocina', 1, 'Rabas', 'entregado', '2018-06-25 22:04:54', '2018-06-25 18:05:06', '2018-06-25 18:11:18'),
(17, 'mvugy', 'barra', 2, 'Whiskey', 'cancelado', '2018-07-09 15:53:57', '2018-07-08 17:07:25', '2018-07-09 15:53:04'),
(18, 'mvugy', 'cocina', 1, 'Rabas', 'entregado', '2018-06-26 17:29:16', '2018-07-08 17:07:25', '2018-07-05 03:40:51'),
(19, 'mvugy', 'candy', 7, 'Tiramisu', 'entregado', '2018-07-09 15:06:29', '2018-07-08 17:07:25', '2018-07-09 15:06:22'),
(20, 'f416v', 'barra', 2, 'Whiskey', 'entregado', '2018-07-05 04:00:52', '2018-07-05 03:32:59', '2018-07-09 15:52:53'),
(21, 'f416v', 'cerveza', 4, 'Quilmes', 'entregado', '2018-07-09 15:54:30', '2018-07-05 03:32:59', '2018-07-09 15:53:31'),
(22, 'f416v', 'cocina', 1, 'Sanguche de lomo', 'entregado', '2018-07-05 04:36:11', '2018-07-05 03:32:59', '2018-07-09 15:51:26'),
(23, 'f416v', 'candy', 7, 'Helado', 'entregado', '2018-07-09 15:54:46', '2018-07-05 03:32:59', '2018-07-09 15:53:48'),
(24, 'oa137', 'barra', 2, 'Fernet con coca', 'entregado', '2018-07-09 15:54:07', '2018-07-09 15:24:08', '2018-07-09 15:53:10'),
(25, 'oa137', 'cerveza', 4, 'Corona', 'entregado', '2018-07-09 15:54:26', '2018-07-09 15:24:08', '2018-07-09 15:53:27'),
(26, 'oa137', 'cocina', 1, 'Pizza de muzzarella', 'entregado', '2018-07-09 15:53:18', '2018-07-09 15:24:08', '2018-07-09 15:52:43'),
(27, '74iey', 'barra', 2, 'Destornillador', 'entregado', '2018-07-09 15:54:12', '2018-07-09 15:34:12', '2018-07-09 15:53:14'),
(28, '74iey', 'cerveza', 4, 'Corona', 'entregado', '2018-07-09 15:54:21', '2018-07-09 15:34:12', '2018-07-09 15:53:23'),
(29, '1z714', 'cerveza', 4, 'Heineken', 'cancelado', '2018-07-09 19:00:09', '2018-07-09 17:55:53', NULL),
(30, '1z714', 'cocina', NULL, 'Papas fritas con cheddar', 'cancelado', NULL, '2018-07-09 17:55:53', NULL),
(31, '1z714', 'barra', NULL, 'Destornillador', 'pendiente', NULL, '2018-07-09 17:55:53', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
