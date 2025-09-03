-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 12:15 PM
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
-- Database: `loja_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrinho`
--

CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `produtoId` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrinho`
--

INSERT INTO `carrinho` (`id`, `userId`, `produtoId`, `quantidade`) VALUES
(6, 6, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `imagem`, `stock`) VALUES
(1, 'Smartphone XYT', 'Ecrã OLED 6.7\", 128GB, Câmera 50MP', 799.99, 'https://bigamist.pt/zimag_loja/Samsung2.jpg', 20),
(2, 'Portátil Ultra 2', 'Intel i7, 16GB RAM, SSD 512GB, Tela Retina', 1499.00, 'https://bigamist.pt/zimag_loja/Portatil.jpg', 5),
(3, 'Auriculares Bluetooth', 'Cancelamento de ruído ativo, 20h bateria', 199.50, 'https://bigamist.pt/zimag_loja/Auriculares.jpg', 15),
(4, 'Smartwatch Pro', 'Monitorização cardíaca, GPS, 5 dias bateria', 299.99, 'https://bigamist.pt/zimag_loja/Smartwatch.jpg', 8),
(11, 'Smartphone XYX', 'Ecrã OLED 6.8\", 236GB, Câmera 50MP', 1299.99, 'https://bigamist.pt/zimag_loja/Samsung2.jpg', 6),
(12, 'Portátil Ultra', 'Intel i9, 64GB RAM, SSD 1024GB, Tela Retina', 1999.00, 'https://bigamist.pt/zimag_loja/Portatil.jpg', 4),
(13, 'Auriculares Bluetooth', 'Cancelamento de ruído ativo, 40h bateria', 299.50, 'https://bigamist.pt/zimag_loja/Auriculares.jpg', 10),
(14, 'Smartwatch Pro', 'Monitorização cardíaca, GPS, 6 dias bateria', 399.99, 'https://bigamist.pt/zimag_loja/Smartwatch.jpg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updateAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `description`, `createdAt`, `updateAt`) VALUES
(1, 'ADMIN', '2025-05-20 14:16:12', '2025-05-20 14:16:12'),
(2, 'CLIENT', '2025-05-20 14:16:12', '2025-05-20 14:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `utilizador`
--

CREATE TABLE `utilizador` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telemovel` varchar(9) DEFAULT NULL,
  `nif` varchar(9) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `RoleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilizador`
--

INSERT INTO `utilizador` (`id`, `username`, `email`, `password`, `telemovel`, `nif`, `token`, `active`, `created_at`, `updated_at`, `RoleID`) VALUES


--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `produtoId` (`produtoId`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `utilizador`
--
ALTER TABLE `utilizador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nif` (`nif`),
  ADD KEY `RoleID` (`RoleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utilizador`
--
ALTER TABLE `utilizador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `utilizador` (`id`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produtoId`) REFERENCES `produtos` (`id`);

--
-- Constraints for table `utilizador`
--
ALTER TABLE `utilizador`
  ADD CONSTRAINT `utilizador_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
