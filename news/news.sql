--add News

--UP
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `description` text,
  `text` text,
  `created_on` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `news_images` (
  `id` INT  NOT NULL AUTO_INCREMENT,
  `title` varchar(250)  NOT NULL,
  `file` varchar(250)  NOT NULL,
  `thumb` varchar(250)  NOT NULL,
  `news_id` int  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--DOWN
DROP TABLE `news`;
DROP TABLE `news_images`; 