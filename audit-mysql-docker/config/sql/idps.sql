-- DROP TABLE `idps`;

CREATE TABLE `idps` (
--   `idp_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idp_entityid` varchar(1000) NOT NULL UNIQUE,
  `idp_displayname` varchar(2000) NOT NULL,
  `idp_country` varchar(2),
  `idp_domain` varchar(2000),
  `idp_hash` varchar(256),
  `idp_tested` BOOLEAN default FALSE,
--   PRIMARY KEY (`idp_id`)
  PRIMARY KEY (`idp_entityid`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE INDEX `idx_idp_entityid` ON idps(`idp_entityid`);
CREATE INDEX `idx_idp_hash` ON idps(`idp_hash`);
CREATE INDEX `idx_idp_country` ON idps(`idp_country`);
