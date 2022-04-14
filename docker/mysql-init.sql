CREATE DATABASE `userstransactions` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `userstransactions`;
CREATE USER 'user'@'%' IDENTIFIED BY 'pass123';
GRANT ALL PRIVILEGES ON `userstransactions`.* TO 'user'@'%';
FLUSH PRIVILEGES;