-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2023 at 08:16 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `melisma_db`
--

-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS melisma_db;
USE melisma_db;
--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(1024) NOT NULL,
  `artist_id` int NOT NULL,
  `category_id` int NOT NULL,
   FOREIGN KEY(artist_id) REFERENCES artist(id),
   FOREIGN KEY(category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`id`, `name`, `image`, `artist_id`, `category_id`) VALUES
(11, 'Folklore', 'uploads/download.jpg', 3, 15),
(12, 'Born This Way', 'uploads/dda16339-0172-4611-8195-6361e1b77fc7.jpg', 6, 14),
(13, 'Favourite Worst Nightmare', 'uploads/fav worst nm.jpg', 8, 16);

-- --------------------------------------------------------

--
-- Stand-in structure for view `album_songs`
-- (See below for the actual view)
--
CREATE TABLE `album_songs` (
`id` int
,`name` varchar(50)
,`title` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_id` int NOT NULL,
  `image` varchar(1024) NOT NULL,
   FOREIGN KEY(user_id) REFERENCES users(id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `name`, `bio`, `user_id`, `image`) VALUES
(1, 'Hozier', NULL, 1, 'uploads/01.png'),
(2, 'Mitski', NULL, 1, 'uploads/02.jpeg'),
(3, 'Taylor Swift', NULL, 1, 'uploads/03.jpeg'),
(4, 'Selena Gomez', NULL, 1, 'uploads/04.jpeg'),
(5, 'Conan Gray', NULL, 1, 'uploads/07.jpeg'),
(6, 'Lady Gaga', NULL, 1, 'uploads/08.jpeg'),
(7, 'Fleetwood Mac', NULL, 1, 'uploads/06.jpeg'),
(8, 'Arctic Monkeys', 'Arctic Monkeys are an English indie rock band. Formed in 2002 in High Green, a suburb of Sheffield. The band currently consists of Alex Turner (lead vocals, lead/rhythm guitar), Jamie Cook (rhythm/lead guitar), Nick O’Malley (bass guitar, backing vocals), and Matt Helders (drums, backing vocals).', 1, 'uploads/05.jpeg'),
(12, 'BTS', 'BTS is a South Korean boy group that consists of 7 members: RM, Jin, Suga, J-Hope, Jimin, V and Jungkook. They are under HYBE Labels (formerly known as Big Hit Entertainment). BTS debuted on June 13, 2013 with the lead single ‘ No More Dream ‘ on album ‘ 2 Cool 4 Skool \'.', 1, 'uploads/thv on Snapchat.jpg'),
(13, 'Blackpink', 'Blackpink is a South Korean girl group formed by YG Entertainment, consisting of members Jisoo, Jennie, Lisa, and Rose. The group debuted on August 8, 2016, with their single Square One, which spawned \"Whistle,\" their first number-one song in South Korea.', 1, 'uploads/𓏲 ִֶָ 𝐈𝐂𝐎𝐍  _     ©𝐦𝐢𝐧𝐭𝐲𝐦𝐢𝐥𝐤𝐲.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `category` varchar(30) NOT NULL,
  `disabled` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `disabled`) VALUES
(14, 'Pop', 0),
(15, 'Country', 0),
(16, 'Indie', 0),
(17, 'Rock', 0),
(18, 'Indian Classical', 0),
(19, 'K-pop', 0),
(20, 'dummy', 1);

-- --------------------------------------------------------

--
-- Table structure for table `main_playlist`
--

CREATE TABLE `main_playlist` (
  `user_id` int NOT NULL,
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
   FOREIGN KEY(user_id) REFERENCES users(id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `main_playlist`
--

INSERT INTO `main_playlist` (`user_id`, `id`, `name`, `image`) VALUES
(1, 6, 'songs to vibe to', 'uploads/Paradoxical Mirror.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_songs`
--

CREATE TABLE `playlist_songs` (
  `id` int NOT NULL,
  `song_id` int NOT NULL.
   FOREIGN KEY(song_id) REFERENCES songs(id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `playlist_songs`
--

INSERT INTO `playlist_songs` (`id`, `song_id`) VALUES
(2, 4),
(2, 4),
(2, 6),
(3, 7),
(4, 1),
(4, 4),
(4, 6),
(4, 7),
(4, 7),
(4, 6),
(4, 5),
(2, 5),
(6, 4),
(6, 1),
(6, 7),
(6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `user_id` int NOT NULL,
  `artist_id` int NOT NULL,
  `image` varchar(1024) NOT NULL,
  `file` varchar(1024) NOT NULL,
  `category_id` int NOT NULL,
  `album_id` int NOT NULL,
  `date` datetime NOT NULL,
  `views` int NOT NULL,
  `slug` varchar(100) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
   FOREIGN KEY(user_id) REFERENCES users(id),
   FOREIGN KEY(artist_id) REFERENCES artist(id),
   FOREIGN KEY(category_id) REFERENCES categories(id),
   FOREIGN KEY(album_id) REFERENCES album(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `title`, `user_id`, `artist_id`, `image`, `file`, `category_id`, `album_id`, `date`, `views`, `slug`, `featured`) VALUES
(1, '505', 1, 8, 'uploads/Arctic Monkeys.jpg', 'uploads/505_Arctic_Monkeys.mp3', 16, 13, '2023-01-22 06:04:58', 5, 'im-going-back', 1),
(4, 'August', 1, 3, 'uploads/Beauty in black & white.jpg', 'uploads/August_TS.mp3', 15, 11, '2023-01-22 07:23:41', 43, 'august', 1),
(5, 'Bloody Mary', 1, 6, 'uploads/Lady Gaga.jpg', 'uploads/Bloody Mary_Lady Gaga.mp3', 14, 11, '2023-01-22 07:24:17', 9, 'bloody-mary', 0),
(6, 'Bad Romance', 1, 6, 'uploads/ae98c6f4-857d-4139-bea5-5018336111a0.jpg', 'uploads/Bad Romance_LG.mp3', 14, 12, '2023-01-22 09:35:24', 6, 'bad-romance', 0),
(7, 'Just One Day', 1, 12, 'uploads/972b5809-812d-4e7f-9b06-72a78c6967b1.jpg', 'uploads/[MV] BTS(방탄소년단) _ Just One Day(하루만).mp3', 19, 11, '2023-01-22 16:32:34', 1, 'just-one-day', 0),
(8, 'Boy in Luv', 1, 12, 'uploads/BTS.jpg', 'uploads/[MV] BTS(방탄소년단) _ Boy In Luv(상남자).mp3', 19, 11, '2023-01-24 04:24:53', 3, 'boy-in-luv', 0),
(17, 'Dreams', 1, 7, 'uploads/fav worst nm.jpg', 'uploads/Fleetwood Mac - Dreams (Official Music Video).mp3', 17, 11, '2023-01-27 19:12:22', 0, 'dreams', 0),
(18, 'dummy1', 1, 12, 'uploads/fav worst nm.jpg', 'uploads/BLACKPINK - \'How You Like That\' M_V.mp3', 16, 11, '2023-01-31 13:17:41', 0, 'dummy1', 0),
(19, 'dummy2', 1, 7, 'uploads/wby.jpg', 'uploads/Fleetwood Mac - Dreams (Official Music Video).mp3', 14, 11, '2023-01-31 13:18:17', 0, 'dummy2', 0),
(20, 'dummy3', 1, 5, 'uploads/wby.jpg', 'uploads/Fleetwood Mac - Dreams (Official Music Video).mp3', 14, 11, '2023-01-31 13:18:37', 0, 'dummy3', 0);

--
-- Triggers `songs`
--
DELIMITER $$
CREATE TRIGGER `disabled` BEFORE INSERT ON `songs` FOR EACH ROW BEGIN
    IF ((select disabled from categories where id = new.category_id) = 1)  THEN
       SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Can not insert songs to a disabled category';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `not_more_than_6songs` BEFORE INSERT ON `songs` FOR EACH ROW BEGIN
	IF (select count(*) from songs group by album_id having album_id=new.album_id)>6 THEN
        SIGNAL SQLSTATE '45000' set message_text='Cannot add more than 6 songs to an album';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `date`) VALUES
(1, 'nish', 'nish@email.com', '$2y$10$PphgVoiytsQDHdVPzayPIu.TasBcNS9iQFsY1FF79h8vF9fMynB32', 'admin', '2023-01-21 13:40:28'),
(28, 'aish', 'aish@email.com', '$2y$10$01ub2u.wmpHCNikre3gwJ.CjW5rPywSic6QKNp5dJE3.MepABNQpK', 'user', '2023-01-24 04:15:51'),
(29, 'varsh', 'varsh@email.com', '$2y$10$gezvQQEsbSyotupCcicqb.16c.BbVn/RMDVXo3C4IvQzrNs9KRRue', 'user', '2023-01-24 04:16:19'),
(30, 'gouth', 'gouth@email.com', '$2y$10$j23UhfzMFR1tk9pkgBRocOZO9Iu75.biAG.iKrs2diEIpqxiN9otO', 'user', '2023-01-24 04:17:09'),
(31, 'medini', 'medini@email.com', '$2y$10$ylW5DfgwvHzC0YXuiJiCRuq6LMvGNmSTojSOICiY7m/PR6JimU50i', 'user', '2023-01-24 07:58:01');

-- --------------------------------------------------------

--
-- Structure for view `album_songs`
--
DROP TABLE IF EXISTS `album_songs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `album_songs`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `s`.`title` AS `title` FROM (`album` `a` join `songs` `s`) WHERE (`a`.`id` = `s`.`album_id`)  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `main_playlist`
--
ALTER TABLE `main_playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_fk` (`user_id`);

--
-- Indexes for table `playlist_songs`
--
ALTER TABLE `playlist_songs`
  ADD KEY `pid_fk` (`id`),
  ADD KEY `song_id` (`song_id`) USING BTREE;

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `date` (`date`),
  ADD KEY `views` (`views`),
  ADD KEY `slug` (`slug`),
  ADD KEY `featured` (`featured`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `date` (`date`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `main_playlist`
--
ALTER TABLE `main_playlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `playlist_songs`
--
ALTER TABLE `playlist_songs`
  ADD CONSTRAINT `song_fk` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
