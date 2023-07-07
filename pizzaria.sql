-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 07-Jul-2023 às 03:24
-- Versão do servidor: 5.7.36
-- versão do PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pizzaria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `CPF` varchar(14) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Idade` int(11) NOT NULL,
  `Endereco` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Senha` varchar(16) NOT NULL,
  PRIMARY KEY (`CPF`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`CPF`, `Nome`, `Idade`, `Endereco`, `Email`, `Senha`) VALUES
('00099900099', 'Pablo', 18, 'Rua contagem', 'pablo@vasc', '1234'),
('11100022251', 'Kuradio', 18, 'sao gaga', 'claudio@gmail.com', '1234'),
('70479870020', 'Julia', 19, 'Rua Tal, 470', 'julia@gmail.com', '1234'),
('76394400092', 'Lucas Roberto', 52, 'Rua São paulo, 53, bairro esse', 'lu@gmail.com', 'albergue123'),
('87831441026', 'Pedro Gustavo Hiroki', 24, 'Rua Senador Desembargador, 802, ', 'PeGuHi@gmail.com', 'MacBook123'),
('91848831055', 'Clara Assis', 74, 'Avenida Jose candindo', 'Clacla@gmail.com', 'cof132'),
('92176655095', 'Humberto', 25, 'Rua da bahia, 458', 'hum@gmail.com', '123455'),
('99999999999', 'Celsin Dela', 21, 'Carlos Prates', 'celso123@gmail.com', '1234');

--
-- Acionadores `cliente`
--
DROP TRIGGER IF EXISTS `email_correto`;
DELIMITER $$
CREATE TRIGGER `email_correto` BEFORE INSERT ON `cliente` FOR EACH ROW IF !(new.Email like '%@%') THEN
                 signal sqlstate '45000' set message_text =  'Formato de E-mail incorreto. Volte a página e tente novamente';
        END IF
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `idade_minima`;
DELIMITER $$
CREATE TRIGGER `idade_minima` BEFORE INSERT ON `cliente` FOR EACH ROW IF new.Idade < 18 THEN
                 signal sqlstate '45000' set message_text =  'Nao e permitido cadastrar menores de idade! Volte a página e tente novamente';
        END IF
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `senha_minima`;
DELIMITER $$
CREATE TRIGGER `senha_minima` BEFORE INSERT ON `cliente` FOR EACH ROW IF length(new.Senha) < 4 THEN
                 signal sqlstate '45000' set message_text =  'Nao e permitido cadastrar senha com menos de 4 dígitos! Volte a página e tente novamente';
        END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `formapagamento`
--

