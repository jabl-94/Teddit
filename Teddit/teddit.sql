-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 25, 2024 alle 18:22
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teddit`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `id_blog` int(9) UNSIGNED ZEROFILL NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `img` varchar(1000) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `id_author` int(9) UNSIGNED NOT NULL,
  `id_category` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `category`
--

CREATE TABLE `category` (
  `id_cat` int(9) UNSIGNED ZEROFILL NOT NULL,
  `id_parent` int(9) UNSIGNED DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `commento`
--

CREATE TABLE `commento` (
  `id_comment` int(9) UNSIGNED ZEROFILL NOT NULL,
  `testo` text NOT NULL,
  `dt_tm` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `n_vts` int(10) UNSIGNED DEFAULT 0,
  `id_us` int(9) UNSIGNED NOT NULL,
  `id_p` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `commento`
--
DELIMITER $$
CREATE TRIGGER `update_comments_count_delete` AFTER DELETE ON `commento` FOR EACH ROW UPDATE post
   SET post.n_comments = (SELECT COUNT(*) FROM commento WHERE commento.id_p = OLD.id_p)
   WHERE post.id_post = OLD.id_p
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_comments_count_insert` AFTER INSERT ON `commento` FOR EACH ROW UPDATE post
   SET post.n_comments = (SELECT COUNT(*) FROM commento WHERE commento.id_p = NEW.id_p)
   WHERE post.id_post = NEW.id_p
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `co_manages`
--

CREATE TABLE `co_manages` (
  `id_aut` int(9) UNSIGNED NOT NULL,
  `id_bl` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `image`
--

CREATE TABLE `image` (
  `id_image` int(9) UNSIGNED ZEROFILL NOT NULL,
  `path` varchar(1000) NOT NULL,
  `id_pst` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `id_post` int(9) UNSIGNED ZEROFILL NOT NULL,
  `title_post` varchar(50) NOT NULL,
  `text_post` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `n_comments` int(10) UNSIGNED DEFAULT 0,
  `n_votes` int(10) UNSIGNED DEFAULT 0,
  `id_b` int(9) UNSIGNED NOT NULL,
  `id_u` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `premium`
--

CREATE TABLE `premium` (
  `id_premium` int(9) UNSIGNED NOT NULL,
  `expiry_sub` date NOT NULL,
  `expiry_card` date NOT NULL,
  `card_number` int(19) UNSIGNED NOT NULL,
  `card_holder` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `security_question`
--

CREATE TABLE `security_question` (
  `id_question` int(9) UNSIGNED ZEROFILL NOT NULL,
  `question` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id_user` int(9) UNSIGNED ZEROFILL NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(320) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `sec_answer` varchar(256) NOT NULL,
  `propic` varchar(1000) DEFAULT '0.png',
  `id_quest` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `vote_comment`
--

CREATE TABLE `vote_comment` (
  `id_vc` int(9) UNSIGNED ZEROFILL NOT NULL,
  `id_usr` int(9) UNSIGNED NOT NULL,
  `id_cmt` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `vote_comment`
--
DELIMITER $$
CREATE TRIGGER `update_votes_count_comment_delete` AFTER DELETE ON `vote_comment` FOR EACH ROW UPDATE commento
   SET commento.n_votes = (SELECT COUNT(*) FROM vote_comment WHERE vote_comment.id_cmt = OLD.id_cmt)
   WHERE commento.id_comment = OLD.id_cmt
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_votes_count_comment_insert` AFTER INSERT ON `vote_comment` FOR EACH ROW UPDATE commento
   SET commento.n_votes = (SELECT COUNT(*) FROM vote_comment WHERE vote_comment.id_cmt = NEW.id_cmt)
   WHERE commento.id_comment = NEW.id_cmt
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `vote_post`
--

CREATE TABLE `vote_post` (
  `id_vp` int(9) UNSIGNED ZEROFILL NOT NULL,
  `id_ur` int(9) UNSIGNED NOT NULL,
  `id_pt` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `vote_post`
--
DELIMITER $$
CREATE TRIGGER `update_votes_count_post_delete` AFTER DELETE ON `vote_post` FOR EACH ROW UPDATE post
   SET post.n_votes = (SELECT COUNT(*) FROM vote_post WHERE vote_post.id_pt = OLD.id_pt)
   WHERE post.id_post = OLD.id_pt
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_votes_count_post_insert` AFTER INSERT ON `vote_post` FOR EACH ROW UPDATE post
   SET post.n_votes = (SELECT COUNT(*) FROM vote_post WHERE vote_post.id_pt = NEW.id_pt)
   WHERE post.id_post = NEW.id_pt
$$
DELIMITER ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `id_author` (`id_author`),
  ADD KEY `id_category` (`id_category`);

--
-- Indici per le tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_cat`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indici per le tabelle `commento`
--
ALTER TABLE `commento`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_us` (`id_us`),
  ADD KEY `id_p` (`id_p`);

--
-- Indici per le tabelle `co_manages`
--
ALTER TABLE `co_manages`
  ADD PRIMARY KEY (`id_aut`,`id_bl`),
  ADD KEY `id_bl` (`id_bl`);

--
-- Indici per le tabelle `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_pst` (`id_pst`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_b` (`id_b`),
  ADD KEY `id_u` (`id_u`);

--
-- Indici per le tabelle `premium`
--
ALTER TABLE `premium`
  ADD PRIMARY KEY (`id_premium`);

--
-- Indici per le tabelle `security_question`
--
ALTER TABLE `security_question`
  ADD PRIMARY KEY (`id_question`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_quest` (`id_quest`);

--
-- Indici per le tabelle `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD PRIMARY KEY (`id_vc`),
  ADD KEY `id_usr` (`id_usr`),
  ADD KEY `id_cmt` (`id_cmt`);

--
-- Indici per le tabelle `vote_post`
--
ALTER TABLE `vote_post`
  ADD PRIMARY KEY (`id_vp`),
  ADD KEY `id_ur` (`id_ur`),
  ADD KEY `id_pt` (`id_pt`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `category`
--
ALTER TABLE `category`
  MODIFY `id_cat` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `commento`
--
ALTER TABLE `commento`
  MODIFY `id_comment` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `security_question`
--
ALTER TABLE `security_question`
  MODIFY `id_question` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id_user` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `vote_comment`
--
ALTER TABLE `vote_comment`
  MODIFY `id_vc` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `vote_post`
--
ALTER TABLE `vote_post`
  MODIFY `id_vp` int(9) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `commento`
--
ALTER TABLE `commento`
  ADD CONSTRAINT `commento_ibfk_1` FOREIGN KEY (`id_us`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commento_ibfk_2` FOREIGN KEY (`id_p`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `co_manages`
--
ALTER TABLE `co_manages`
  ADD CONSTRAINT `co_manages_ibfk_1` FOREIGN KEY (`id_aut`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `co_manages_ibfk_2` FOREIGN KEY (`id_bl`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`id_pst`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_b`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`id_u`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `premium`
--
ALTER TABLE `premium`
  ADD CONSTRAINT `premium_ibfk_1` FOREIGN KEY (`id_premium`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`id_quest`) REFERENCES `security_question` (`id_question`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD CONSTRAINT `vote_comment_ibfk_1` FOREIGN KEY (`id_usr`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_comment_ibfk_2` FOREIGN KEY (`id_cmt`) REFERENCES `commento` (`id_comment`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `vote_post`
--
ALTER TABLE `vote_post`
  ADD CONSTRAINT `vote_post_ibfk_1` FOREIGN KEY (`id_ur`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_post_ibfk_2` FOREIGN KEY (`id_pt`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
