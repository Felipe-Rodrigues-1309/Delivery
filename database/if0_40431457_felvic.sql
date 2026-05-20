-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql312.infinityfree.com
-- Tempo de geração: 18/05/2026 às 21:16
-- Versão do servidor: 11.4.7-MariaDB
-- Versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `if0_40431457_felvic`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) DEFAULT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `ponto_de_referencia` varchar(255) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `endereco`
--

INSERT INTO `endereco` (`id`, `rua`, `numero`, `bairro`, `cidade`, `ponto_de_referencia`, `usuario`) VALUES
(7, 'teste', '50', 'centro', 'sobral0750', 'ap', 'teste0743'),
(8, 'teste0808', '50', 'centro', 'sss', '2', 'f5'),
(2, 'teste', '50', 'centro', 'sobral0750', 'ap1147', 'Felipe'),
(9, 'rua 1', '1', 'centro', 'sobral', '1', 'Felipe f5'),
(11, 'teste', '50', 'centro', 'sobral', '50', 'ggggggteste'),
(13, 'RANDAL POMPEU DE SABOYA MAGALH', '50', '50', 'Sobral', 'Lp', 'Felipe'),
(14, 'Paulo Malaquias', '445', 'Jose antonio de Vasconcelos ', 'Groairas', '', 'Iasmim Rodrigues');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario` int(10) UNSIGNED NOT NULL,
  `item` text NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Em preparo',
  `tempo_preparo` int(11) DEFAULT NULL,
  `saiu_entrega` tinyint(1) NOT NULL DEFAULT 0,
  `telefone_cliente` varchar(25) DEFAULT NULL,
  `pagamento` varchar(10) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `ponto_de_referencia` varchar(100) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`id`, `usuario`, `item`, `valor`, `data_pedido`, `status`, `tempo_preparo`, `saiu_entrega`, `telefone_cliente`, `pagamento`, `rua`, `bairro`, `numero`, `cidade`, `ponto_de_referencia`, `nome`) VALUES
(1, 2, 'ðŸ›’ *NOVO PEDIDO*\r\n\r\nðŸ” PICANHA3\r\nQuantidade: 1\r\nPreÃ§o: R$ 61,99\r\nAdicionais:\r\n - trocar baiao (R$ 2,00)\r\n\r\n *Total: R$ 61,99*', '61.99', '2026-03-13 02:53:53', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'ðŸ›’ *NOVO PEDIDO*\r\n\r\nðŸ” PICANHA3\r\nQuantidade: 1\r\nPreÃ§o: R$ 59,99\r\n\r\n *Total: R$ 59,99*', '59.99', '2026-03-13 03:01:55', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 2, '1x PICANHA', '62.99', '2026-03-13 03:04:04', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 2, '1x PICANHA3', '109.99', '2026-03-19 02:48:49', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 2, '1x PICANHA2', '62.99', '2026-03-19 02:51:27', 'Pronto', 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 2, '1x PICANHA3', '59.99', '2026-03-19 02:59:40', 'Em preparo', NULL, 0, '88988188728', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 2, '1x PICANHA3', '129.99', '2026-03-19 03:02:13', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 6, '1x PICANHA2', '61.99', '2026-03-22 03:57:21', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 6, '1x PICANHA3', '149.99', '2026-03-22 04:14:21', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 2, '1x PICANHA3, 1x PICANHA3, 1x PICANHA3, 1x PICANHA3, 1x PICANHA3', '669.95', '2026-03-22 04:35:29', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 2, '2x PICANHA3', '123.98', '2026-03-22 04:35:58', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 2, '1x PICANHA', '62.99', '2026-03-22 04:36:29', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 2, '1x PICANHA3, 1x PICANHA', '121.98', '2026-03-22 05:03:41', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, '1x PICANHA2', '61.99', '2026-03-22 05:03:54', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 2, '5x PICANHA', '299.95', '2026-03-22 05:07:14', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 2, '2x PICANHA3', '239.98', '2026-03-22 05:07:26', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 2, '1x PICANHA2', '59.99', '2026-03-22 05:08:04', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 2, '1x PICANHA3', '149.99', '2026-03-22 05:08:23', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 2, '1x PICANHA3', '61.99', '2026-03-22 05:10:33', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 2, '1x PICANHA3, 1x PICANHA3, 1x PICANHA3, 1x PICANHA', '351.96', '2026-03-24 03:27:03', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 2, '1x PICANHA3', '61.99', '2026-03-24 03:28:08', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 2, '1x PICANHA3', '61.99', '2026-03-24 03:28:26', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 2, '1x PICANHA3, 1x PICANHA', '192.98', '2026-03-24 03:30:16', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 2, '1x PICANHA3', '119.99', '2026-03-24 03:37:29', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 2, '1x PICANHA3, 1x PICANHA', '161.98', '2026-03-24 03:54:14', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 2, '1x PICANHA3, 1x PICANHA3', '201.98', '2026-03-24 04:12:56', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 2, '1x PICANHA2', '62.99', '2026-03-24 04:20:52', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 2, '1x PICANHA', '62.99', '2026-03-24 04:24:49', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 7, '1x PICANHA3', '61.99', '2026-03-25 03:16:32', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 2, '1x PICANHA3', '59.99', '2026-03-25 05:17:07', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 7, '1x PICANHA', '62.99', '2026-03-25 05:29:36', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 7, '1x PICANHA', '61.99', '2026-03-25 05:30:13', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 2, '1x PICANHA, 1x PICANHA, 1x PICANHA, 1x PICANHA, 1x PICANHA3', '319.95', '2026-03-25 05:53:01', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 2, '1x PICANHA', '62.99', '2026-03-31 02:15:26', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 17, '1x PICANHA', '61.99', '2026-03-31 03:52:37', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 2, '1x PICANHA', '59.99', '2026-03-31 04:31:25', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 2, '1x PICANHA3', '119.99', '2026-03-31 04:51:12', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 18, '1x PICANHA3', '59.99', '2026-03-31 04:54:30', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 20, '1x PICANHA3', '59.99', '2026-03-31 05:08:05', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 20, '1x PICANHA3', '59.99', '2026-03-31 05:11:36', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 20, '1x PICANHA', '59.99', '2026-03-31 05:13:36', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 2, '1x PICANHA', '59.99', '2026-03-31 05:15:22', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 2, '1x PICANHA', '61.99', '2026-04-01 02:04:53', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 23, '1x PICANHA', '59.99', '2026-04-01 03:08:18', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 23, '1x PICANHA3', '59.99', '2026-04-03 05:05:50', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 23, '1x PICANHA3, 2x PICANHA', '183.97', '2026-04-03 05:12:41', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 23, '1x PICANHA', '62.99', '2026-04-03 05:14:32', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 23, '1x PICANHA3', '89.99', '2026-04-03 05:17:08', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 23, '1x PICANHA3, 1x PICANHA3', '189.98', '2026-04-03 05:20:02', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 23, '10x PICANHA2', '629.90', '2026-04-03 05:21:15', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 24, '15x PICANHA', '944.85', '2026-04-03 05:29:57', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 25, '1x PICANHA', '59.99', '2026-04-03 05:30:27', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 25, '1x PICANHA', '59.99', '2026-04-03 05:35:28', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 26, '1x PICANHA3', '109.99', '2026-04-04 20:32:40', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 26, '1x PICANHA', '61.99', '2026-04-04 20:39:03', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 2, '1x PICANHA, 1x PICANHA3', '209.98', '2026-04-04 21:24:19', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 2, '1x PICANHA3', '61.99', '2026-04-04 21:27:06', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 2, '1x salgado aquino 2', '5.00', '2026-04-06 10:48:48', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 2, '1x salgado aquino 2', '7.00', '2026-04-06 13:13:33', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 2, '1x salgado da aquino', '3.50', '2026-04-07 11:36:22', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 2, '1x salgado aquino 2', '9.00', '2026-04-07 11:37:10', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 9, '1x salgado da aquino, 1x salgado aquino 2', '17.50', '2026-04-07 12:43:49', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 9, '1x salgado da aquino', '3.50', '2026-04-07 12:50:43', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 9, '1x salgado aquino 2', '5.00', '2026-04-07 12:51:29', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 9, '1x salgado aquino 2', '5.00', '2026-04-07 12:51:53', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 9, '1x salgado aquino 2', '5.00', '2026-04-07 12:52:31', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 9, '1x salgado da aquino', '8.50', '2026-04-07 12:55:34', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 10, '1x salgado da aquino, 1x salgado da aquino, 1x salgado da aquino', '15.50', '2026-04-08 10:37:00', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 2, '1x salgado aquino 2, 1x salgado aquino 2, 1x salgado da aquino', '13.50', '2026-04-09 17:32:50', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 2, '1x salgado da aquino, 1x salgado aquino 2', '8.50', '2026-04-10 10:28:05', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 2, '1x salgado da aquino', '3.50', '2026-04-10 10:42:44', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 2, '1x salgado aquino 2', '7.00', '2026-04-16 18:05:40', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 2, '1x salgado aquino 2', '5.00', '2026-04-28 17:06:57', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 2, '3x salgado da aquino', '40.50', '2026-05-12 10:23:46', 'Em preparo', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 2, '1x salgado aquino 2, 1x salgado aquino 2', '16.00', '2026-05-12 13:15:53', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 2, '1x salgado da aquino', '8.50', '2026-05-12 13:48:11', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 2, '1x salgado aquino 2', '5.00', '2026-05-12 14:14:54', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 2, '1x salgado da aquino', '13.50', '2026-05-12 14:21:21', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 2, '1x salgado da aquino', '13.50', '2026-05-12 14:25:27', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 2, '1x salgado da aquino', '3.50', '2026-05-12 14:28:21', 'Em preparo', NULL, 0, NULL, 'Cartão', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 2, '1x salgado aquino 2', '7.00', '2026-05-12 14:28:58', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 2, '1x salgado da aquino', '3.50', '2026-05-12 14:29:58', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 2, '1x salgado da aquino', '3.50', '2026-05-12 14:31:18', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(84, 2, '1x salgado da aquino', '3.50', '2026-05-12 14:31:55', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(85, 2, '1x salgado da aquino', '3.50', '2026-05-12 14:34:55', 'Em preparo', NULL, 0, NULL, 'Cartão', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 2, '1x salgado aquino 2, 1x salgado da aquino', '17.50', '2026-05-12 14:59:57', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 2, '1x salgado da aquino', '3.50', '2026-05-12 15:00:52', 'Em preparo', NULL, 0, NULL, 'Dinheiro', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 2, '1x salgado aquino 2', '9.00', '2026-05-12 15:01:28', 'Em preparo', NULL, 0, NULL, 'Cartão', NULL, NULL, NULL, NULL, NULL, NULL),
(89, 2, '1x salgado da aquino', '8.50', '2026-05-12 15:23:27', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', NULL, NULL, NULL, NULL, NULL),
(90, 2, '1x salgado da aquino, 1x salgado da aquino', '7.00', '2026-05-14 15:33:28', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'teste', 'centro', 50, 'sobral0750', 'ap1147', '[object Object]'),
(91, 2, '1x salgado da aquino', '3.50', '2026-05-14 15:38:03', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', 'centro', 50, 'sobral0750', 'ap1147', ''),
(92, 2, '1x salgado da aquino', '3.50', '2026-05-14 15:42:00', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'teste', 'centro', 50, 'sobral0750', 'ap1147', '[object Object]'),
(93, 2, '3x salgado da aquino', '10.50', '2026-05-14 15:48:33', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', 'centro', 50, 'sobral0750', 'ap1147', 'Felipe'),
(94, 2, '6x salgado aquino 2', '30.00', '2026-05-14 15:53:55', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', 'centro', 50, 'sobral0750', 'ap1147', '[object Object]'),
(95, 2, '1x salgado da aquino', '3.50', '2026-05-14 15:54:39', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', 'centro', 50, 'sobral0750', 'ap1147', 'Felipe'),
(96, 2, '1x salgado da aquino', '3.50', '2026-05-14 17:28:43', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'teste', 'centro', 50, 'sobral0750', 'ap1147', 'Felipe'),
(97, 2, '1x salgado da aquino', '3.50', '2026-05-14 17:33:19', 'Em preparo', NULL, 0, NULL, 'Cartão', 'teste', 'centro', 50, 'sobral0750', 'ap1147', 'Felipe'),
(98, 2, '1x salgado da aquino', '8.50', '2026-05-14 17:37:37', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'teste', 'centro', 50, 'sobral0750', 'ap1147', 'Felipe'),
(99, 11, '1x salgado da aquino', '3.50', '2026-05-14 19:03:19', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'teste', 'centro', 50, 'sobral', '50', 'ggggggteste'),
(100, 13, '1x mistï¿½o a moda', '89.99', '2026-05-14 16:39:22', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(101, 13, '1x maminha completa', '89.99', '2026-05-14 16:56:35', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(102, 13, '1x qwerty', '222.00', '2026-05-14 17:38:14', 'Em preparo', NULL, 0, NULL, 'Vale Alime', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(103, 13, '1x PICANHA', '61.99', '2026-05-14 20:12:01', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(104, 13, '1x maminha completa', '89.99', '2026-05-14 20:12:15', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(105, 13, '1x qwerty, 1x mistï¿½o a moda', '139.99', '2026-05-14 20:14:32', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(106, 13, '1x maminha completa', '89.99', '2026-05-15 08:04:41', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(107, 13, '1x mistï¿½o a moda', '89.99', '2026-05-15 11:28:48', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(108, 13, '4x qwerty', '888.00', '2026-05-15 11:29:48', 'Em preparo', NULL, 0, NULL, 'CartÃ£o', 'RANDAL POMPEU DE SABOYA MAGALH', '50', 50, 'Sobral', 'Lp', 'Felipe'),
(109, 14, '1x mistï¿½o a moda', '89.99', '2026-05-17 11:34:19', 'Em preparo', NULL, 0, NULL, 'Dinheiro', 'Paulo Malaquias', 'Jose antonio de Vasconcelos ', 445, 'Groairas', '', 'Iasmim Rodrigues');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `cod` varchar(10) DEFAULT NULL,
  `item` varchar(20) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `adicional1` varchar(15) DEFAULT NULL,
  `adicional2` varchar(15) DEFAULT NULL,
  `adicional3` varchar(15) DEFAULT NULL,
  `adicional4` varchar(15) DEFAULT NULL,
  `adicional5` varchar(15) DEFAULT NULL,
  `adicional6` varchar(15) DEFAULT NULL,
  `adicional7` varchar(15) DEFAULT NULL,
  `adicional8` varchar(15) DEFAULT NULL,
  `adicional9` varchar(15) DEFAULT NULL,
  `adicional10` varchar(15) DEFAULT NULL,
  `valoradicional1` decimal(10,2) DEFAULT NULL,
  `valoradicional2` decimal(10,2) DEFAULT NULL,
  `valoradicional3` decimal(10,2) DEFAULT NULL,
  `valoradicional4` decimal(10,2) DEFAULT NULL,
  `valoradicional5` decimal(10,2) DEFAULT NULL,
  `valoradicional6` decimal(10,2) DEFAULT NULL,
  `valoradicional7` decimal(10,2) DEFAULT NULL,
  `valoradicional8` decimal(10,2) DEFAULT NULL,
  `valoradicional9` decimal(10,2) DEFAULT NULL,
  `valoradicional10` decimal(10,2) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `cod`, `item`, `valor`, `descricao`, `adicional1`, `adicional2`, `adicional3`, `adicional4`, `adicional5`, `adicional6`, `adicional7`, `adicional8`, `adicional9`, `adicional10`, `valoradicional1`, `valoradicional2`, `valoradicional3`, `valoradicional4`, `valoradicional5`, `valoradicional6`, `valoradicional7`, `valoradicional8`, `valoradicional9`, `valoradicional10`, `imagem`) VALUES
(48, '1', 'salgado da aquino', '3.50', 'tem frango ', 'troca 1', 'troca 2', '', '', '', '', '', '', '', '', '5.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '69b1c947998ae_background.png'),
(49, '2', 'salgado aquino 2', '5.00', 'servido com frango ', 'troca 1', 'troca 2', '', '', '', '', '', '', '', '', '2.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '69b1ca98e9c0d_logo-mktzap.png'),
(50, '30', 'picanha', '59.99', 'baião e batata', 'felipe', '', '', '', '', '', '', '', '', '', '22.99', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a0620b5250d5_Fundo de tela.png'),
(51, '2222', 'qwerty', '50.00', 'qwerty', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a0628deb4120_Captura_de_tela_20260227_105303.png'),
(52, '22223', 'qwerty', '50.00', 'qwerty', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a0629339041a_2026-03-17_16-35.png'),
(53, '222235', 'qwerty', '50.00', 'qwerty', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a06296417aa2_logo-mktzap.png'),
(54, '30', 'qwerty1309', '552.55', '1309', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062a0e8d586_2026-03-17_16-35.png'),
(55, '30', 'qwerty1309', '552.55', '1309', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062a42adc98_Captura_de_tela_20260227_105303.png'),
(56, '30', 'qwerty1309', '552.55', '1309', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062a90708f1_2026-03-17_16-35.png'),
(57, '30', 'qwerty', '222.00', 'qwerty', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062ab9a3dd1_2026-03-17_16-35.png'),
(58, '30', 'qwerty', '222.00', 'qwerty', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062adf4b9da_Captura_de_tela_20260227_105303.png'),
(59, '89', 'maminha completa', '89.99', 'baião e batata frita ', 'trocar batata', '', '', '', '', '', '', '', '', '', '22.99', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062b4bd0d06_Captura_de_tela_20260514_115924.png'),
(60, '89', 'mistão a moda', '89.99', 'baião e batata frita ', 'trocar batata', '', '', '', '', '', '', '', '', '', '22.99', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a062bfd508fb_Captura_de_tela_20260514_115924.png'),
(61, '45', 'Teste celular ', '258.00', 'Gjgjfjfjf', '', '', '', '', '', '', '', '', '', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '6a07e94a20c3c_IMG-20260513-WA0016.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(20) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `email`) VALUES
(1, 'Felipe', '130915', ''),
(2, 'Felipe', '$2y$12$EKwPl35ROjhwi39YYNMrS.oQ3oGnDa.uW9kZLWH88sw0dCtcPSZt2', 'feliphi10@gmail.com'),
(3, 'viviane', '$2y$12$JRznMaN4bFKmUIgVvt4mRec1uE3jEXMmxYQcExmJ8bDJfQActCt8m', 'vivianemelo1309@gmail.com'),
(4, 'Felipe', '$2y$12$c3UO9fcWfE3KCh4LZXxhPOBGOLYmR/oIOX0.9JWDxa58Sd7YdIDuK', 'feliphi13@gmail.com'),
(5, 'Aquino', '$2y$12$IrQN35mC/Fpy4mzbwC38MeZ7nu4V5C/yQBh5trFTQjeuuoYp9TKHa', 'aquino@gmail.com'),
(6, 'Felipe', '$2y$12$K9jg6Q9WL1WYz3qMVTvBQuo4fHiD2NL9qlFUf27pNTYlzJdxtyv.O', 'feliperodrigues@grupof5.com.br'),
(7, 'teste0743', '$2y$12$GEWKG1mAAnkQ3iYoyIEhVO30T4WwcPoYXiMOrUxzlB06DS/VzRaqS', 'teste0743@gmail.com'),
(8, 'f5', '$2y$12$q5ckqS8QUnTzLIkY4uSG2eDDhplbmoKyBxZcEvIoJvGEPW2P78tue', 'testefelipe0807@gmail.com'),
(9, 'Felipe f5', '$2y$12$mf3yWvGW5YuxYmX7JSKFmu0sIQq2vjV0Jl4.znYx5GuIEMuoEfM96', 'feliphi43@gmail.com'),
(10, 'Felipe', '$2y$12$kg0hBRvrCOAHe.w83cVt7uqT/fPhxHUmRiJloGSb955bIrSqYcXYG', 'feliphi1309@gmail.com'),
(11, 'ggggggteste', '$2y$12$SM29/2aWkQ9K.tQ.M2BL6ekMXd9tSqAT1QvqrZm/1FXoaxS8lFi3m', 'teste130915@gmail.com'),
(12, 'Felipe', '$2y$10$Bm.opMIhzqjQGaYJUJl6HeLUkIKviQB4gKkAnS/hKqha/oTQ1QwBu', 'feliphi130915@gmail.com'),
(13, 'Felipe', '$2y$10$3ENVzm6Uc/DDZFG4sbYqMO6SJrh6L7oVFXuVxSJ5.fZN/4eO3QKqW', 'feliphi1@gmail.com'),
(14, 'Iasmim Rodrigues', '$2y$10$P1onVDUx9YSd6wVzvaSb/OarvtehujbPSUMZnkrstXt6dOnMKQ5qG', 'iasmimrodriguesx@gmail.com'),
(15, 'Iasmim Rodrigues', '$2y$10$LpaLLc7HAMzkT1IcF4t.A.7isKHL0ppGCb2Vns7sN7GDfqpLsB2LS', 'franciscaiasm@gmail.com');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
