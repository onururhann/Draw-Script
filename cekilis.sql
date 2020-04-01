-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Anamakine: localhost
-- Üretim Zamanı: 21 Ağu 2012, 11:16:24
-- Sunucu sürümü: 5.5.16
-- PHP Sürümü: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `cekilis`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `katilimcilar`
--

CREATE TABLE IF NOT EXISTS `katilimcilar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `katilimci` varchar(50) NOT NULL,
  `hak` smallint(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `katilimci` (`katilimci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kazananlar`
--

CREATE TABLE IF NOT EXISTS `kazananlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `katilimci` varchar(50) NOT NULL,
  `yedek` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `katilimci` (`katilimci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
