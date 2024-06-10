-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 09:30 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `borges_pastorelli`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `id_blog` int(9) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `img` varchar(1000) DEFAULT '1.png',
  `n_followers` int(10) UNSIGNED DEFAULT 0,
  `n_coauthors` int(10) UNSIGNED DEFAULT 0,
  `id_author` int(9) UNSIGNED NOT NULL,
  `id_category` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id_blog`, `title`, `description`, `img`, `n_followers`, `n_coauthors`, `id_author`, `id_category`) VALUES
(1, 'Biden\'s antics', 'Nooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo', '665df1980a.jpg', 1, 3, 1, 1),
(2, 'Trump\'s antics', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore e', '665df1f7a5.jpg', 1, 2, 1, 26),
(3, 'European elections', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididuntum.\"\r\n\r\n', '665df28d1a.jpg', 2, 0, 1, 25),
(4, 'Master plan', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut eis\r\n', '665df2d280.jpeg', 2, 2, 2, 16),
(5, 'The NCR', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', '665df32c6e.png', 1, 2, 2, 9),
(6, 'Real Madrid', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim', '665df6895a.jpg', 0, 0, 3, 27),
(7, 'The Brotherhood of Steel', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born.', '665ec1b32c.jpg', 2, 1, 2, 4),
(8, 'Booooefwbicvbweofwbfwiuefbo', 'ecqecvergwcrtvhdrthvdeytjbdfyjdyhvrdhcstseghcrvth', '1.png', 0, 0, 2, 5),
(9, 'Blog 1', 'On the other hand, we denounce with righteous indignation and dislike men who are so beguiled', '1.png', 0, 0, 1, 10),
(10, 'Blog 2', 'On the other hand, we denounce with righteous indignation and dislike men', '1.png', 0, 0, 1, 7),
(11, 'Another blog', 'On the other hand, we denounce with righteous indignation and dislike men', '1.png', 0, 0, 2, 18),
(12, 'Yet another blog', 'On the other hand, we denounce with righteous indignation and dislike men', '1.png', 0, 0, 2, 20);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_cat` int(9) UNSIGNED NOT NULL,
  `id_parent` int(9) UNSIGNED DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_cat`, `id_parent`, `name`) VALUES
(1, NULL, 'Politics'),
(2, NULL, 'Sports'),
(3, NULL, 'Entertainment'),
(4, NULL, 'Technology'),
(5, NULL, 'Health'),
(6, NULL, 'Travel'),
(7, NULL, 'Cooking'),
(8, NULL, 'Economy'),
(9, NULL, 'Education'),
(10, NULL, 'Art and Culture'),
(11, NULL, 'Fashion'),
(12, NULL, 'Environment'),
(13, NULL, 'Science'),
(14, NULL, 'Cars'),
(15, NULL, 'Work'),
(16, NULL, 'Announcements'),
(17, NULL, 'Daily Tips'),
(18, NULL, 'Curiosities'),
(19, NULL, 'Product Reviews'),
(20, NULL, 'Outdoor Activities'),
(21, NULL, 'Volunteering'),
(22, NULL, 'DIY'),
(23, NULL, 'Personal Development'),
(24, 1, 'Italian Politics'),
(25, 1, 'European Politics'),
(26, 1, 'International Politics'),
(27, 2, 'Football'),
(28, 2, 'Basketball'),
(29, 2, 'Tennis'),
(30, 2, 'Cycling'),
(31, 2, 'Swimming'),
(32, 3, 'Cinema'),
(33, 3, 'Music'),
(34, 3, 'Theater'),
(35, 3, 'Books'),
(36, 3, 'Games'),
(37, 4, 'Gadgets'),
(38, 4, 'Software'),
(39, 4, 'Hardware'),
(40, 4, 'Internet'),
(41, 4, 'Innovations'),
(42, 5, 'Nutrition'),
(43, 5, 'Fitness'),
(44, 5, 'Medicine'),
(45, 5, 'Mental Health'),
(46, 5, 'Prevention'),
(47, 6, 'Adventure'),
(48, 6, 'Cities and Metropolises'),
(49, 6, 'Local Culture'),
(50, 6, 'Luxury Travel'),
(51, 6, 'Travel Guides'),
(52, 7, 'Recipes'),
(53, 7, 'Restaurant Reviews'),
(54, 7, 'Regional Cuisine'),
(55, 7, 'Cooking Techniques'),
(56, 7, 'Wines and Drinks'),
(57, 8, 'Personal Finance'),
(58, 8, 'Investments'),
(59, 8, 'Economic News'),
(60, 8, 'Startups'),
(61, 8, 'Markets'),
(62, 9, 'Online Learning'),
(63, 9, 'Professional Development'),
(64, 9, 'Children Education'),
(65, 9, 'Educational Technologies'),
(66, 9, 'Educational Policy'),
(67, 10, 'Painting'),
(68, 10, 'Sculpture'),
(69, 10, 'Photography'),
(70, 10, 'Literature'),
(71, 10, 'Art History'),
(72, 11, 'Trends'),
(73, 11, 'Designers'),
(74, 11, 'Fashion Shows'),
(75, 11, 'Style Tips'),
(76, 11, 'Shopping'),
(77, 12, 'Renewable Energy'),
(78, 12, 'Conservation'),
(79, 12, 'Sustainability'),
(80, 12, 'Pollution'),
(81, 12, 'Biodiversity'),
(82, 13, 'Astronomy'),
(83, 13, 'Biology'),
(84, 13, 'Chemistry'),
(85, 13, 'Physics'),
(86, 13, 'Scientific Innovations'),
(87, 14, 'Sports Cars'),
(88, 14, 'Electric Cars'),
(89, 14, 'Reviews'),
(90, 14, 'Automotive Technology'),
(91, 14, 'Car History'),
(92, 15, 'Job Search'),
(93, 15, 'Career Advice'),
(94, 15, 'Entrepreneurship'),
(95, 15, 'Worker Rights'),
(96, 15, 'Job Market Trends');

-- --------------------------------------------------------

--
-- Table structure for table `commento`
--

CREATE TABLE `commento` (
  `id_comment` int(9) UNSIGNED NOT NULL,
  `testo` text NOT NULL,
  `dt_tm` char(19) NOT NULL,
  `n_vts` int(10) UNSIGNED DEFAULT 0,
  `id_us` int(9) UNSIGNED NOT NULL,
  `id_p` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commento`
