-- schema.sql
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  nickname VARCHAR(100),
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  role ENUM('admin','god','user') DEFAULT 'user',
  status ENUM('activated','disabled') DEFAULT 'activated',
  phone VARCHAR(30),
  birthdate DATE
);

CREATE TABLE zones (
  zone_id INT AUTO_INCREMENT PRIMARY KEY,
  zone_name VARCHAR(255) NOT NULL
);

CREATE TABLE clients (
  client_id INT AUTO_INCREMENT PRIMARY KEY,
  birthdate DATE,
  creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  title ENUM('Mr','Mme','Mr & Mme'),
  phone VARCHAR(30),
  email VARCHAR(255),
  address TEXT,
  city VARCHAR(100),
  zone_id INT,
  indications TEXT
);

CREATE TABLE periods (
  period_id INT AUTO_INCREMENT PRIMARY KEY,
  month TINYINT NOT NULL,
  year SMALLINT NOT NULL,
  UNIQUE KEY (month, year)
);

CREATE TABLE meals (
  meals_id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  period_id INT NOT NULL,
  date DATE NOT NULL,
  count INT DEFAULT 0,
  UNIQUE KEY (client_id, date)
);
