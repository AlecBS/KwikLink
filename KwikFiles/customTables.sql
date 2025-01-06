CREATE TABLE `SocialSites` (
    `UID`            INT UNSIGNED NOT NULL auto_increment,
    `AddDate`        timestamp NOT NULL default CURRENT_TIMESTAMP,
    `DelDate`        datetime,
    `WebsiteName`    varchar(80),
    `ButtonColor`    varchar(24),
    `IconHTML`       varchar(120),
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `UserLinks` (
    `UID`            INT UNSIGNED NOT NULL auto_increment,
    `AddDate`        timestamp NOT NULL default CURRENT_TIMESTAMP,
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
    ADD `BackgroundImage` char(12) DEFAULT NULL AFTER `BackgroundColor2`,
    ADD `BGFilePath` char(30) DEFAULT NULL AFTER `BackgroundImage`;

ALTER TABLE `wtkUsers` CHANGE `BackgroundType`
  `BackgroundType` CHAR(1) DEFAULT 'N' COMMENT 'None, Color, Gradient, Image';

ALTER TABLE `wtkUsers` CHANGE `BackgroundColor2`
  `BackgroundColor2` CHAR(7) DEFAULT 'N' COMMENT 'if gradient';

ALTER TABLE `wtkUsers` ADD `FullName` varchar(120) NULL DEFAULT NULL AFTER `LastName`;

INSERT INTO `wtkPages` (`PageName`,`Path`, `FileName`) VALUES ('Social Media', '/admin/', 'socialSiteList');

INSERT INTO `SocialSites` (`WebsiteName`, `ButtonColor`, `IconHTML`) VALUES
('LinkedIn', 'blue darken-3', '<i class=\"fab fa-linkedin\"></i>'),
('Facebook', 'indigo darken-2', '<i class=\"fab fa-square-facebook\"></i>'),
('YouTube', 'red', '<i class=\"fab fa-youtube\"></i>'),
('Twitter', 'blue', '<i class=\"fa-brands fa-x-twitter\"></i>'),
('TikTok', 'black', '<i class=\"fa-brands fa-tiktok\"></i>'),
('GitHub', 'black', '<i class=\"fab fa-github\"></i>'),
('BlueSky', 'blue accent-3', '<i class=\"fa-brands fa-bluesky\"></i>'),
('Discord', 'deep-purple darken-4', '<i class=\"fa-brands fa-discord\"></i>'),
('Docker', 'blue darken-4', '<i class=\"fab fa-docker\"></i>'),
('Spotify', 'green darken-2', '<i class=\"fab fa-spotify\"></i>'),
('Twitch', 'deep-purple accent-1', '<i class=\"fab fa-twitch\"></i>'),
('yahoo!', 'deep-purple accent-2', '<i class=\"fab fa-yahoo\"></i>'),
('tumblr', 'black', '<i class=\"fab fa-tumblr\"></i>'),
('threads', 'black', '<i class=\"fa-brands fa-threads\"></i>'),
('reddit', 'deep-orange accent-3', '<i class=\"fab fa-reddit-alien\"></i>'),
('Snapchat', 'yellow accent-2', '<i class=\"fa-brands fa-snapchat black-text\"></i>');

-- SQL Trigger for Priority defaulting based on UserUID
CREATE TRIGGER `tib_UserLinks`
    BEFORE INSERT ON `UserLinks`
    FOR EACH ROW
  BEGIN
    DECLARE fncLastPriority SMALLINT;

    SELECT COUNT(*) INTO fncLastPriority
      FROM `UserLinks`
    WHERE `UserUID` = NEW.`UserUID`;

    IF (fncLastPriority > 0) THEN
        SELECT `Priority` INTO fncLastPriority
          FROM `UserLinks`
        WHERE `UserUID` = NEW.`UserUID`
        ORDER BY `Priority` DESC LIMIT 1;
    END IF;
    SET NEW.`Priority` = (fncLastPriority + 10);
END
$$

-- script for testing image uploads
SELECT `UID`, `FilePath`,`NewFileName`,`BGFilePath`,`BackgroundImage` FROM `wtkUsers`;
