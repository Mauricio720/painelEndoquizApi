-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2021 às 02:12
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `endoquiz`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `classifieds`
--

CREATE TABLE `classifieds` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `classifieds`
--

INSERT INTO `classifieds` (`id`, `name`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(3, 'Novo Classificado', '2021-10-03', '21:03:01', 1),
(6, 'classificado', '2021-10-14', '21:49:33', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `classified_data`
--

CREATE TABLE `classified_data` (
  `id` int(11) NOT NULL,
  `id_classified_subtopic` int(11) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `classified_data`
--

INSERT INTO `classified_data` (`id`, `id_classified_subtopic`, `content`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(1, 1, 'Teste', '2021-10-04', '08:55:56', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `classified_subtopics`
--

CREATE TABLE `classified_subtopics` (
  `id` int(11) NOT NULL,
  `id_classified_topic` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `classified_subtopics`
--

INSERT INTO `classified_subtopics` (`id`, `id_classified_topic`, `name`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(1, 1, 'teste124564 EDITADO2', '2021-10-04', '08:12:09', 1),
(5, 1, 'novo subtopico', '2021-10-11', '15:11:42', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `classified_topics`
--

CREATE TABLE `classified_topics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `id_classified` int(11) NOT NULL DEFAULT 0,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `classified_topics`
--

INSERT INTO `classified_topics` (`id`, `name`, `id_classified`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(1, 'novo topico EDITADO', 3, '2021-10-03', '21:44:55', 1),
(4, 'topico lala.', 3, '2021-10-04', '11:18:13', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `default_alternative_questions`
--

CREATE TABLE `default_alternative_questions` (
  `id` int(11) NOT NULL,
  `id_default_question` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `is_correct` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `default_alternative_questions`
--

INSERT INTO `default_alternative_questions` (`id`, `id_default_question`, `description`, `is_correct`) VALUES
(1, 1, 'Alternativa 1 (EDITADA)', 0),
(2, 1, 'Alternativa 2', 0),
(3, 1, 'Alternativa 3', 1),
(4, 1, 'Alternativa 4', 0),
(7, 3, 'dasfffffffffffff', 0),
(8, 3, 'asfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfd', 0),
(9, 3, 'asdffffffffffff', 0),
(10, 3, 'asffffffffffffffffffffffff', 1),
(11, 3, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfd', 0),
(24, 2, 'asdfffffffffffffffffffffffffffff', 0),
(25, 2, 'NOVA ALTERNATIVA', 1),
(38, 4, 'Teste', 0),
(39, 4, 'sdafffffffffffffff', 0),
(40, 4, 'asfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfd', 0),
(41, 4, 'sfffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffg', 1),
(44, 5, 'dasffffffffffffffffffffffffffff', 1),
(45, 5, 'afdssssssssssssssssssssssssssssssssss', 0),
(46, 5, 'asdddddddddddddddddddddddddd', 0),
(47, 6, 'asdasddasdasdas asasdasdasd', 1),
(48, 6, 'dasfasfasfasfasfsdafsafd', 0),
(61, 8, 'sdaaaaaaaaaaaaaa', 0),
(62, 8, 'sadadadadadadadadadadadadadadad', 1),
(63, 7, 'alternativa editada 1', 0),
(64, 7, 'alternativa editada 2', 1),
(65, 9, 'alternativa 1', 0),
(66, 9, 'alternativa 2', 1),
(67, 10, 'ALTERNATIVA 1', 1),
(68, 10, 'ALTERNATIVA 2', 0),
(93, 11, 'alternativa1', 0),
(94, 11, 'alternativa2', 0),
(95, 11, 'alternativa3', 1),
(96, 11, 'alternativa4', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `default_questions`
--

CREATE TABLE `default_questions` (
  `id` int(11) NOT NULL,
  `id_subject_topics` varchar(255) NOT NULL DEFAULT '0',
  `question` text NOT NULL,
  `image` varchar(55) DEFAULT '',
  `video` text DEFAULT NULL,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idUserRegister` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `premium` tinyint(4) NOT NULL DEFAULT 0,
  `justifyContent` text DEFAULT NULL,
  `justifyImage` varchar(60) DEFAULT '',
  `justifyVideoLink` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `default_questions`
--

INSERT INTO `default_questions` (`id`, `id_subject_topics`, `question`, `image`, `video`, `registerDate`, `registerTime`, `idUserRegister`, `active`, `premium`, `justifyContent`, `justifyImage`, `justifyVideoLink`) VALUES
(1, '1,4', 'sfdaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '7ecb8e4bb13a8d7085c26a8e2daf0986.jpg', '', '2021-09-22', '16:38:48', 0, 0, 1, '', '', ''),
(2, '1,4,5', 'sadadadadadadadadadadadadEDITADOOOOOOOOOOOOOOEDITADOOOOOOOOOOOOOOEDITADOOOOOOOOOOOOOOEDITADOOOOO', '7ecb8e4bb13a8d7085c26a8e2daf0986.jpg', '', '2021-09-22', '17:32:00', 0, 1, 1, '', '', ''),
(3, '1,4', 'asfffffffffffffffffffffffffffffffffffffffffffff', '', '', '2021-09-22', '17:49:37', 0, 1, 1, '', '', ''),
(4, '4,5', 'dasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasf dasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfas\r\nasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfasasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfasasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfasasfasfasfasfasfasfasfasfasfasfasfasfasfasfdasfasfasfasfasfasfasfasfasfasfas', '043bf53785e8f98da544a153832b0cdc.jpg', '', '2021-09-22', '17:53:34', 0, 1, 1, '', '', ''),
(5, '1,4', 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdsdf sdsdsdsdsdsdsdsdsdsdsdsdsd sdadddddddddddddddddddddd', '', '', '2021-09-27', '08:49:28', 0, 1, 1, '', '', ''),
(6, '1', 'fdsssssssssssssssssssfdsfdsfdsfdsfdsfdsfdsfdsfdsfd', '9461ea277fa162f091d367720d76baca.jpg', 'https://www.youtube.com/watch?v=SbeMrbJ6R9E&t=16s', '2021-09-27', '11:25:35', 0, 1, 0, '', '', ''),
(7, '1', 'TESTEEEEEEEEE', '1508c74c6b5a8e594278baeeba2b7754.jpg', 'https://www.youtube.com/watch?v=XDdZs1dgn9E', '2021-10-14', '16:11:34', 0, 1, 0, '', '', ''),
(8, '2', 'afdssssssssssssssssssssssssssssssssssssss', '', '', '2021-10-14', '21:25:33', 0, 0, 0, '', '', ''),
(9, '5', 'NOVA QUESTÃAAAAAAAAAAAAO', '', '', '2021-11-16', '16:27:35', 0, 0, 0, 'AAAAAAAAAAAAAAAAAAAAAAAAAA', '', ''),
(10, '1,4', 'QUESTÃAAAAAAAAAO NOOOOOVAAAAAAAAA', '', '', '2021-11-16', '16:34:12', 0, 1, 0, 'CXZZZZZZZZZZZZZZZZZ', '', ''),
(11, '1,4', 'DFAAAAAAAAAAJKdsfaaaaaaaaaaaaaaaaaaaaaaaa', '85c8fb674b2b8ebbbe0276751862bd0f.jpg', NULL, '2021-11-16', '16:42:58', 0, 1, 0, 'JUSTIFICANDO A JUSTIFICATIVA', 'f03738c4e32d335baafa29fee5f769c4.jpg', 'https://www.youtube.com/watch?v=213122J-omY');

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plan_payments`
--

CREATE TABLE `plan_payments` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `typePlan` int(11) NOT NULL DEFAULT 0 COMMENT '1-GRATUITO; 2-PLANO PREMIUM',
  `limitQuestions` int(11) DEFAULT 0,
  `idUser` int(11) NOT NULL DEFAULT 0,
  `questionsAnswered` int(11) NOT NULL DEFAULT 0,
  `linkMercadoPago` varchar(450) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `externalReference` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `plan_payments`
--

INSERT INTO `plan_payments` (`id`, `date`, `time`, `value`, `typePlan`, `limitQuestions`, `idUser`, `questionsAnswered`, `linkMercadoPago`, `status`, `externalReference`) VALUES
(1, '2021-10-18', '11:10:52', '0.00', 1, 0, 1, 0, 'https://www.mercadopago.com.br/checkout/v1/redirect?pref_id=777857152-61001447-343f-4be8-ac49-d7d61ee5698e', NULL, 'b9f55fe93dcb20df5719f05c791a2918');

-- --------------------------------------------------------

--
-- Estrutura da tabela `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(1, 'Teste EDITADOO 98989', '2021-09-16', '22:51:56', 1),
(3, 'teste124564', '2021-09-17', '11:47:42', 1),
(7, 'classificado', '2021-10-14', '21:38:52', 1),
(8, 'NOVO AGORA', '2021-11-16', '17:12:00', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `subject_topics`
--

CREATE TABLE `subject_topics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `idSubject` int(11) NOT NULL DEFAULT 0,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `idRegisterUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `subject_topics`
--

INSERT INTO `subject_topics` (`id`, `name`, `idSubject`, `registerDate`, `registerTime`, `idRegisterUser`) VALUES
(1, 'subtopicoasddasd', 1, '2021-09-17', '16:47:44', 1),
(2, 'subtopico2', 3, '2021-09-17', '16:48:07', 1),
(4, 'subtopico EDITADOo', 1, '2021-09-20', '08:21:17', 1),
(5, 'subtopicoasddasd', 1, '2021-09-20', '08:25:08', 1),
(7, 'teste2', 5, '2021-09-28', '11:37:45', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `supports`
--

CREATE TABLE `supports` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL DEFAULT 0,
  `subject` varchar(255) NOT NULL DEFAULT '0',
  `idUserPainel` int(11) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `supports`
--

INSERT INTO `supports` (`id`, `idUser`, `subject`, `idUserPainel`, `status`, `registerDate`, `registerTime`) VALUES
(1, 3, 'Teste', 0, 0, '2021-10-06', '12:53:27'),
(2, 3, 'Teste', 0, 1, '2021-10-06', '14:51:21'),
(3, 3, 'teste SWAGGER', 0, 1, '2021-10-06', '14:51:30'),
(4, 1, 'Teste', 0, 1, '2021-10-12', '19:55:29'),
(5, 3, 'Teste', 0, 1, '2021-10-12', '19:55:52'),
(6, 1, 'Teste', 0, 1, '2021-10-12', '19:58:39'),
(7, 1, 'Teste', 0, 1, '2021-10-12', '19:59:45'),
(8, 1, 'TesteAGORA', 0, 0, '2021-10-12', '20:01:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `support_chats`
--

CREATE TABLE `support_chats` (
  `id` int(11) NOT NULL,
  `idSupport` int(11) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `idUser` int(11) NOT NULL,
  `typeUser` tinyint(4) NOT NULL DEFAULT 0,
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `support_chats`
--

INSERT INTO `support_chats` (`id`, `idSupport`, `content`, `idUser`, `typeUser`, `registerDate`, `registerTime`) VALUES
(1, 1, 'asdddddddddddddddddasddddd', 3, 1, '2021-10-06', '12:53:27'),
(2, 1, 'asfddfffffffffffghhhhhhhhhhhhhh', 1, 2, '2021-10-06', '13:10:19'),
(3, 1, 'asfddfffffffffffghhhhhhhhhhhhhh', 1, 2, '2021-10-06', '13:10:54'),
(4, 1, 'novo chat', 3, 1, '2021-10-06', '13:36:26'),
(5, 2, 'asdddddddddddddddddasddddd', 3, 1, '2021-10-06', '14:51:21'),
(6, 3, 'lalalala', 3, 1, '2021-10-06', '14:51:30'),
(7, 3, 'sakdajskdljaskljasjd', 3, 1, '2021-10-06', '14:51:57'),
(8, 3, 'vaaaaaaa', 1, 2, '2021-10-06', '14:54:25'),
(9, 1, 'testando', 1, 2, '2021-10-11', '13:51:41'),
(10, 4, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfddsf dffffffffffffffffffffffffffffff', 1, 1, '2021-10-12', '19:55:29'),
(11, 5, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfddsf dffffffffffffffffffffffffffffff', 3, 1, '2021-10-12', '19:55:52'),
(12, 6, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfddsf dffffffffffffffffffffffffffffff', 1, 1, '2021-10-12', '19:58:39'),
(13, 7, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfddsf dffffffffffffffffffffffffffffff', 1, 1, '2021-10-12', '19:59:45'),
(14, 8, 'asfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfdfddsf dffffffffffffffffffffffffffffff', 1, 1, '2021-10-12', '20:01:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `all_subject_test` varchar(450) NOT NULL DEFAULT '0',
  `status` int(11) DEFAULT 0 COMMENT '1-CRIADA;\r\n2-EM ANDAMENTO\r\n3-PAUSADA; \r\n4-FINALIZADA',
  `registerDate` date NOT NULL,
  `registerTime` time NOT NULL,
  `totalQuestions` int(11) NOT NULL DEFAULT 0,
  `start` date DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `pause` date DEFAULT NULL,
  `pauseTime` time DEFAULT NULL,
  `restart` date DEFAULT NULL,
  `restartTime` time DEFAULT NULL,
  `finish` date DEFAULT NULL,
  `finishTime` time DEFAULT NULL,
  `concluded_percentage` float DEFAULT NULL,
  `correct_questions` int(11) DEFAULT NULL,
  `wrong_questions` int(11) DEFAULT NULL,
  `unanswered_questions` int(11) DEFAULT NULL,
  `answered_questions` int(11) DEFAULT NULL,
  `test_time` time DEFAULT NULL,
  `idUser` int(11) DEFAULT NULL,
  `totalPercentageQuestions` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tests`
--

INSERT INTO `tests` (`id`, `name`, `all_subject_test`, `status`, `registerDate`, `registerTime`, `totalQuestions`, `start`, `startTime`, `pause`, `pauseTime`, `restart`, `restartTime`, `finish`, `finishTime`, `concluded_percentage`, `correct_questions`, `wrong_questions`, `unanswered_questions`, `answered_questions`, `test_time`, `idUser`, `totalPercentageQuestions`) VALUES
(1, 'NOVA PROVA GRATUITA', '1', 2, '2021-11-16', '19:22:10', 4, '2021-11-16', '19:22:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00:00:00', 1, NULL),
(2, 'NOVA PROVA GRATUITA', '1', 2, '2021-11-16', '19:23:20', 8, '2021-11-16', '19:23:20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `id_test` int(11) NOT NULL DEFAULT 0,
  `id_default_question` int(11) NOT NULL DEFAULT 0,
  `annotation` text DEFAULT NULL,
  `chosen_alternative` int(11) DEFAULT NULL,
  `correct_alternative_id` int(11) DEFAULT NULL,
  `is_correct` tinyint(4) DEFAULT NULL,
  `favorite` tinyint(4) DEFAULT NULL,
  `resolved` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `test_questions`
--

INSERT INTO `test_questions` (`id`, `id_test`, `id_default_question`, `annotation`, `chosen_alternative`, `correct_alternative_id`, `is_correct`, `favorite`, `resolved`) VALUES
(1, 1, 7, NULL, NULL, 2, NULL, 0, 0),
(2, 1, 11, NULL, NULL, 5, NULL, 0, 0),
(3, 1, 10, NULL, NULL, 7, NULL, 0, 0),
(4, 1, 6, NULL, NULL, 9, NULL, 0, 0),
(5, 2, 7, NULL, NULL, 12, NULL, 0, 0),
(6, 2, 6, NULL, NULL, 13, NULL, 0, 0),
(7, 2, 2, NULL, NULL, 16, NULL, 0, 0),
(8, 2, 5, NULL, NULL, 17, NULL, 0, 0),
(9, 2, 3, NULL, NULL, 23, NULL, 0, 0),
(10, 2, 10, NULL, NULL, 25, NULL, 0, 0),
(11, 2, 11, NULL, NULL, 29, NULL, 0, 0),
(12, 2, 1, NULL, NULL, 33, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_question_alternatives`
--

CREATE TABLE `test_question_alternatives` (
  `id` int(11) NOT NULL,
  `id_default_alternative` int(11) NOT NULL DEFAULT 0,
  `id_testQuestion` int(11) NOT NULL DEFAULT 0,
  `is_correct` tinyint(4) NOT NULL DEFAULT 0,
  `not_used` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `test_question_alternatives`
--

INSERT INTO `test_question_alternatives` (`id`, `id_default_alternative`, `id_testQuestion`, `is_correct`, `not_used`) VALUES
(1, 63, 1, 0, 0),
(2, 64, 1, 1, 0),
(3, 93, 2, 0, 0),
(4, 94, 2, 0, 0),
(5, 95, 2, 1, 0),
(6, 96, 2, 0, 0),
(7, 67, 3, 1, 0),
(8, 68, 3, 0, 0),
(9, 47, 4, 1, 0),
(10, 48, 4, 0, 0),
(11, 63, 5, 0, 0),
(12, 64, 5, 1, 0),
(13, 47, 6, 1, 0),
(14, 48, 6, 0, 0),
(15, 24, 7, 0, 0),
(16, 25, 7, 1, 0),
(17, 44, 8, 1, 0),
(18, 45, 8, 0, 0),
(19, 46, 8, 0, 0),
(20, 7, 9, 0, 0),
(21, 8, 9, 0, 0),
(22, 9, 9, 0, 0),
(23, 10, 9, 1, 0),
(24, 11, 9, 0, 0),
(25, 67, 10, 1, 0),
(26, 68, 10, 0, 0),
(27, 93, 11, 0, 0),
(28, 94, 11, 0, 0),
(29, 95, 11, 1, 0),
(30, 96, 11, 0, 0),
(31, 1, 12, 0, 0),
(32, 2, 12, 0, 0),
(33, 3, 12, 1, 0),
(34, 4, 12, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `idUserClientInfo` int(11) DEFAULT NULL,
  `permissions` int(11) DEFAULT NULL,
  `registerDate` date DEFAULT NULL,
  `registerTime` time DEFAULT NULL,
  `nickname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `blocked` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `email_verified_at`, `password`, `type`, `remember_token`, `idUserClientInfo`, `permissions`, `registerDate`, `registerTime`, `nickname`, `blocked`) VALUES
(1, 'Mauricio Teste', 'Ferreira Teste', 'mauricio-ferreira2015@outlook.com', NULL, '$2y$10$QeegXucAucbUWP6N/gGL0ONd01a1WmXhCxiHk0khhMF/EOBnbQzXa', 2, NULL, 3, NULL, '2021-10-18', '11:03:56', '', NULL),
(2, 'Mauricio', 'Ferreira', 'mauriciolinkinpark2015@gmail.com', NULL, '$2y$10$wUbXdmwa5K1LPA5MJGSqA.RXwd/5/.cHcR6uTpU4FVG/Rpdc0ovWW', 1, NULL, NULL, NULL, '2021-10-24', '17:53:35', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_client_infos`
--

CREATE TABLE `user_client_infos` (
  `id` int(11) NOT NULL,
  `street` varchar(450) NOT NULL DEFAULT '0',
  `number` varchar(10) NOT NULL DEFAULT '0',
  `neighboorhood` varchar(255) NOT NULL DEFAULT '0',
  `city` varchar(255) NOT NULL DEFAULT '0',
  `state` varchar(255) NOT NULL DEFAULT '0',
  `cep` char(9) NOT NULL DEFAULT '0',
  `hospitalWork` varchar(255) DEFAULT '0',
  `residenceLocal` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `user_client_infos`
--

INSERT INTO `user_client_infos` (`id`, `street`, `number`, `neighboorhood`, `city`, `state`, `cep`, `hospitalWork`, `residenceLocal`) VALUES
(3, 'Raul Seixas editado25', '114', 'Roseira', 'São Paulo', 'São Paulo', '08466-010', '0', '0');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `classifieds`
--
ALTER TABLE `classifieds`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `classified_data`
--
ALTER TABLE `classified_data`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `classified_subtopics`
--
ALTER TABLE `classified_subtopics`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `classified_topics`
--
ALTER TABLE `classified_topics`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `default_alternative_questions`
--
ALTER TABLE `default_alternative_questions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `default_questions`
--
ALTER TABLE `default_questions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `plan_payments`
--
ALTER TABLE `plan_payments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `subject_topics`
--
ALTER TABLE `subject_topics`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `supports`
--
ALTER TABLE `supports`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `support_chats`
--
ALTER TABLE `support_chats`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_question_alternatives`
--
ALTER TABLE `test_question_alternatives`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Índices para tabela `user_client_infos`
--
ALTER TABLE `user_client_infos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `classifieds`
--
ALTER TABLE `classifieds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `classified_data`
--
ALTER TABLE `classified_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `classified_subtopics`
--
ALTER TABLE `classified_subtopics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `classified_topics`
--
ALTER TABLE `classified_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `default_alternative_questions`
--
ALTER TABLE `default_alternative_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de tabela `default_questions`
--
ALTER TABLE `default_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `plan_payments`
--
ALTER TABLE `plan_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `subject_topics`
--
ALTER TABLE `subject_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `supports`
--
ALTER TABLE `supports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `support_chats`
--
ALTER TABLE `support_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `test_question_alternatives`
--
ALTER TABLE `test_question_alternatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `user_client_infos`
--
ALTER TABLE `user_client_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
