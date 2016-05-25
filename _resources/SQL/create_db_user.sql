CREATE DATABASE movs_dev;

CREATE USER 'movs_dev_user'@'localhost' IDENTIFIED BY 'p@55W0rd';

GRANT ALL PRIVILEGES ON movs_dev.* TO 'movs_dev_user'@'localhost';
