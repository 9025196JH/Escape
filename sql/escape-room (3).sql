-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260521.66b235a3ea
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2026 at 02:45 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `escape-room`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `answer` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `hint` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `roomId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer`, `hint`, `roomId`) VALUES
(1, 'Wat heeft sleutels maar geen sloten?', 'Toetsenbord', 'Je gebruikt het bij een computer.', 1),
(2, 'Wat wordt natter terwijl het droogt?', 'Handdoek', 'Je gebruikt het na douchen.', 1),
(3, 'Ik heb een gezicht en twee handen maar geen armen of benen. Wat ben ik?', 'Klok', 'Het geeft de tijd aan.', 1),
(4, 'Ik heb steden, maar geen huizen. Ik heb bergen, maar geen bomen. Ik heb water, maar geen vis. Wat ben ik?', 'Kaart', 'Je gebruikt me om de weg te vinden.', 2),
(5, 'Hoe meer je wegneemt, hoe groter het wordt. Wat is het?', 'Gat', 'Denk aan graven in de grond.', 2),
(6, 'Wat kan je vullen met water, maar zit vol gaten?', 'Spons', 'Je gebruikt het om af te wassen.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('speler','admin') NOT NULL DEFAULT 'speler'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'Hans', '$2y$10$oTtbdcTsAXRq0jxjuTmM4OCY05CAzpZHuLnQqGiQ5C/r63kViBXBe', 'speler'),
(2, 'admin_jehad', '$2y$10$fyIXXDA8BNiQf6a3sJJ2O.z3utCmKatUtrLzpPP2M/J/4hYzeMD9.', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
