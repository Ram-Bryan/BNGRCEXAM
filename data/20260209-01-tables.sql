-- Active: 1741104595338@@127.0.0.1@3306@takalo
CREATE DATABASE takalo
    DEFAULT CHARACTER SET = 'utf8mb4';

use takalo;
CREATE TABLE `user`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL,
    `mail` TEXT NOT NULL,
    `role` TEXT NOT NULL
);

CREATE TABLE `objet`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `lib` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `id_proprio` INT UNSIGNED NOT NULL,
    CONSTRAINT `objet_id_proprio_foreign` FOREIGN KEY(`id_proprio`) REFERENCES `user`(`id`)
);

CREATE TABLE `echange`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_objet` INT UNSIGNED NOT NULL,
    `id_actuel` INT UNSIGNED NOT NULL,
    `id_new` INT UNSIGNED NOT NULL,
    `date_echange` DATE NOT NULL,
    CONSTRAINT `echange_id_objet_foreign` FOREIGN KEY(`id_objet`) REFERENCES `objet`(`id`),
    CONSTRAINT `echange_id_actuel_foreign` FOREIGN KEY(`id_actuel`) REFERENCES `user`(`id`),
    CONSTRAINT `echange_id_new_foreign` FOREIGN KEY(`id_new`) REFERENCES `user`(`id`)
);