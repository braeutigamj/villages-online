-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 09. Sep 2017 um 11:17
-- Server Version: 5.5.57-0+deb8u1
-- PHP-Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `vl_main`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `addtable`
--

CREATE TABLE IF NOT EXISTS `addtable` (
`id` int(11) NOT NULL,
  `size` text NOT NULL,
  `clickcount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE IF NOT EXISTS `login` (
`id` int(11) NOT NULL,
  `fbid` int(11) NOT NULL,
  `name` text NOT NULL,
  `activate` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`id` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rounds`
--

CREATE TABLE IF NOT EXISTS `rounds` (
`id` int(11) NOT NULL,
  `start_date` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_date` int(11) NOT NULL,
  `end_time` time NOT NULL,
  `config` text NOT NULL,
  `world` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

CREATE TABLE IF NOT EXISTS `session` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sessioncode` text NOT NULL,
  `client` text NOT NULL,
  `cookiename` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `worlds`
--

CREATE TABLE IF NOT EXISTS `worlds` (
`id` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `starttime` time NOT NULL,
  `enddate` int(11) NOT NULL,
  `endtime` time NOT NULL,
  `speed` int(11) NOT NULL,
  `w_bonus` int(11) NOT NULL,
  `max_ally` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `worlds`
--

INSERT INTO `worlds` (`id`, `startdate`, `starttime`, `enddate`, `endtime`, `speed`, `w_bonus`, `max_ally`) VALUES
(1, 2017, '12:00:00', 2022, '19:00:00', 100, 11, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `addtable`
--
ALTER TABLE `addtable`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `rounds`
--
ALTER TABLE `rounds`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `worlds`
--
ALTER TABLE `worlds`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `addtable`
--
ALTER TABLE `addtable`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `login`
--
ALTER TABLE `login`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `rounds`
--
ALTER TABLE `rounds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `session`
--
ALTER TABLE `session`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `worlds`
--
ALTER TABLE `worlds`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
