DROP DATABASE IF EXISTS locationvoiture;
CREATE DATABASE locationvoiture;
USE locationvoiture;

CREATE TABLE utilisateur (
    id VARCHAR(50),
    passe VARCHAR(30),
    role ENUM('gerant', 'client') DEFAULT 'client',
    PRIMARY KEY(id)
);

CREATE TABLE client (
    num_client INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(50),
    telephone INT(11),
    nom VARCHAR(20),
    prenom VARCHAR(30),
    sexe ENUM('M','F'),
    profession VARCHAR(50),
    status ENUM('actif', 'bloque') DEFAULT 'actif',
    PRIMARY KEY(num_client),
    FOREIGN KEY(email) REFERENCES utilisateur(id)
);

CREATE TABLE gerant (
    id_gerant INT NOT NULL AUTO_INCREMENT,
    id VARCHAR(50),
    email VARCHAR(50),
    nom VARCHAR(20),
    prenom VARCHAR(30),
    PRIMARY KEY(id_gerant),
    FOREIGN KEY(id) REFERENCES utilisateur(id)
);

CREATE TABLE voiture (
    code VARCHAR(20),
    marque VARCHAR(20),
    modele VARCHAR(20),
    description VARCHAR(255),
    tarif_reserv FLOAT NOT NULL,
    qt_initial INT NOT NULL DEFAULT 0,
    qt_disponible INT NOT NULL DEFAULT 0,
    PRIMARY KEY(code),
    CHECK (qt_initial >= qt_disponible)
);

CREATE TABLE photo (
    id_photo INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(20),
    url VARCHAR(255),
    PRIMARY KEY(id_photo),
    FOREIGN KEY(code) REFERENCES voiture(code)
);

CREATE TABLE offre (
    id_offr INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(20),
    debut_offr DATE,
    fin_offr DATE,
    tarif_reduit FLOAT,
    PRIMARY KEY(id_offr),
    FOREIGN KEY(code) REFERENCES voiture(code),
    CHECK (debut_offr <= fin_offr)
);

CREATE TABLE reservation (
    id_reser INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(20),
    id VARCHAR(50),
    status_reser ENUM('suivie', 'confirmer', 'annuler', 'terminer', 'interrompue', 'en cours') DEFAULT 'suivie',
    debut_reser DATE,
    fin_reserv DATE,
    tarif FLOAT,
    PRIMARY KEY(id_reser),
    FOREIGN KEY(code) REFERENCES voiture(code),
    FOREIGN KEY(id) REFERENCES utilisateur(id),
    CHECK (debut_reser < fin_reserv)
);

CREATE TABLE favorie (
    id_fav INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(20),
    id VARCHAR(50),
    PRIMARY KEY(id_fav),
    FOREIGN KEY(code) REFERENCES voiture(code),
    FOREIGN KEY(id) REFERENCES utilisateur(id)
);

CREATE TABLE historique(
    id_histo INT NOT NULL AUTO_INCREMENT,
    marque VARCHAR(20),
    modele VARCHAR(20),
    tarif FLOAT,
    debut_reser DATE,
    fin_reserv DATE,
    email VARCHAR(50),
    status ENUM('suivie', 'confirmer', 'annuler', 'terminer', 'interrompue', 'en cours') DEFAULT 'suivie',
    PRIMARY KEY(id_histo)
);