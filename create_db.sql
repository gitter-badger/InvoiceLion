CREATE DATABASE `invoicelion` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER 'invoicelion'@'localhost' IDENTIFIED BY 'invoicelion';
GRANT ALL PRIVILEGES ON `invoicelion`.* TO 'invoicelion'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
