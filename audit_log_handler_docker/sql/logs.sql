-- DROP TABLE `logs`;

CREATE TABLE `logs`(
--    `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
   `log_sessionid` varchar(300) NOT NULL UNIQUE,
   `log_timestamp` DATETIME NOT NULL,
   `log_domain` varchar(100) NOT NULL,
   `log_sp` varchar(1000) NOT NULL,
   `log_idp` varchar(1000) NOT NULL,
--    `log_affiliations` varchar(200) NOT NULL,
   `log_affiliate` boolean,
   `log_employee` boolean,
   `log_member` boolean,
   `log_faculty` boolean,
   `log_staff` boolean,
   `log_student` boolean,
--    PRIMARY KEY (`log_id`)
   PRIMARY KEY (`log_sessionid`)
);

CREATE INDEX `idx_log_timestamp` ON logs(`log_timestamp`);
CREATE INDEX `idx_log_sessionid` ON logs(`log_timestamp`);
CREATE INDEX `idx_log_domain` ON logs(`log_domain`);
-- CREATE INDEX `idx_log_sp` ON logs(`log_sp`);
-- CREATE INDEX `idx_log_idp` ON logs(`log_idp`);
-- CREATE INDEX `idx_log_affiliations` ON logs(`log_affiliations`);
