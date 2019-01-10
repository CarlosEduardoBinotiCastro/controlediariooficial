-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 09-Jan-2019 às 11:58
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
(3, 'Teste'),
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
(3, 3),
(3, 4),
(3, 5),
(4, 1),
(4, 5),
(7, 1),
(7, 3),
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
(37, '2019-01-09', '5737');

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
  `comprovantePago` varchar(150) DEFAULT NULL,
  `situacaoID` int(11) NOT NULL,
  `descricaoCancelamento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fatura`
--

INSERT INTO `fatura` (`protocolo`, `protocoloAno`, `protocoloCompleto`, `dataEnvioFatura`, `tipoID`, `subcategoriaID`, `usuarioID`, `largura`, `centimetragem`, `valorColuna`, `valor`, `diarioDataID`, `observacao`, `cpfCnpj`, `empresa`, `requisitante`, `arquivoOriginal`, `arquivoFormatado`, `arquivoVisualizacao`, `comprovantePago`, `situacaoID`, `descricaoCancelamento`) VALUES
(0, 2019, '02019FAT', '2019-01-07 11:45:45', 46, NULL, 1, 9.2, 2.82, 18.85, 53.16, 37, 'As experiências acumuladas demonstram que a mobilidade dos capitais internacionais não pode mais se dissociar de alternativas às soluções ortodoxas.', '12111444444444', 'A Praça é NoSsaaaA', 'Carlos Alberto de Nóbrega da Çilva Túlio aaaaaaaaa', '12111444444444-2019-01-07-11-44-27.docx', '12111444444444-2019-01-07-11-44-27_format.docx', '12111444444444-2019-01-07-11-44-27_visualizacao.pdf', '02019FAT_comprovantePago.pdf', 2, NULL),
(1, 2019, '12019FAT', '2019-01-07 12:12:22', 42, NULL, 1, 9.2, 1.41, 18.85, 26.58, NULL, NULL, '98654798565', 'asdasd', 'Eduardo Castro', '98654798565-2019-01-07-12-12-18.docx', '98654798565-2019-01-07-12-12-18_format.docx', '98654798565-2019-01-07-12-12-19_visualizacao.pdf', '12019FAT_comprovantePago.pdf', 2, 'dfsdfs'),
(2, 2019, '22019FAT', '2019-01-07 13:23:23', 20, NULL, 1, 9.2, 1.41, 18.85, 26.58, NULL, NULL, '12313131231', 'asda', 'dedu', '12313131231-2019-01-07-13-23-21.docx', '12313131231-2019-01-07-13-23-21_format.docx', '12313131231-2019-01-07-13-23-21_visualizacao.pdf', NULL, 2, 'teste'),
(3, 2019, '32019FAT', '2019-01-07 13:35:44', 44, NULL, 1, 9.2, 1.41, 18.85, 26.58, NULL, NULL, '13131231231', 'teste', 'teste4', '13131231231-2019-01-07-13-35-42.docx', '13131231231-2019-01-07-13-35-42_format.docx', '13131231231-2019-01-07-13-35-43_visualizacao.pdf', NULL, 2, NULL),
(4, 2019, '42019FAT', '2019-01-08 10:56:28', 53, 10, 13, 9.2, 1.41, 18.85, 26.58, NULL, NULL, '9999999999999', 'xxxxx', 'xxxx', '9999999999999-2019-01-08-10-56-22.docx', '9999999999999-2019-01-08-10-56-22_format.docx', '9999999999999-2019-01-08-10-56-22_visualizacao.pdf', '42019FAT_comprovantePago.pdf', 3, NULL),
(5, 2019, '52019FAT', '2019-01-08 11:03:06', 20, NULL, 1, 9.2, 1.41, 18.85, 26.58, NULL, 'aaaaaa', '65446546546', 'teste', 'teste', '65446546546-2019-01-08-11-02-51.docx', '65446546546-2019-01-08-11-02-51_format.docx', '65446546546-2019-01-08-11-02-51_visualizacao.pdf', NULL, 4, NULL),
(6, 2019, '62019FAT', '2019-01-08 11:05:46', 20, NULL, 1, 9.2, 1.41, 18.85, 26.58, NULL, 'ta', '12312312312', 'teste2', 'teste2', '12312312312-2019-01-08-11-05-44.docx', '12312312312-2019-01-08-11-05-44_format.docx', '12312312312-2019-01-08-11-05-44_visualizacao.pdf', NULL, 4, NULL);

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
(3, 'Fatura');

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
(8, 'Diario Oficial');

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
('eduardbinoti@gmail.com', '$2y$10$jhgW63tP7BoVaWvULtQVw.RVGsbe4iafOpMV8LT6ht6AjucnjNU3S', '2018-12-05 13:55:06'),
('pmci.diario.oficial@gmail.com', '$2y$10$n5P1/OxN/FZaaSkF0e7t3.ylOKVWw.cz96SfXWrFWjGOpXYKQA7eu', '2019-01-08 12:43:21');

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
  `arquivo` varchar(255) NOT NULL,
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

--
-- Extraindo dados da tabela `publicacao`
--

