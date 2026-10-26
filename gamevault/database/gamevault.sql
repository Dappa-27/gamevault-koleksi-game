-- GameVault - skrip database (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS gamevault CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gamevault;

DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE games (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(150) NOT NULL,
    platform     ENUM('pc','playstation','xbox','switch','mobile','other') NOT NULL,
    genre        ENUM('action','adventure','rpg','strategy','sports','racing','simulation','puzzle','horror','other') NOT NULL,
    status       ENUM('playing','completed','backlog','wishlist') NOT NULL DEFAULT 'backlog',
    release_year SMALLINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_title (title)
) ENGINE=InnoDB;

-- Akun demo: username admin / password admin123
INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$10$ukhIfb8Bzm7GDrPvcLqw2.SXEBV.ecU/fD7FiSWutZLRUtdoVsdIm');

INSERT INTO games (title, platform, genre, status, release_year) VALUES
('The Legend of Zelda: Breath of the Wild', 'switch', 'adventure', 'completed', 2017),
('Elden Ring', 'pc', 'rpg', 'playing', 2022),
('God of War Ragnarök', 'playstation', 'action', 'backlog', 2022),
('Forza Horizon 5', 'xbox', 'racing', 'completed', 2021),
('Stardew Valley', 'pc', 'simulation', 'playing', 2016),
('Civilization VI', 'pc', 'strategy', 'backlog', 2016),
('Resident Evil 4 Remake', 'playstation', 'horror', 'wishlist', 2023),
('Portal 2', 'pc', 'puzzle', 'completed', 2011),
('Genshin Impact', 'mobile', 'rpg', 'playing', 2020),
('EA Sports FC 25', 'playstation', 'sports', 'wishlist', 2024);
