-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 22-Jan-2019 às 14:06
-- Versão do servidor: 10.1.36-MariaDB
-- versão do PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `controladoriadiariooficial`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `caderno`
--

CREATE TABLE `caderno` (
  `cadernoID` int(11) NOT NULL,
  `cadernoNome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `caderno`
--

INSERT INTO `caderno` (`cadernoID`, `cadernoNome`) VALUES
(1, 'Poder Executivo'),
(4, 'Poder Legislativo'),
(5, 'Diversos'),
(6, 'Licitação');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadernotipodocumento`
--

CREATE TABLE `cadernotipodocumento` (
  `tipoID` int(11) NOT NULL,
  `cadernoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cadernotipodocumento`
--

INSERT INTO `cadernotipodocumento` (`tipoID`, `cadernoID`) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 6),
(3, 1),
(3, 4),
(3, 5),
(4, 1),
(4, 5),
(7, 1),
(7, 5),
(9, 4),
(9, 5),
(10, 1),
(10, 5),
(11, 1),
(11, 5),
(12, 1),
(12, 5),
(13, 1),
(13, 5),
(14, 1),
(14, 5),
(15, 1),
(15, 5),
(16, 1),
(16, 5),
(17, 1),
(17, 5),
(18, 1),
(18, 5),
(19, 1),
(19, 5),
(20, 1),
(20, 5),
(21, 1),
(21, 5),
(22, 1),
(22, 5),
(23, 1),
(23, 5),
(24, 1),
(24, 5),
(25, 1),
(25, 5),
(26, 1),
(26, 5),
(27, 1),
(27, 5),
(28, 1),
(28, 5),
(29, 1),
(29, 5),
(30, 1),
(30, 5),
(31, 1),
(31, 5),
(32, 1),
(32, 5),
(33, 1),
(33, 5),
(34, 5),
(34, 6),
(35, 5),
(35, 6),
(36, 5),
(36, 6),
(37, 5),
(37, 6),
(38, 5),
(38, 6),
(39, 5),
(39, 6),
(40, 5),
(40, 6),
(41, 5),
(41, 6),
(42, 5),
(42, 6),
(43, 5),
(43, 6),
(44, 5),
(44, 6),
(45, 5),
(45, 6),
(46, 5),
(47, 5),
(48, 5),
(49, 5),
(50, 5),
(51, 5),
(53, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `comunicado`
--

CREATE TABLE `comunicado` (
  `comunicadoID` int(11) NOT NULL,
  `usuarioID` int(11) NOT NULL,
  `tituloMensagem` varchar(150) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `dataComunicado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `comunicado`
--

INSERT INTO `comunicado` (`comunicadoID`, `usuarioID`, `tituloMensagem`, `mensagem`, `dataComunicado`) VALUES
(1, 13, 'Erro no Diário', 'Santa após cadastrar o diário no site da prefeitura, favor abrir e conferir para verificar se está tudo certo.\r\n\r\nsds... Maurício', '2019-01-17 09:27:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comunicadogrupousuario`
--

CREATE TABLE `comunicadogrupousuario` (
  `grupoID` int(11) NOT NULL,
  `comunicadoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `comunicadogrupousuario`
--

INSERT INTO `comunicadogrupousuario` (`grupoID`, `comunicadoID`) VALUES
(1, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `comunicadousuario`
--

CREATE TABLE `comunicadousuario` (
  `comunicadoID` int(11) NOT NULL,
  `usuarioID` int(11) NOT NULL,
  `visualizado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `comunicadousuario`
--

INSERT INTO `comunicadousuario` (`comunicadoID`, `usuarioID`, `visualizado`) VALUES
(1, 1, 1),
(1, 13, 1),
(1, 15, 1),
(1, 17, 1),
(1, 23, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracaofatura`
--

CREATE TABLE `configuracaofatura` (
  `configID` int(11) NOT NULL,
  `largura` double NOT NULL DEFAULT '0',
  `valorColuna` double NOT NULL DEFAULT '0',
  `cadernoID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `configuracaofatura`
--

INSERT INTO `configuracaofatura` (`configID`, `largura`, `valorColuna`, `cadernoID`) VALUES
(1, 9.2, 18.85, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `diariodata`
--

CREATE TABLE `diariodata` (
  `diarioDataID` int(11) NOT NULL,
  `diarioData` date NOT NULL,
  `numeroDiario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `diariodata`
--

INSERT INTO `diariodata` (`diarioDataID`, `diarioData`, `numeroDiario`) VALUES
(35, '2019-01-07', '5735'),
(36, '2019-01-08', '5736'),
(37, '2019-01-09', '5737'),
(38, '2019-01-10', '5738'),
(41, '2019-01-16', '5742'),
(42, '2019-01-17', '5743'),
(43, '2019-01-18', '5744'),
(44, '2019-01-11', '5739'),
(45, '2019-01-14', '5740'),
(46, '2019-01-15', '5741'),
(47, '2019-01-21', '5745'),
(48, '2019-01-22', '5746'),
(49, '2019-01-23', '5747'),
(50, '2019-01-24', '5748'),
(51, '2019-01-25', '5749'),
(52, '2019-01-28', '5750'),
(53, '2019-01-29', '5751'),
(54, '2019-01-30', '5752'),
(55, '2019-01-31', '5753');

-- --------------------------------------------------------

--
-- Estrutura da tabela `diasnaouteis`
--

CREATE TABLE `diasnaouteis` (
  `diaID` int(11) NOT NULL,
  `diaNaoUtilData` date NOT NULL,
  `diaDescricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `diasnaouteis`
--

INSERT INTO `diasnaouteis` (`diaID`, `diaNaoUtilData`, `diaDescricao`) VALUES
(1, '2019-03-04', 'Carnaval'),
(2, '2019-03-05', 'Carnaval'),
(3, '2019-03-06', 'Quarta-feira Cinzas (PF)'),
(4, '2019-04-18', 'Ponto Facultativo'),
(5, '2019-04-19', 'Paixão de Cristo'),
(7, '2019-04-29', 'Nossa Senhora da Penha'),
(8, '2019-05-01', 'Dia Mundial do Trabalho'),
(9, '2019-06-20', 'Corpus Christi'),
(10, '2019-06-21', 'Ponto Facultativo'),
(11, '2019-11-15', 'Proclamação da República'),
(12, '2019-12-24', 'Ponto Facultativo'),
(13, '2019-12-25', 'Natal'),
(14, '2019-12-31', 'Ponto Facultativo'),
(15, '2018-12-31', 'Facultativo'),
(16, '2018-12-01', 'Ano Novo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fatura`
--

CREATE TABLE `fatura` (
  `protocolo` int(11) NOT NULL,
  `protocoloAno` int(11) NOT NULL,
  `protocoloCompleto` varchar(255) NOT NULL,
  `dataEnvioFatura` datetime NOT NULL,
  `tipoID` int(11) NOT NULL,
  `subcategoriaID` int(11) DEFAULT NULL,
  `usuarioID` int(11) NOT NULL,
  `usuarioIDApagou` int(11) DEFAULT NULL,
  `usuarioIDPublicou` int(11) DEFAULT NULL,
  `largura` double NOT NULL,
  `centimetragem` double NOT NULL,
  `valorColuna` double NOT NULL,
  `valor` double NOT NULL,
  `diarioDataID` int(11) DEFAULT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `cpfCnpj` varchar(14) NOT NULL,
  `empresa` varchar(200) NOT NULL,
  `requisitante` varchar(200) NOT NULL,
  `arquivoOriginal` varchar(255) NOT NULL,
  `arquivoFormatado` varchar(255) NOT NULL,
  `arquivoVisualizacao` varchar(255) NOT NULL,
  `dam` varchar(255) DEFAULT NULL,
  `comprovantePago` varchar(150) DEFAULT NULL,
  `situacaoID` int(11) NOT NULL,
  `descricaoCancelamento` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefoneFixo` varchar(13) DEFAULT NULL,
  `telefoneCelular` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fatura`
--

INSERT INTO `fatura` (`protocolo`, `protocoloAno`, `protocoloCompleto`, `dataEnvioFatura`, `tipoID`, `subcategoriaID`, `usuarioID`, `usuarioIDApagou`, `usuarioIDPublicou`, `largura`, `centimetragem`, `valorColuna`, `valor`, `diarioDataID`, `observacao`, `cpfCnpj`, `empresa`, `requisitante`, `arquivoOriginal`, `arquivoFormatado`, `arquivoVisualizacao`, `dam`, `comprovantePago`, `situacaoID`, `descricaoCancelamento`, `email`, `telefoneFixo`, `telefoneCelular`) VALUES
(0, 2019, '02019FAT', '2019-01-10 10:34:55', 53, 10, 17, NULL, NULL, 9.2, 2.84, 18.85, 53.53, 45, NULL, '12532983000196', 'Lava Jato Boa Aparência', 'Júlio Cesar da Silva', '12532983000196-2019-01-10-10-34-16.docx', '12532983000196-2019-01-10-10-34-16_format.docx', '12532983000196-2019-01-10-10-34-16_visualizacao.pdf', NULL, '02019FAT_comprovantePago.pdf', 1, NULL, NULL, NULL, NULL),
(1, 2019, '12019FAT', '2019-01-10 10:56:15', 53, 10, 17, NULL, NULL, 9.2, 4.06, 18.85, 76.53, 45, NULL, '06895138000182', 'J.S. Industria de Carrocerias Ltda', 'Maria Helena Martelete', '06895138000182-2019-01-10-10-56-06.docx', '06895138000182-2019-01-10-10-56-06_format.docx', '06895138000182-2019-01-10-10-56-06_visualizacao.pdf', NULL, '12019FAT_comprovantePago.pdf', 1, NULL, NULL, NULL, NULL),
(2, 2019, '22019FAT', '2019-01-10 11:14:06', 53, 10, 17, NULL, NULL, 9.2, 3.65, 18.85, 68.8, 45, NULL, '03996973000110', 'Mocapri Marmores e Granitos Ltda', 'Maria Helena Martelete', '03996973000110-2019-01-10-11-14-00.docx', '03996973000110-2019-01-10-11-14-00_format.docx', '03996973000110-2019-01-10-11-14-00_visualizacao.pdf', NULL, '22019FAT_comprovantePago.pdf', 1, NULL, NULL, NULL, NULL),
(5, 2019, '52019FAT', '2019-01-14 17:36:03', 53, 11, 15, NULL, NULL, 9.2, 4.06, 18.85, 112.93, 37, NULL, '28129260000857', 'Drift Comércio de Alimentos S/A', 'Jerlen', '28129260000857-2019-01-14-17-34-39.docx', '28129260000857-2019-01-14-17-34-39_format.docx', '28129260000857-2019-01-14-17-34-40_visualizacao.pdf', NULL, '52019FAT_comprovantePago.pdf', 1, NULL, NULL, NULL, NULL),
(6, 2019, '62019FAT', '2019-01-16 17:11:34', 53, 9, 20, NULL, NULL, 9.2, 4.46, 18.85, 84.07, NULL, NULL, '14467980709', 'CAPIXABA GRANITOS LTDA.', 'Eduarda Andrade Bayerl', '14467980709-2019-01-16-17-11-16.docx', '14467980709-2019-01-16-17-11-16_format.docx', '14467980709-2019-01-16-17-11-17_visualizacao.pdf', 'DAM-62019FAT.pdf', NULL, 4, NULL, 'ambiental@icgprojetos.com.br', '(28)3036-8326', '(28)99962-2960'),
(7, 2019, '72019FAT', '2019-01-16 17:29:59', 53, 13, 20, NULL, NULL, 9.2, 4.06, 18.85, 76.53, NULL, NULL, '14467880709', 'FIORI PEDRAS MARM E GRAN LTDA', 'Eduarda Andrade Bayerl', '14467880709-2019-01-16-17-29-50.docx', '14467880709-2019-01-16-17-29-50_format.docx', '14467880709-2019-01-16-17-29-50_visualizacao.pdf', 'DAM-72019FAT.pdf', NULL, 4, NULL, 'ambiental@icgprojetos.com.br', '(28)3036-8326', '(29)99962-2960'),
(8, 2019, '82019FAT', '2019-01-16 17:45:20', 53, 10, 20, NULL, NULL, 9.2, 4.46, 18.85, 84.07, NULL, NULL, '28129260000857', 'DRIFT Com Alim S/A', 'Italo Nicoli Calegário', '28129260000857-2019-01-16-17-44-58.docx', '28129260000857-2019-01-16-17-44-58_format.docx', '28129260000857-2019-01-16-17-44-58_visualizacao.pdf', 'DAM-82019FAT.pdf', NULL, 4, NULL, 'italonicoli8@gmail.com', NULL, NULL),
(9, 2019, '92019FAT', '2019-01-17 09:19:35', 53, 10, 23, NULL, NULL, 9.2, 3.25, 18.85, 61.26, NULL, NULL, '12929417792', 'FÁBIO RIBEIRO GOMES 09303968719', 'Gleisson Gomes Fonseca', '12929417792-2019-01-17-09-19-08.docx', '12929417792-2019-01-17-09-19-08_format.docx', '12929417792-2019-01-17-09-19-08_visualizacao.pdf', 'DAM-92019FAT.pdf', NULL, 4, NULL, 'fenixconsultoriambiental@gmail.com', '(28)3521-0957', '(28)99945-1466'),
(10, 2019, '102019FAT', '2019-01-17 09:28:13', 53, 14, 20, NULL, NULL, 9.2, 4.46, 18.85, 84.07, NULL, NULL, '12929417792', 'MECÂNICA MOTO SUL LTDA ME', 'Gleisson Gomes Fonseca', '12929417792-2019-01-17-09-27-57.docx', '12929417792-2019-01-17-09-27-57_format.docx', '12929417792-2019-01-17-09-27-57_visualizacao.pdf', 'DAM-102019FAT.pdf', NULL, 4, NULL, 'fenixconsultoriambiental@gmail.com', '(28)3521-0957', '(28)99945-1466'),
(11, 2019, '112019FAT', '2019-01-17 09:33:26', 53, 10, 20, NULL, NULL, 9.2, 4.87, 18.85, 91.8, NULL, NULL, '12929417792', 'JUAREZ SATHLER DE RESENDE 75326868768', 'Gleisson Gomes Fonseca', '12929417792-2019-01-17-09-33-15.docx', '12929417792-2019-01-17-09-33-15_format.docx', '12929417792-2019-01-17-09-33-16_visualizacao.pdf', 'DAM-112019FAT.pdf', NULL, 4, NULL, 'fenixconsultoriaambiental@gmail.com', '(28)3521-0957', '(28)99945-1466'),
(12, 2019, '122019FAT', '2019-01-17 09:38:52', 53, 10, 20, NULL, NULL, 9.2, 3.65, 18.85, 68.8, NULL, NULL, '12929417792', 'H. ROBSON DE OLIVEIRA – ME', 'Gleisson Gomes Fonseca', '12929417792-2019-01-17-09-38-49.docx', '12929417792-2019-01-17-09-38-49_format.docx', '12929417792-2019-01-17-09-38-49_visualizacao.pdf', 'DAM-122019FAT.pdf', NULL, 4, NULL, 'fenixconsultoriambiental@gmail.com', '(28)3521-0957', '(28)99945-1466'),
(13, 2019, '132019FAT', '2019-01-17 09:44:38', 53, 13, 20, NULL, NULL, 9.2, 5.27, 18.85, 99.34, NULL, NULL, '12929417792', 'FÊNIX MOTO CENTER EIRELI', 'Gleisson Gomes Fonseca', '12929417792-2019-01-17-09-44-34.docx', '12929417792-2019-01-17-09-44-34_format.docx', '12929417792-2019-01-17-09-44-34_visualizacao.pdf', 'DAM-132019FAT.pdf', NULL, 4, NULL, 'fenixconsultoriambiental@gmail.com', '(28)3521-0957', '(28)99945-1466'),
(14, 2019, '142019FAT', '2019-01-17 09:59:07', 53, 14, 20, NULL, NULL, 9.2, 3.25, 18.85, 61.26, NULL, NULL, '11948765799', 'U. DE S. SILVA SERVICOS', 'Lorranny Felipe Santos', '11948765799-2019-01-17-09-58-54.docx', '11948765799-2019-01-17-09-58-54_format.docx', '11948765799-2019-01-17-09-58-55_visualizacao.pdf', 'DAM-142019FAT.pdf', NULL, 4, NULL, 'mineracao@genesisconsult.com.br', '(28)3511-7282', NULL),
(15, 2019, '152019FAT', '2019-01-17 10:07:00', 53, 10, 20, NULL, NULL, 9.2, 4.06, 18.85, 76.53, NULL, NULL, '09797371760', 'GRAMAZINI GRANITOS E MARMORES THOMAZINI LTDA', 'Giovani Américo Tomé', '09797371760-2019-01-17-10-06-52.docx', '09797371760-2019-01-17-10-06-52_format.docx', '09797371760-2019-01-17-10-06-52_visualizacao.pdf', 'DAM-152019FAT.pdf', NULL, 4, NULL, 'eng.minas.giovani@gmail.com', NULL, '(28)99958-9242'),
(16, 2019, '162019FAT', '2019-01-17 10:14:48', 53, 11, 20, NULL, NULL, 9.2, 4.06, 18.85, 76.53, NULL, NULL, '28263183704', 'JANETE MESQUITA (282.531.837-04)', 'Janete Mesquita', '28263183704-2019-01-17-10-14-04.docx', '28263183704-2019-01-17-10-14-04_format.docx', '28263183704-2019-01-17-10-14-04_visualizacao.pdf', 'DAM-162019FAT.pdf', NULL, 4, NULL, 'kfengenharia@outlook.com', '(28)3036-1878', '(28)99222-3791'),
(17, 2019, '172019FAT', '2019-01-18 17:05:16', 53, 13, 20, NULL, NULL, 9.2, 4.46, 18.85, 84.07, NULL, NULL, '12711279707', 'AMADEU BRUN FELETTI 09075681739', 'Aline Oliveira Freitas', '12711279707-2019-01-18-17-04-49.docx', '12711279707-2019-01-18-17-04-49_format.docx', '12711279707-2019-01-18-17-04-50_visualizacao.pdf', 'DAM-172019FAT.pdf', NULL, 4, NULL, 'alibeolifreitas@gmail.com', NULL, '(28)99982-4487'),
(18, 2019, '182019FAT', '2019-01-21 09:41:12', 53, 10, 23, NULL, NULL, 9.2, 4.46, 18.85, 84.07, NULL, NULL, '00982344635', 'J. MESQUITA EIRELI EPP', 'Karla Patrícia Andrade Pinheiro Boldotto', '00982344635-2019-01-21-09-40-37.docx', '00982344635-2019-01-21-09-40-37_format.docx', '00982344635-2019-01-21-09-40-38_visualizacao.pdf', 'DAM-182019FAT.pdf', NULL, 4, NULL, 'kfengenharia@outlook.com', '(28)3036-1878', '(28)99222-3791'),
(19, 2019, '192019FAT', '2019-01-21 09:46:28', 53, 10, 23, NULL, NULL, 9.2, 3.65, 18.85, 68.8, NULL, NULL, '00982344635', 'PEDRAS DECORATIVAS ITALIA EIRELI', 'Karla Patrícia Andrade Pinheiro Boldotto', '00982344635-2019-01-21-09-46-16.docx', '00982344635-2019-01-21-09-46-16_format.docx', '00982344635-2019-01-21-09-46-16_visualizacao.pdf', 'DAM-192019FAT.pdf', NULL, 4, NULL, 'kfengenharia@outlook.com', '(28)3036-1878', '(28)99222-3791'),
(20, 2019, '202019FAT', '2019-01-21 09:54:35', 53, 12, 23, NULL, NULL, 9.2, 5.27, 18.85, 99.34, NULL, NULL, '12873994797', 'ALINE CRISTINE NEVES VENANCIO GEAQUINTO 11346259798', 'Pedro dos Santos Baptista Filho', '12873994797-2019-01-21-09-54-24.docx', '12873994797-2019-01-21-09-54-24_format.docx', '12873994797-2019-01-21-09-54-24_visualizacao.pdf', 'DAM-202019FAT.pdf', NULL, 4, NULL, 'pedrobaptistafilho@hotmail.com', NULL, '(28)99883-1841'),
(21, 2019, '212019FAT', '2019-01-21 10:05:37', 53, 13, 23, NULL, NULL, 9.2, 3.65, 18.85, 68.8, NULL, NULL, '39349287000103', 'BEIRAL SERRARIA E MARMORARIA LTDA', 'Beiral Serraria e Marmoraria LTDA', '39349287000103-2019-01-21-10-05-21.docx', '39349287000103-2019-01-21-10-05-21_format.docx', '39349287000103-2019-01-21-10-05-21_visualizacao.pdf', 'DAM-212019FAT.pdf', NULL, 4, NULL, NULL, NULL, NULL),
(22, 2019, '222019FAT', '2019-01-21 10:12:24', 53, 9, 23, NULL, NULL, 9.2, 5.68, 18.85, 107.07, NULL, NULL, '11458094723', 'DUDU COMERCIO DE PEDRAS E TRANSPORTES EIRELI', 'Pollyana Pontes', '11458094723-2019-01-21-10-12-11.docx', '11458094723-2019-01-21-10-12-11_format.docx', '11458094723-2019-01-21-10-12-11_visualizacao.pdf', 'DAM-222019FAT.pdf', NULL, 4, NULL, 'cunha.ambiental@gmail.com', NULL, '(28)99222-8152');

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupousuario`
--

CREATE TABLE `grupousuario` (
  `grupoID` int(11) NOT NULL,
  `grupoDescricao` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupousuario`
--

INSERT INTO `grupousuario` (`grupoID`, `grupoDescricao`) VALUES
(1, 'Administrador'),
(2, 'Usuário'),
(3, 'Fatura'),
(4, 'Publicador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `logID` bigint(20) NOT NULL,
  `logDescricao` varchar(400) NOT NULL,
  `usuarioID` int(11) NOT NULL,
  `logData` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orgaorequisitante`
--

CREATE TABLE `orgaorequisitante` (
  `orgaoID` int(11) NOT NULL,
  `orgaoNome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `orgaorequisitante`
--

INSERT INTO `orgaorequisitante` (`orgaoID`, `orgaoNome`) VALUES
(1, 'DATACI'),
(2, 'Secretaria da Fazenda'),
(4, 'Prefeitura'),
(5, 'Câmara'),
(6, 'SEMAD - GTI'),
(7, 'Gabinete do Prefeito'),
(8, 'Diario Oficial'),
(9, 'SEMMA');

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('pmci.diario.oficial@gmail.com', '$2y$10$n5P1/OxN/FZaaSkF0e7t3.ylOKVWw.cz96SfXWrFWjGOpXYKQA7eu', '2019-01-08 12:43:21'),
('eduardbinoti@gmail.com', '$2y$10$08//aDRA6uVU.GDd5w0of.Td8to9RTNYcoG3KPoebIbLzDKEVakRm', '2019-01-15 12:53:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicacao`
--

CREATE TABLE `publicacao` (
  `situacaoID` int(11) NOT NULL,
  `cadernoID` int(11) NOT NULL,
  `tipoID` int(11) NOT NULL,
  `usuarioID` int(11) NOT NULL,
  `diarioDataID` int(11) NOT NULL,
  `dataEnvio` datetime NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `protocolo` int(11) NOT NULL,
  `protocoloAno` int(11) NOT NULL,
  `protocoloCompleto` varchar(80) NOT NULL,
  `pub` char(3) NOT NULL DEFAULT 'pub',
  `usuarioIDApagou` int(11) DEFAULT NULL,
  `dataApagada` datetime DEFAULT NULL,
  `rejeitadaDescricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `publicacaoarquivo`
--

CREATE TABLE `publicacaoarquivo` (
  `protocoloCompleto` varchar(80) NOT NULL,
  `arquivoID` int(11) NOT NULL,
  `arquivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `situacao`
--

CREATE TABLE `situacao` (
  `situacaoID` int(11) NOT NULL,
  `situacaoNome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `situacao`
--

INSERT INTO `situacao` (`situacaoID`, `situacaoNome`) VALUES
(1, 'Publicada'),
(2, 'Apagada'),
(3, 'Aceita'),
(4, 'Enviada'),
(5, 'Rejeitada');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `statusID` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`statusID`, `descricao`) VALUES
(1, 'Ativo'),
(2, 'Inativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subcategoria`
--

CREATE TABLE `subcategoria` (
  `subcategoriaID` int(11) NOT NULL,
  `subcategoriaNome` varchar(100) NOT NULL,
  `tipoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `subcategoria`
--

INSERT INTO `subcategoria` (`subcategoriaID`, `subcategoriaNome`, `tipoID`) VALUES
(9, 'Licença de Instalação', 53),
(10, 'Licença de Operação', 53),
(11, 'Licença Prévia', 53),
(12, 'Licença Prévia, de Instalação e de Operação', 53),
(13, 'Renovação da Licença de Operação', 53),
(14, 'Licença de Operação (Por Procedimento Corretivo)', 53),
(15, 'Ampliação da Licença', 53),
(16, 'Transferência de Titularidade', 53),
(17, 'Prorrogação da Licença', 53);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `tipoID` int(11) NOT NULL,
  `tipoDocumento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tipodocumento`
--

INSERT INTO `tipodocumento` (`tipoID`, `tipoDocumento`) VALUES
(1, 'Errata'),
(3, 'Lei'),
(4, 'Edital'),
(7, 'Contrato'),
(9, 'legislacionario'),
(10, 'Decreto'),
(11, 'Portaria'),
(12, 'Resolução'),
(13, 'Instrução Normativa'),
(14, 'Deliberação'),
(15, 'Intimação'),
(16, 'Convocação'),
(17, 'Instrução de Serviço'),
(18, 'Contrato de Estágio'),
(19, 'Rescisão de Contrato de Estágio'),
(20, 'Acordão'),
(21, 'Ordem de Serviço'),
(22, 'Termos'),
(23, 'Ordem de Fornecimento'),
(24, 'Aditivo'),
(25, 'Convênio'),
(26, 'Decisão'),
(27, 'Pauta'),
(28, 'Lista'),
(29, 'Plantão'),
(30, 'Aviso'),
(31, 'Extrato Social'),
(32, 'Estatuto'),
(33, 'Comunicado'),
(34, 'Aviso de Licitação'),
(35, 'Resultado de Licitação'),
(36, 'Dispensa de Licitação'),
(37, 'Inexigibilidade de Licitação'),
(38, 'Adiamento de Licitação'),
(39, 'Revogação de Licitação'),
(40, 'Suspensão de Licitação'),
(41, 'Cancelamento de Licitação'),
(42, 'Adjudicação'),
(43, 'Homologação'),
(44, 'Ata de Registro de Preço'),
(45, 'Chamada Pública'),
(46, 'Despacho'),
(47, 'Centro de Apoio'),
(48, 'Edital de Citação'),
(49, 'Edital de Intimação'),
(50, 'Edital de Interdição'),
(51, 'Uso Capião'),
(53, 'Extrato de Licença');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefoneSetor` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefoneCelular` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `grupoID` int(11) NOT NULL,
  `orgaoID` int(11) NOT NULL,
  `statusID` int(11) NOT NULL,
  `horaEnvio` time NOT NULL,
  `login` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primeiroLogin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `cpf`, `telefoneSetor`, `telefoneCelular`, `password`, `remember_token`, `created_at`, `updated_at`, `grupoID`, `orgaoID`, `statusID`, `horaEnvio`, `login`, `primeiroLogin`) VALUES
(1, 'Carlos Eduardo Castro', 'eduardbinoti@gmail.com', '56310841041', '2835224896', '28999654178', '$2y$10$1Q02pW1d0l9MjjvCo8UJeOdyFinyS0ixN8eEPyjaH./ll5EpZbMfi', 'wsrrW0ABpTrksLfOUeTgVMKFnBtNjs2jUbQIMhzkBcKbFMrTX2e7nL0tqjp2', NULL, '2018-11-13 17:36:26', 1, 4, 1, '17:00:00', 'dedu', 0),
(8, 'Maurício P. Lima', 'mauriciopicoli@hotmail.com', '08094627700', '2835112713', '99999999999', '$2y$10$fo3r21dtnAXqLSYRXE2ly.jfn.nv70zigr4C5p1FT.83rKhQ75v5O', 'ibARP8dIPGnFyEEheJ938K1b5ROicbt6tdYdksBBVCPv1P4avk020f6MDmKG', NULL, '2018-12-05 12:12:59', 3, 6, 1, '17:00:00', 'mauricio', 0),
(13, 'Informatica', 'semad.informatica@cachoeiro.es.gov.br', '02781851779', '2835112713', '28992532511', '$2y$10$c82jLr9IeLaZJ/qnxxfAK.bCgvIfHePNB.JIvmI56jAItpN4Oa9Rm', 'LwrEH5VvhpQxQNU8ueKyzztRrGFaQm7Y8aDpYLdMUKUwXeLpyeuYJAYuhu8f', NULL, '2019-01-02 19:14:46', 1, 6, 1, '17:00:00', 'informatica', 0),
(15, 'Santa Gama de Freitas', 'pmci.diario.oficial@gmail.com', '93023057753', '2835224708', '99999999999', '$2y$10$TXfsbYl4Dj3To.KaoSxCrOZMD/WjH3mYgvwJmwhjok18MYFRxiLpS', 'gPo3Hz7iAVD4YclmDVLCyymfgHVewN7fqCzc9z3vX8ahuElEKoWWuxJIB7vV', NULL, '2019-01-04 17:29:41', 1, 8, 1, '17:00:00', 'santa', 0),
(17, 'Talia Ferreira Guerra', 'taliafguerra@gmail.com', '96201894772', '2835224708', '28999250709', '$2y$10$U5dVmQrcI4ls8b/hROFm6eWM2ZNVaXEUc7g02PMMLRcHfE2OqaVAy', 'eJa4j9RFKom2qZGwnwrUpq5xc3Fwp2KBu2TUs1wCbdmQWGsLROUhN2Qycq1r', NULL, '2019-01-10 11:42:11', 1, 8, 1, '17:00:00', 'talia', 0),
(18, 'Teste Usuários', 'teste@gmail.com', '27838386055', '1231231231', '12312312312', '$2y$10$ZCfMko8b2RapYYaqFFxgTOjXDpAUiwimKhUyfCjU0MwRICg.7kKym', 'ADV1CcRNHmSiUTZppqOzibWdK5x8pKkyJcYUbzSEILQGazDqS5U2wV4JKLLH', NULL, '2019-01-10 18:23:58', 3, 4, 1, '17:00:00', 'dedubr', 0),
(20, 'Valéria Araujo Fraga', 'semma.vafraga@cachoeiro.es.gov.br', '76178838700', '2831555326', '99999999999', '$2y$10$OzNkKcxljVt8IrpBER2Rs.t99un6LunFZ6YXiMEnCITKn/9JKLcVu', '6qcFaLNDzdab7JIw2PzKIgzXy8MIEaccpgtZFVQugkdkaYWfIsptM9bKm3K6', NULL, '2019-01-15 13:05:08', 3, 9, 1, '19:00:00', 'vafraga', 0),
(21, 'Elisandra Baiense', 'semma.elisandra@cachoeiro.es.gov.br', '03463169797', '2831555311', '28999182220', '$2y$10$24z4z51PqRZ8raHoplwTHOHCBtGf0EYqA6G9PINQ9s9xC9MCPmzcC', NULL, NULL, NULL, 3, 9, 1, '19:00:00', 'elisandra', 1),
(22, 'José Roberto Pereira Cardoso', 'betaocardoso38@gmail.com', '95203958734', '2831555326', '28988031118', '$2y$10$TD1PYhq4TWNq6ZZwRy1P5OfebQXXVcfZWMKoHo0KB5IcE4c68MZ5a', NULL, NULL, NULL, 3, 9, 1, '19:58:00', 'jrpereira', 1),
(23, 'Gustavo Dal Rio Figueiredo', 'gustavof.semma@gmail.com', '14006331762', '2831555326', '28999999999', '$2y$10$2YWmYpFMKjADNOqZI9dEreTjllkP/k4iL6mONYESovQfSZBaCzq9G', 'onYtGpxIygqG3VoqBFfrEWbGFZr1BbYssmMN71N2Wf42rNddfROmvxITSbvR', NULL, '2019-01-15 19:13:56', 3, 9, 1, '17:00:00', 'gustavod', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuariocaderno`
--

CREATE TABLE `usuariocaderno` (
  `usuarioID` int(11) NOT NULL,
  `cadernoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuariocaderno`
--

INSERT INTO `usuariocaderno` (`usuarioID`, `cadernoID`) VALUES
(1, 1),
(1, 5),
(1, 6),
(2, 1),
(5, 1),
(5, 5),
(7, 1),
(8, 5),
(9, 1),
(9, 4),
(10, 4),
(11, 1),
(11, 5),
(12, 5),
(13, 1),
(13, 4),
(13, 5),
(13, 6),
(14, 5),
(15, 1),
(15, 4),
(15, 5),
(15, 6),
(16, 1),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `caderno`
--
ALTER TABLE `caderno`
  ADD PRIMARY KEY (`cadernoID`);

--
-- Indexes for table `cadernotipodocumento`
--
ALTER TABLE `cadernotipodocumento`
  ADD PRIMARY KEY (`tipoID`,`cadernoID`);

--
-- Indexes for table `comunicado`
--
ALTER TABLE `comunicado`
  ADD PRIMARY KEY (`comunicadoID`);

--
-- Indexes for table `comunicadogrupousuario`
--
ALTER TABLE `comunicadogrupousuario`
  ADD PRIMARY KEY (`grupoID`,`comunicadoID`);

--
-- Indexes for table `comunicadousuario`
--
ALTER TABLE `comunicadousuario`
  ADD PRIMARY KEY (`comunicadoID`,`usuarioID`);

--
-- Indexes for table `configuracaofatura`
--
ALTER TABLE `configuracaofatura`
  ADD PRIMARY KEY (`configID`);

--
-- Indexes for table `diariodata`
--
ALTER TABLE `diariodata`
  ADD PRIMARY KEY (`diarioDataID`);

--
-- Indexes for table `diasnaouteis`
--
ALTER TABLE `diasnaouteis`
  ADD PRIMARY KEY (`diaID`);

--
-- Indexes for table `fatura`
--
ALTER TABLE `fatura`
  ADD PRIMARY KEY (`protocolo`,`protocoloAno`);

--
-- Indexes for table `grupousuario`
--
ALTER TABLE `grupousuario`
  ADD PRIMARY KEY (`grupoID`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orgaorequisitante`
--
ALTER TABLE `orgaorequisitante`
  ADD PRIMARY KEY (`orgaoID`);

--
-- Indexes for table `publicacao`
--
ALTER TABLE `publicacao`
  ADD PRIMARY KEY (`protocolo`,`protocoloAno`),
  ADD UNIQUE KEY `protocoloCompleto` (`protocoloCompleto`);

--
-- Indexes for table `publicacaoarquivo`
--
ALTER TABLE `publicacaoarquivo`
  ADD PRIMARY KEY (`arquivoID`,`protocoloCompleto`),
  ADD UNIQUE KEY `arquivo` (`arquivo`);

--
-- Indexes for table `situacao`
--
ALTER TABLE `situacao`
  ADD PRIMARY KEY (`situacaoID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusID`);

--
-- Indexes for table `subcategoria`
--
ALTER TABLE `subcategoria`
  ADD PRIMARY KEY (`subcategoriaID`);

--
-- Indexes for table `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`tipoID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Indexes for table `usuariocaderno`
--
ALTER TABLE `usuariocaderno`
  ADD PRIMARY KEY (`usuarioID`,`cadernoID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caderno`
--
ALTER TABLE `caderno`
  MODIFY `cadernoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comunicado`
--
ALTER TABLE `comunicado`
  MODIFY `comunicadoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `configuracaofatura`
--
ALTER TABLE `configuracaofatura`
  MODIFY `configID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diariodata`
--
ALTER TABLE `diariodata`
  MODIFY `diarioDataID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `diasnaouteis`
--
ALTER TABLE `diasnaouteis`
  MODIFY `diaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `grupousuario`
--
ALTER TABLE `grupousuario`
  MODIFY `grupoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `logID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orgaorequisitante`
--
ALTER TABLE `orgaorequisitante`
  MODIFY `orgaoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `publicacaoarquivo`
--
ALTER TABLE `publicacaoarquivo`
  MODIFY `arquivoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `situacao`
--
ALTER TABLE `situacao`
  MODIFY `situacaoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategoria`
--
ALTER TABLE `subcategoria`
  MODIFY `subcategoriaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `tipoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
