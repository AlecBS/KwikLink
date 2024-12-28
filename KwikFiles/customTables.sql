CREATE TABLE `SocialSites` (
    `UID`            INT UNSIGNED NOT NULL auto_increment,
    `AddDate`        timestamp NOT NULL default CURRENT_TIMESTAMP,
    `DelDate`        datetime,
    `WebsiteName`    varchar(80),
    `IconHTML`       varchar(120),
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `UserLinks` (
    `UID`            INT UNSIGNED NOT NULL auto_increment,
    `AddDate`        timestamp NOT NULL default CURRENT_TIMESTAMP,
    `DelDate`        datetime,
    `UserUID`        INT UNSIGNED,
    `SocialUID`      INT UNSIGNED,
    `SocialLink`     varchar(180),
    `Priority`       smallint NOT NULL DEFAULT 10,
  PRIMARY KEY (`UID`),
  KEY `ix_UserLinks_UserPriority` (`UserUID`, `Priority`),
  CONSTRAINT `fk_UserLinks_SocialUID`
      FOREIGN KEY (`SocialUID`) REFERENCES SocialSites(`UID`),
  CONSTRAINT `fk_UserLinks_UserUID`
      FOREIGN KEY (`UserUID`) REFERENCES wtkUsers(`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `UserWebsites` (
    `UID`            INT UNSIGNED NOT NULL auto_increment,
    `AddDate`        timestamp NOT NULL default CURRENT_TIMESTAMP,
    `DelDate`        datetime,
    `UserUID`        INT UNSIGNED,
    `WebsiteName`    varchar(120),
    `WebsiteLink`    varchar(240),
    `WebsiteDesc`    varchar(240),
    `Priority`       smallint NOT NULL DEFAULT 10,
  PRIMARY KEY (`UID`),
  KEY `ix_UserWebsites_UserPriority` (`UserUID`, `Priority`),
  CONSTRAINT `fk_UserWebsites_UserUID`
      FOREIGN KEY (`UserUID`) REFERENCES wtkUsers(`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `wtkUsers` CHANGE `Title` `Title` VARCHAR(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

ALTER TABLE `wtkUsers`
    ADD `ShowAddressLink` char(1) DEFAULT 'N' AFTER `LangPref`,
    ADD `ShowEmail` char(1) DEFAULT 'N' AFTER `ShowAddressLink`,
    ADD `ShowLocale` char(1) DEFAULT 'N' AFTER `ShowEmail`,
    ADD `BackgroundType` char(1) DEFAULT 'N' AFTER `ShowLocale`,
    ADD `BackgroundColor` char(7) DEFAULT NULL AFTER `BackgroundType`,
    ADD `BackgroundColor2` char(7) DEFAULT NULL AFTER `BackgroundColor`,
    ADD `BackgroundImage` char(12) DEFAULT NULL AFTER `BackgroundColor2`;

ALTER TABLE `wtkUsers` CHANGE `BackgroundType`
  `BackgroundType` CHAR(1) DEFAULT 'N' COMMENT 'None, Color, Gradient, Image';

ALTER TABLE `wtkUsers` CHANGE `BackgroundColor2`
  `BackgroundColor2` CHAR(1) DEFAULT 'N' COMMENT 'if gradient';

INSERT INTO `wtkPages` (`PageName`,`Path`, `FileName`) VALUES ('Social Media Sites', '/admin/', 'socialSiteList');
