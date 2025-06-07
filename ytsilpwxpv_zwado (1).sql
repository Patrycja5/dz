-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Cze 07, 2025 at 04:51 PM
-- Wersja serwera: 10.6.21-MariaDB-cll-lve
-- Wersja PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `ytsilpwxpv_zwado`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `komentarze`
--

CREATE TABLE `komentarze` (
  `id` int(11) NOT NULL,
  `zaginiecie_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tresc` text NOT NULL,
  `data_dodania` datetime NOT NULL,
  `typ` enum('zaginione','adoptowane') NOT NULL DEFAULT 'zaginione'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Zrzut danych tabeli `komentarze`
--

INSERT INTO `komentarze` (`id`, `zaginiecie_id`, `username`, `tresc`, `data_dodania`, `typ`) VALUES
(1, 8, 'Eren', 'niezły bandzior', '2025-06-05 22:58:22', 'zaginione'),
(2, 7, 'Piesek', 'Ale wariat', '2025-06-05 23:03:21', 'zaginione'),
(3, 3, 'Piesek', 'Widziałam niedaleko ozimskiej. Chyba się przemieszcza', '2025-06-05 23:04:19', 'zaginione'),
(4, 3, 'Eren', 'XDDDDDDD', '2025-06-05 23:08:17', 'zaginione'),
(5, 6, 'Piesek', 'To tragedia', '2025-06-05 23:09:48', 'zaginione'),
(6, 8, 'a', 'a', '2025-06-06 00:50:23', 'zaginione'),
(7, 5, 'Jan', 'Smakowty kąsek', '2025-06-06 00:53:19', 'zaginione'),
(8, 7, 'Jan', 'twarda sztuka', '2025-06-06 01:18:12', 'adoptowane');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ogloszenia`
--

CREATE TABLE `ogloszenia` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Zrzut danych tabeli `ogloszenia`
--

INSERT INTO `ogloszenia` (`id`, `user_id`, `name`, `species`, `breed`, `age`, `description`, `image_url`, `created_at`, `price`) VALUES
(1, 8, '1', '23', '4', 5, '6', '7', '2025-06-04 20:19:05', NULL),
(2, 8, 'ola', 'dog', 'bos', 2, 'lookt', '2', '2025-06-04 21:01:57', NULL),
(3, 3, 'Janik', 'pies', 'jamink', 2, 'slodziak', 'uploads/wilczur.jpg', '2025-06-04 21:48:22', 2.00),
(5, 3, 'pawełek', 'batonik', 'adwokatowy', 2, 'słodki, pyszny', 'uploads/pawelek.jpg', '2025-06-04 22:16:53', NULL),
(6, 3, 'Kim Namjoon', 'Raper', 'BTS', 30, 'piękny człowiek, wielki lider', 'uploads/em.jpg', '2025-06-04 22:52:59', 99999999.99),
(7, 4, 'Boguś', 'Krokodyl', 'Krokodyl Australijsko-Polski', 2137, 'duży, ładny, groźny', 'uploads/krokodyl-nilowy-3.jpg', '2025-06-05 21:11:12', 200.00),
(8, 11, 'Gacuś', 'Biedra', 'Wielokropkowa', 99, 'Biedra z kropkami', 'uploads/1000010534.jpg', '2025-06-05 21:21:16', 80000.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `user_latitude` double DEFAULT NULL,
  `user_longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `phone`, `address`, `user_latitude`, `user_longitude`) VALUES
