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
-- Creation de la database
--

DROP DATABASE IF EXISTS stras_cook;

create database stras_cook;

use stras_cook;

--
-- Suppression des tables si elles existent
--

drop table if exists tag;
drop table if exists menu;
drop table if exists menu_tag;
drop table if exists cook;
drop table if exists booking;
drop table if exists booking_menu;

--
-- Structure de la table `tag`
--

CREATE TABLE tag
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name_tag VARCHAR(25) NOT NULL
);

--
-- Structure de la table `menu`
--

CREATE TABLE menu
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name_menu VARCHAR(50) NOT NULL,
	price_menu double NOT NULL,
	note_menu double,
	descr_menu_appetizer text,
    descr_menu_starter text,
    descr_menu_meal text,
    descr_menu_dessert text,
    descr_menu_cheese text,
    descr_menu_cuteness text
);

--
-- Structure de la table `cook`
--

CREATE TABLE cook
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    firstname_cook VARCHAR(50) NOT NULL,
	lastname_cook VARCHAR(50) NOT NULL,
	description_cook text,
	begin_cook VARCHAR(2) NOT NULL,
    end_cook VARCHAR(2) NOT NULL
);

--
-- Structure de la table `booking`
--

CREATE TABLE booking
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    date_booking DATE NOT NULL,
	adress_booking VARCHAR(255) NOT NULL,
	price_prestation double NOT NULL,
    is_lesson bool,
	id_cook int NOT NULL,
    FOREIGN KEY (id_cook) REFERENCES cook(id)
);

--
-- Structure de la table `menu_tag`
--

CREATE TABLE menu_tag (
    id_menu int NOT NULL,
	id_tag int NOT NULL,
    FOREIGN KEY (id_menu) REFERENCES menu(id),
    FOREIGN KEY (id_tag) REFERENCES tag(id)
);

--
-- Structure de la table `booking_menu`
--

CREATE TABLE booking_menu (
	id_booking int NOT NULL,
    id_menu int NOT NULL,
    FOREIGN KEY (id_booking) REFERENCES booking(id),
    FOREIGN KEY (id_menu) REFERENCES menu(id)
);