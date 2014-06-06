CREATE TABLE `guestbook`(
  `guestbookId` INT NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(255) NOT NULL,
  `user` VARCHAR(64),
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`guestbookId`)
);