(1, 'Ann', 'sugahasthisswag@gmail.com', '$2y$10$he8FU//IEcK6pwA7Nu/RuebNjwG09NMr1uMGE4xZECdVBxOYO4526', '2025-06-03 02:30:19', '', '', NULL, NULL),
(3, 'Jan', 'kookiejungkookie@gmail.com', '$2y$10$ibUPDv6MwtsS5AHg0ZzZ5OhVAqfxahDWz/Ht/sOhKmNmomkQSOQsm', '2025-06-03 02:30:59', '777 777 888', 'Opole', 50.06143, 19.93658),
(4, 'Eren', 'r-smyk@wp.pl', '$2y$10$dMIHj9PGX0p3jLUxTXAeh.tMW5FBBRWFKL2urtOEtFMw6zjF/Szm6', '2025-06-03 02:38:00', '334223442', 'Opole', 52.22977, 21.01223),
(5, 'Joanna', 'look@gmail.com', '$2y$10$zdLJJTKgyIfZHU0lgC/vE.SJfhYun/xNq.J1r3GwPnRUZS/uPquG6', '2025-06-03 02:41:24', '', '', NULL, NULL),
(6, 'Jasiu', 'bruh@gmail.com', '$2y$10$I71SQFHwRkf.DpFuZYI4/.oGxZHt6lDWqRNr2Go0gMpR23R33/sTm', '2025-06-03 22:36:32', '', '', NULL, NULL),
(7, 'Sura', 'siur@wp.pl', '$2y$10$FlDJ06ynXOB1KOby7MII3eLQeVH32h.IJMoTGqPJ/KfKUK4bqct0m', '2025-06-03 22:38:13', '', '', NULL, NULL),
(8, 'Jureczek', 'ajak@wp.pl', '$2y$10$.OSgLqzgLUKKkFJyx4V7tekH2nje.OE5HyrBLHDSPUBvBuICmM/aa', '2025-06-04 00:26:12', '', '', NULL, NULL),
(9, 'Val', 'valeriia.shcherbakova12@gmail.com', '$2y$10$TpmNXwZZBfwMcEBfoKTRMeqC6Wt/2NLJS.DtRB1Vhh73DW2uc97NG', '2025-06-04 02:32:20', '', '', NULL, NULL),
(10, '1', 'www@gmail.com', '$2y$10$bTI1TDIp80IsOZrwjPw4zuYvtA6EBHlmRnCaSUckwbxFaE8Ct112e', '2025-06-05 18:26:30', '', '', NULL, NULL),
(11, 'Piesek', 'siwooariel@gmail.com', '$2y$10$B0sL5qmVgxvtNLThEzihdOFkKL8fCgvWGXzJL1u6aA3XkHZ5JmYf6', '2025-06-05 20:48:40', '', '', NULL, NULL),
(12, 'Karyna', 'smak@wp.pl', '$2y$10$IOeJLK.3WLBqERc/aqSVUecXC3Vp8ky9jHCSXNDLA4l3iMEi6/xDq', '2025-06-05 23:17:34', '', '', NULL, NULL),
(13, 'Ina', 'wo@gmail.com', '$2y$10$fNSrYuaY8Y6pXzRCbs08XOvwpOzLYCJ9TjxgBTajbDtzx12d2Gq3W', '2025-06-06 18:09:14', '', 'Namysłów', NULL, NULL),
(14, 'fghj', 'valeriia@gmail.com', '$2y$10$fR0rPnyOl5k3Z1PjWc4SZewf9U9s1NQ3bKV249Od/Dssgqp4.6ABW', '2025-06-07 08:00:17', '', 'Warszawa', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id` int(11) NOT NULL,
  `nadawca_id` int(11) NOT NULL,
  `odbiorca_id` int(11) NOT NULL,
  `ogloszenie_id` int(11) NOT NULL,
  `tresc` text NOT NULL,
  `data_wyslania` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Zrzut danych tabeli `wiadomosci`
--

INSERT INTO `wiadomosci` (`id`, `nadawca_id`, `odbiorca_id`, `ogloszenie_id`, `tresc`, `data_wyslania`) VALUES
(1, 3, 3, 6, 'ej poprosze', '2025-06-05 01:24:10'),
(2, 4, 3, 6, 'no elegancik', '2025-06-05 02:24:35'),
(3, 3, 4, 6, 'kurde pewnie że tak ', '2025-06-05 02:52:14'),
(4, 3, 3, 6, '47 miliardów i jest twój bez możliwości negocjacji', '2025-06-05 02:52:38'),
(7, 3, 8, 2, 'siema za ile ?', '2025-06-05 18:05:54'),
(8, 3, 3, 6, 'o ty wariacie ', '2025-06-05 18:06:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zaginiecia`
--

CREATE TABLE `zaginiecia` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `opis` text NOT NULL,
  `zdjecie` varchar(255) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `radius` double NOT NULL,
  `kontakt` varchar(100) NOT NULL,
  `data_zaginiecia` datetime NOT NULL,
  `odnaleziony` tinyint(1) DEFAULT 0,
  `uzytkownik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Zrzut danych tabeli `zaginiecia`
--

INSERT INTO `zaginiecia` (`id`, `nazwa`, `opis`, `zdjecie`, `latitude`, `longitude`, `radius`, `kontakt`, `data_zaginiecia`, `odnaleziony`, `uzytkownik_id`) VALUES
(3, 'Pawelek', 'sss', 'uploads/1749154085_pawelek.jpg', 50.6596091051033, 17.95028686523438, 500, 'E‑mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-06 00:00:00', 0, 4),
(4, 'Pawelek', 'ss', 'uploads/1749154358_pawelek.jpg', 50.624474105994544, 17.948913574218754, 500, 'E‑mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-06 00:00:00', 0, 4),
(5, 'Pawelek', 'ss', 'uploads/1749154886_wilczur.jpg', 52.222926261608386, 21.060447692871097, 500, 'E‑mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-06 00:00:00', 0, 4),
(6, 'nikodem', 'No nie ma go', 'uploads/1749155129_em.jpg', 52.157433487577975, 20.818061828613285, 50000, 'E-mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-11 00:00:00', 0, 4),
(7, 'wariacik', 'no wariay no uciekl', 'uploads/1749155556_wilczur.jpg', 50.36458618952248, 18.396606445312504, 1000, 'E-mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-03 00:00:00', 0, 4),
(8, 'Kotek', 'Kotek mi zaginął ????????????????????????', 'uploads/1749156680_inbound276890513221388070.jpg', 52.229022001332076, 20.233182101619484, 350, 'E-mail: siwooariel@gmail.com; Telefon: ; Miejsce zam.: ', '2025-06-04 00:00:00', 0, 11),
(9, 'Bilbo', 'Bilbo Bagins', 'uploads/1749157041_psiurek.jpg', 52.401266800143915, 22.703247070312504, 500, 'E-mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-02 00:00:00', 0, 4),
(10, 'Wiewiór Tomasz', 'Zaginął Wiewiór Tomasz r', 'uploads/1749249909_logo.png', 50.657528926792, 17.918648114427928, 500, 'E-mail: kookiejungkookie@gmail.com; Telefon: 777 777 888; Miejsce zam.: Opole', '2025-06-06 00:00:00', 0, 3),
(11, 'Brodziu', 'wyskoczył z okna w siną dal', 'uploads/1749250192_pawelek.jpg', 50.666888645037, 17.936965946573768, 500, 'E-mail: r-smyk@wp.pl; Telefon: 334223442; Miejsce zam.: Opole', '2025-06-07 00:00:00', 0, 4);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `komentarze`
--
ALTER TABLE `komentarze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zaginiecie_id` (`zaginiecie_id`);

--
-- Indeksy dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nadawca_id` (`nadawca_id`),
  ADD KEY `odbiorca_id` (`odbiorca_id`),
  ADD KEY `ogloszenie_id` (`ogloszenie_id`);

--
-- Indeksy dla tabeli `zaginiecia`
--
ALTER TABLE `zaginiecia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zaginiecia_uzytkownik_id` (`uzytkownik_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `komentarze`
--
ALTER TABLE `komentarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `zaginiecia`
--
ALTER TABLE `zaginiecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `komentarze`
--
ALTER TABLE `komentarze`
  ADD CONSTRAINT `komentarze_ibfk_1` FOREIGN KEY (`zaginiecie_id`) REFERENCES `zaginiecia` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD CONSTRAINT `ogloszenia_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD CONSTRAINT `wiadomosci_ibfk_1` FOREIGN KEY (`nadawca_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wiadomosci_ibfk_2` FOREIGN KEY (`odbiorca_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wiadomosci_ibfk_3` FOREIGN KEY (`ogloszenie_id`) REFERENCES `ogloszenia` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `zaginiecia`
--
ALTER TABLE `zaginiecia`
  ADD CONSTRAINT `fk_zaginiecia_uzytkownik_id` FOREIGN KEY (`uzytkownik_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
