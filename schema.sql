CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL UNIQUE,
  code VARCHAR(32) NOT NULL UNIQUE
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  description VARCHAR(255) NOT NULL,
  image TEXT(512) NOT NULL,
  start_price INT NOT NULL,
  publish_date TIMESTAMP NOT NULL,
  expire_date TIMESTAMP NOT NULL,
  bet_step INT NOT NULL,
  author_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL
);

CREATE FULLTEXT INDEX lot_ft_search ON lots(name, description);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  make_time TIMESTAMP NOT NULL,
  price INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_date TIMESTAMP NOT NULL,
  email VARCHAR(128) NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  password VARCHAR(128) NOT NULL,
  contacts TEXT(1024)
);


