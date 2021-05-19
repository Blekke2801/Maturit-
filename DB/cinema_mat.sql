-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 18, 2021 alle 19:59
-- Versione del server: 5.7.17
-- Versione PHP: 5.6.30

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

-- --------------------------------------------------------

--
-- Struttura della tabella `biglietto`
--

CREATE TABLE `biglietto` (
  `ID_Ticket` int(11) NOT NULL,
  `Fila` char(2) NOT NULL,
  `Numero` int(11) NOT NULL,
  `Data` date NOT NULL,
  `Ora` time NOT NULL,
  `ID_User` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `film_prenotabili`
--

CREATE TABLE `film_prenotabili` (
  `ID_Film` int(11) NOT NULL,
  `Titolo` char(30) NOT NULL,
  `Genere` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `film_stream`
--

CREATE TABLE `film_stream` (
  `ID_FIlm` int(11) NOT NULL,
  `Titolo` char(30) NOT NULL,
  `Data_Add` date NOT NULL,
  `Genere` char(20) NOT NULL,
  `Free_Premium` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `film_stream`
--

INSERT INTO `film_stream` (`ID_FIlm`, `Titolo`, `Data_Add`, `Genere`, `Free_Premium`) VALUES
(1, 'Bohemian Rhapsody', '2019-10-15', 'Biografico', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `lista`
--

CREATE TABLE `lista` (
  `ID_User` int(11) NOT NULL,
  `ID_FIlm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `mostra`
--

CREATE TABLE `mostra` (
  `ID_Film` int(11) NOT NULL,
  `ID_Sala` int(11) NOT NULL,
  `Ora` time NOT NULL,
  `Data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `posti`
--

CREATE TABLE `posti` (
  `Fila` char(2) NOT NULL,
  `Numero` int(11) NOT NULL,
  `Prenotato` tinyint(1) NOT NULL,
  `ID_Sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `posti`
--

INSERT INTO `posti` (`Fila`, `Numero`, `Prenotato`, `ID_Sala`) VALUES
('A', 1, 0, 1),
('A', 1, 0, 2),
('A', 1, 0, 3),
('A', 1, 0, 4),
('B', 1, 0, 1),
('B', 1, 0, 2),
('B', 1, 0, 3),
('B', 1, 0, 4),
('C', 1, 0, 1),
('C', 1, 0, 2),
('C', 1, 0, 3),
('C', 1, 0, 4),
('D', 1, 0, 1),
('D', 1, 0, 2),
('D', 1, 0, 3),
('D', 1, 0, 4),
('E', 1, 0, 1),
('E', 1, 0, 2),
('E', 1, 0, 3),
('E', 1, 0, 4),
('A', 2, 0, 1),
('A', 2, 0, 2),
('A', 2, 0, 3),
('A', 2, 0, 4),
('B', 2, 0, 1),
('B', 2, 0, 2),
('B', 2, 0, 3),
('B', 2, 0, 4),
('C', 2, 0, 1),
('C', 2, 0, 2),
('C', 2, 0, 3),
('C', 2, 0, 4),
('D', 2, 0, 1),
('D', 2, 0, 2),
('D', 2, 0, 3),
('D', 2, 0, 4),
('E', 2, 0, 1),
('E', 2, 0, 2),
('E', 2, 0, 3),
('E', 2, 0, 4),
('A', 3, 0, 1),
('A', 3, 0, 2),
('A', 3, 0, 3),
('A', 3, 0, 4),
('B', 3, 0, 1),
('B', 3, 0, 2),
('B', 3, 0, 3),
('B', 3, 0, 4),
('C', 3, 0, 1),
('C', 3, 0, 2),
('C', 3, 0, 3),
('C', 3, 0, 4),
('D', 3, 0, 1),
('D', 3, 0, 2),
('D', 3, 0, 3),
('D', 3, 0, 4),
('E', 3, 0, 1),
('E', 3, 0, 2),
('E', 3, 0, 3),
('E', 3, 0, 4),
('A', 4, 0, 1),
('A', 4, 0, 2),
('A', 4, 0, 3),
('A', 4, 0, 4),
('B', 4, 0, 1),
('B', 4, 0, 2),
('B', 4, 0, 3),
('B', 4, 0, 4),
('C', 4, 0, 1),
('C', 4, 0, 2),
('C', 4, 0, 3),
('C', 4, 0, 4),
('D', 4, 0, 1),
('D', 4, 0, 2),
('D', 4, 0, 3),
('D', 4, 0, 4),
('E', 4, 0, 1),
('E', 4, 0, 2),
('E', 4, 0, 3),
('E', 4, 0, 4),
('A', 5, 0, 1),
('A', 5, 0, 2),
('A', 5, 0, 3),
('A', 5, 0, 4),
('B', 5, 0, 1),
('B', 5, 0, 2),
('B', 5, 0, 3),
('B', 5, 0, 4),
('C', 5, 0, 1),
('C', 5, 0, 2),
('C', 5, 0, 3),
('C', 5, 0, 4),
('D', 5, 0, 1),
('D', 5, 0, 2),
('D', 5, 0, 3),
('D', 5, 0, 4),
('E', 5, 0, 1),
('E', 5, 0, 2),
('E', 5, 0, 3),
('E', 5, 0, 4),
('A', 6, 0, 1),
('A', 6, 0, 2),
('A', 6, 0, 3),
('A', 6, 0, 4),
('B', 6, 0, 1),
('B', 6, 0, 2),
('B', 6, 0, 3),
('B', 6, 0, 4),
('C', 6, 0, 1),
('C', 6, 0, 2),
('C', 6, 0, 3),
('C', 6, 0, 4),
('D', 6, 0, 1),
('D', 6, 0, 2),
('D', 6, 0, 3),
('D', 6, 0, 4),
('E', 6, 0, 1),
('E', 6, 0, 2),
('E', 6, 0, 3),
('E', 6, 0, 4),
('A', 7, 0, 1),
('A', 7, 0, 2),
('A', 7, 0, 3),
('A', 7, 0, 4),
('B', 7, 0, 1),
('B', 7, 0, 2),
('B', 7, 0, 3),
('B', 7, 0, 4),
('C', 7, 0, 1),
('C', 7, 0, 2),
('C', 7, 0, 3),
('C', 7, 0, 4),
('D', 7, 0, 1),
('D', 7, 0, 2),
('D', 7, 0, 3),
('D', 7, 0, 4),
('E', 7, 0, 1),
('E', 7, 0, 2),
('E', 7, 0, 3),
('E', 7, 0, 4),
('A', 8, 0, 1),
('A', 8, 0, 2),
('A', 8, 0, 3),
('A', 8, 0, 4),
('B', 8, 0, 1),
('B', 8, 0, 2),
('B', 8, 0, 3),
('B', 8, 0, 4),
('C', 8, 0, 1),
('C', 8, 0, 2),
('C', 8, 0, 3),
('C', 8, 0, 4),
('D', 8, 0, 1),
('D', 8, 0, 2),
('D', 8, 0, 3),
('D', 8, 0, 4),
('E', 8, 0, 1),
('E', 8, 0, 2),
('E', 8, 0, 3),
('E', 8, 0, 4),
('A', 9, 0, 1),
('A', 9, 0, 2),
('A', 9, 0, 3),
('A', 9, 0, 4),
('B', 9, 0, 1),
('B', 9, 0, 2),
('B', 9, 0, 3),
('B', 9, 0, 4),
('C', 9, 0, 1),
('C', 9, 0, 2),
('C', 9, 0, 3),
('C', 9, 0, 4),
('D', 9, 0, 1),
('D', 9, 0, 2),
('D', 9, 0, 3),
('D', 9, 0, 4),
('E', 9, 0, 1),
('E', 9, 0, 2),
('E', 9, 0, 3),
('E', 9, 0, 4),
('A', 10, 0, 1),
('A', 10, 0, 2),
('A', 10, 0, 3),
('A', 10, 0, 4),
('B', 10, 0, 1),
('B', 10, 0, 2),
('B', 10, 0, 3),
('B', 10, 0, 4),
('C', 10, 0, 1),
('C', 10, 0, 2),
('C', 10, 0, 3),
('C', 10, 0, 4),
('D', 10, 0, 1),
('D', 10, 0, 2),
('D', 10, 0, 3),
('D', 10, 0, 4),
('E', 10, 0, 1),
('E', 10, 0, 2),
('E', 10, 0, 3),
('E', 10, 0, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `sala`
--

CREATE TABLE `sala` (
  `ID_Sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `sala`
--

INSERT INTO `sala` (`ID_Sala`) VALUES
(1),
(2),
(3),
(4);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `ID_User` int(11) NOT NULL,
  `Mail` char(30) NOT NULL,
  `Password` char(30) NOT NULL,
  `Nome` char(30) NOT NULL,
  `Cognome` char(30) NOT NULL,
  `Data_Birth` int(11) NOT NULL,
  `Cln_Imp` tinyint(1) NOT NULL,
  `Free_Premium` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `biglietto`
--
ALTER TABLE `biglietto`
  ADD PRIMARY KEY (`ID_Ticket`),
  ADD KEY `Numero` (`Numero`,`Fila`),
  ADD KEY `Prenotato da` (`ID_User`);

--
-- Indici per le tabelle `film_prenotabili`
--
ALTER TABLE `film_prenotabili`
  ADD PRIMARY KEY (`ID_Film`);

--
-- Indici per le tabelle `film_stream`
--
ALTER TABLE `film_stream`
  ADD PRIMARY KEY (`ID_FIlm`);

--
-- Indici per le tabelle `lista`
--
ALTER TABLE `lista`
  ADD PRIMARY KEY (`ID_User`,`ID_FIlm`),
  ADD KEY `Lista con` (`ID_FIlm`);

--
-- Indici per le tabelle `mostra`
--
ALTER TABLE `mostra`
  ADD PRIMARY KEY (`ID_Film`,`ID_Sala`),
  ADD KEY `Mostra in` (`ID_Sala`);

--
-- Indici per le tabelle `posti`
--
ALTER TABLE `posti`
  ADD PRIMARY KEY (`Numero`,`Fila`,`ID_Sala`),
  ADD KEY `Sala` (`ID_Sala`);

--
-- Indici per le tabelle `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`ID_Sala`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  MODIFY `ID_Ticket` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `film_prenotabili`
--
ALTER TABLE `film_prenotabili`
  MODIFY `ID_Film` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `film_stream`
--
ALTER TABLE `film_stream`
  MODIFY `ID_FIlm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `sala`
--
ALTER TABLE `sala`
  MODIFY `ID_Sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  ADD CONSTRAINT `Prenotato da` FOREIGN KEY (`ID_User`) REFERENCES `utente` (`ID_User`);

--
-- Limiti per la tabella `lista`
--
ALTER TABLE `lista`
  ADD CONSTRAINT `Lista con` FOREIGN KEY (`ID_FIlm`) REFERENCES `film_stream` (`ID_FIlm`),
  ADD CONSTRAINT `Lista di` FOREIGN KEY (`ID_User`) REFERENCES `utente` (`ID_User`);

--
-- Limiti per la tabella `mostra`
--
ALTER TABLE `mostra`
  ADD CONSTRAINT `Mostra il film` FOREIGN KEY (`ID_Film`) REFERENCES `film_prenotabili` (`ID_Film`),
  ADD CONSTRAINT `Mostra in` FOREIGN KEY (`ID_Sala`) REFERENCES `sala` (`ID_Sala`);

--
-- Limiti per la tabella `posti`
--
ALTER TABLE `posti`
  ADD CONSTRAINT `Sala` FOREIGN KEY (`ID_Sala`) REFERENCES `sala` (`ID_Sala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
