/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.8-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: delivery
-- ------------------------------------------------------
-- Server version	11.8.8-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES
(1,'PIZZAS'),
(2,'ENTRADAS'),
(3,'CARNES NA BRASA '),
(4,'SUSHI'),
(5,'SOBREMESAS'),
(6,'BEBIDAS'),
(11,'l'),
(12,'aa'),
(13,'h'),
(14,'felipe'),
(15,'maminha'),
(16,'ç'),
(17,'aaa'),
(18,'j'),
(19,'y'),
(20,'okok'),
(21,'testtt'),
(22,'yyy'),
(23,'felipelocal'),
(24,'llll'),
(25,'kjkjkjkj'),
(26,'1'),
(27,'jjj'),
(28,'1309');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `endereco`
--

DROP TABLE IF EXISTS `endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `endereco` (
  `id` int(11) DEFAULT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `ponto_de_referencia` varchar(255) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
INSERT INTO `endereco` VALUES
(7,'teste','50','centro','sobral0750','ap','teste0743'),
(8,'teste0808','50','centro','sss','2','f5'),
(2,'Fazenda marrecas','50','centro','Groaíras','proximo ao valnei','Felipe'),
(9,'rua 1','1','centro','sobral','1','Felipe f5'),
(11,'teste','50','centro','sobral','50','ggggggteste'),
(13,'RANDAL POMPEU DE SABOYA MAGALH','50','50','Sobral','Lp','Felipe'),
(14,'Paulo Malaquias','445','Jose antonio de Vasconcelos ','Groairas','','Iasmim Rodrigues'),
(16,'groairas','50','paulo malaguias','groairas','50','pedro'),
(18,'Fazenda marrecas ','50','Zona rural ','GroaÃ­ras ','PrÃ³ximo ao valnei','Suporte'),
(20,'RANDAL POMPEU DE SABOYA MAGALH','50','Zona Rural','Sobral','','aquino'),
(23,'RANDAL POMPEU DE SABOYA MAGALH','50','Zona Rural','Sobral','','130915'),
(25,'RANDAL POMPEU DE SABOYA MAGALH','50','Zona Rural','Sobral','12','123');
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` int(10) unsigned NOT NULL,
  `item` text NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT current_timestamp(),
  `tempo_preparo` int(11) DEFAULT NULL,
  `saiu_entrega` tinyint(1) NOT NULL DEFAULT 0,
  `telefone_cliente` varchar(25) DEFAULT NULL,
  `pagamento` varchar(10) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `ponto_de_referencia` varchar(100) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `adicionais` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES
