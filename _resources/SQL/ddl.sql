DROP TABLE IF EXISTS `UserRoles`;
DROP TABLE IF EXISTS `UserScores`;
DROP TABLE IF EXISTS `UserTags`;
DROP TABLE IF EXISTS `MovieTags`;
DROP TABLE IF EXISTS `Roles`;
DROP TABLE IF EXISTS `Tags`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `MovieXtras`;
DROP TABLE IF EXISTS `Movies`;

--
-- Table structure for table `Movies`
--

CREATE TABLE `Movies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `fpath` varchar(255) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `mimetype` varchar(50) DEFAULT NULL,
  `audioCodec` varchar(50) DEFAULT NULL,
  `videoCodec` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `imdbid` varchar(42) DEFAULT NULL,
  `plot` text,
  `runtime` int(11) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `released` date DEFAULT NULL,
  `awards` text,
  `country` text,
  `language` text,
  `rated` varchar(10) DEFAULT NULL,
  `director` text,
  `writer` text,
  `actors` text,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `fpath` (`fpath`),
  UNIQUE KEY `imdbid` (`imdbid`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

--
-- Table structure for table `MovieXtras`
--

CREATE TABLE `MovieXtras` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MovieID` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `fpath` varchar(255) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `mimetype` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MovieID` (`MovieID`),
  CONSTRAINT `MovieIDx-FK` FOREIGN KEY (`MovieID`) REFERENCES `Movies` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User` varchar(25) NOT NULL,
  `Pass` varchar(255) NOT NULL,
  `Fav` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `User` (`User`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
INSERT INTO `Users` VALUES (1,'public','$2y$10$dL0MFPt1HaJ0DDhi6Hp3MuyTGukQUsijUIqw9.woGma13IUqKkNei',NULL),(2,'root','$2y$10$pcHwCUR9DcM/QLSjHH32R.OUAqYlHihv0osq8xmCluZBlG5x4fk.2','?restricted=on&new=on&unbanned=on'),(3,'family','$2y$10$6K49JWvCP8WkgwrALQ1pYe.nix006lvYnqH8gAX94g26FKJxRYCJi','?new=on');
UNLOCK TABLES;

--
-- Table structure for table `Tags`
--

CREATE TABLE `Tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Tags`
--

INSERT INTO `Tags` (`Name`) VALUES ('Restricted'),('Watched'),('Starred'),('Banned'),('Action'),('Adventure'),('Animation'),('Biography'),('Comedy'),('Crime'),('Documentary'),('Drama'),('Family'),('Fantasy'),('Film-Noir'),('History'),('Horror'),('Music'),('Musical'),('Mystery'),('Romance'),('Sci-Fi'),('Sport'),('Thriller'),('War'),('Western');

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Roles`
--

LOCK TABLES `Roles` WRITE;
INSERT INTO `Roles` VALUES (1,'admin'),(2,'unrestricted');
UNLOCK TABLES;

--
-- linking tables
--

--
-- Table structure for table `MovieTags`
--


CREATE TABLE `MovieTags` (
  `TagID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  PRIMARY KEY (`TagID`,`MovieID`),
  CONSTRAINT `MovieIDmt-FK` FOREIGN KEY (`MovieID`) REFERENCES `Movies` (`ID`),
  CONSTRAINT `TagIDmt-FK` FOREIGN KEY (`TagID`) REFERENCES `Tags` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `UserTags`
--


CREATE TABLE `UserTags` (
  `UserID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`TagID`,`MovieID`),
  CONSTRAINT `MovieIDut-FK` FOREIGN KEY (`MovieID`) REFERENCES `Movies` (`ID`),
  CONSTRAINT `TagIDut-FK` FOREIGN KEY (`TagID`) REFERENCES `Tags` (`ID`),
  CONSTRAINT `UserIDut-FK` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `UserScores`
--


CREATE TABLE `UserScores` (
  `UserID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  `Score` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`MovieID`),
  CONSTRAINT `MovieIDus-FK` FOREIGN KEY (`MovieID`) REFERENCES `Movies` (`ID`),
  CONSTRAINT `UserIDus-FK` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
);

--
-- Table structure for table `UserRoles`
--

CREATE TABLE `UserRoles` (
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  PRIMARY KEY (`UserID`,`RoleID`),
  CONSTRAINT `RoleFK` FOREIGN KEY (`RoleID`) REFERENCES `Roles` (`ID`),
  CONSTRAINT `UserFK` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserRoles`
--

LOCK TABLES `UserRoles` WRITE;
INSERT INTO `UserRoles` VALUES (2,1),(1,2);
UNLOCK TABLES;
