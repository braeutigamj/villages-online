-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Aug 2015 um 17:19
-- Server Version: 5.6.16
-- PHP-Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `village`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally`
--

CREATE TABLE IF NOT EXISTS `ally` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `ally_name` text NOT NULL,
  `ally_short` text NOT NULL,
  `points` int(7) NOT NULL,
  `board` text NOT NULL,
  `intern_an` text NOT NULL,
  `welcome` text NOT NULL,
  `homepage` text NOT NULL,
  `beschr` text NOT NULL,
  `logo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_board_answer`
--

CREATE TABLE IF NOT EXISTS `ally_board_answer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `thread_id` int(10) NOT NULL,
  `player_id` int(10) NOT NULL,
  `ally_id` int(10) NOT NULL,
  `answer` text NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_board_thread`
--

CREATE TABLE IF NOT EXISTS `ally_board_thread` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NOT NULL,
  `ally_id` int(10) NOT NULL,
  `ally_categorie` int(10) NOT NULL,
  `thread_name` text NOT NULL,
  `text` text NOT NULL,
  `lastanswer` int(10) NOT NULL,
  `time` int(15) NOT NULL,
  `answers` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_categorie`
--

CREATE TABLE IF NOT EXISTS `ally_categorie` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ally_id` int(5) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_chat`
--

CREATE TABLE IF NOT EXISTS `ally_chat` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `message` text NOT NULL,
  `time` int(15) NOT NULL,
  `allyid` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_invite`
--

CREATE TABLE IF NOT EXISTS `ally_invite` (
  `id` int(10) NOT NULL,
  `ally_id` int(10) NOT NULL,
  `player_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_user_list`
--

CREATE TABLE IF NOT EXISTS `ally_user_list` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `ally_id` int(10) NOT NULL,
  `player_id` int(10) NOT NULL,
  `level` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `build`
--

CREATE TABLE IF NOT EXISTS `build` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `village` int(10) NOT NULL,
  `end_time` int(15) NOT NULL,
  `what` text NOT NULL,
  `build_time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `user1` int(10) NOT NULL,
  `user2` int(10) NOT NULL,
  `akzeptiert` int(2) NOT NULL,
  `since` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `market`
--

CREATE TABLE IF NOT EXISTS `market` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `handler` int(10) NOT NULL,
  `wood` int(8) NOT NULL,
  `clay` int(8) NOT NULL,
  `iron` int(8) NOT NULL,
  `gold` int(8) NOT NULL,
  `from_village` int(10) NOT NULL,
  `to_village` int(10) NOT NULL,
  `type` text NOT NULL,
  `end_time` int(15) NOT NULL,
  `move_time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `market_angebot`
--

CREATE TABLE IF NOT EXISTS `market_angebot` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `handler` int(3) NOT NULL,
  `from_village` int(10) NOT NULL,
  `sell` int(4) NOT NULL,
  `sell_w` text NOT NULL,
  `buy` int(4) NOT NULL,
  `buy_w` text NOT NULL,
  `anzahl` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `userid` int(15) NOT NULL,
  `readed` int(2) NOT NULL,
  `betreff` text NOT NULL,
  `message` text NOT NULL,
  `von_player` text NOT NULL,
  `time` int(15) NOT NULL,
  `answerid` text NOT NULL,
  `level` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `message_answer`
--

CREATE TABLE IF NOT EXISTS `message_answer` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `von_player` text NOT NULL,
  `text` text NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `movement`
--

CREATE TABLE IF NOT EXISTS `movement` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `from_village` int(5) NOT NULL,
  `to_village` int(5) NOT NULL,
  `to_user` int(10) NOT NULL,
  `start_time` int(15) NOT NULL,
  `end_time` int(15) NOT NULL,
  `units` text NOT NULL,
  `booty` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `userid` int(15) NOT NULL,
  `type` text NOT NULL,
  `att` text NOT NULL,
  `deff` text NOT NULL,
  `booty` text NOT NULL,
  `readed` int(2) NOT NULL,
  `time` int(15) NOT NULL,
  `att_lost` text NOT NULL,
  `deff_lose` text NOT NULL,
  `att_dorf` int(5) NOT NULL,
  `deff_dorf` int(5) NOT NULL,
  `wall` int(2) NOT NULL,
  `what` text NOT NULL,
  `agreement` text NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `sessioncode` text NOT NULL,
  `client` text NOT NULL,
  `cookiename` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shoutbox`
