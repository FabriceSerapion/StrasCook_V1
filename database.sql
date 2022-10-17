-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Octobre 2017 à 13:53
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `simple-mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE tag
(
    id_tag INT PRIMARY KEY NOT NULL,
    name_tag VARCHAR(25)
);

--
-- Structure de la table `menu`
--

CREATE TABLE menu
(
    id_menu INT PRIMARY KEY NOT NULL,
    name_menu VARCHAR(50),
	price_menu double,
	note_menu double,
	description_menu text
);

--
-- Structure de la table `cook`
--

CREATE TABLE cook
(
    id_cook INT PRIMARY KEY NOT NULL,
    firstname_cook VARCHAR(50),
	lastname_cook VARCHAR(50),
	description_cook text,
	available_cook VARCHAR(5)
);

--
-- Structure de la table `booking`
--

CREATE TABLE BOOKING
(
    id_booking INT PRIMARY KEY NOT NULL,
    date_booking DATE,
	adress_booking VARCHAR(255),
	price_prestation double,
    is_lesson bool,
	id_cook int,
    FOREIGN KEY (id_cook) REFERENCES cook(id_cook)
);

--
-- Structure de la table `tag_menu`
--

CREATE TABLE tag_menu (
	id_tag int,
    id_menu int,
    FOREIGN KEY (id_tag) REFERENCES tag(id_tag),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);

--
-- Structure de la table `booking_menu`
--

CREATE TABLE booking_menu (
	id_booking int,
    id_menu int,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);