--

INSERT INTO `commento` (`id_comment`, `testo`, `dt_tm`, `n_vts`, `id_us`, `id_p`) VALUES
(1, 'No vabbe\'', '2024-06-03 18:46:05', 0, 2, 2),
(2, 'rewreeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '2024-06-03 19:06:25', 0, 2, 7),
(3, 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '2024-06-03 19:06:29', 0, 2, 7),
(4, 'drgegrerger54353453453453545', '2024-06-03 19:08:14', 1, 2, 2),
(5, 'jteshthwhwtwg3gq54', '2024-06-04 10:59:43', 0, 2, 12),
(6, 'egaerhethsertbsrthw4hsrtsthsrths', '2024-06-04 10:59:48', 1, 2, 12);

--
-- Triggers `commento`
--
DELIMITER $$
CREATE TRIGGER `update_comments_count_DELETE` AFTER DELETE ON `commento` FOR EACH ROW UPDATE post
   SET post.n_comments = (SELECT COUNT(*) FROM commento WHERE commento.id_p = OLD.id_p)
   WHERE post.id_post = OLD.id_p
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_comments_count_INSERT` AFTER INSERT ON `commento` FOR EACH ROW UPDATE post
   SET post.n_comments = (SELECT COUNT(*) FROM commento WHERE commento.id_p = NEW.id_p)
   WHERE post.id_post = NEW.id_p
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `co_manages`
--

CREATE TABLE `co_manages` (
  `id_aut` int(9) UNSIGNED NOT NULL,
  `id_bl` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `co_manages`
--

INSERT INTO `co_manages` (`id_aut`, `id_bl`) VALUES
(1, 4),
(1, 5),
(1, 7),
(2, 1),
(2, 2),
(3, 4),
(3, 5),
(4, 1),
(5, 1),
(5, 2);

--
-- Triggers `co_manages`
--
DELIMITER $$
CREATE TRIGGER `update_coauthors_count_DELETE` AFTER DELETE ON `co_manages` FOR EACH ROW UPDATE blog
   SET blog.n_coauthors = (SELECT COUNT(*) FROM co_manages WHERE co_manages.id_bl = OLD.id_bl)
   WHERE blog.id_blog = OLD.id_bl
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_coauthors_count_INSERT` AFTER INSERT ON `co_manages` FOR EACH ROW UPDATE blog
   SET blog.n_coauthors = (SELECT COUNT(*) FROM co_manages WHERE co_manages.id_bl = NEW.id_bl)
   WHERE blog.id_blog = NEW.id_bl
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id_auth` int(9) UNSIGNED NOT NULL,
  `id_b` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id_auth`, `id_b`) VALUES
