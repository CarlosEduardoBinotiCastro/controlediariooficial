-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 02-Jan-2019 às 14:47
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
(1, 'Executivo'),
(3, 'Teste'),
(4, 'Legislação'),
(5, 'Diversos');

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
(3, 1),
(3, 3),
(3, 4),
(3, 5),
(4, 1),
(7, 1),
(7, 3),
(7, 5),
(8, 4),
(9, 4);

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
(1, 9.5, 18.85, 5);

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
(33, '2019-01-31', '45912');

-- --------------------------------------------------------

--
-- Estrutura da tabela `diasnaouteis`
--

CREATE TABLE `diasnaouteis` (
  `diaID` int(11) NOT NULL,
  `diaNaoUtilData` date NOT NULL,
  `diaDescricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `subcategoriaID` int(11) NOT NULL,
  `usuarioID` int(11) NOT NULL,
  `largura` double NOT NULL,
  `centimetragem` double NOT NULL,
  `valorColuna` double NOT NULL,
  `valor` double NOT NULL,
  `diarioDataID` int(11) NOT NULL,
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
(2, 'Usuário');

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
(4, 'Prefeitura');

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
('eduardbinoti@gmail.com', '$2y$10$jhgW63tP7BoVaWvULtQVw.RVGsbe4iafOpMV8LT6ht6AjucnjNU3S', '2018-12-05 13:55:06');

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
(1, 'Lei Trabalhista', 3),
(3, 'Contrato de Obra', 7),
(4, 'Contrato de Serviço', 7),
(5, 'Lei Consumidor', 3);

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
(8, 'documento teste'),
(9, 'legislacionario');

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
(1, 'carlos eduardo castro', 'eduardbinoti@gmail.com', '78598523654', '2835224896', '28999654178', '$2y$10$1Q02pW1d0l9MjjvCo8UJeOdyFinyS0ixN8eEPyjaH./ll5EpZbMfi', 'y7JdNKEk0D1XBdDEhGBkQYyGn5CccfNBq9dVSKBLbtJmUPXOnBTLUIhCk5zd', NULL, '2018-11-13 17:36:26', 1, 1, 1, '17:00:00', 'dedu', 0),
(2, 'Joao Silva Gomes', 'joao@gmail.com', '32156987541', '2875469845', '28996658754', '$2y$10$HXAhM5FiuGp/gy3Xv3O3KeXnAywxyt0c7y/XrC6wrFBTFlrms069.', 'HcYC0xhaVDq4DvQzM9ZuQjzb2Gn3eBXKlwpzboVFfrHYDCFp8dmQtXL53dDj', NULL, NULL, 2, 2, 1, '17:00:00', 'joao', 0),
(3, 'Tista Binoti', 'tista@gmail.com', '12365478521', '2832224569', '28666541785', '$2y$10$sSHqzbieeqZZ7bjKI2q8..I30m3aJAbPCfUTQsNRgjGiTFvFC0rTq', 'lA2h4sxZkP5xsi2z0H2U6U0hSFQQIBwbbBdQ4kh4Q5BpKidHpSTB2bKDM3Ub', NULL, NULL, 2, 1, 2, '17:00:00', 'tista', 0),
(5, 'Eduardo', 'dedubr@gmail.com', '32165498799', '2865658955', '28956565656', '$2y$10$d4Dg92diRF66c23cKnu3hurCyQPKo8hxNrzUaNHX5tPAqPjNqs.T.', '6VWYrs5wD3izRj2LGNcCCVmgFGQGU59UvACH5gI6QSbmDDuWB5PpcYs6Atym', NULL, '2018-12-05 11:00:03', 1, 4, 1, '17:00:00', 'dedubr', 0),
(6, 'amerio', 'amerio@gmail.com', '11515141414', '1321312312', '12312312312', '$2y$10$hCj.4QTbdyMqvsg8lYNtjOyBUdQ7fOU97UrD.D9M.QLEwEfQoQynC', NULL, NULL, NULL, 1, 1, 2, '17:00:00', 'amerio', 1),
(7, 'pedro silva', 'pedro@gmail.com', '44444444444', '3232323232', '35656565656', '$2y$10$FwG0eCG27dPN3Bn.F3ahDO8SYsOG5RLbK3.RAHK8Gw7LAqTFqIGBO', 'F4ugB1y3EIkbZB3KSRPsqrHAt2G94T2x8PhqkBVqSAWjma3oLvtch6gYCLtI', NULL, '2018-12-05 11:04:49', 2, 1, 1, '17:00:00', 'pedro', 0),
(8, 'mauricio', 'mauriciopicoli@hotmail.com', '99999999999', '9999999999', '99999999999', '$2y$10$fo3r21dtnAXqLSYRXE2ly.jfn.nv70zigr4C5p1FT.83rKhQ75v5O', 'ZjiDjlaUoDjAkAD7xk4I2B9uIf8iuFLXeeOT9NZ1Opc0PvFv84wqNCn3feP8', NULL, '2018-12-05 12:12:59', 1, 4, 1, '17:00:00', 'diario', 0),
(9, 'cliente', 'cliente@gmail.com', '22324544444', '4444444444', '44444444444', '$2y$10$sMt76Ggii4/JrL03hhxPmeDdwgNKeSqfjuHJbk7cKvQiy0EAtkqpa', 'ZNbJo4nDPgMGQR7ZrqIDZF66wP1bb61hCceiMlYiQK93u696bMzI1E3KcurX', NULL, '2018-12-05 13:39:10', 2, 4, 1, '17:00:00', 'cliente', 0),
(10, 'josenildo', 'josenildo@josenildo.com', '24242424243', '1234411231', '12313123142', '$2y$10$y5qzs1NQCyzGbLAL.eCeoujBrP4zZdBFtOYCk.3Bp9ZMZK.3I26j6', 'kGwhdDmEMRcPv4QUhFMpmgTmd5CzbQrPvzPiuaLoecyqZVe7NPEIDMNeLoRE', NULL, '2018-12-05 11:37:50', 2, 4, 1, '17:00:00', 'josenildo', 0),
(11, 'carlos tinil', 'carl@gmail.com', '28909175095', '1231231312', '12312312312', '$2y$10$ykDqoi73JQAL.cR.QmglXOsxLC7DbIdYkdaEzcTm3VmPQ7.FJ63gm', NULL, NULL, NULL, 1, 1, 1, '17:00:00', 'carl', 1);

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
(1, 4),
(2, 1),
(3, 3),
(5, 1),
(5, 4),
(6, 3),
(7, 1),
(8, 1),
(8, 4),
(9, 1),
(9, 4),
(10, 4),
(11, 1);

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
  MODIFY `cadernoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `configuracaofatura`
--
ALTER TABLE `configuracaofatura`
  MODIFY `configID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diariodata`
--
ALTER TABLE `diariodata`
  MODIFY `diarioDataID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `diasnaouteis`
--
ALTER TABLE `diasnaouteis`
  MODIFY `diaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupousuario`
--
ALTER TABLE `grupousuario`
  MODIFY `grupoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orgaorequisitante`
--
ALTER TABLE `orgaorequisitante`
  MODIFY `orgaoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `subcategoriaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `tipoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
