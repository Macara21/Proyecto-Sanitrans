-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-02-2025 a las 20:40:18
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sanitrans`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambulancias`
--

CREATE TABLE `ambulancias` (
  `id` int(10) UNSIGNED NOT NULL,
  `matricula` varchar(10) NOT NULL,
  `tipo` enum('SVA','SVB','Convencional') NOT NULL,
  `estado` enum('Operativa','En mantenimiento','Fuera de servicio') NOT NULL DEFAULT 'Operativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambulancias`
--

INSERT INTO `ambulancias` (`id`, `matricula`, `tipo`, `estado`) VALUES
(1, '1234ABC', 'SVA', 'Operativa'),
(2, '5678DEF', 'Convencional', 'Operativa'),
(3, '7412ACD', 'SVA', 'Operativa'),
(4, '3698BCD', 'Convencional', 'En mantenimiento'),
(5, '2587CDB', 'SVB', 'Operativa'),
(6, '6213BBB', 'Convencional', 'Operativa'),
(7, '6666ABC', 'SVA', 'Operativa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `checklist`
--

CREATE TABLE `checklist` (
  `id` int(10) UNSIGNED NOT NULL,
  `turno_id` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `ruedas` tinyint(1) DEFAULT 0,
  `aceite` tinyint(1) DEFAULT 0,
  `anticongelante` tinyint(1) DEFAULT 0,
  `golpes_exteriores` tinyint(1) DEFAULT 0,
  `luces` tinyint(1) DEFAULT 0,
  `sirena` tinyint(1) DEFAULT 0,
  `limpieza` tinyint(1) DEFAULT 0,
  `camilla` tinyint(1) DEFAULT 0,
  `ferulas` tinyint(1) DEFAULT 0,
  `ambu` tinyint(1) DEFAULT 0,
  `desfibrilador` tinyint(1) DEFAULT 0,
  `camilla_pala` tinyint(1) DEFAULT 0,
  `tablero_espinal` tinyint(1) DEFAULT 0,
  `collarines` tinyint(1) DEFAULT 0,
  `guedels` tinyint(1) DEFAULT 0,
  `sueros` tinyint(1) DEFAULT 0,
  `silla_evacuacion` tinyint(1) DEFAULT 0,
  `extintor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `checklist`
--

INSERT INTO `checklist` (`id`, `turno_id`, `fecha`, `ruedas`, `aceite`, `anticongelante`, `golpes_exteriores`, `luces`, `sirena`, `limpieza`, `camilla`, `ferulas`, `ambu`, `desfibrilador`, `camilla_pala`, `tablero_espinal`, `collarines`, `guedels`, `sueros`, `silla_evacuacion`, `extintor`) VALUES
(1, 9, '2025-02-12 23:25:04', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 9, '2025-02-12 23:32:35', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 11, '2025-02-14 19:28:24', 1, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 12, '2025-02-14 20:27:51', 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 21, '2025-02-15 19:56:44', 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 21, '2025-02-15 19:56:57', 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 22, '2025-02-15 20:22:22', 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 23, '2025-02-15 20:32:08', 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 24, '2025-02-15 20:40:28', 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combustible`
--

CREATE TABLE `combustible` (
  `id` int(10) UNSIGNED NOT NULL,
  `turno_id` int(10) UNSIGNED NOT NULL,
  `litros` float NOT NULL,
  `coste` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `combustible`
--

INSERT INTO `combustible` (`id`, `turno_id`, `litros`, `coste`, `fecha`) VALUES
(2, 3, 32, 50.00, '2025-02-06 20:59:07'),
(3, 8, 80.2, 95.00, '2025-02-12 18:48:50'),
(4, 12, 40, 60.00, '2025-02-14 20:28:44'),
(5, 19, 20, 30.00, '2025-02-14 21:48:09'),
(6, 21, 48, 80.00, '2025-02-15 19:58:24'),
(7, 21, 40, 62.00, '2025-02-15 19:58:50'),
(8, 36, 80, 90.00, '2025-02-24 10:04:22'),
(9, 36, 52, 56.00, '2025-02-24 10:04:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_incidente`
--

CREATE TABLE `detalles_incidente` (
  `id` int(10) UNSIGNED NOT NULL,
  `parte_id` int(10) UNSIGNED NOT NULL,
  `tipo_incidente` enum('AccidenteTrafico','AccidenteLaboral','CompaniaPrivada','Extranjero') NOT NULL,
  `rol` enum('Conductor','Ocupante','Peaton','Motorista','Ciclista') DEFAULT NULL,
  `lugar_accidente` varchar(200) DEFAULT NULL,
  `nombre_asegurado` varchar(100) DEFAULT NULL,
  `matricula_vehiculo` varchar(20) DEFAULT NULL,
  `marca_vehiculo` varchar(50) DEFAULT NULL,
  `aseguradora_vehiculo` varchar(100) DEFAULT NULL,
  `numero_poliza` varchar(50) DEFAULT NULL,
  `nombre_asegurado_contrario` varchar(100) DEFAULT NULL,
  `matricula_vehiculo_contrario` varchar(20) DEFAULT NULL,
  `marca_vehiculo_contrario` varchar(50) DEFAULT NULL,
  `aseguradora_contrario` varchar(100) DEFAULT NULL,
  `numero_poliza_contrario` varchar(50) DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `mutua_accidente_trabajo` varchar(100) DEFAULT NULL,
  `aseguradora_compania` varchar(100) DEFAULT NULL,
  `numero_poliza_compania` varchar(50) DEFAULT NULL,
  `pais_origen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_incidente`
--

INSERT INTO `detalles_incidente` (`id`, `parte_id`, `tipo_incidente`, `rol`, `lugar_accidente`, `nombre_asegurado`, `matricula_vehiculo`, `marca_vehiculo`, `aseguradora_vehiculo`, `numero_poliza`, `nombre_asegurado_contrario`, `matricula_vehiculo_contrario`, `marca_vehiculo_contrario`, `aseguradora_contrario`, `numero_poliza_contrario`, `empresa`, `mutua_accidente_trabajo`, `aseguradora_compania`, `numero_poliza_compania`, `pais_origen`) VALUES
(1, 1, 'AccidenteTrafico', 'Conductor', 'A5', 'Luis', '5663DSC', 'Ford', 'Mapfre', '66565669999999', 'Juan', '4563DEF', 'Citroen', 'Allianz', '228888888552255', NULL, NULL, NULL, NULL, NULL),
(2, 2, 'AccidenteLaboral', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Soluciones S.A', 'Generali', NULL, NULL, NULL),
(3, 3, 'CompaniaPrivada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adeslas', '446659595ww9595959', NULL),
(4, 4, 'CompaniaPrivada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adeslas', '446659595ww9595959', NULL),
(5, 5, 'Extranjero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Alemania'),
(6, 6, 'CompaniaPrivada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adeslas', '555eeeee5555', NULL),
(7, 7, 'CompaniaPrivada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'adeslas', '446659595ww9595959', NULL),
(8, 8, 'AccidenteLaboral', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Autos GMZ', 'Zurich', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pswd` varchar(255) DEFAULT NULL,
  `rol` enum('empleado','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellidos`, `dni`, `email`, `pswd`, `rol`) VALUES
(7, 'Admin', 'Sanitrans', '12345678A', 'admin@sanitrans.com', '$2y$10$fi1b.6DBSP506CBVqw54aOraBWeuMvdsVAjiBBszCpYl.0IDszRMa', 'admin'),
(11, 'Mario', 'Carmona Ramos', '12345678D', 'mario@sanitrans.com', '$2y$10$.ikIUb4XNr0wyaevKnUX0.hgm0zfuFNGc/22Ze.1CwUd.5lSKua92', 'admin'),
(12, 'Laura', 'Gómez Pérez', '87654321B', 'laura@sanitrans.com', '$2y$10$NIkhMZzi9gDHL0PbzMpROeQNpCL5G2ONTqeYxWyYXh.Fw8X7kQ94W', 'empleado'),
(13, 'Carlos', 'Martínez López', '11223344C', 'carlos@sanitrans.com', '$2y$10$72LuUkIV6GpYkHnwwurZee7085aLuyGUQ7FnC/mfVP/TrxKME7zKK', 'empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` int(10) UNSIGNED NOT NULL,
  `turno_id` int(10) UNSIGNED NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `descripcion` text NOT NULL,
  `estado` enum('Pendiente','Resuelto') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `turno_id`, `fecha`, `descripcion`, `estado`) VALUES
(2, 3, '2025-02-06 20:59:51', 'La ambulancia tiene un golpe en el frontal, lateral derecho', 'Pendiente'),
(3, 8, '2025-02-12 18:49:50', 'Golpe en la aleta trasera izquierda.', 'Pendiente'),
(4, 11, '2025-02-14 19:28:46', 'Test 1', 'Pendiente'),
(5, 12, '2025-02-14 20:27:59', 'test2\r\n', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partes_asistencia`
--

CREATE TABLE `partes_asistencia` (
  `id` int(10) UNSIGNED NOT NULL,
  `turno_id` int(10) UNSIGNED NOT NULL,
  `fecha_servicio` date NOT NULL,
  `hora_servicio` time NOT NULL,
  `nombre_paciente` varchar(100) NOT NULL,
  `apellidos_paciente` varchar(100) NOT NULL,
  `telefono_paciente` varchar(20) NOT NULL,
  `dni_paciente` varchar(20) NOT NULL,
  `domicilio_paciente` varchar(200) NOT NULL,
  `poblacion_paciente` varchar(100) NOT NULL,
  `provincia_paciente` varchar(100) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `tipo_asistencia` enum('SVA','SVB','Convencional') NOT NULL,
  `diagnostico` text NOT NULL,
  `tipo_incidente` enum('AccidenteTrafico','AccidenteLaboral','CompaniaPrivada','Extranjero') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partes_asistencia`
--

INSERT INTO `partes_asistencia` (`id`, `turno_id`, `fecha_servicio`, `hora_servicio`, `nombre_paciente`, `apellidos_paciente`, `telefono_paciente`, `dni_paciente`, `domicilio_paciente`, `poblacion_paciente`, `provincia_paciente`, `codigo_postal`, `tipo_asistencia`, `diagnostico`, `tipo_incidente`) VALUES
(1, 26, '2025-02-18', '22:00:00', 'Maria', 'Luisa', '600271782', '778952365H', 'Calle huertas', 'Montijo', 'Badajoz', '06480', 'SVA', 'test1', 'AccidenteTrafico'),
(2, 27, '2025-02-08', '21:50:00', 'Eloy', 'Dominguez', '325369852', '08456321', 'Calle huertas', 'Montijo', 'Badajoz', '06480', 'SVB', 'Test2', 'AccidenteLaboral'),
(3, 25, '2025-02-01', '05:20:00', 'Oscar', 'Sanchez Torrejon', '456789123', '088520142K', 'C/Alconchel', 'Olivenza', 'Badajoz', '06325', 'Convencional', 'Test3', 'CompaniaPrivada'),
(4, 28, '2025-02-14', '22:00:00', 'Luisa', 'Almodovar jimena', '456128745', '41256657J', 'Calle Madrid', 'Badajoz', 'Badajoz', '06480', 'Convencional', 'test4', 'CompaniaPrivada'),
(5, 29, '2025-02-02', '15:00:00', 'Mario', 'Carmona', '600271782', '08456321M', 'C/Alconchel', 'Montijo', 'Badajoz', '06480', 'Convencional', 'test4', 'Extranjero'),
(6, 29, '2025-02-03', '21:41:00', 'Laura', 'Salazar Vargas', '123569874', '41256657J', 'calle perejil', 'Montijo', 'Badajoz', '06480', 'SVB', 'test5', 'CompaniaPrivada'),
(7, 34, '2025-02-21', '00:01:00', 'Mario', 'Carmona Ramos', '699938533', '41256657J', 'Calle huertas', 'Montijo', 'BADAJOZ', '06480', 'SVA', 'dadadd', 'CompaniaPrivada'),
(8, 41, '2025-02-27', '08:00:00', 'Luis', 'Llados Garcia', '+34 123456987', '09123654H', 'Calle huertas', 'Montijo', 'Badajoz', '06480', 'SVA', 'Test1', 'AccidenteLaboral');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` int(10) UNSIGNED NOT NULL,
  `empleado_id` int(10) UNSIGNED NOT NULL,
  `ambulancia_id` int(10) UNSIGNED DEFAULT NULL,
  `inicio_turno` datetime NOT NULL,
  `fin_turno` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `empleado_id`, `ambulancia_id`, `inicio_turno`, `fin_turno`) VALUES
(2, 13, 1, '2025-02-06 20:57:20', '2025-02-06 20:57:52'),
(3, 13, 1, '2025-02-06 20:58:51', '2025-02-06 21:02:23'),
(4, 13, 2, '2025-02-07 18:47:21', '2025-02-07 18:53:09'),
(5, 13, 1, '2025-02-07 19:09:06', '2025-02-07 19:10:12'),
(6, 13, 1, '2025-02-11 20:08:38', '2025-02-12 10:31:07'),
(7, 12, 2, '2025-02-12 10:53:51', '2025-02-12 10:54:15'),
(8, 13, 1, '2025-02-12 18:48:00', '2025-02-12 18:53:18'),
(9, 13, 1, '2025-02-12 23:24:44', '2025-02-12 23:33:28'),
(10, 12, 2, '2025-02-12 23:36:17', '2025-02-12 23:37:27'),
(11, 13, 1, '2025-02-14 19:28:09', '2025-02-14 19:34:20'),
(12, 13, 1, '2025-02-14 20:26:58', '2025-02-14 20:28:47'),
(13, 12, 6, '2025-02-14 20:36:25', '2025-02-14 20:57:29'),
(14, 12, 5, '2025-02-14 21:09:41', '2025-02-14 21:24:20'),
(15, 12, 6, '2025-02-14 21:24:39', '2025-02-14 21:30:39'),
(16, 12, 3, '2025-02-14 21:24:47', '2025-02-14 21:30:39'),
(17, 12, 1, '2025-02-14 21:30:55', '2025-02-14 21:31:25'),
(18, 12, 3, '2025-02-14 21:31:39', '2025-02-14 21:46:49'),
(19, 13, 1, '2025-02-14 21:47:33', '2025-02-14 21:48:43'),
(20, 13, 1, '2025-02-14 21:59:14', '2025-02-15 19:28:40'),
(21, 12, 3, '2025-02-15 19:29:11', '2025-02-15 20:21:53'),
(22, 13, 1, '2025-02-15 20:22:07', '2025-02-15 20:30:15'),
(23, 13, 1, '2025-02-15 20:31:54', '2025-02-15 20:32:45'),
(24, 13, 3, '2025-02-15 20:40:17', '2025-02-15 20:40:52'),
(25, 12, 1, '2025-02-16 18:15:47', '2025-02-18 18:33:09'),
(26, 13, 1, '2025-02-16 23:42:23', '2025-02-18 18:25:46'),
(27, 13, 3, '2025-02-18 18:26:33', '2025-02-18 18:29:42'),
(28, 13, 6, '2025-02-18 18:33:55', '2025-02-18 18:37:39'),
(29, 12, 3, '2025-02-18 18:37:53', '2025-02-18 18:53:40'),
(30, 13, 1, '2025-02-18 19:25:32', '2025-02-18 19:25:57'),
(31, 12, 1, '2025-02-18 19:28:25', '2025-02-18 19:36:48'),
(32, 13, 3, '2025-02-18 20:04:58', '2025-02-18 20:05:05'),
(33, 13, 3, '2025-02-18 20:56:02', '2025-02-18 20:56:05'),
(34, 13, 1, '2025-02-18 21:57:14', '2025-02-18 22:00:24'),
(35, 13, 1, '2025-02-22 12:36:22', '2025-02-22 13:02:05'),
(36, 13, 7, '2025-02-24 10:04:08', '2025-02-24 10:55:03'),
(37, 12, 1, '2025-02-24 10:52:34', '2025-02-24 10:53:50'),
(38, 12, 1, '2025-02-24 10:55:42', '2025-02-24 20:34:41'),
(39, 13, 2, '2025-02-26 20:44:57', '2025-02-26 20:45:53'),
(40, 13, 2, '2025-02-27 12:55:15', '2025-02-27 12:55:41'),
(41, 12, 2, '2025-02-27 14:11:58', '2025-02-27 14:16:02'),
(42, 13, 2, '2025-02-27 19:11:04', '2025-02-27 19:13:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos_programados`
--

CREATE TABLE `turnos_programados` (
  `id` int(10) UNSIGNED NOT NULL,
  `empleado_id` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `turnos_programados`
--

INSERT INTO `turnos_programados` (`id`, `empleado_id`, `fecha`, `hora_inicio`, `hora_fin`, `descripcion`) VALUES
(19, 13, '2025-02-21', '08:10:00', '16:00:00', 'SVB'),
(20, 13, '2025-02-22', '16:00:00', '23:59:00', 'SVB'),
(22, 12, '2025-02-21', '16:00:00', '23:59:00', 'SVA'),
(23, 12, '2025-02-28', '08:00:00', '16:00:00', 'Convencional');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ambulancias`
--
ALTER TABLE `ambulancias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Indices de la tabla `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `combustible`
--
ALTER TABLE `combustible`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `detalles_incidente`
--
ALTER TABLE `detalles_incidente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parte_id` (`parte_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `partes_asistencia`
--
ALTER TABLE `partes_asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turno_id` (`turno_id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`),
  ADD KEY `ambulancia_id` (`ambulancia_id`);

--
-- Indices de la tabla `turnos_programados`
--
ALTER TABLE `turnos_programados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ambulancias`
--
ALTER TABLE `ambulancias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `checklist`
--
ALTER TABLE `checklist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `combustible`
--
ALTER TABLE `combustible`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `detalles_incidente`
--
ALTER TABLE `detalles_incidente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `partes_asistencia`
--
ALTER TABLE `partes_asistencia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `turnos_programados`
--
ALTER TABLE `turnos_programados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `checklist`
--
ALTER TABLE `checklist`
  ADD CONSTRAINT `checklist_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `combustible`
--
ALTER TABLE `combustible`
  ADD CONSTRAINT `combustible_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalles_incidente`
--
ALTER TABLE `detalles_incidente`
  ADD CONSTRAINT `detalles_incidente_ibfk_1` FOREIGN KEY (`parte_id`) REFERENCES `partes_asistencia` (`id`);

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `partes_asistencia`
--
ALTER TABLE `partes_asistencia`
  ADD CONSTRAINT `partes_asistencia_ibfk_1` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`);

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_ibfk_2` FOREIGN KEY (`ambulancia_id`) REFERENCES `ambulancias` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `turnos_programados`
--
ALTER TABLE `turnos_programados`
  ADD CONSTRAINT `turnos_programados_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