(1, 4),
(1, 5),
(1, 7),
(2, 1),
(2, 3),
(3, 2),
(3, 3),
(3, 4),
(4, 7);

--
-- Triggers `follows`
--
DELIMITER $$
CREATE TRIGGER `update_followers_count_DELETE` AFTER DELETE ON `follows` FOR EACH ROW UPDATE blog
   SET blog.n_followers = (SELECT COUNT(*) FROM follows WHERE follows.id_b = OLD.id_b)
   WHERE blog.id_blog = OLD.id_b
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_followers_count_INSERT` AFTER INSERT ON `follows` FOR EACH ROW UPDATE blog
   SET blog.n_followers = (SELECT COUNT(*) FROM follows WHERE follows.id_b = NEW.id_b)
   WHERE blog.id_blog = NEW.id_b
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id_image` int(9) UNSIGNED NOT NULL,
  `path` varchar(1000) DEFAULT NULL,
  `id_pst` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id_image`, `path`, `id_pst`) VALUES
(1, '665df2f18b.jpg', 1),
(2, '665df33bb3.jpg', 2),
(3, '665df37f22.jpg', 3),
(4, '665df3c9ec.jpg', 4),
(5, '665df48153.jpg', 5),
(6, '665df48156.jpg', 5),
(7, '665df48158.jpg', 5),
(8, '665df4bbda.jpeg', 6),
(9, '665df70746.jpg', 7),
(10, '665df70749.jpg', 7),
(11, '665df7074b.jpg', 7),
(12, '665ebe8283.png', 8),
(13, '665ebf82b9.jpg', 9),
(14, '665ebf82bc.jpg', 9),
(15, '665ebf82bd.png', 9),
(16, '665ebfa515.jpg', 4),
(17, '665ec026f3.jpeg', 10),
(18, '665ec1cb02.png', 11),
(24, '6664252256.jpg', 14),
(25, '6664253d8c.jpg', 14);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id_post` int(9) UNSIGNED NOT NULL,
  `title_post` varchar(50) NOT NULL,
  `text_post` text NOT NULL,
  `date_time` char(19) NOT NULL,
  `link` varchar(256) DEFAULT NULL,
  `n_comments` int(10) UNSIGNED DEFAULT 0,
  `n_votes` int(10) UNSIGNED DEFAULT 0,
  `id_b` int(9) UNSIGNED NOT NULL,
  `id_u` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id_post`, `title_post`, `text_post`, `date_time`, `link`, `n_comments`, `n_votes`, `id_b`, `id_u`) VALUES
(1, 'Graaaaandeeeee', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\r\n\r\n', '2024-06-03 18:44:33', NULL, 0, 2, 1, 2),
(2, 'Show appearance', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\r\n\r\n', '2024-06-03 18:45:47', NULL, 2, 2, 5, 2),
(3, 'Kill Mr. House', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\r\n\r\n', '2024-06-03 18:46:55', NULL, 0, 1, 4, 2),
(4, 'The way to kill House', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugia', '2024-06-03 18:48:09', 'https://www.youtube.com/watch?v=EP6dlYU8dLQ', 0, 1, 4, 1),
(5, 'Final step', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\r\n\r\n', '2024-06-03 18:51:13', 'https://www.youtube.com/watch?v=Sy5fFldNoew', 0, 1, 4, 1),
(6, 'Texting the reps', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"\r\n\r\n', '2024-06-03 18:52:11', '', 0, 1, 2, 1),
(7, 'Champions', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamcLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamcLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamcLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamc', '2024-06-03 19:01:59', '', 2, 2, 6, 3),
(8, 'I can\'t believe they lost the first BoHD', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.', '2024-06-04 09:12:42', 'https://www.youtube.com/watch?v=2bQrTz5AZbk', 0, 2, 5, 1),
(9, 'They shouldn\'t have taken Helios one', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.', '2024-06-04 09:17:22', '', 0, 1, 5, 1),
(10, 'Got \'em!!!!!!!', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.', '2024-06-04 09:20:06', '', 0, 2, 2, 1),
(11, 'The power armor is so cool', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful', '2024-06-04 09:27:06', NULL, 0, 2, 7, 2),
(12, 'How to get the power armor in Fallout 4?', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful', '2024-06-04 09:28:30', 'https://www.youtube.com/watch?v=cA5OFSjodH4', 2, 2, 7, 1),
(13, 'Come funzionano le elezioni Europee?', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful', '2024-06-04 09:37:53', 'https://www.youtube.com/watch?v=q1W0fHGU6jE', 0, 1, 3, 1),
(14, 'prova edizione immagine', 'hfghjklnkgckuvkckhvkuvmhvkftkhctjydyhc', '2024-06-08 11:21:15', '', 0, 1, 3, 1),
(16, 'Ho finito!!!!!!', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\n', '2024-06-08 12:22:07', NULL, 0, 0, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `premium`
--

CREATE TABLE `premium` (
  `id_premium` int(9) UNSIGNED NOT NULL,
  `expiry_sub` date NOT NULL,
  `expiry_card` date NOT NULL,
  `card_number` char(16) NOT NULL,
  `card_holder` varchar(100) NOT NULL,
  `card_type` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `premium`
--

INSERT INTO `premium` (`id_premium`, `expiry_sub`, `expiry_card`, `card_number`, `card_holder`, `card_type`) VALUES
(3, '2025-06-03', '2024-12-01', '************1111', 'Javier Borges', 'Visa');

-- --------------------------------------------------------

--
-- Table structure for table `saves`
--

CREATE TABLE `saves` (
  `id_a` int(9) UNSIGNED NOT NULL,
  `id_po` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saves`
