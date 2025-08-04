-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2025 a las 20:40:38
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
-- Base de datos: `services_cargamasiva`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id`, `nombre`) VALUES
(1, 'APOYO'),
(2, 'CEDI'),
(3, 'COMERCIAL'),
(4, 'COMERCIO EXTERIOR'),
(5, 'CONTABILIDAD'),
(6, 'CONTRALORIA'),
(7, 'DISEÑO'),
(8, 'E-COMMERCE'),
(9, 'GAF'),
(10, 'GESTION HUMANA'),
(11, 'MERCADEO'),
(12, 'PLANEACION'),
(13, 'PRODUCCION'),
(14, 'TECNOLOGIA'),
(15, 'VISUAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora_cambios`
--

CREATE TABLE `bitacora_cambios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL,
  `registro_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `campo` varchar(100) NOT NULL,
  `valor_anterior` text DEFAULT NULL,
  `valor_nuevo` text DEFAULT NULL,
  `accion` varchar(20) DEFAULT 'UPDATE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora_cambios`
--

INSERT INTO `bitacora_cambios` (`id`, `usuario`, `fecha`, `registro_id`, `nombre`, `campo`, `valor_anterior`, `valor_nuevo`, `accion`) VALUES
(1, 'Lady Marin', '2025-06-09 11:12:41', 5, 'CHALECO', 'MES', '11-Noviembre', '10-Octubre', 'UPDATE'),
(65, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 17, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(66, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 17, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(67, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 17, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(68, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 18, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(69, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 18, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(70, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 18, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(71, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 19, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(72, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 19, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(73, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 19, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(74, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 20, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(75, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 20, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(76, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 20, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(77, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 21, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(78, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 21, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(79, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 21, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(80, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 22, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(81, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 22, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(82, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 22, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(83, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 23, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(84, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 23, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(85, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 23, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(86, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 24, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(87, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 24, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(88, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 24, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR'),
(89, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 25, 'CHAQUETA', 'COLOR_FDS', '123', '101', 'CAMBIO_MASIVO_COLOR'),
(90, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 25, 'CHAQUETA', 'NOM_COLOR', 'KAKI', 'OFFWHITE', 'CAMBIO_MASIVO_COLOR'),
(91, 'CAMILO VERA PINERES', '2025-06-20 22:18:53', 25, 'CHAQUETA', 'GAMA', 'BEIGE', 'BLANCO', 'CAMBIO_MASIVO_COLOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `borradores_carga_manual`
--

CREATE TABLE `borradores_carga_manual` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `datos_json` longtext NOT NULL,
  `fecha_guardado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capsulas`
--

CREATE TABLE `capsulas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cargas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cargas` (
`id` int(11)
,`SAP` varchar(50)
,`YEAR` int(11)
,`MES` varchar(20)
,`OCASION_DE_USO` varchar(100)
,`NOMBRE` varchar(100)
,`MODULO` varchar(50)
,`TEMPORADA` varchar(50)
,`CAPSULA` varchar(50)
,`CLIMA` varchar(50)
,`TIENDA` varchar(100)
,`CLASIFICACION` varchar(100)
,`CLUSTER` varchar(100)
,`PROVEEDOR` varchar(100)
,`CATEGORIAS` varchar(100)
,`SUBCATEGORIAS` varchar(100)
,`DISENO` varchar(100)
,`DESCRIPCION` text
,`MANGA` varchar(50)
,`TIPO_MANGA` varchar(50)
,`PUNO` varchar(50)
,`CAPOTA` varchar(50)
,`ESCOTE` varchar(50)
,`LARGO` varchar(50)
,`CUELLO` varchar(50)
,`TIRO` varchar(50)
,`BOTA` varchar(50)
,`CINTURA` varchar(50)
,`SILUETA` varchar(50)
,`CIERRE` varchar(50)
,`GALGA` varchar(50)
,`TIPO_GALGA` varchar(50)
,`COLOR_FDS` varchar(50)
,`NOM_COLOR` varchar(100)
,`GAMA` varchar(50)
,`PRINT` varchar(50)
,`TALLAS` varchar(100)
,`TIPO_TEJIDO` varchar(100)
,`TIPO_DE_FIBRA` varchar(100)
,`BASE_TEXTIL` varchar(100)
,`DETALLES` text
,`SUB_DETALLES` text
,`GRUPO` varchar(50)
,`INSTRUCCION_DE_LAVADO_1` text
,`INSTRUCCION_DE_LAVADO_2` text
,`INSTRUCCION_DE_LAVADO_3` text
,`INSTRUCCION_DE_LAVADO_4` text
,`INSTRUCCION_DE_LAVADO_5` text
,`INSTRUCCION_BLANQUEADO_1` text
,`INSTRUCCION_BLANQUEADO_2` text
,`INSTRUCCION_BLANQUEADO_3` text
,`INSTRUCCION_BLANQUEADO_4` text
,`INSTRUCCION_BLANQUEADO_5` text
,`INSTRUCCION_SECADO_1` text
,`INSTRUCCION_SECADO_2` text
,`INSTRUCCION_SECADO_3` text
,`INSTRUCCION_SECADO_4` text
,`INSTRUCCION_SECADO_5` text
,`INSTRUCCION_PLANCHADO_1` text
,`INSTRUCCION_PLANCHADO_2` text
,`INSTRUCCION_PLANCHADO_3` text
,`INSTRUCCION_PLANCHADO_4` text
,`INSTRUCCION_PLANCHADO_5` text
,`INSTRUCC_CUIDADO_TEXTIL_PROF_1` text
,`INSTRUCC_CUIDADO_TEXTIL_PROF_2` text
,`INSTRUCC_CUIDADO_TEXTIL_PROF_3` text
,`INSTRUCC_CUIDADO_TEXTIL_PROF_4` text
,`INSTRUCC_CUIDADO_TEXTIL_PROF_5` text
,`COMPOSICION_1` varchar(100)
,`%_COMP_1` varchar(10)
,`COMPOSICION_2` varchar(100)
,`%_COMP_2` varchar(10)
,`COMPOSICION_3` varchar(100)
,`%_COMP_3` varchar(10)
,`COMPOSICION_4` varchar(100)
,`%_COMP_4` varchar(10)
,`TOT_COMP` varchar(20)
,`FORRO` varchar(100)
,`COMP_FORRO_1` varchar(100)
,`%_FORRO_1` varchar(10)
,`COMP_FORRO_2` varchar(100)
,`%_FORRO_2` varchar(10)
,`TOT_FORRO` varchar(20)
,`RELLENO` varchar(100)
,`COMP_RELLENO_1` varchar(100)
,`%_RELLENO_1` varchar(10)
,`COMP_RELLENO_2` varchar(100)
,`%_RELLENO_2` varchar(10)
,`TOT_RELLENO` varchar(20)
,`XX` varchar(100)
,`usuario` varchar(255)
,`fecha_creacion` date
,`precio_compra` decimal(12,2) unsigned
,`costo` decimal(12,2) unsigned
,`precio_venta` decimal(12,2) unsigned
,`tipo` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_disenos`
--

CREATE TABLE `catalogo_disenos` (
  `id` int(11) NOT NULL,
  `SAP` varchar(50) DEFAULT NULL,
  `YEAR` int(11) DEFAULT NULL,
  `MES` varchar(20) DEFAULT NULL,
  `OCASION_DE_USO` varchar(100) DEFAULT NULL,
  `NOMBRE` varchar(100) DEFAULT NULL,
  `MODULO` varchar(50) DEFAULT NULL,
  `TEMPORADA` varchar(50) DEFAULT NULL,
  `CAPSULA` varchar(50) DEFAULT NULL,
  `CLIMA` varchar(50) DEFAULT NULL,
  `TIENDA` varchar(100) DEFAULT NULL,
  `CLASIFICACION` varchar(100) DEFAULT NULL,
  `CLUSTER` varchar(100) DEFAULT NULL,
  `PROVEEDOR` varchar(100) DEFAULT NULL,
  `CATEGORIAS` varchar(100) DEFAULT NULL,
  `SUBCATEGORIAS` varchar(100) DEFAULT NULL,
  `DISENO` varchar(100) DEFAULT NULL,
  `DESCRIPCION` text DEFAULT NULL,
  `MANGA` varchar(50) DEFAULT NULL,
  `TIPO_MANGA` varchar(50) DEFAULT NULL,
  `PUNO` varchar(50) DEFAULT NULL,
  `CAPOTA` varchar(50) DEFAULT NULL,
  `ESCOTE` varchar(50) DEFAULT NULL,
  `LARGO` varchar(50) DEFAULT NULL,
  `CUELLO` varchar(50) DEFAULT NULL,
  `TIRO` varchar(50) DEFAULT NULL,
  `BOTA` varchar(50) DEFAULT NULL,
  `CINTURA` varchar(50) DEFAULT NULL,
  `SILUETA` varchar(50) DEFAULT NULL,
  `CIERRE` varchar(50) DEFAULT NULL,
  `GALGA` varchar(50) DEFAULT NULL,
  `TIPO_GALGA` varchar(50) DEFAULT NULL,
  `COLOR_FDS` varchar(50) DEFAULT NULL,
  `NOM_COLOR` varchar(100) DEFAULT NULL,
  `GAMA` varchar(50) DEFAULT NULL,
  `PRINT` varchar(50) DEFAULT NULL,
  `TALLAS` varchar(100) DEFAULT NULL,
  `TIPO_TEJIDO` varchar(100) DEFAULT NULL,
  `TIPO_DE_FIBRA` varchar(100) DEFAULT NULL,
  `BASE_TEXTIL` varchar(100) DEFAULT NULL,
  `DETALLES` text DEFAULT NULL,
  `SUB_DETALLES` text DEFAULT NULL,
  `GRUPO` varchar(50) DEFAULT NULL,
  `INSTRUCCION_DE_LAVADO_1` text DEFAULT NULL,
  `INSTRUCCION_DE_LAVADO_2` text DEFAULT NULL,
  `INSTRUCCION_DE_LAVADO_3` text DEFAULT NULL,
  `INSTRUCCION_DE_LAVADO_4` text DEFAULT NULL,
  `INSTRUCCION_DE_LAVADO_5` text DEFAULT NULL,
  `INSTRUCCION_BLANQUEADO_1` text DEFAULT NULL,
  `INSTRUCCION_BLANQUEADO_2` text DEFAULT NULL,
  `INSTRUCCION_BLANQUEADO_3` text DEFAULT NULL,
  `INSTRUCCION_BLANQUEADO_4` text DEFAULT NULL,
  `INSTRUCCION_BLANQUEADO_5` text DEFAULT NULL,
  `INSTRUCCION_SECADO_1` text DEFAULT NULL,
  `INSTRUCCION_SECADO_2` text DEFAULT NULL,
  `INSTRUCCION_SECADO_3` text DEFAULT NULL,
  `INSTRUCCION_SECADO_4` text DEFAULT NULL,
  `INSTRUCCION_SECADO_5` text DEFAULT NULL,
  `INSTRUCCION_PLANCHADO_1` text DEFAULT NULL,
  `INSTRUCCION_PLANCHADO_2` text DEFAULT NULL,
  `INSTRUCCION_PLANCHADO_3` text DEFAULT NULL,
  `INSTRUCCION_PLANCHADO_4` text DEFAULT NULL,
  `INSTRUCCION_PLANCHADO_5` text DEFAULT NULL,
  `INSTRUCC_CUIDADO_TEXTIL_PROF_1` text DEFAULT NULL,
  `INSTRUCC_CUIDADO_TEXTIL_PROF_2` text DEFAULT NULL,
  `INSTRUCC_CUIDADO_TEXTIL_PROF_3` text DEFAULT NULL,
  `INSTRUCC_CUIDADO_TEXTIL_PROF_4` text DEFAULT NULL,
  `INSTRUCC_CUIDADO_TEXTIL_PROF_5` text DEFAULT NULL,
  `COMPOSICION_1` varchar(100) DEFAULT NULL,
  `%_COMP_1` varchar(10) DEFAULT NULL,
  `COMPOSICION_2` varchar(100) DEFAULT NULL,
  `%_COMP_2` varchar(10) DEFAULT NULL,
  `COMPOSICION_3` varchar(100) DEFAULT NULL,
  `%_COMP_3` varchar(10) DEFAULT NULL,
  `COMPOSICION_4` varchar(100) DEFAULT NULL,
  `%_COMP_4` varchar(10) DEFAULT NULL,
  `TOT_COMP` varchar(20) DEFAULT NULL,
  `FORRO` varchar(100) DEFAULT NULL,
  `COMP_FORRO_1` varchar(100) DEFAULT NULL,
  `%_FORRO_1` varchar(10) DEFAULT NULL,
  `COMP_FORRO_2` varchar(100) DEFAULT NULL,
  `%_FORRO_2` varchar(10) DEFAULT NULL,
  `TOT_FORRO` varchar(20) DEFAULT NULL,
  `RELLENO` varchar(100) DEFAULT NULL,
  `COMP_RELLENO_1` varchar(100) DEFAULT NULL,
  `%_RELLENO_1` varchar(10) DEFAULT NULL,
  `COMP_RELLENO_2` varchar(100) DEFAULT NULL,
  `%_RELLENO_2` varchar(10) DEFAULT NULL,
  `TOT_RELLENO` varchar(20) DEFAULT NULL,
  `XX` varchar(100) DEFAULT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `precio_compra` decimal(12,2) UNSIGNED DEFAULT NULL,
  `costo` decimal(12,2) UNSIGNED DEFAULT NULL,
  `precio_venta` decimal(12,2) UNSIGNED DEFAULT NULL,
  `tipo` varchar(100) NOT NULL,
  `LINEA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_compra`
--

CREATE TABLE `ordenes_compra` (
  `id` int(11) NOT NULL,
  `EBELN` varchar(50) NOT NULL,
  `AEDAT` varchar(20) NOT NULL,
  `ERNAM` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `WAERS` varchar(10) DEFAULT NULL,
  `FRGZU` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `PROCSTAT` varchar(2) DEFAULT NULL,
  `RLWRT` varchar(50) DEFAULT NULL,
  `BSART` varchar(10) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reglas_dependencia`
--

CREATE TABLE `reglas_dependencia` (
  `id` int(11) NOT NULL,
  `nombre_regla` varchar(255) DEFAULT NULL,
  `campo_destino` varchar(100) NOT NULL,
  `condiciones` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`condiciones`)),
  `valores_permitidos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `es_activa` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `usuario_creacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reglas_dependencia`
--

INSERT INTO `reglas_dependencia` (`id`, `nombre_regla`, `campo_destino`, `condiciones`, `valores_permitidos`, `es_activa`, `fecha_creacion`, `fecha_modificacion`, `usuario_creacion`) VALUES
(31, 'Diseño por categorias y subcategorias', 'DISENO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"BLAZER\"}]},\"then_allow\":[\"ESTRUCTURADO\",\"SIN ESTRUCTURA\",\"OVER SIZE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"ANORAK\"}]},\"then_allow\":[\"ABULLANADA\",\"ULTRA LIGTH\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"CAZADORA\"}]},\"then_allow\":[\"SOBRE CAMISA\",\"BOMBER\",\"DIKER\",\"DENIM\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"ABRIGO\"}]},\"then_allow\":[\"ESTRUCTURADO\",\"SIN ESTRUCTURA\",\"OVER SIZE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"ENGUATADA\"}]},\"then_allow\":[\"ABULLANADA\",\"ULTRA LIGTH\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"TRENCH\"}]},\"then_allow\":[\"OVER SIZE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"CHALECO\"}]},\"then_allow\":[\"ABULLANADA\",\"ULTRA LIGTH\",\"ESTRUCTURADO\",\"SIN ESTRUCTURA\",\"OVER SIZE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"MODA\"}]},\"then_allow\":[\"CULOTTE\",\"CIGARRETE\",\"PALAZZO\",\"RECTO\",\"PRENSES\",\"STRECH\",\"PAPER BAG\",\"SAFARY\",\"5 BOLSILLOS\",\"CHINO\",\"JOGGINS\",\"BAGGY\",\"LEGGINS\",\"BERMUDA\",\"SHORT\",\"SLOWCHY\",\"JOGGER\",\"BOYFRIEND\",\"BASICO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"FITS\"}]},\"then_allow\":[\"WONDER\",\"ILUSSION\",\"DIVA\",\"MUSE\",\"MOM\",\"ELEGANCE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"MIDI\"}]},\"then_allow\":[\"LAPIZ\",\"TRAPECIO\",\"PLISADA\",\"NESGAS\",\"SAFARY\",\"PAREO\",\"EVASE\",\"LINEA A\",\"DENIM\",\"5 BOLSILLOS\",\"RECTA\",\"SPORT\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"},{\"operator\":\"condition\",\"field\":\"SUBCATEGORIAS\",\"value\":\"OVER SIZE\"}]},\"then_allow\":[\"LAPIZ\",\"TRAPECIO\",\"PLISADA\",\"NESGAS\",\"SAFARY\",\"PAREO\",\"EVASE\",\"LINEA A\",\"DENIM\",\"5 BOLSILLOS\",\"RECTA\",\"SPORT\"]}],\"default_allow\":[\"Seleccione\"]}', '[]', 1, '2025-05-06 19:22:44', '2025-05-13 13:50:50', NULL),
(32, 'Subcategorias por Categoria', 'SUBCATEGORIAS', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"KIMONO\",\"CERRADA\",\"ABIERTA\",\"CRUZADA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"SOBRE CAMISA\",\"BASICA\",\"OVERSIZE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"TOP\",\"T-SHIRT\",\"TANK \\/SIN MANGAS\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"CARDIGAN\",\"JERSEY\",\"VESTIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"CARDIGAN\",\"SUDADERAS\",\"PANTALON\",\"FALDA\",\"JERSEY\",\"VESTIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"BLAZER\",\"ANORAK\",\"CAZADORA\",\"ABRIGO\",\"ENGUATADA\",\"TRENCH\",\"CHALECO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"CORTO\",\"MIDI\",\"MEDIO\",\"LARGO\",\"MAXI\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"CIGARRETE\",\"PALAZZO\",\"RECTO\",\"PRENSES\",\"STRECH\",\"PAPER BAG\",\"SAFARY\",\"5 BOLSILLOS\",\"CHINO\",\"JOGGINS\",\"BAGGY\",\"LEGGINS\",\"BERMUDA\",\"SHORT\",\"CULOTTE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"MODA\",\"FITS\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"CORTO\",\"MIDI\",\"LARGO\",\"OVER SIZE\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 19:41:10', NULL, NULL),
(33, 'Manga por categoria', 'MANGA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"3\\/4\",\"TIRAS\",\"AL CODO\",\"SISA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"3\\/4\",\"TIRAS\",\"AL CODO\",\"SISA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"3\\/4\",\"TIRAS\",\"AL CODO\",\"SISA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"3\\/4\",\"TIRAS\",\"AL CODO\",\"SIN MANGA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"3\\/4\",\"TIRAS\",\"AL CODO\",\"SIN MANGA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"SIN MANGA\",\"LARGA\",\"CORTA\",\"TIRAS\",\"AL CODO\",\"3\\/4\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"SISA\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 19:54:18', '2025-06-13 15:52:06', NULL),
(34, 'Tipo de manga por categoria', 'TIPO_MANGA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"CUT OUT\",\"RANGLAN\",\"PUFFY\",\"GLOBO\",\"RODADA\",\"BOLERO\",\"CASQUITO\",\"TULIPAN\",\"FAROL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"CUT OUT\",\"RANGLAN\",\"PUFFY\",\"GLOBO\",\"RODADA\",\"BOLERO\",\"CASQUITO\",\"TULIPAN\",\"FAROL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"CUT OUT\",\"RANGLAN\",\"PUFFY\",\"GLOBO\",\"RODADA\",\"BOLERO\",\"TULIPAN\",\"FAROL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"CUT OUT\",\"RANGLAN\",\"PUFFY\",\"GLOBO\",\"RODADA\",\"BOLERO\",\"CASQUITO\",\"TULIPAN\",\"FAROL\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 20:02:07', NULL, NULL),
(35, 'Puño por categoria', 'PUNO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"RIB\",\"CAMPANA\",\"GOMA\",\"CON SESGO\",\"INCLUIDO\",\"SIN ACABADO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:03:39', NULL, NULL),
(36, 'Capota por categoria', 'CAPOTA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"SI\",\"NO\",\"CON PELO\",\"SIN PELO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"SI\",\"NO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"SI\",\"NO\",\"CON PELO\",\"SIN PELO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:08:02', NULL, NULL),
(38, 'Largo por categoria', 'LARGO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"MINI\",\"CORTO\",\"MEDIO\",\"3\\/4\",\"7\\/8\",\"LARGO\",\"CROP\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"CORTA\",\"LARGA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"MINI\",\"CORTO\",\"MEDIO\",\"3\\/4\",\"7\\/8\",\"LARGO\",\"CROP\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"CORTA\",\"MEDIO\",\"LARGO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"CORTA\",\"LARGA\",\"MEDIO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:17:11', NULL, NULL),
(39, 'Cuello por categoria', 'CUELLO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"BEBE\",\"CAMISERO\",\"HALTER\",\"NERU\",\"SPORT\",\"TORTUGA\",\"MAO\",\"LAZADA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"BANDEJA\",\"BEBE\",\"CAMISERO\",\"HALTER\",\"NERU\",\"OJAL\",\"REDONDO\",\"SPORT\",\"TORTUGA\",\"MAO\",\"U\",\"SIN CUELLO\",\"V\",\"LAZADA\",\"TRAPECIO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"ALTO\",\"HALTER\",\"BEBE\",\"CAMISERO\",\"V\",\"NERU\",\"SPORT\",\"TORTUGA\",\"MAO\",\"LAZADA\",\"TRAPECIO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"ALTO\",\"SOLAPA\",\"NERU\",\"REDONDO\",\"SPORT\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:25:35', NULL, NULL),
(40, 'Tiro por categoria', 'TIRO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"ALTO\",\"MEDIO\",\"BAJO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"ALTO\",\"MEDIO\",\"BAJO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:40:55', '2025-05-12 15:07:48', NULL),
(41, 'Escote por categorias', 'ESCOTE', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"NORMAL\",\"ASIMETRICO\",\"STRAPLE\",\"BANDEJA\",\"OJAL\",\"REDONDO\",\"U\",\"V\",\"STRAPLE\",\"DRAPEADO\",\"CORAZON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"BANDEJA\",\"OJAL\",\"REDONDO\",\"U\",\"V\",\"STRAPLE\",\"DRAPEADO\",\"CORAZON\",\"ASIMETRICO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"NORMAL\",\"ASIMETRICO\",\"STRAPLE\",\"BANDEJA\",\"OJAL\",\"REDONDO\",\"U\",\"V\",\"STRAPLE\",\"DRAPEADO\",\"CORAZON\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:53:25', NULL, NULL),
(42, 'Bota por categoria', 'BOTA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"FLARE\",\"RECTA\",\"SKINNY\",\"STRAIGTH\",\"CROP\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"FLARE\",\"RECTA\",\"SKINNY\",\"STRAIGTH\",\"SLIM\",\"FLARE CROP\",\"RECTA CROP\",\"SKINNY CROP\",\"STRAIGTH CROP\",\"SUPER SLIM\",\"BOOTCUT\",\"CROP\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-06 21:57:04', NULL, NULL),
(43, 'Bota por categoria', 'BOTA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"FLARE\",\"RECTA\",\"SKINNY\",\"STRAIGTH\",\"CROP\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"FLARE\",\"RECTA\",\"SKINNY\",\"STRAIGTH\",\"SLIM\",\"FLARE CROP\",\"RECTA CROP\",\"SKINNY CROP\",\"STRAIGTH CROP\",\"SUPER SLIM\",\"BOOTCUCT\",\"CROP\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-08 13:50:33', NULL, NULL),
(44, 'Cintura por categoria', 'CINTURA', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]}', '[\"ALTO\",\"MEDIO\",\"BAJO\"]', 1, '2025-05-08 13:51:19', NULL, NULL),
(45, 'Silueta por categoria', 'SILUETA', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]}', '[\"MONO\",\"CAMISERO\",\"CRUZADO\",\"LENCERO\",\"KIMONO\",\"PLAYERO\",\"GLAM\",\"CORTE\",\"ENTALLADO\",\"SPORT\",\"SAFARY\",\"RECTO\",\"OVER SIZE\",\"LAPIZ\"]', 1, '2025-05-08 13:53:03', NULL, NULL),
(46, 'Cierre por categoria', 'CIERRE', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]}', '[\"BOTONES\",\"BROCHES\",\"VELCRO\",\"CREMALLERA\",\"SIN BROCHE\"]', 1, '2025-05-08 13:53:58', NULL, NULL),
(47, 'Galga por categoria', 'CATEGORIAS', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]}', '[\"GRUESO\",\"DELGADO\",\"5\",\"7\",\"10\",\"12\"]', 1, '2025-05-08 13:56:15', NULL, NULL),
(48, 'Tipo de galga por categoria', 'TIPO_GALGA', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"GALGA\",\"value\":\"5\"}]},\"then_allow\":[\"CANALE\",\"PONTELLE\",\"CROCHET\",\"JERSEY\",\"JAQUARD\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"GALGA\",\"value\":\"7\"}]},\"then_allow\":[\"CANALE\",\"PONTELLE\",\"CROCHET\",\"JERSEY\",\"JAQUARD\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"10\"}]},\"then_allow\":[\"CANALE\",\"PONTELLE\",\"CROCHET\",\"JERSEY\",\"JAQUARD\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"12\"}]},\"then_allow\":[\"CANALE\",\"PONTELLE\",\"CROCHET\",\"JERSEY\",\"JAQUARD\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-08 13:59:56', NULL, NULL),
(49, 'Tipo de tejido por categoria', 'TIPO_TEJIDO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"CIRCULAR\",\"BURDA\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"PLANTO\",\"PUNTO\",\"CIRCULAR\",\"BURDA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"CIRCULAR\",\"BURDA\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"PUNTO\",\"CIRCULAR\",\"BURDA\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"PUNTO\",\"CIRCULAR\",\"BURDA\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"CIRCULAR\",\"BURDA\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"NO TEJIDO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"PLANO\",\"PUNTO\",\"NO TEJIDO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-08 14:05:41', NULL, NULL),
(50, 'Base textil por tipo de fibra y categoria', 'BASE_TEXTIL', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSA\",\"LANA\",\"RAYON VISCOSA\",\"TENCEL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSA\",\"LANA\",\"RAYON VISCOSA\",\"TENCEL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"VISCOSA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LANA\",\"VISCOSA\",\"VISCOSA\",\"RAYON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LANA\",\"VISCOSA\",\"RAYON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSAS\",\"LANA\",\"DENIM\",\"RAYON VISCOSA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSA\",\"LANA\",\"RAYON VISCOSA\",\"TENCEL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSA\",\"RAYON\",\"RAYON VISCOSA\",\"TENCEL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"VISCOSA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"NATURAL\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"NATURAL\",\"ALGODON\",\"LINO\",\"VISCOSA\",\"DENIM\",\"RAYON VISCOSA\",\"TENCEL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"POLIESTER\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\",\"LUREX\",\"ACRILICO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\",\"LUREX\",\"ACRILICO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\",\"ACRILICO\",\"PU\",\"VINILO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"POLIESTER\",\"SPANDEX\",\"POLIAMIDA\",\"PU\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"POLIESTER\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"TIPO_DE_FIBRA\",\"value\":\"SINTETICA\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDA\"}]},\"then_allow\":[\"POLIESSTER\",\"SPANDEX\",\"POLIAMIDA\",\"PU\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-08 14:28:01', '2025-06-19 16:34:23', NULL),
(51, 'Subdetalles por detalles y categorias', 'SUB_DETALLES', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"ACCESORIOS\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"CADENAS\",\"CINTURON\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"BLUSAS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISAS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CAMISETAS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PUNTO\"}]},\"then_allow\":[\"BOLERO\",\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"ESTAMPADO\",\"BORDADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FELPA\"}]},\"then_allow\":[\"LAVADO\",\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"ESTAMPADO\",\"BORDADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"VESTIDOS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"PANTALONES\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\",\"ALFORZAS\",\"POSICIONAL\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"JEANS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"DETALLES\",\"value\":\"MANUALIDAD\"},{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"APLIQUE\",\"BORLAS\",\"MILLARE\",\"HERRAJES\",\"BORDADO\",\"ESTAMPADO\",\"PICOETA\",\"ENCAUCHADO\"]}],\"default_allow\":[]}', '[]', 1, '2025-05-08 15:09:18', NULL, NULL),
(52, 'Forro por categoria', 'FORRO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"SI TIENE\",\"NO TIENE\"]},{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"FALDAS\"}]},\"then_allow\":[\"SI TIENE\",\"NO TIENE\"]}],\"default_allow\":[\"NO TIENE\"]}', '[]', 1, '2025-05-08 15:18:47', NULL, NULL),
(53, 'Composicion del Forro si tiene forro', 'COMP_FORRO_1', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"FORRO\",\"value\":\"SI TIENE\"}]}', '[\"ACRILICO\",\"ALGODON\",\"ALGODON MEZCLAS\",\"ALGODON TANGUIS\",\"ANGORA\",\"CUERO\",\"ELASTANO\",\"ELASTOMERO\",\"GAMUZA\",\"LANA\",\"LINO\",\"LUREX\",\"LYCRA\",\"MODAL\",\"NYLON\",\"PLASTICO\",\"POLIAMIDA\",\"POLIAMIDA\",\"POLIPROPILENO\",\"POLIESTER\",\"POLIESTER MEZCLAS\",\"POLIESTER MICROFIBRA\",\"POLIESTER VISCOSA\",\"POLIURETANO\",\"POLIVINYL\",\"RAYON\",\"RAYON VISCOSA\",\"SEDA\",\"SINTETICO\",\"SPANDEX\",\"TENCEL\",\"VISCOSA\"]', 1, '2025-05-08 15:28:53', '2025-05-12 15:01:56', NULL),
(54, 'Composicion del Forro 1 si tiene forro', 'COMP_FORRO_2', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"FORRO\",\"value\":\"SI TIENE\"}]}', '[\"ACRILICO\",\"ALGODON\",\"ALGODON MEZCLAS\",\"ALGODON TANGUIS\",\"ANGORA\",\"CUERO\",\"ELASTANO\",\"ELASTOMERO\",\"GAMUZA\",\"LANA\",\"LINO\",\"LUREX\",\"LYCRA\",\"MODAL\",\"NYLON\",\"PLASTICO\",\"POLIAMIDA\",\"POLIAMIDA\",\"POLIPROPILENO\",\"POLIESTER\",\"POLIESTER MEZCLAS\",\"POLIESTER MICROFIBRA\",\"POLIESTER VISCOSA\",\"POLIURETANO\",\"POLIVINYL\",\"RAYON\",\"RAYON VISCOSA\",\"SEDA\",\"SINTETICO\",\"SPANDEX\",\"TENCEL\",\"VISCOSA\"]', 1, '2025-05-08 15:28:53', NULL, NULL),
(55, 'Composicion del relleno si tiene relleno', 'COMP_RELLENO_1', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"RELLENO\",\"value\":\"SI TIENE\"}]}', '[\"ACRILICO\",\"ALGODON\",\"ALGODON MEZCLAS\",\"ALGODON TANGUIS\",\"ANGORA\",\"CUERO\",\"ELASTANO\",\"ELASTOMERO\",\"GAMUZA\",\"LANA\",\"LINO\",\"LUREX\",\"LYCRA\",\"MODAL\",\"NYLON\",\"PLASTICO\",\"POLIAMIDA\",\"POLIAMIDA\",\"POLIPROPILENO\",\"POLIESTER\",\"POLIESTER MEZCLAS\",\"POLIESTER MICROFIBRA\",\"POLIESTER VISCOSA\",\"POLIURETANO\",\"POLIVINYL\",\"RAYON\",\"RAYON VISCOSA\",\"SEDA\",\"SINTETICO\",\"SPANDEX\",\"TENCEL\",\"VISCOSA\"]', 1, '2025-05-08 15:28:53', '2025-05-12 15:11:11', NULL),
(56, 'Composicion del relleno 2 si tiene relleno', 'COMP_RELLENO_2', '{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"RELLENO\",\"value\":\"SI TIENE\"}]}', '[\"ACRILICO\",\"ALGODON\",\"ALGODON MEZCLAS\",\"ALGODON TANGUIS\",\"ANGORA\",\"CUERO\",\"ELASTANO\",\"ELASTOMERO\",\"GAMUZA\",\"LANA\",\"LINO\",\"LUREX\",\"LYCRA\",\"MODAL\",\"NYLON\",\"PLASTICO\",\"POLIAMIDA\",\"POLIAMIDA\",\"POLIPROPILENO\",\"POLIESTER\",\"POLIESTER MEZCLAS\",\"POLIESTER MICROFIBRA\",\"POLIESTER VISCOSA\",\"POLIURETANO\",\"POLIVINYL\",\"RAYON\",\"RAYON VISCOSA\",\"SEDA\",\"SINTETICO\",\"SPANDEX\",\"TENCEL\",\"VISCOSA\"]', 1, '2025-05-08 15:28:53', '2025-05-12 15:10:37', NULL),
(57, 'Relleno por categoria', 'RELLENO', '{\"type\":\"branched_conditions\",\"branches\":[{\"if\":{\"operator\":\"AND\",\"conditions\":[{\"operator\":\"condition\",\"field\":\"CATEGORIAS\",\"value\":\"CHAQUETAS\"}]},\"then_allow\":[\"SI TIENE\",\"NO TIENE\"]}],\"default_allow\":[\"NO TIENE\"]}', '[]', 1, '2025-05-08 15:37:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'admin', 'CREACION SKU\'S'),
(2, 'editor', 'CREACION SKU\'S'),
(3, 'user', 'CREACION SKU\'S'),
(4, 'GESTOR', 'GESTION O.C.'),
(5, 'APROBADOR_AREA', 'GESTION O.C.'),
(6, 'APROBADOR_GENERAL', 'GESTION O.C.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `notificar` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `role_id_oc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido`, `username`, `correo`, `password`, `role_id`, `fecha_creacion`, `notificar`, `area_id`, `role_id_oc`) VALUES
(1, 'CAMILO', 'VERA PINERES', 'C_VERA', 'fdstecnologia@fueradeserie.com.co', '$2y$10$LwmjSS16cdYEjWqjDERuOO4XyyJy.zFKSl6h2QD/Za766.WrmtVKe', 1, '2025-06-10', 1, 14, 6),
(10, 'Camilo', 'Piñeres', 'C-VERA', 'camilov0310@gmail.com', '$2y$10$EjyFS9yJHBZpeoUHFjJmp.yr9nKaBSjQVAe/k3x3n2OxK8bdfAKFC', NULL, NULL, NULL, 14, 4),
(11, 'Camilo', 'X', 'cvera', 'camilotechgrow@gmail.com', '$2y$10$yjwZed7z/X74y7OyJeR.4egIInvTUAEVoYmOEyjz1g1HgpJpNkwSi', 1, NULL, 0, 14, 5),
(12, 'EDGARD', 'DIAZ', 'E_DIAZ', 'sistemas@fueradeserie.com.co', '$2y$10$Z50ISJeF.iNDrSa9u4BoWu4mG1OyUtw7Ewov9p4LQLO1EKg../PD.', 1, '2025-06-10', 0, NULL, NULL),
(14, 'Leidy', 'Argotte', 'Largotte', 'asistentediseno@fds.com.co', '$2y$10$va6NuqE6d9di4Plrmrpju.9FCyKmALh6hbaTgGdCIE/yi3504Cwf.', 1, '2025-05-23', 1, NULL, NULL),
(15, 'Lady', 'Marin', 'Lmarin', 'asistentediseno2@fds.com.co', '$2y$10$Aj6iiDJMbztvu9r/603I7up8Z5LsmRxe6cbaAPhPLwL7ns/hzpYUW', 2, '2025-05-23', 1, NULL, NULL),
(16, 'Uriel', 'Parada', 'Uparada', 'datosmaestros@fueradeserie.com.co', '$2y$10$zHwtmbXT43KiKerBvdNEde1qPOsX582yHs16FAsoue39IR0ZQC6am', 3, '2025-05-23', 1, NULL, NULL),
(17, 'Anderson', 'Sanchez', 'Asanchez', 'directorsupplychain@fueradeserie.com.co', '$2y$10$YAvHbAd03Dy/L.U0sYdA7uqSu1UOLQvhI5whzwBOJLZHb44XN7ylq', 3, '2025-05-23', 1, NULL, NULL),
(18, 'Olga', 'Sanabria', 'Osanabria', 'directorcomercioexterior@fueradeserie.com.co', '$2y$10$V45LlQ8iiuUe50xwNZZR..9KxjnlFtisBmjyrbxrS8BMybaFgZv/u', 3, '2025-05-23', 1, NULL, NULL),
(50, 'JOHANA', 'DIAZ HERNANDEZ', 'JDH_DIAZ', 'contabilidad4@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(51, 'ANGELA YOHANA', 'GUTIERREZ GUZMAN', 'A_GUTIERREZ', 'Liderderecibocedi@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(52, 'NELSON', 'BERMUDEZ', 'N-BERMUDEZ', 'liderfuncionalsd@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(53, 'FERNANDO', 'PARADA', 'F-PARADA', 'datosmaestros@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(54, 'HUMANA', 'GESTION', 'GH-GESTION', 'camilov0310@gmail.com', '', NULL, '2025-06-10', 0, 14, 4),
(55, 'FABIOLA', 'PASTRAN', 'F-PASTRAN', 'gerenciaadm@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(56, 'MARLEN', 'GALVIS', 'M-GALVIS', 'Planillas3@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(57, 'BRAYAN STEVEN', 'CELEITA GOMEZ', 'B_CELEITA', 'asistentecomercio@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(58, 'FABIAN', 'HUERFANO', 'F-HUERFANO', 'supervisorcedi1@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(59, 'JUAN DAVID', 'RODRIGUEZ GARZON', 'J-RODRIGUEZ', 'auxiliarcomercial@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(60, 'WILSON', 'ALGARRA', 'W-ALGARRA', 'ocumplimiento@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(61, 'MARIA FERNEY', 'AREVALO MEDINA', 'M-AREVALO', 'asistenteadm@ivasof.com', '', NULL, '2025-06-10', NULL, NULL, NULL),
(62, 'ROJAS PELAEZ', 'DIANA MARCELA', 'R-DIANA', 'coordinador1@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(63, 'EDGAR MAURICIO', 'NEIRA PAIPA', 'EMNP_NEIRA', 'coordinador3@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(64, 'OLGA JANNETH', 'SANABRIA RODRIGUEZ', 'O-SANABRIA', 'directorcomercioexterior@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(65, 'EDWARD', 'GARZON', 'E_GARZON', 'especialistalogisticosd@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(66, 'ANGELA', 'ERAZO', 'A_ERAZO', 'mercadeo@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(67, 'AMANDA', 'VARGAS', 'A-VARGAS', 'contador@ivasof.com', '', NULL, '2025-06-10', NULL, NULL, NULL),
(68, 'ALEXANDER', 'CASTILLO', 'J_CASTILLO', 'auditoria3@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(69, 'KAREN', 'CANTOR', 'K_CANTOR', 'analistamercadeo@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(70, 'XIOMARA', 'MARIN', 'X_MARIN', 'asistentediseno2@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(71, 'MARIBEL', 'BAUTISTA GARCIA', 'M_BAUTISTA', 'contador@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(72, 'BRAYAN ANDERSON', 'SANCHEZ', 'A_SANCHEZ', 'directorcedi@fueradeserie.com.co', '$2y$10$DAKCuSUUqsnUyGI.FNmM.uKAmojBB4vTI5/zNhswAqbu6hkz6dq.u', 3, '2025-06-10', 0, 2, 5),
(73, 'MANUEL', 'MORILLO', 'M_MORILLO', NULL, '', NULL, '2025-06-10', NULL, NULL, NULL),
(74, 'ROBINSON JAVIER', 'GARAVITO GRANADOS', 'R_GARAVITO', 'coorauditoria@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(75, 'JAZMIN', 'BERNAL', 'J-BERNAL', 'sistemas@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(76, 'JOHANA JIMENA', 'SANCHEZ BARRANTES', 'J_SANCHEZ', 'auxiliarcomercial@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(77, 'LEIDY TATIANA', 'ESCOBAR RUBIO', 'L_ESCOBAR', 'tiendavirtual1@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(78, 'CINDY PAOLA', 'GUZMAN BAQUERO', 'C_GUZMAN', 'analistaecommerce@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(79, 'SERGIO DAVID', 'PAEZ', 'S_PAEZ', 'Auditoria4@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(80, 'PAULA ANDREA', 'ALVARADO', 'P_ALVARADO', 'servicioalcliente@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(81, 'JUAN CARLOS', 'LOZANO', 'J_LOZANO', 'contraloria@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(82, 'ANGIE CAROLINA', 'RODRIGUEZ CERPA', 'A_RODRIGUEZ', 'crm@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(83, 'DUVAN', 'TELLEZ', 'D_TELLEZ', 'coordinadorgaf@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(84, 'YINA LUDIVIA', 'PINEDA RODRIGUEZ', 'Y_PINEDA', 'auxiliarcontable@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(85, 'DERLY PAOLA', 'RODRIGUEZ CASTELLANOS', 'P_RODRIGUEZ', 'camilov0310@gmail.com\r\n', '', NULL, '2025-06-10', NULL, 14, 4),
(87, 'JOHANA', 'ARGOTTE', 'J_ARGOTTE', 'asistentediseno@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(88, 'YEILY NATALIA', 'LOPEZ DIAZ', 'Y_LOPEZ', 'camilov0310@gmail.com', '', NULL, '2025-06-10', NULL, 14, 4),
(89, 'JEISON', 'ESTUPINAN', 'J_ESTUPINAN', 'auditoria2@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(90, 'CLAUDIA', 'MONTANO', 'C_MONTANO', 'auxiliaralmacen@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(91, 'SEBASTIAN', 'ORJUELA GARCIA', 'S_ORJUELA', 'monitoreo@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(92, 'MIGUEL ANGEL', 'MAHECHA GOMEZ', 'M_MAHECHA', NULL, '', NULL, '2025-06-10', NULL, NULL, NULL),
(93, 'DAVID SANTIAGO', 'DIAZ RIVERO', 'D_DIAZ', 'Inventarioscedi@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(94, 'ROCIO', 'RODRIGUEZ', 'S_VELOZA', 'coordinadorproducto@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(95, 'DAYANA', 'FIGUEROA', 'D_FIGUEROA', 'auxiliarcontraloria@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(96, 'EDNA ROCIO', 'RODRIGUEZ', 'R_RODRIGUEZ', 'coordinadorproducto@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(98, 'ALEXANDRA', 'BOHORQUEZ GARCIA', 'A_BOHORQUEZ', 'directorcomercial@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(99, 'LISBETH', 'PATINO SANCHEZ', 'L_PATINO', 'coordinador3@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(100, 'JANETH', 'SANTANA PARADA', 'J_SANTANA', 'coordinadorcontable@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(101, 'DIANA MARISOL', 'LEAL RODRIGUEZ', 'D_LEAL', 'asistenteadm@vamis.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(102, 'DANIEL', 'DUENAS', 'D_DUENAS', 'asistentecomercio@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(103, 'CARMEN ANDREA', 'PRADA BOTACHE', 'C_PRADA', 'mediospago@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(104, 'DIEGO FERNANDO', 'RAMIREZ BENITEZ', 'D_RAMIREZ', 'analistaproducto3@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(105, 'CRISTIAN JULIAN WILFREDO', 'CARDENAS PARRA', 'C_CARDENAS', 'helpdesk2@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(106, 'HAROLD', 'ALVIS MALAGON', 'H_ALVIS', 'aprendiztic@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(107, 'ANGELA YURITZA', 'AYALA GARZON', 'A_AYALA', 'aprendizproducto@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(108, 'EDWIN ARTURO', 'BUITRAGO MORA', 'E_BUITRAGO', 'productocompras@fueradeserie.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(109, 'NATALIA', 'BERNAL GONZALEZ', 'N_BERNAL', 'aprendizcontable@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(110, 'CESAR ESTIBEN', 'RAMIREZ CHINCHILLA', 'C_RAMIREZ', 'mediospago@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(111, 'LEIDY', 'CABALLERO', 'L_CABALLERO', 'asistenteproduccion@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(112, 'YEIMY ALEXANDRA', 'BUSTOS BELTRAN', 'Y_BUSTOS', 'planillas@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL),
(113, 'KAREN JOHANA', 'SALCEDO LOPEZ', 'K_SALCEDO', 'ventaswhatsapp@fds.com.co', '', NULL, '2025-06-10', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura para la vista `cargas`
--
DROP TABLE IF EXISTS `cargas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`services`@`localhost` SQL SECURITY DEFINER VIEW `cargas`  AS SELECT `catalogo_disenos`.`id` AS `id`, `catalogo_disenos`.`SAP` AS `SAP`, `catalogo_disenos`.`YEAR` AS `YEAR`, `catalogo_disenos`.`MES` AS `MES`, `catalogo_disenos`.`OCASION_DE_USO` AS `OCASION_DE_USO`, `catalogo_disenos`.`NOMBRE` AS `NOMBRE`, `catalogo_disenos`.`MODULO` AS `MODULO`, `catalogo_disenos`.`TEMPORADA` AS `TEMPORADA`, `catalogo_disenos`.`CAPSULA` AS `CAPSULA`, `catalogo_disenos`.`CLIMA` AS `CLIMA`, `catalogo_disenos`.`TIENDA` AS `TIENDA`, `catalogo_disenos`.`CLASIFICACION` AS `CLASIFICACION`, `catalogo_disenos`.`CLUSTER` AS `CLUSTER`, `catalogo_disenos`.`PROVEEDOR` AS `PROVEEDOR`, `catalogo_disenos`.`CATEGORIAS` AS `CATEGORIAS`, `catalogo_disenos`.`SUBCATEGORIAS` AS `SUBCATEGORIAS`, `catalogo_disenos`.`DISENO` AS `DISENO`, `catalogo_disenos`.`DESCRIPCION` AS `DESCRIPCION`, `catalogo_disenos`.`MANGA` AS `MANGA`, `catalogo_disenos`.`TIPO_MANGA` AS `TIPO_MANGA`, `catalogo_disenos`.`PUNO` AS `PUNO`, `catalogo_disenos`.`CAPOTA` AS `CAPOTA`, `catalogo_disenos`.`ESCOTE` AS `ESCOTE`, `catalogo_disenos`.`LARGO` AS `LARGO`, `catalogo_disenos`.`CUELLO` AS `CUELLO`, `catalogo_disenos`.`TIRO` AS `TIRO`, `catalogo_disenos`.`BOTA` AS `BOTA`, `catalogo_disenos`.`CINTURA` AS `CINTURA`, `catalogo_disenos`.`SILUETA` AS `SILUETA`, `catalogo_disenos`.`CIERRE` AS `CIERRE`, `catalogo_disenos`.`GALGA` AS `GALGA`, `catalogo_disenos`.`TIPO_GALGA` AS `TIPO_GALGA`, `catalogo_disenos`.`COLOR_FDS` AS `COLOR_FDS`, `catalogo_disenos`.`NOM_COLOR` AS `NOM_COLOR`, `catalogo_disenos`.`GAMA` AS `GAMA`, `catalogo_disenos`.`PRINT` AS `PRINT`, `catalogo_disenos`.`TALLAS` AS `TALLAS`, `catalogo_disenos`.`TIPO_TEJIDO` AS `TIPO_TEJIDO`, `catalogo_disenos`.`TIPO_DE_FIBRA` AS `TIPO_DE_FIBRA`, `catalogo_disenos`.`BASE_TEXTIL` AS `BASE_TEXTIL`, `catalogo_disenos`.`DETALLES` AS `DETALLES`, `catalogo_disenos`.`SUB_DETALLES` AS `SUB_DETALLES`, `catalogo_disenos`.`GRUPO` AS `GRUPO`, `catalogo_disenos`.`INSTRUCCION_DE_LAVADO_1` AS `INSTRUCCION_DE_LAVADO_1`, `catalogo_disenos`.`INSTRUCCION_DE_LAVADO_2` AS `INSTRUCCION_DE_LAVADO_2`, `catalogo_disenos`.`INSTRUCCION_DE_LAVADO_3` AS `INSTRUCCION_DE_LAVADO_3`, `catalogo_disenos`.`INSTRUCCION_DE_LAVADO_4` AS `INSTRUCCION_DE_LAVADO_4`, `catalogo_disenos`.`INSTRUCCION_DE_LAVADO_5` AS `INSTRUCCION_DE_LAVADO_5`, `catalogo_disenos`.`INSTRUCCION_BLANQUEADO_1` AS `INSTRUCCION_BLANQUEADO_1`, `catalogo_disenos`.`INSTRUCCION_BLANQUEADO_2` AS `INSTRUCCION_BLANQUEADO_2`, `catalogo_disenos`.`INSTRUCCION_BLANQUEADO_3` AS `INSTRUCCION_BLANQUEADO_3`, `catalogo_disenos`.`INSTRUCCION_BLANQUEADO_4` AS `INSTRUCCION_BLANQUEADO_4`, `catalogo_disenos`.`INSTRUCCION_BLANQUEADO_5` AS `INSTRUCCION_BLANQUEADO_5`, `catalogo_disenos`.`INSTRUCCION_SECADO_1` AS `INSTRUCCION_SECADO_1`, `catalogo_disenos`.`INSTRUCCION_SECADO_2` AS `INSTRUCCION_SECADO_2`, `catalogo_disenos`.`INSTRUCCION_SECADO_3` AS `INSTRUCCION_SECADO_3`, `catalogo_disenos`.`INSTRUCCION_SECADO_4` AS `INSTRUCCION_SECADO_4`, `catalogo_disenos`.`INSTRUCCION_SECADO_5` AS `INSTRUCCION_SECADO_5`, `catalogo_disenos`.`INSTRUCCION_PLANCHADO_1` AS `INSTRUCCION_PLANCHADO_1`, `catalogo_disenos`.`INSTRUCCION_PLANCHADO_2` AS `INSTRUCCION_PLANCHADO_2`, `catalogo_disenos`.`INSTRUCCION_PLANCHADO_3` AS `INSTRUCCION_PLANCHADO_3`, `catalogo_disenos`.`INSTRUCCION_PLANCHADO_4` AS `INSTRUCCION_PLANCHADO_4`, `catalogo_disenos`.`INSTRUCCION_PLANCHADO_5` AS `INSTRUCCION_PLANCHADO_5`, `catalogo_disenos`.`INSTRUCC_CUIDADO_TEXTIL_PROF_1` AS `INSTRUCC_CUIDADO_TEXTIL_PROF_1`, `catalogo_disenos`.`INSTRUCC_CUIDADO_TEXTIL_PROF_2` AS `INSTRUCC_CUIDADO_TEXTIL_PROF_2`, `catalogo_disenos`.`INSTRUCC_CUIDADO_TEXTIL_PROF_3` AS `INSTRUCC_CUIDADO_TEXTIL_PROF_3`, `catalogo_disenos`.`INSTRUCC_CUIDADO_TEXTIL_PROF_4` AS `INSTRUCC_CUIDADO_TEXTIL_PROF_4`, `catalogo_disenos`.`INSTRUCC_CUIDADO_TEXTIL_PROF_5` AS `INSTRUCC_CUIDADO_TEXTIL_PROF_5`, `catalogo_disenos`.`COMPOSICION_1` AS `COMPOSICION_1`, `catalogo_disenos`.`%_COMP_1` AS `%_COMP_1`, `catalogo_disenos`.`COMPOSICION_2` AS `COMPOSICION_2`, `catalogo_disenos`.`%_COMP_2` AS `%_COMP_2`, `catalogo_disenos`.`COMPOSICION_3` AS `COMPOSICION_3`, `catalogo_disenos`.`%_COMP_3` AS `%_COMP_3`, `catalogo_disenos`.`COMPOSICION_4` AS `COMPOSICION_4`, `catalogo_disenos`.`%_COMP_4` AS `%_COMP_4`, `catalogo_disenos`.`TOT_COMP` AS `TOT_COMP`, `catalogo_disenos`.`FORRO` AS `FORRO`, `catalogo_disenos`.`COMP_FORRO_1` AS `COMP_FORRO_1`, `catalogo_disenos`.`%_FORRO_1` AS `%_FORRO_1`, `catalogo_disenos`.`COMP_FORRO_2` AS `COMP_FORRO_2`, `catalogo_disenos`.`%_FORRO_2` AS `%_FORRO_2`, `catalogo_disenos`.`TOT_FORRO` AS `TOT_FORRO`, `catalogo_disenos`.`RELLENO` AS `RELLENO`, `catalogo_disenos`.`COMP_RELLENO_1` AS `COMP_RELLENO_1`, `catalogo_disenos`.`%_RELLENO_1` AS `%_RELLENO_1`, `catalogo_disenos`.`COMP_RELLENO_2` AS `COMP_RELLENO_2`, `catalogo_disenos`.`%_RELLENO_2` AS `%_RELLENO_2`, `catalogo_disenos`.`TOT_RELLENO` AS `TOT_RELLENO`, `catalogo_disenos`.`XX` AS `XX`, `catalogo_disenos`.`usuario` AS `usuario`, `catalogo_disenos`.`fecha_creacion` AS `fecha_creacion`, `catalogo_disenos`.`precio_compra` AS `precio_compra`, `catalogo_disenos`.`costo` AS `costo`, `catalogo_disenos`.`precio_venta` AS `precio_venta`, `catalogo_disenos`.`tipo` AS `tipo` FROM `catalogo_disenos` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bitacora_cambios`
--
ALTER TABLE `bitacora_cambios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `borradores_carga_manual`
--
ALTER TABLE `borradores_carga_manual`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `capsulas`
--
ALTER TABLE `capsulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo_disenos`
--
ALTER TABLE `catalogo_disenos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `oc_unica` (`EBELN`),
  ADD KEY `fk_usuarios_ernam` (`ERNAM`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `reglas_dependencia`
--
ALTER TABLE `reglas_dependencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campo_destino` (`campo_destino`),
  ADD KEY `idx_es_activa` (`es_activa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `fk_role_id_oc` (`role_id_oc`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `bitacora_cambios`
--
ALTER TABLE `bitacora_cambios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `borradores_carga_manual`
--
ALTER TABLE `borradores_carga_manual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `capsulas`
--
ALTER TABLE `capsulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo_disenos`
--
ALTER TABLE `catalogo_disenos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reglas_dependencia`
--
ALTER TABLE `reglas_dependencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `borradores_carga_manual`
--
ALTER TABLE `borradores_carga_manual`
  ADD CONSTRAINT `borradores_carga_manual_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `ordenes_compra`
--
ALTER TABLE `ordenes_compra`
  ADD CONSTRAINT `fk_usuarios_ernam` FOREIGN KEY (`ERNAM`) REFERENCES `users` (`username`);

--
-- Filtros para la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role_id_oc` FOREIGN KEY (`role_id_oc`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
