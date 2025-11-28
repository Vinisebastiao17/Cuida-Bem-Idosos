-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 04:48
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cuidabem`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `horario` time NOT NULL,
  `frequencia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios`
--

INSERT INTO `horarios` (`id_horario`, `horario`, `frequencia`) VALUES
(1, '08:00:00', 'Diário'),
(2, '12:00:00', 'Diário'),
(3, '16:00:00', 'Diário'),
(4, '20:00:00', 'Diário');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicamento`
--

CREATE TABLE `medicamento` (
  `id_medicamento` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `dosagem` varchar(50) DEFAULT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `medicamento`
--

INSERT INTO `medicamento` (`id_medicamento`, `nome`, `dosagem`, `observacao`) VALUES
(1, 'Paracetamol', '1 comprimido', 'Tomar com água'),
(2, 'Omeprazol', '1 cápsula', 'Em jejum'),
(3, 'Losartana', '2 comprimido', 'Controlar pressão'),
(4, 'Metformina', '1 comprimido', 'Após refeições');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicamentos_tratamentos`
--

CREATE TABLE `medicamentos_tratamentos` (
  `id_medicamento` int(11) NOT NULL,
  `id_tratamento` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `status` enum('pendente','concluído') DEFAULT 'pendente',
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacao`
--

CREATE TABLE `notificacao` (
  `id` int(11) NOT NULL,
  `id_medicamento` int(11) DEFAULT NULL,
  `id_tratamento` int(11) DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `email` tinyint(1) DEFAULT 0,
  `whatsapp` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `cpf` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sintoma`
--

CREATE TABLE `sintoma` (
  `id_sintoma` int(11) NOT NULL,
  `tipo` enum('condicao','sintoma') NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `intensidade` varchar(50) DEFAULT NULL,
  `data_registro` datetime DEFAULT current_timestamp(),
  `id_paciente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tratamento`
--

CREATE TABLE `tratamento` (
  `id_tratamento` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tratamento`
--

INSERT INTO `tratamento` (`id_tratamento`, `nome`, `descricao`) VALUES
(1, 'Tratamento Hipertensão', 'Controle da pressão arterial'),
(2, 'Tratamento Diabetes', 'Controle glicêmico');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('idoso','cuidador','familiar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'Luiz Gustavo da Silva Damazio', '4540672168@estudante.sed.sc.gov.br', '$2y$10$ESSbiNQgO1roijpzP2qZ4uZdx3i9XTnoUPcyt6QI11HF6hZqz1P.i', 'idoso'),
(2, 'Luiz Gustavo da Silva Damazio', 'luizgdamazio@gmail.com', '$2y$10$H1ZHin8UPAANBQ06yeyMJeIJWP9Ni9c8rHKG.p3j9wJy9RWqOAEvC', 'idoso'),
(3, 'Maria de Lurdes', 'Marilus157@gmail.com', '$2y$10$C0geStqjBA3FcWjqhDNpz.oDymOBqQAG8MO7k7hTUijOy0PJQdNmK', 'idoso'),
(4, 'Arthur Fernando', 'ArthurFpetry@gmail.com', '$2y$10$spFkrwo0qG748NjBEl7PJupLBhM5Rp/HqBTyo36LIaR.3zYPkz5o2', 'cuidador'),
(5, 'vinicius Sebastião', '4540994262@estudante.sed.sc.gov.br', '$2y$10$FT.1NRVFjPOc/ifZNdBGMOD4HUmvIOdAsvXGqfTAvY1LsguC5WZ3y', 'familiar');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Índices de tabela `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`id_medicamento`);

--
-- Índices de tabela `medicamentos_tratamentos`
--
ALTER TABLE `medicamentos_tratamentos`
  ADD PRIMARY KEY (`id_medicamento`,`id_tratamento`,`id_paciente`,`id_horario`),
  ADD KEY `id_tratamento` (`id_tratamento`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_horario` (`id_horario`);

--
-- Índices de tabela `notificacao`
--
ALTER TABLE `notificacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notificacao_horario` (`id_horario`);

--
-- Índices de tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `sintoma`
--
ALTER TABLE `sintoma`
  ADD PRIMARY KEY (`id_sintoma`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Índices de tabela `tratamento`
--
ALTER TABLE `tratamento`
  ADD PRIMARY KEY (`id_tratamento`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `notificacao`
--
ALTER TABLE `notificacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `sintoma`
--
ALTER TABLE `sintoma`
  MODIFY `id_sintoma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tratamento`
--
ALTER TABLE `tratamento`
  MODIFY `id_tratamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `medicamentos_tratamentos`
--
ALTER TABLE `medicamentos_tratamentos`
  ADD CONSTRAINT `medicamentos_tratamentos_ibfk_1` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicamentos_tratamentos_ibfk_2` FOREIGN KEY (`id_tratamento`) REFERENCES `tratamento` (`id_tratamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicamentos_tratamentos_ibfk_3` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE,
  ADD CONSTRAINT `medicamentos_tratamentos_ibfk_4` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacao`
--
ALTER TABLE `notificacao`
  ADD CONSTRAINT `fk_notificacao_horario` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`) ON DELETE SET NULL;

--
-- Restrições para tabelas `sintoma`
--
ALTER TABLE `sintoma`
  ADD CONSTRAINT `sintoma_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
