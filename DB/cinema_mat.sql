-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 20, 2021 alle 19:36
-- Versione del server: 5.7.17
-- Versione PHP: 7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema_mat`
--
CREATE DATABASE IF NOT EXISTS `cinema_mat` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cinema_mat`;

-- --------------------------------------------------------

--
-- Struttura della tabella `biglietto`
--

CREATE TABLE `biglietto` (
  `ID_Ticket` int(11) NOT NULL,
  `ID_User` int(11) NOT NULL,
  `posto` varchar(6) NOT NULL,
  `ID_TimeTable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `film_prenotabili`
--

CREATE TABLE `film_prenotabili` (
  `ID_Film` int(11) NOT NULL,
  `Titolo` char(30) NOT NULL,
  `Genere` char(20) NOT NULL,
  `durata` int(11) NOT NULL,
  `prezzo_a_persona` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `film_prenotabili`
--

INSERT INTO `film_prenotabili` (`ID_Film`, `Titolo`, `Genere`, `durata`, `prezzo_a_persona`) VALUES
(1, 'il cattivo poeta', 'biografico', 103, '5.70');

-- --------------------------------------------------------

--
-- Struttura della tabella `film_stream`
--

CREATE TABLE `film_stream` (
  `ID_Film` int(11) NOT NULL,
  `Titolo` char(30) NOT NULL,
  `Data_Add` date NOT NULL,
  `Genere` char(20) NOT NULL,
  `Free_Premium` tinyint(1) NOT NULL,
  `durata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `film_stream`
--

INSERT INTO `film_stream` (`ID_Film`, `Titolo`, `Data_Add`, `Genere`, `Free_Premium`, `durata`) VALUES
(1, 'bohemian rhapsody', '2019-10-15', 'Biografico', 1, 133),
(2, 'avengers endgame', '2021-06-20', 'azione', 0, 182),
(3, 'jojo rabbit', '2021-06-20', 'storico', 1, 108),
(4, 'your name', '2021-06-20', 'anime', 0, 107),
(5, 'ritorno al futuro', '2021-06-20', 'fantascienza', 0, 116);

-- --------------------------------------------------------

--
-- Struttura della tabella `lista`
--

CREATE TABLE `lista` (
  `ID_User` int(11) NOT NULL,
  `ID_Film` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `lista`
--

INSERT INTO `lista` (`ID_User`, `ID_Film`) VALUES
(3, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `tentativi_login`
--

CREATE TABLE `tentativi_login` (
  `User_ID` int(11) NOT NULL,
  `time` varchar(30) NOT NULL,
  `ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `tentativi_login`
--

INSERT INTO `tentativi_login` (`User_ID`, `time`, `ip`) VALUES
(1, '1622472016', '127.0.0.1');

-- --------------------------------------------------------

--
-- Struttura della tabella `timetable`
--

CREATE TABLE `timetable` (
  `ID_TimeTable` int(11) NOT NULL,
  `Data` varchar(40) NOT NULL,
  `ora` varchar(40) NOT NULL,
  `sala` int(11) NOT NULL,
  `liberi` int(11) NOT NULL DEFAULT '50',
  `ID_Film` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `timetable`
--

INSERT INTO `timetable` (`ID_TimeTable`, `Data`, `ora`, `sala`, `liberi`, `ID_Film`) VALUES
(1, '2021-05-31', '17:15', 2, 50, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `ID_User` int(11) NOT NULL,
  `Mail` char(30) NOT NULL,
  `Password` char(33) NOT NULL,
  `Nome` char(30) NOT NULL,
  `Cognome` char(30) NOT NULL,
  `Data_Birth` date NOT NULL,
  `Cln_Imp` tinyint(1) NOT NULL,
  `Free_Premium` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`ID_User`, `Mail`, `Password`, `Nome`, `Cognome`, `Data_Birth`, `Cln_Imp`, `Free_Premium`) VALUES
(1, 'comi.emanuele@issvigano.org', 'c6a427a9f1fc3250cfcffee2ba0cc936', 'Emanuele', 'Comi', '2001-12-28', 1, 0),
(2, 'admin.cinema@ComVid.com', '2ac9cb7dc02b3c0083eb70898e549b63', 'admin', 'admin', '2001-11-02', 0, NULL),
(3, 'ghisleni.davi@gmail.com', '35a0f08b3f003041ad467775734e9a5d', 'davide', 'ghisleni', '2001-10-08', 1, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `biglietto`
--
ALTER TABLE `biglietto`
  ADD PRIMARY KEY (`ID_Ticket`),
  ADD KEY `Prenotato da` (`ID_User`),
  ADD KEY `prenota per` (`ID_TimeTable`);

--
-- Indici per le tabelle `film_prenotabili`
--
ALTER TABLE `film_prenotabili`
  ADD PRIMARY KEY (`ID_Film`);

--
-- Indici per le tabelle `film_stream`
--
ALTER TABLE `film_stream`
  ADD PRIMARY KEY (`ID_Film`);

--
-- Indici per le tabelle `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`ID_User`,`ID_Film`),
  ADD KEY `Lista con` (`ID_Film`);

--
-- Indici per le tabelle `tentativi_login`
--
ALTER TABLE `tentativi_login`
  ADD KEY `User_ID` (`User_ID`);

--
-- Indici per le tabelle `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`ID_TimeTable`),
  ADD KEY `Film disponibile` (`ID_Film`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`ID_User`),
  ADD UNIQUE KEY `Mail` (`Mail`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  MODIFY `ID_Ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT per la tabella `film_prenotabili`
--
ALTER TABLE `film_prenotabili`
  MODIFY `ID_Film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `film_stream`
--
ALTER TABLE `film_stream`
  MODIFY `ID_Film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `timetable`
--
ALTER TABLE `timetable`
  MODIFY `ID_TimeTable` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  ADD CONSTRAINT `Prenotato da` FOREIGN KEY (`ID_User`) REFERENCES `utente` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenota per` FOREIGN KEY (`ID_TimeTable`) REFERENCES `timetable` (`ID_TimeTable`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `lista`
--
ALTER TABLE `lista`
  ADD CONSTRAINT `Lista con` FOREIGN KEY (`ID_Film`) REFERENCES `film_stream` (`ID_Film`),
  ADD CONSTRAINT `Lista di` FOREIGN KEY (`ID_User`) REFERENCES `utente` (`ID_User`);

--
-- Limiti per la tabella `tentativi_login`
--
ALTER TABLE `tentativi_login`
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`User_ID`) REFERENCES `utente` (`ID_User`);

--
-- Limiti per la tabella `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `Film disponibile` FOREIGN KEY (`ID_Film`) REFERENCES `film_prenotabili` (`ID_Film`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