(160,2,'1x COCA COLA1 (R$ 59,99)',59.99,'2026-06-27 00:02:36',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(161,2,'1x Picanha completa (R$ 59,99)\r\nAdicionais:\r\n - 1x trocar baiao (R$ 3,00)',62.99,'2026-06-27 00:07:22',NULL,0,NULL,'Cartão','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(162,2,'2x Picanha completa (R$ 119,98)\r\nAdicionais:\r\n - 2x trocar baiao (R$ 6,00)',125.98,'2026-06-27 02:09:02',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(163,2,'1x 1 (R$ 1,00)\r\nAdicionais:\r\n - 1x 1 (R$ 1,00)',2.00,'2026-06-27 04:57:10',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(164,2,'1x 2 (R$ 3,00)\r\nAdicionais:\r\n - 1x 1 (R$ 1,00)\r\n - 1x 2 (R$ 2,00)\r\n - 1x 3 (R$ 3,00)',9.00,'2026-06-27 05:12:19',NULL,0,NULL,'Cartão','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(165,2,'1x feeeeeee (R$ 50,00)\r\nAdicionais:\r\n - 1x adicionar por ? (R$ 1,00)',51.00,'2026-06-27 20:41:30',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL),
(166,2,'1x felipe (R$ 50,00)',50.00,'2026-07-10 02:07:09',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(167,2,'1x 1 (R$ 1,00)\r\nAdicionais:\r\n - 1x 1 (R$ 1,00)',2.00,'2026-07-15 01:42:27',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(168,2,'1x CARNE TESTE 1  (R$ 50,00)',50.00,'2026-07-20 02:02:34',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(169,2,'1x CARNE TESTE 1  (R$ 50,00)',50.00,'2026-07-20 02:02:52',NULL,0,NULL,'Cartão','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(170,2,'1x Picanha completa (R$ 59,99)\r\n\r\n1x CARNE TESTE 1  (R$ 50,00)',109.99,'2026-07-20 03:29:50',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(171,2,'1x Picanha completa (R$ 59,99)\r\n\r\n1x CARNE TESTE 1  (R$ 50,00)\r\n\r\n2x Picanha Completa (R$ 199,98)\r\nAdicionais:\r\n - 2x troca batata (R$ 22,00)',331.97,'2026-07-20 04:12:25',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(172,2,'1x Picanha Completa (R$ 99,99)\r\nAdicionais:\r\n - 1x troca baião (R$ 10,00)\r\n - 1x troca batata (R$ 11,00)',120.99,'2026-07-20 04:12:41',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(173,2,'2x SUCO DE MARACUJA (R$ 15,98)',15.98,'2026-08-08 20:12:18',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(174,2,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-08 20:16:12',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(175,2,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-08 20:21:31',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(176,2,'1x SUCO DE MORANGO (R$ 12,99)\r\n\r\n1x SUCO DE MORANGO (R$ 12,99)',25.98,'2026-08-15 15:14:52',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(177,2,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-16 18:17:44',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(178,2,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-16 18:20:03',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(179,2,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 01:55:10',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(180,2,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 01:59:31',NULL,0,NULL,'Dinheiro','','',0,'','','Felipe',NULL,NULL),
(181,2,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:00:06',NULL,0,NULL,'Dinheiro','','',0,'','','Felipe',NULL,NULL),
(182,20,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:01:58',NULL,0,NULL,'Dinheiro','','',0,'','','aquino','em preparo',NULL),
(183,20,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:02:59',NULL,0,NULL,'Dinheiro','','',0,'','','aquino',NULL,NULL),
(184,20,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:04:30',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','','aquino',NULL,NULL),
(185,21,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:06:01',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','','teste1309',NULL,NULL),
(186,21,'1x Coca cola lata (R$ 6,99)\r\n\r\n1x Coca cola lata (R$ 6,99)',13.98,'2026-08-18 02:08:58',NULL,0,NULL,'Cartão','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','','teste1309',NULL,NULL),
(187,2,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:11:01',NULL,0,NULL,'Cartão','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe',NULL,NULL),
(188,22,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:12:05',NULL,0,NULL,'Dinheiro','null','null',0,'null','null','1309',NULL,NULL),
(189,23,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:17:17',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','','130915',NULL,NULL),
(190,23,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:20:59',NULL,0,NULL,'Dinheiro','','',0,'','','130915',NULL,NULL),
(191,24,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:21:34',NULL,0,NULL,'Dinheiro','','',0,'','','12',NULL,NULL),
(192,25,'1x COCA 1,5 ZERO (R$ 11,99)',11.99,'2026-08-18 02:25:35',NULL,0,NULL,'Dinheiro','','',0,'','','123',NULL,NULL),
(193,25,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:29:40',NULL,0,NULL,'Dinheiro','','',0,'','','123',NULL,NULL),
(194,25,'1x SUCO DE MORANGO (R$ 12,99)',12.99,'2026-08-18 02:31:24',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','','123',NULL,NULL),
(195,25,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:32:16',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','12','123',NULL,NULL),
(196,26,'1x SUCO DE MORANGO (R$ 12,99)\r\n\r\n1x SUCO DE MARACUJA (R$ 7,99)\r\n\r\n1x SUCO DE MARACUJA (R$ 7,99)\r\n\r\n1x COCA 1,5 ZERO (R$ 11,99)',40.96,'2026-08-18 02:41:42',NULL,0,NULL,'Dinheiro','RANDAL POMPEU DE SABOYA MAGALH','Zona Rural',50,'Sobral','12','Felipe13091508',NULL,NULL),
(197,26,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 02:42:46',NULL,0,NULL,'Dinheiro','null','null',0,'null','null','Felipe13091508','concluído',NULL),
(198,26,'1x COCA 1,5 ZERO (R$ 11,99)\r\n\r\n1x SUCO DE MORANGO (R$ 12,99)',24.98,'2026-08-18 02:47:13',NULL,0,NULL,'Dinheiro','null','null',0,'null','null','Felipe13091508','saiu para entrega',NULL),
(199,2,'1x SUCO DE MARACUJA (R$ 7,99)',7.99,'2026-08-18 03:14:52',NULL,0,NULL,'Dinheiro','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','saiu para entrega',NULL),
(200,2,'1x COCA COLA KS (R$ 8,99)',8.99,'2026-08-18 03:17:11',NULL,0,NULL,'Cartão','Fazenda marrecas','centro',50,'Groaíras','proximo ao valnei','Felipe','concluído',NULL);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `imagem` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `valor_promocional` decimal(10,2) DEFAULT NULL,
  `duracao_promocao` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES
(150,'1','Picanha Completa',15.00,'servidor com baião, batata frita, farofa e vinagrete','troca baião','troca batata','','','','','','','','',10.00,11.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a5da003edbb7_images.jpeg',3,11.00,'2026-08-01'),
(156,'2','CARNE DE SOL',59.99,'SERVIDO COM BAIÃO E BATATA FRITA','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e242e336a1_Captura_de_tela_20260801_135118.png',3,NULL,NULL),
(158,'2058','MAMINHA COMPLETA',69.00,'SERVIDO COM BAIÃO E BATATA FRITA','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e24771ab41_Captura_de_tela_20260801_135054.png',3,NULL,NULL),
(159,'2055','Cupim na brasa',89.99,'SERVIDO COM BAIÃO E BATATA FRITA','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e24bd3ed7e_Captura_de_tela_20260801_135350.png',3,NULL,NULL),
(160,'10','Coca cola lata',6.99,'LATA','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e252249a03_Captura_de_tela_20260801_135456.png',6,NULL,NULL),
(161,'11','COCA COLA KS',8.99,'350 ML','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e254ad8c9e_Captura_de_tela_20260801_135435.png',6,NULL,NULL),
(162,'13','COCA 1,5 ZERO',11.99,'ZERO','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e256bba8ea_Captura_de_tela_20260801_135445.png',6,NULL,NULL),
(163,'15','SUCO DE MARACUJA',7.99,'COPO','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e2583bde00_Captura_de_tela_20260801_135514.png',6,NULL,NULL),
(164,'16','SUCO DE MORANGO',12.99,'JARRA','','','','','','','','','','',0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,'6a6e25960129f_Captura_de_tela_20260801_135526.png',6,NULL,NULL);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'Felipe','130915',''),
(2,'Felipe','$2y$12$EKwPl35ROjhwi39YYNMrS.oQ3oGnDa.uW9kZLWH88sw0dCtcPSZt2','feliphi10@gmail.com'),
(3,'viviane','$2y$12$JRznMaN4bFKmUIgVvt4mRec1uE3jEXMmxYQcExmJ8bDJfQActCt8m','vivianemelo1309@gmail.com'),
(4,'Felipe','$2y$12$c3UO9fcWfE3KCh4LZXxhPOBGOLYmR/oIOX0.9JWDxa58Sd7YdIDuK','feliphi13@gmail.com'),
(5,'Aquino','$2y$12$IrQN35mC/Fpy4mzbwC38MeZ7nu4V5C/yQBh5trFTQjeuuoYp9TKHa','aquino@gmail.com'),
(6,'Felipe','$2y$12$K9jg6Q9WL1WYz3qMVTvBQuo4fHiD2NL9qlFUf27pNTYlzJdxtyv.O','feliperodrigues@grupof5.com.br'),
(7,'teste0743','$2y$12$GEWKG1mAAnkQ3iYoyIEhVO30T4WwcPoYXiMOrUxzlB06DS/VzRaqS','teste0743@gmail.com'),
(8,'f5','$2y$12$q5ckqS8QUnTzLIkY4uSG2eDDhplbmoKyBxZcEvIoJvGEPW2P78tue','testefelipe0807@gmail.com'),
(9,'Felipe f5','$2y$12$mf3yWvGW5YuxYmX7JSKFmu0sIQq2vjV0Jl4.znYx5GuIEMuoEfM96','feliphi43@gmail.com'),
(10,'Felipe','$2y$12$kg0hBRvrCOAHe.w83cVt7uqT/fPhxHUmRiJloGSb955bIrSqYcXYG','feliphi1309@gmail.com'),
(11,'ggggggteste','$2y$12$SM29/2aWkQ9K.tQ.M2BL6ekMXd9tSqAT1QvqrZm/1FXoaxS8lFi3m','teste130915@gmail.com'),
(12,'Felipe','$2y$10$Bm.opMIhzqjQGaYJUJl6HeLUkIKviQB4gKkAnS/hKqha/oTQ1QwBu','feliphi130915@gmail.com'),
(13,'Felipe','$2y$10$3ENVzm6Uc/DDZFG4sbYqMO6SJrh6L7oVFXuVxSJ5.fZN/4eO3QKqW','feliphi1@gmail.com'),
(14,'Iasmim Rodrigues','$2y$10$P1onVDUx9YSd6wVzvaSb/OarvtehujbPSUMZnkrstXt6dOnMKQ5qG','iasmimrodriguesx@gmail.com'),
(15,'Iasmim Rodrigues','$2y$10$LpaLLc7HAMzkT1IcF4t.A.7isKHL0ppGCb2Vns7sN7GDfqpLsB2LS','franciscaiasm@gmail.com'),
(16,'pedro','$2y$12$OZCyJHLsFsXko8QwV/vVQugx4bdQa2bhTz4CX28HL7bQMfOG87Tya','pedro@gmail.com'),
(17,'Felipe','$2y$12$3DCwQ6p87CaiHJn1.AwhDOkfgCCsq00D.SO/qubhePnSEADdeMgOO','pedro1309@gmail.com'),
(18,'Suporte','$2y$10$Q539mgKMPDy1mQ2rxIPN5OtHR28PmecwVXJtydZVY5OO1Fg3YA51.','suporte@gmail.com'),
(19,'felipe teste','$2y$10$1yKwDmHwI2txYImRRuDpx.ivdrwuUHxQHkBqGJvDlvCVfronS9Uxq','feliphi13091525@gmail.com'),
(20,'aquino','$2y$12$1KJos027G2YdFvseDxEguO1nosXSsRpPj1dbHLAXKlqG5Zjk4SOje','aquino1309@gmail.com'),
(21,'teste1309','$2y$12$W2gqfvFdPCiUlfU1yawDH.KX0N1LtCLtOhGZB3UT0VoQ0VbMa8OZC','feliphi100@gmail.com'),
(22,'1309','$2y$12$mYIvr1lhvkcx2/B3TaItJO/9SB2Sy8ToBmLDZgf0u14jmkjF/ts9i','feliphi11@gmail.com'),
(23,'130915','$2y$12$wDyVYr4hr/GvZpyRoIHp6uJV4l4v6FLhTWds6.93aIyF9nuy33QZe','feliphi110@gmail.com'),
(24,'12','$2y$12$DF9KZTH6Jnw6S3MLIjLVFOVsYFyHnkLnDZ2tQ6EHKi1t9WFKiMWwS','feliphi12@gmail.com'),
(25,'123','$2y$12$jYr5uKo3umobjj64O7c/U.HMMLQqJUj4KdRmNaGj4ItXbLOeCN.rK','feliphi123@gmail.com'),
(26,'Felipe13091508','$2y$12$vz7eNfgnCye4ETVfEsU5BumGpKx9t.YGQnPrPRvfklMMuDqjOc8sm','feliphi90@gmail.com');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-18  0:20:16
