-- DROP TABLE `clients`;

CREATE TABLE `clients` (
--   `client_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(1000) NOT NULL UNIQUE,
  `client_displayname` varchar(2000) NOT NULL,
--   PRIMARY KEY (`client_id`)
  PRIMARY KEY (`client_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `idx_client_name` ON clients(`client_name`);
