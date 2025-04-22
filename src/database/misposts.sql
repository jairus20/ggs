-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 05:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `misposts`
--

-- --------------------------------------------------------

--
-- Table structure for table `archivos`
--

CREATE TABLE `archivos` (
  `idarchivo` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_guardado` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_subida` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `idcomment` int(11) NOT NULL,
  `description` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `idpost` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`idcomment`, `description`, `fecha`, `idpost`, `usuario_id`) VALUES
(1, 'Este es un comentario de prueba', '2025-04-20 08:58:07', 1, 1),
(2, 'Otro comentario de prueba', '2025-04-20 08:58:07', 2, 1),
(6, 'h', '2025-04-20 09:06:57', 1, 2),
(10, 'xfgdjkhgfkh', '2025-04-20 10:24:34', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `idpost` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`idpost`, `title`, `description`, `fecha`, `usuario_id`) VALUES
(1, 'Primer post', 'Contenido del primer post', '2025-04-20 08:58:07', 1),
(2, 'Segundo post', 'Contenido del segundo post', '2025-04-20 08:58:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `habilitado` bit(1) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`idusuario`, `email`, `name`, `habilitado`, `password`) VALUES
(1, 'holi@mispostsgrupo5.com', 'holi', b'1', '$2y$10$p8bMQrGcYwrODWvG8G89P.Q6aLrD1Ku1XI44gGIsnTRO/ccBAtDre'),
(2, 'a@gmail.com', 'a', b'1', '$2y$10$Ui4ADbxF/k4zsrlHJc6sXufpRZLSnmqlBpXcfvDEfugZAVb4WcV4m');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archivos`
--
ALTER TABLE `archivos`
  ADD PRIMARY KEY (`idarchivo`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`idcomment`),
  ADD KEY `idpost` (`idpost`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idpost`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `archivos`
--
ALTER TABLE `archivos`
  MODIFY `idarchivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `archivos`
--
ALTER TABLE `archivos`
  ADD CONSTRAINT `archivos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