--

CREATE TABLE IF NOT EXISTS `shoutbox` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `message` text NOT NULL,
  `time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `smith`
--

CREATE TABLE IF NOT EXISTS `smith` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `villageid` int(10) NOT NULL,
  `unit` text NOT NULL,
  `end_time` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `support`
--

CREATE TABLE IF NOT EXISTS `support` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `from_village` int(5) NOT NULL,
  `to_village` int(5) NOT NULL,
  `units` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `train`
--

CREATE TABLE IF NOT EXISTS `train` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `village` int(5) NOT NULL,
  `end_time` int(15) NOT NULL,
  `what` text NOT NULL,
  `build_time` int(15) NOT NULL,
  `times` int(9) NOT NULL,
  `time_start` int(15) NOT NULL,
  `type` text NOT NULL,
  `finished` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `global_id` int(15) NOT NULL,
  `message` text NOT NULL,
  `report` int(2) NOT NULL,
  `points` int(8) NOT NULL,
  `extra_show` int(2) NOT NULL,
  `admin` int(2) NOT NULL,
  `last_activity` int(15) NOT NULL,
  `tut` int(2) NOT NULL,
  `village_style` int(1) NOT NULL,
  `build_show` int(1) NOT NULL,
  `level_show` int(1) NOT NULL,
  `main_show` int(1) NOT NULL,
  `menue_fixed` int(1) NOT NULL,
  `restart` int(2) NOT NULL,
  `ptext` text NOT NULL,
  `logo` text NOT NULL,
  `shoutbox` int(1) NOT NULL,
  `right` int(1) NOT NULL,
  `notes` text NOT NULL,
  `gamescreen` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `village`
--

CREATE TABLE IF NOT EXISTS `village` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `userid` int(15) NOT NULL,
  `name` text NOT NULL,
  `points` int(10) NOT NULL,
  `x` int(5) NOT NULL,
  `y` int(5) NOT NULL,
  `gold` int(5) NOT NULL,
  `wood_r` text NOT NULL,
  `clay_r` text NOT NULL,
  `iron_r` text NOT NULL,
  `arbwood` int(5) NOT NULL,
  `arbclay` int(5) NOT NULL,
  `arbiron` int(5) NOT NULL,
  `arbnone` int(10) NOT NULL,
  `food` int(8) NOT NULL,
  `ress_times` int(15) NOT NULL,
  `agreement` int(3) NOT NULL,
  `agreement_time` int(15) NOT NULL,
  `main` int(2) NOT NULL,
  `place` int(1) NOT NULL,
  `wood` int(2) NOT NULL,
  `clay` int(2) NOT NULL,
  `iron` int(2) NOT NULL,
  `farm` int(2) NOT NULL,
  `storage` int(2) NOT NULL,
  `barracks` int(2) NOT NULL,
  `stable` int(2) NOT NULL,
  `garage` int(2) NOT NULL,
  `smith` int(2) NOT NULL,
  `settlerplace` int(2) NOT NULL,
  `market` int(2) NOT NULL,
  `wall` int(2) NOT NULL,
  `group` int(15) NOT NULL,
  `arbmiliz` int(10) NOT NULL,
  `spear` int(10) NOT NULL,
  `sword` int(10) NOT NULL,
  `axe` int(10) NOT NULL,
  `archer` int(10) NOT NULL,
  `spy` int(10) NOT NULL,
  `light` int(10) NOT NULL,
  `marcher` int(10) NOT NULL,
  `heavy` int(10) NOT NULL,
  `ram` int(10) NOT NULL,
  `catapult` int(10) NOT NULL,
  `settler` int(10) NOT NULL,
  `sspear` int(1) NOT NULL,
  `ssword` int(1) NOT NULL,
  `saxe` int(1) NOT NULL,
  `sarcher` int(1) NOT NULL,
  `sspy` int(1) NOT NULL,
  `slight` int(1) NOT NULL,
  `smarcher` int(1) NOT NULL,
  `sheavy` int(1) NOT NULL,
  `sram` int(1) NOT NULL,
  `scatapult` int(1) NOT NULL,
  `ssettler` int(1) NOT NULL,
  `hut` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=106 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
