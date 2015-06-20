-- Adminer 3.7.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `angebote`;
CREATE TABLE `angebote` (
  `angebotsnummer` int(11) NOT NULL AUTO_INCREMENT,
  `kundennummer` int(11) NOT NULL DEFAULT 0,
  `angebotsdatum` text NOT NULL DEFAULT '',
  `lieferdatum` text NOT NULL DEFAULT '',
  `ueberschrift` text NOT NULL DEFAULT '',
  `zahlungsart` text NOT NULL DEFAULT '',
  `skonto_prozente` text NOT NULL DEFAULT '',
  `skonto_datum` text NOT NULL DEFAULT '',
  `abschlag_summe` text NOT NULL DEFAULT '',
  `abschlag_datum` text NOT NULL DEFAULT '',
  `converted` int(11) NOT NULL DEFAULT 0,
  `text_oben` text NOT NULL DEFAULT '',
  `text_unten` text NOT NULL DEFAULT '',
  `betrag` double NOT NULL DEFAULT 0.00,
  `endbetrag_typ` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`angebotsnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `artikel`;
CREATE TABLE `artikel` (
  `herstellerID` varchar(3) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL DEFAULT 0,
  `artikelnummer` text NOT NULL COMMENT 'externe Artikelnummer beim Hersteller',
  `name` text NOT NULL DEFAULT '',
  `menge` double NOT NULL DEFAULT 0.00,
  `einheit` text NOT NULL DEFAULT '',
  `einzelpreis` double NOT NULL DEFAULT 0.00,
  `gesamtpreis` double NOT NULL DEFAULT 0.00,
  `lieferzeit` text NOT NULL DEFAULT '',
  PRIMARY KEY (`herstellerID`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `einheiten`;
CREATE TABLE `einheiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL DEFAULT '',
  `html_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `einheiten` (`id`, `name`, `html_name`) VALUES
(1,	'Stück',	'Stück'),
(2,	'm3',	'm&sup3;'),
(3,	'm2',	'm&sup2;'),
(4,	'lfm',	'lfm'),
(5,	'Stunden',	'Stunden'),
(6,	'Liter',	'Liter');

DROP TABLE IF EXISTS `einstellungen`;
CREATE TABLE `einstellungen` (
  `name` text NOT NULL DEFAULT '',
  `wert` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `einstellungen` (`name`, `wert`) VALUES
('FALZMARKE_1',	'105'),
('FALZMARKE_2',	'199'),
('ADRESSE_ABSTAND_OBEN',	'53'),
('ADRESSE_ABSTAND_LINKS',	'25'),
('ADRESSE_BREITE',	'85'),
('ADRESSE_HOEHE',	'45'),
('RECHNUNG_ABSTAND_OBEN',	'95'),
('RECHNUNG_ABSTAND_LINKS',	'140'),
('UEBERSCHRIFT_ABSTAND_OBEN',	'90'),
('UEBERSCHRIFT_ABSTAND_LINKS',	'30'),
('MENGE_ABSTAND_LINKS',	'95'),
('ARTIKELNAME_ABSTAND_LINKS',	'20'),
('NUMMER_VOR_ARTIKELNAME_ABSTAND_LINKS',	'10'),
('PREIS_PRO_EINHEIT_ABSTAND_LINKS',	'140'),
('PREIS_MAL_MENGE_ABSTAND_LINKS',	'170'),
('EINHEIT_ABSTAND_LINKS',	'120'),
('SCHRIFTGROESSE_ADRESSE',	'12'),
('SCHRIFTGROESSE_ARTIKEL',	'10'),
('SCHRIFTGROESSE_UEBERSCHRIFTEN',	'13'),
('schriftgroesse_text_oben',	'10'),
('schriftgroesse_text_unten',	'10'),
('text_oben_abstand_links',	'12'),
('text_unten_abstand_links',	'40'),
('text_oben_abstand_nach_unten',	'7'),
('text_unten_abstand_nach_oben',	'0'),
('falzmarken_anzeigen',	'0'),
('kalender_id',	'tischlerei.kebernik@gmail.com');

DROP TABLE IF EXISTS `hersteller`;
CREATE TABLE `hersteller` (
  `id` varchar(3) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `kundennummer` varchar(40) NOT NULL DEFAULT '',
  `telefon` int(30) NOT NULL DEFAULT 0,
  `fax` int(30) NOT NULL DEFAULT 0,
  `passwort` varchar(20) NOT NULL COMMENT 'Passwort für Online-Shop',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `kunden`;
CREATE TABLE `kunden` (
  `kundennummer` int(11) NOT NULL AUTO_INCREMENT,
  `vorname` text NOT NULL DEFAULT '',
  `nachname` text NOT NULL DEFAULT '',
  `adresse` text NOT NULL DEFAULT '',
  `plz` text NOT NULL DEFAULT '',
  `ort` text NOT NULL DEFAULT '',
  `geschlecht` int(11) NOT NULL DEFAULT 0,
  `mail` text NOT NULL DEFAULT '',
  `fax` text NOT NULL DEFAULT '',
  `telefon` text NOT NULL DEFAULT '',
  `bemerkung` text NOT NULL DEFAULT '',
  `titel` text NOT NULL DEFAULT '',
  PRIMARY KEY (`kundennummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mitarbeiter`;
CREATE TABLE `mitarbeiter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(50) NOT NULL DEFAULT '',
  `nachname` varchar(50) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `hinweis` varchar(200) NOT NULL DEFAULT '',
  `handy` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `notizen`;
CREATE TABLE `notizen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mitarbeiterID` int(11) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notiz` varchar(300) NOT NULL DEFAULT '',
  `projektID` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `positionen`;
CREATE TABLE `positionen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL DEFAULT '',
  `menge` double NOT NULL DEFAULT 0.00,
  `einheit` text NOT NULL DEFAULT '',
  `preis` double NOT NULL DEFAULT 0.00,
  `angebotID` int(11) NOT NULL DEFAULT 0,
  `rechnungID` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `projekte`;
CREATE TABLE `projekte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kundeID` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(40) NOT NULL DEFAULT '',
  `stundenGesamt` int(11) NOT NULL DEFAULT 0,
  `stundenBisher` int(11) NOT NULL DEFAULT 0,
  `erstellDatum` date NOT NULL DEFAULT '2015-01-01',
  `fertigDatum` date NOT NULL DEFAULT '2015-01-01',
  `hinweis` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `rechnungen`;
CREATE TABLE `rechnungen` (
  `rechnungsnummer` int(11) NOT NULL AUTO_INCREMENT,
  `kundennummer` int(11) NOT NULL DEFAULT 0,
  `rechnungsdatum` text NOT NULL DEFAULT '',
  `lieferdatum` text NOT NULL DEFAULT '',
  `ueberschrift` text NOT NULL DEFAULT '',
  `zahlungsart` text NOT NULL DEFAULT '',
  `skonto_prozente` text NOT NULL DEFAULT '',
  `skonto_datum` text NOT NULL DEFAULT '',
  `abschlag_summe` text NOT NULL DEFAULT '',
  `abschlag_datum` text NOT NULL DEFAULT '',
  `text_oben` text NOT NULL DEFAULT '',
  `text_unten` text NOT NULL DEFAULT '',
  `betrag` double NOT NULL DEFAULT 0.00,
  `endbetrag_typ` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`rechnungsnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `rechnungzuprojekt`;
CREATE TABLE `rechnungzuprojekt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rechnungsnummer` int(11) NOT NULL DEFAULT 0,
  `projektID` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `textvorlagen`;
CREATE TABLE `textvorlagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(200) NOT NULL DEFAULT '',
  `text` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `vertreter`;
CREATE TABLE `vertreter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL DEFAULT '',
  `telefon` text NOT NULL DEFAULT '',
  `handy` text NOT NULL DEFAULT '',
  `herstellerID` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `zeiten`;
CREATE TABLE `zeiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `art` varchar(200) NOT NULL COMMENT 'Tätigkeit',
  `stunden` int(11) NOT NULL DEFAULT 0,
  `mitarbeiterID` int(11) NOT NULL DEFAULT 0,
  `projektID` int(11) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2015-06-10 09:22:32