INSERT INTO `publicacao` (`situacaoID`, `cadernoID`, `tipoID`, `usuarioID`, `diarioDataID`, `dataEnvio`, `arquivo`, `descricao`, `titulo`, `protocolo`, `protocoloAno`, `protocoloCompleto`, `pub`, `usuarioIDApagou`, `dataApagada`, `rejeitadaDescricao`) VALUES
(2, 6, 42, 1, 37, '2019-01-07 13:34:45', '12019-01-07-13-34-45.pdf', 'asdasdasd', 'dddd', 0, 2019, '02019PUB', 'pub', 1, '2019-01-08 09:54:10', NULL),
(3, 1, 10, 9, 37, '2019-01-08 08:55:09', '92019-01-08-08-55-09.doc', 'SUPLEMENTAÇÃO DE DOTAÇÕES ORÇAMENTÁRIAS', 'Decreto nº 27.443/2017', 1, 2019, '12019PUB', 'pub', NULL, NULL, NULL),
(3, 1, 11, 9, 36, '2019-01-08 08:59:16', '92019-01-08-08-59-16.doc', 'Afastamento em virtude de luto da servidora Marília Barboza Fernandes', 'Portaria nº 1.015/2017', 2, 2019, '22019PUB', 'pub', NULL, NULL, NULL),
(2, 6, 34, 1, 37, '2019-01-08 09:54:54', '12019-01-08-09-54-54.pdf', 'teste teste teste teste teste', 'teste de licitação para teste de titulo', 3, 2019, '32019PUB', 'pub', 1, '2019-01-08 09:55:02', NULL),
(4, 1, 3, 1, 37, '2019-01-08 11:29:15', '92019-01-08-10-48-39.doc', 'teste', 'Lei 76/2019', 4, 2019, '42019PUB', 'pub', NULL, NULL, 'art 1');

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
(13, 'Licença de Instalação e AMPLIAÇÃO da Licença de Operação', 53),
(14, 'Licença de Operação Corretiva', 53),
(15, 'AMPLIAÇÃO da Licença de Instalação e a AMPLIAÇÃO e RENOVAÇÃO da Licença de Operação', 53);

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
(1, 'carlos eduardo castro', 'eduardbinoti@gmail.com', '56310841041', '2835224896', '28999654178', '$2y$10$1Q02pW1d0l9MjjvCo8UJeOdyFinyS0ixN8eEPyjaH./ll5EpZbMfi', 'oO25IncjDwfaVsXQ1MRCqGAVLts7Hh1GguPQL7YqqSMpVhpCdp16RlNdDTzc', NULL, '2018-11-13 17:36:26', 1, 1, 1, '17:00:00', 'dedu', 0),
(8, 'mauricio', 'mauriciopicoli@hotmail.com', '08094627700', '2835112713', '99999999999', '$2y$10$fo3r21dtnAXqLSYRXE2ly.jfn.nv70zigr4C5p1FT.83rKhQ75v5O', 'JWKf78vhUyPcQNbgenV7B2wh149aEohMVVhFK37XYqK1uK4h4vK15GDvd6hu', NULL, '2018-12-05 12:12:59', 1, 4, 1, '17:00:00', 'diario', 0),
(9, 'cliente', 'cliente@gmail.com', '86886479091', '4444444444', '44444444444', '$2y$10$sMt76Ggii4/JrL03hhxPmeDdwgNKeSqfjuHJbk7cKvQiy0EAtkqpa', 'VC7xqzHQfhjMV6yeexLsiKsnaVMUVCYZzKqHhRboJ2qLwkZL1diTCi8e1sOQ', NULL, '2018-12-05 13:39:10', 2, 4, 1, '17:00:00', 'cliente', 0),
(13, 'Informatica', 'semad.informatica@cachoeiro.es.gov.br', '02781851779', '2835112713', '28992532511', '$2y$10$c82jLr9IeLaZJ/qnxxfAK.bCgvIfHePNB.JIvmI56jAItpN4Oa9Rm', 'pQs4HVC9gvqdWetyYvRW6bqlHRStrSwpnYZctMFWRWmeunkRY5PlwpaS35IL', NULL, '2019-01-02 19:14:46', 1, 5, 1, '17:00:00', 'informatica', 0),
(15, 'Santa Gama de Freitas', 'pmci.diario.oficial@gmail.com', '93023057753', '2835224708', '99999999999', '$2y$10$BsV8y5hdAfLPPcDep8lofOvoA9xtMEiE3gDUsWMs9.wvB19GdR6Dm', NULL, NULL, '2019-01-04 17:29:41', 1, 8, 1, '17:00:00', 'santa', 0),
(16, 'Araci da Cunha', 'camara@cmci.es.gov.br', '53595025687', '2899999999', '99999999999', '$2y$10$m0t6XacYy/oUK8yQwgtlr.8ZsXVgKxjeCsDOQGbtZ5kKPdG9vjCvK', '3k2AR5K1HRKVHKevn0xvIDdrdmtxdZGFqaiQiVOrw4QJTcgqUWiNqrhkIu3s', NULL, '2019-01-08 11:20:55', 2, 5, 1, '17:00:00', 'araci', 0);

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
(3, 3),
(5, 1),
(5, 5),
(6, 3),
(7, 1),
(8, 1),
(8, 4),
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
(16, 1);

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
  MODIFY `cadernoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `configuracaofatura`
--
ALTER TABLE `configuracaofatura`
  MODIFY `configID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diariodata`
--
ALTER TABLE `diariodata`
  MODIFY `diarioDataID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `diasnaouteis`
--
ALTER TABLE `diasnaouteis`
  MODIFY `diaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `grupousuario`
--
ALTER TABLE `grupousuario`
  MODIFY `grupoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orgaorequisitante`
--
ALTER TABLE `orgaorequisitante`
  MODIFY `orgaoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `subcategoriaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `tipoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