--

INSERT INTO `saves` (`id_a`, `id_po`) VALUES
(1, 4),
(1, 5),
(1, 6),
(1, 8),
(1, 9),
(1, 11),
(1, 12),
(2, 8),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `security_question`
--

CREATE TABLE `security_question` (
  `id_question` int(9) UNSIGNED NOT NULL,
  `question` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_question`
--

INSERT INTO `security_question` (`id_question`, `question`) VALUES
(1, 'What is the name of your mother?'),
(2, 'What is the name of your first dog?'),
(3, 'In which city were you born?'),
(4, 'What is your favorite movie?'),
(5, 'What is the name of your elementary school?'),
(6, 'What is your favorite food?');

-- --------------------------------------------------------

--
-- Table structure for table `utente`
--

CREATE TABLE `utente` (
  `id_user` int(9) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(320) NOT NULL,
  `pass` varchar(256) NOT NULL,
  `sec_answer` varchar(50) NOT NULL,
  `propic` varchar(1000) DEFAULT '0.png',
  `bio` varchar(200) DEFAULT NULL,
  `id_quest` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utente`
--

INSERT INTO `utente` (`id_user`, `username`, `email`, `pass`, `sec_answer`, `propic`, `bio`, `id_quest`) VALUES
(1, 'adios', 'adios@gmail.com', '9d31af55892c261033eebe9c536f7ff87de84ae4233adc77c8eb12a8cdf1f04c', 'mamma', '665df1cdbe.jpg', 'Non voglio dire ciao', 1),
(2, 'Benny', 'benny@tops.com', '9d31af55892c261033eebe9c536f7ff87de84ae4233adc77c8eb12a8cdf1f04c', 'mamma', '665ec04dc4.jpg', 'Truth is, the game was rigged from the start...', 1),
(3, 'carlos', 'carlos@gmail.com', '9d31af55892c261033eebe9c536f7ff87de84ae4233adc77c8eb12a8cdf1f04c', 'mamma', '0.png', NULL, 1),
(4, 'hehe', 'hehe@gmail.com', '9d31af55892c261033eebe9c536f7ff87de84ae4233adc77c8eb12a8cdf1f04c', 'mamma', '0.png', NULL, 1),
(5, 'pepe', 'pepe@gmail.com', '9d31af55892c261033eebe9c536f7ff87de84ae4233adc77c8eb12a8cdf1f04c', 'mamma', '0.png', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vote_comment`
--

CREATE TABLE `vote_comment` (
  `id_vc` int(9) UNSIGNED NOT NULL,
  `id_usr` int(9) UNSIGNED NOT NULL,
  `id_cmt` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_comment`
--

INSERT INTO `vote_comment` (`id_vc`, `id_usr`, `id_cmt`) VALUES
(1, 2, 4),
(2, 2, 6);

--
-- Triggers `vote_comment`
--
DELIMITER $$
CREATE TRIGGER `update_votes_count_comment_DELETE` AFTER DELETE ON `vote_comment` FOR EACH ROW UPDATE commento
   SET commento.n_vts = (SELECT COUNT(*) FROM vote_comment WHERE vote_comment.id_cmt = OLD.id_cmt)
   WHERE commento.id_comment = OLD.id_cmt
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_votes_count_comment_INSERT` AFTER INSERT ON `vote_comment` FOR EACH ROW UPDATE commento
   SET commento.n_vts = (SELECT COUNT(*) FROM vote_comment WHERE vote_comment.id_cmt = NEW.id_cmt)
   WHERE commento.id_comment = NEW.id_cmt
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vote_post`
--

CREATE TABLE `vote_post` (
  `id_vp` int(9) UNSIGNED NOT NULL,
  `id_ur` int(9) UNSIGNED NOT NULL,
  `id_pt` int(9) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_post`
--

INSERT INTO `vote_post` (`id_vp`, `id_ur`, `id_pt`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 1, 3),
(5, 1, 5),
(6, 1, 4),
(7, 1, 1),
(8, 1, 2),
(10, 2, 7),
(11, 1, 8),
(13, 1, 10),
(14, 2, 10),
(15, 2, 9),
(16, 2, 8),
(17, 1, 6),
(18, 2, 11),
(19, 1, 11),
(21, 1, 7),
(23, 2, 12),
(24, 1, 12),
(25, 1, 13),
(26, 1, 14);

--
-- Triggers `vote_post`
--
DELIMITER $$
CREATE TRIGGER `update_votes_count_post_DELETE` AFTER DELETE ON `vote_post` FOR EACH ROW UPDATE post
   SET post.n_votes = (SELECT COUNT(*) FROM vote_post WHERE vote_post.id_pt = OLD.id_pt)
   WHERE post.id_post = OLD.id_pt
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_votes_count_post_INSERT` AFTER INSERT ON `vote_post` FOR EACH ROW UPDATE post
   SET post.n_votes = (SELECT COUNT(*) FROM vote_post WHERE vote_post.id_pt = NEW.id_pt)
   WHERE post.id_post = NEW.id_pt
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `id_author` (`id_author`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_cat`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indexes for table `commento`
--
ALTER TABLE `commento`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_us` (`id_us`),
  ADD KEY `id_p` (`id_p`);

--
-- Indexes for table `co_manages`
--
ALTER TABLE `co_manages`
  ADD PRIMARY KEY (`id_aut`,`id_bl`),
  ADD KEY `id_bl` (`id_bl`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id_auth`,`id_b`),
  ADD KEY `id_b` (`id_b`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_pst` (`id_pst`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_b` (`id_b`),
  ADD KEY `id_u` (`id_u`);

--
-- Indexes for table `premium`
--
ALTER TABLE `premium`
  ADD PRIMARY KEY (`id_premium`);

--
-- Indexes for table `saves`
--
ALTER TABLE `saves`
  ADD PRIMARY KEY (`id_a`,`id_po`),
  ADD KEY `id_po` (`id_po`);

--
-- Indexes for table `security_question`
--
ALTER TABLE `security_question`
  ADD PRIMARY KEY (`id_question`);

--
-- Indexes for table `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_quest` (`id_quest`);

--
-- Indexes for table `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD PRIMARY KEY (`id_vc`),
  ADD KEY `id_usr` (`id_usr`),
  ADD KEY `id_cmt` (`id_cmt`);

--
-- Indexes for table `vote_post`
--
ALTER TABLE `vote_post`
  ADD PRIMARY KEY (`id_vp`),
  ADD KEY `id_ur` (`id_ur`),
  ADD KEY `id_pt` (`id_pt`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_cat` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `commento`
--
ALTER TABLE `commento`
  MODIFY `id_comment` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `security_question`
--
ALTER TABLE `security_question`
  MODIFY `id_question` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `utente`
--
ALTER TABLE `utente`
  MODIFY `id_user` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vote_comment`
--
ALTER TABLE `vote_comment`
  MODIFY `id_vc` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vote_post`
--
ALTER TABLE `vote_post`
  MODIFY `id_vp` int(9) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `category` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commento`
--
ALTER TABLE `commento`
  ADD CONSTRAINT `commento_ibfk_1` FOREIGN KEY (`id_us`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commento_ibfk_2` FOREIGN KEY (`id_p`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `co_manages`
--
ALTER TABLE `co_manages`
  ADD CONSTRAINT `co_manages_ibfk_1` FOREIGN KEY (`id_aut`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `co_manages_ibfk_2` FOREIGN KEY (`id_bl`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`id_auth`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`id_b`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`id_pst`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_b`) REFERENCES `blog` (`id_blog`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`id_u`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `premium`
--
ALTER TABLE `premium`
  ADD CONSTRAINT `premium_ibfk_1` FOREIGN KEY (`id_premium`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `saves`
--
ALTER TABLE `saves`
  ADD CONSTRAINT `saves_ibfk_1` FOREIGN KEY (`id_a`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `saves_ibfk_2` FOREIGN KEY (`id_po`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`id_quest`) REFERENCES `security_question` (`id_question`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote_comment`
--
ALTER TABLE `vote_comment`
  ADD CONSTRAINT `vote_comment_ibfk_1` FOREIGN KEY (`id_usr`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_comment_ibfk_2` FOREIGN KEY (`id_cmt`) REFERENCES `commento` (`id_comment`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote_post`
--
ALTER TABLE `vote_post`
  ADD CONSTRAINT `vote_post_ibfk_1` FOREIGN KEY (`id_ur`) REFERENCES `utente` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_post_ibfk_2` FOREIGN KEY (`id_pt`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `elimina_utente_premium` ON SCHEDULE EVERY 1 DAY STARTS '2024-06-03 18:33:57' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  DELETE FROM premium
  WHERE expiry_sub <= CURDATE();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
