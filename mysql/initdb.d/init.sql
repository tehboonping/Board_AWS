CREATE DATABASE IF NOT EXISTS boarddata;
USE boarddata;

CREATE TABLE IF NOT EXISTS datas
(
  id int(10),
  name VARCHAR(20),
  message TEXT,
  posttime DATETIME,
)DEFAULT CHARACTER
  SET=utf8;

CREATE TABLE IF NOT EXISTS systems
(
  id int(10) AUTO_INCREMENT NOT NULL,
  user VARCHAR(30),
  pass VARCHAR(255),
  username VARCHAR(20),
  lv int(10),
  PRIMARY KEY(id)
)DEFAULT CHARACTER
  SET=utf8;