DROP TABLE IF EXISTS `formapagamento`;
CREATE TABLE IF NOT EXISTS `formapagamento` (
  `ID` int(11) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Numero` varchar(50) NOT NULL,
  `DataExp` varchar(10) NOT NULL,
  `CVV` varchar(4) NOT NULL,
  `CPFCliente` varchar(14) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `formapagamento`
--

INSERT INTO `formapagamento` (`ID`, `Tipo`, `Nome`, `Numero`, `DataExp`, `CVV`, `CPFCliente`) VALUES
(0, 'null', 'x', 'x', '00/00', '000', '00'),
(2, 'credito', 'Julia B F', '1234500080009000', '12/24', '123', '70479870020'),
(8, 'credito', 'claudio ', '1234567890', '11/29', '200', '11100022251'),
(14, 'credito', 'Cartao do Celso', '1234123412341234', '11/23', '444', '99999999999'),
(39, 'credito', 'Celso', '1234000012340000', '11/26', '111', '00099900099');

--
-- Acionadores `formapagamento`
--
DROP TRIGGER IF EXISTS `preenche-data`;
DELIMITER $$
CREATE TRIGGER `preenche-data` BEFORE INSERT ON `formapagamento` FOR EACH ROW update venda set DataVenda = curdate() where ID = new.ID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `IDProduto` int(11) NOT NULL,
  `Quantidade` int(11) NOT NULL,
  `PrecoItem` int(11) NOT NULL,
  `IDVenda` int(11) NOT NULL,
  PRIMARY KEY (`IDProduto`,`IDVenda`),
  KEY `I_FK_VENDA` (`IDVenda`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `item`
--

INSERT INTO `item` (`IDProduto`, `Quantidade`, `PrecoItem`, `IDVenda`) VALUES
(1, 1, 220, 14),
(1, 1, 25, 30),
(1, 3, 75, 39),
(1, 3, 75, 40),
(1, 1, 25, 41),
(2, 1, 150, 8),
(2, 1, 25, 39),
(2, 1, 25, 41),
(3, 1, 25, 41),
(4, 1, 5000, 2),
(4, 1, 5000, 8),
(5, 1, 700, 8),
(8, 1, 100, 2),
(9, 1, 140, 8);

--
-- Acionadores `item`
--
DROP TRIGGER IF EXISTS `precoVendaUpdate`;
DELIMITER $$
CREATE TRIGGER `precoVendaUpdate` BEFORE UPDATE ON `item` FOR EACH ROW update venda set PrecoTotal = (select PrecoTotal from (select PrecoTotal from venda where ID = new.IDVenda) as t) + old.PrecoItem where ID = new.IDVenda
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `precoVenda_INSERT`;
DELIMITER $$
CREATE TRIGGER `precoVenda_INSERT` BEFORE INSERT ON `item` FOR EACH ROW update venda set PrecoTotal = (select PrecoTotal from (select PrecoTotal from venda where ID = new.IDVenda) as t) + new.PrecoItem where ID = new.IDVenda
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pizza`
--

DROP TABLE IF EXISTS `pizza`;
CREATE TABLE IF NOT EXISTS `pizza` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Ingredientes` varchar(200) NOT NULL,
  `Imagem` text,
  `Tipo` varchar(50) NOT NULL,
  `Tamanho` varchar(2) NOT NULL,
  `Preco` float NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Nome` (`Nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `pizza`
--

INSERT INTO `pizza` (`ID`, `Nome`, `Ingredientes`, `Imagem`, `Tipo`, `Tamanho`, `Preco`) VALUES
(1, 'Pizza de Calabresa', 'Massa, molho de tomate, mussarela, cebola, calabresa e azeitona', 'calabresa.jpg', 'Salgado', 'G', 25),
(2, 'Pizza 4 Queijos', 'Mussarela, Parmesao, Catupiry, Prato', '4queijos.jpg', 'Salgado', 'G', 25),
(3, 'Pizza de Milho e Bacon', 'Milho e Bacon', 'bacon.jpg', 'Salgado', 'G', 25),
(4, 'Pizza de Margherita', 'Tomate, Queijo, Manjericao', 'margherita.jpg', 'Salgado', 'G', 25),
(5, 'Pizza Portuguesa', 'Bacon, Calabresa, Presunto, Queijo, Milho, Ovo cozido, Cebola, Pimentao', 'portuguesa.jpg', 'Salgada', 'G', 30),
(6, 'Pizza Morango com chocolate', 'Morango, Ganache de chocolate meio amargo', 'morango.jpg', 'Doce', 'P', 30),
(7, 'Pizza a Moda', 'Bacon, Calabresa, Presunto, Queijo, Milho, Cebola, Pimentao', 'moda.jpg', 'Salgado', 'G', 30),
(8, 'Pizza de Banana', 'Banana, Ganache, Nutella', 'banana.jpg', 'Doce', 'P', 30),
(9, 'Pizza de doce de leite', 'Doce de leite', 'doceLeite.jpg', 'Doce', 'P', 30),
(10, 'Guaraná', 'Guarana', 'guarana.jpg', 'Bebidas', '2L', 10),
(11, 'Coca cola', 'Coca cola', 'cocacola.jpg', 'Bebidas', '2L', 10),
(12, 'Combo Casal ', 'Combo com uma pizza Margherita, pizza de brigadeiro e Coca-Cola', 'comboCasal.jpg', 'Combo', 'GG', 65),
(13, 'Combo da Tropa', 'Combo com uma pizza Milho e Bacon, pizza a Moda e Portuguesa', 'comboTropa.jpg', 'Combo', 'GG', 60),
(14, 'Combo Familia', 'Combo com uma pizza de Calabresa, pizza de 4 queijos e Guaraná', 'comboFamilia.jpg', 'Combo', 'GG', 60),
(15, 'Galao de agua', 'Galao e agua', 'agua.jpg', 'Combo', '20', 39),
(16, 'Pizza Frango com Catupiry', 'Frango, Catupiry', 'frango.jpg', 'Salgada', 'G', 30);

-- --------------------------------------------------------

--
-- Estrutura da tabela `venda`
--

DROP TABLE IF EXISTS `venda`;
CREATE TABLE IF NOT EXISTS `venda` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DataVenda` varchar(10) DEFAULT NULL,
  `PrecoTotal` float DEFAULT NULL,
  `Endereco` varchar(100) DEFAULT NULL,
  `CPFCliente` varchar(14) DEFAULT NULL,
  `IDFormaPagamento` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_cliente` (`CPFCliente`),
  KEY `FK_FP` (`IDFormaPagamento`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `venda`
--

INSERT INTO `venda` (`ID`, `DataVenda`, `PrecoTotal`, `Endereco`, `CPFCliente`, `IDFormaPagamento`) VALUES
(2, '2022-12-02', 5100, 'Rua betinho, bairro Jardim, Betim, MG', '70479870020', 2),
(6, NULL, 0, NULL, '00099900099', 0),
(8, '2022-12-08', 5990, 'rua sao gabriel', '11100022251', 8),
(9, NULL, 0, NULL, '00099900099', 0),
(10, NULL, 0, NULL, '00099900099', 0),
(11, NULL, 0, NULL, '00099900099', 0),
(12, NULL, 0, NULL, '00099900099', 0),
(13, NULL, 0, NULL, '00099900099', 0),
(14, '2023-07-06', 220, 'Carlos Prates', '99999999999', 14),
(15, NULL, 0, NULL, '00099900099', 0),
(16, NULL, 0, NULL, '00099900099', 0),
(17, NULL, 0, NULL, '00099900099', 0),
(18, NULL, 0, NULL, '00099900099', 0),
(19, NULL, 0, NULL, '00099900099', 0),
(20, NULL, 0, NULL, '00099900099', 0),
(21, NULL, 0, NULL, '00099900099', 0),
(22, NULL, 0, NULL, '00099900099', 0),
(23, NULL, 0, NULL, '00099900099', 0),
(24, NULL, 0, NULL, '00099900099', 0),
(25, NULL, 0, NULL, '00099900099', 0),
(26, NULL, 0, NULL, '00099900099', 0),
(27, NULL, 0, NULL, '00099900099', 0),
(28, NULL, 0, NULL, '00099900099', 0),
(29, NULL, 0, NULL, '00099900099', 0),
(30, NULL, 25, NULL, '00099900099', 0),
(31, NULL, 0, NULL, '00099900099', 0),
(32, NULL, 0, NULL, '00099900099', 0),
(33, NULL, 0, NULL, '00099900099', 0),
(34, NULL, 0, NULL, '00099900099', 0),
(35, NULL, 0, NULL, '00099900099', 0),
(36, NULL, 0, NULL, '00099900099', 0),
(37, NULL, 0, NULL, '00099900099', 0),
(38, NULL, 0, NULL, '00099900099', 0),
(39, '2023-07-06', 125, 'rua do celso ', '00099900099', 39),
(40, NULL, 100, NULL, '99999999999', 0),
(41, NULL, 75, NULL, '99999999999', 0);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `I_FK_PRODUTO` FOREIGN KEY (`IDProduto`) REFERENCES `pizza` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `I_FK_VENDA` FOREIGN KEY (`IDVenda`) REFERENCES `venda` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `FK_FP` FOREIGN KEY (`IDFormaPagamento`) REFERENCES `formapagamento` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_cliente` FOREIGN KEY (`CPFCliente`) REFERENCES `cliente` (`CPF`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
