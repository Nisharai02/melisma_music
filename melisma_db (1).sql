-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2023 at 07:08 PM
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

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_id` int NOT NULL,
  `image` varchar(1024) NOT NULL
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
(18, 'Indian Classical', 0);

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
  `date` datetime NOT NULL,
  `views` int NOT NULL,
  `slug` varchar(100) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `title`, `user_id`, `artist_id`, `image`, `file`, `category_id`, `date`, `views`, `slug`, `featured`) VALUES
(1, '505', 1, 8, 'uploads/Arctic Monkeys.jpg', 'uploads/505_Arctic_Monkeys.mp3', 16, '2023-01-22 06:04:58', 3, 'im-going-back', 1),
(4, 'August', 1, 3, 'uploads/Beauty in black & white.jpg', 'uploads/August_TS.mp3', 15, '2023-01-22 07:23:41', 36, 'august', 1),
(5, 'Bloody Mary', 1, 6, 'uploads/Lady Gaga.jpg', 'uploads/Bloody Mary_Lady Gaga.mp3', 14, '2023-01-22 07:24:17', 5, 'bloody-mary', 0),
(6, 'Bad Romance', 1, 6, 'uploads/ae98c6f4-857d-4139-bea5-5018336111a0.jpg', 'uploads/Bad Romance_LG.mp3', 14, '2023-01-22 09:35:24', 6, 'bad-romance', 0),
(7, 'Just One Day', 1, 12, 'uploads/972b5809-812d-4e7f-9b06-72a78c6967b1.jpg', 'uploads/[MV] BTS(방탄소년단) _ Just One Day(하루만).mp3', 14, '2023-01-22 16:32:34', 1, 'just-one-day', 0);

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
(1, 'nyx', 'nyx@email.com', '$2y$10$PphgVoiytsQDHdVPzayPIu.TasBcNS9iQFsY1FF79h8vF9fMynB32', 'admin', '2023-01-21 13:40:28'),
(4, 'fiona', 'fiona@dummy.com', '$2y$10$VXxDIbUx0pPZjgyNJUcIM.xgaXnG6lhHSvlELDm9EnI35QhTlnokq', 'user', '2023-01-21 15:36:56'),
(5, 'moona', 'moona@email.com', '$2y$10$BYRHMz/1J82u85IQTXXpVeZ7INgb1S8FHCuufNc9/TA6sDq62nc7O', 'user', '2023-01-22 10:30:48'),
(10, 'Nisha', 'nisha@email.com', '$2y$10$GBJ85V1gJYCsjctSizOxtOSJsb9/Q2sSB1AqcnjytaLkb.S/0pflS', 'user', '2023-01-22 12:54:07'),
(11, 'Bob', 'bob@email.com', '$2y$10$ez9MGAoxZIO3nydas2D3F.xa7pDFGnDSDg6jhyzoOBSrRAAQpufHm', 'user', '2023-01-22 12:55:25'),
(12, 'moona', 'moona@email.com', '$2y$10$W8puA6QoHbybyygQlfmCk.LWHioGs45Lra7ZWmt9/5.tU4AbKa1ri', 'user', '2023-01-22 12:56:18'),
(13, 'hina', 'hina@email.com', '$2y$10$1Sx9iXJMYMFrS9OAAopylOCXLYy7C6OoTe..a0hybcL16qlp8XhTm', 'user', '2023-01-22 12:58:29'),
(14, 'hina', 'hina@email.com', '$2y$10$vxjRdT/oQwdU08Un5O./xusETpptblbwLcK9rUhjz1ZnT0glBlK2m', 'user', '2023-01-22 12:58:32'),
(15, 'hina', 'hina@email.com', '$2y$10$COwOGI38dWfXa5oIHj/eiePGzeMIJYcQFjfYr2qisAUTt20ez7Xu2', 'user', '2023-01-22 12:58:33'),
(16, 'hina', 'hina@email.com', '$2y$10$dJHOPHiWygfTNzdypOHuxO7ZaWYzrWe/ZrAuko/5lVRfgmSOQYepO', 'user', '2023-01-22 12:59:35'),
(17, 'Nishanyx', 'nisha@email.com', '$2y$10$atnqGYn25ZT7JGeCXyKAFOYh0ADeDpkthLyaJRpQTGRBjUpf7Sthq', 'user', '2023-01-22 13:04:48'),
(18, 'josh', 'josh@email.com', '$2y$10$35UHtwKLw5MZz.a2BwNETONECtlE0dveuPcFDTxrqZDIbRi8YD03u', 'user', '2023-01-22 13:13:27'),
(19, 'josh', 'josh@email.com', '$2y$10$ADTrhvOivW60bumR4coNou3ces3.ES9PvfJvNcg57kegOrvBSe7ei', 'user', '2023-01-22 13:14:19'),
(20, 'josh', 'josh@email.com', '$2y$10$MBlQiolG3MuSp3rs2sR/lOVaqO0lXv9YKlBL9pW/B38bnuL36qejC', 'user', '2023-01-22 13:16:38'),
(21, 'josh', 'josh@email.com', '$2y$10$bjKmuwqpg3sSTUAfACcv0.qPgiYBzrnfs6dja.uS76Al9dcMyUcfa', 'user', '2023-01-22 13:21:06'),
(22, 'mega', 'mega@email.com', '$2y$10$0DfD.nn1EFX1z.XdGpJSiuP2cXk503wfpkW1am8SZkd8f/flWRXMi', 'user', '2023-01-22 13:22:53'),
(23, 'mega', 'mega@email.com', '$2y$10$BxJO6pWK8rREDOf5se9LXO6ntOYyn9aCDLHfo92I.hwIPukTG/lnW', 'user', '2023-01-22 13:26:14'),
(24, 'mega', 'mega@email.com', '$2y$10$pc0K/ehKBuSLM8EBx6uY4O.2Fkyb3vpmud1QHr4qNdLne7/KcRMf.', 'user', '2023-01-22 13:27:59'),
(25, 'hannah', 'hannah@email.com', '$2y$10$JuN0l09dTq52dlxhWPw3duSi9lpSJfK85mmgBLLhCk7hQobEysrd6', 'user', '2023-01-22 13:32:19');

--
-- Indexes for dumped tables
--

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
  ADD KEY `featured` (`featured`);

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
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
