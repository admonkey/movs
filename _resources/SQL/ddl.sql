DROP TABLE IF EXISTS `UserRoles`;
DROP TABLE IF EXISTS `UserScores`;
DROP TABLE IF EXISTS `UserTags`;
DROP TABLE IF EXISTS `MovieTags`;
DROP TABLE IF EXISTS `Roles`;
DROP TABLE IF EXISTS `Tags`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `MovieXtras`;
DROP TABLE IF EXISTS `Movies`;
DROP TABLE IF EXISTS `Sources`;

--
-- Table structure for table `Sources`
--
CREATE TABLE `Sources` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `sourcename` varchar(255) NOT NULL,
  `realsourcepath` varchar(255) NOT NULL,
  `websourcepath` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `sourcename` (`sourcename`),
  UNIQUE KEY `realsourcepath` (`realsourcepath`),
  UNIQUE KEY `websourcepath` (`websourcepath`)
  
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Movies`
--

CREATE TABLE `Movies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `fpath` varchar(255) NOT NULL,
  `SourceID` int(11) NOT NULL,
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
  KEY `SourceID` (`SourceID`),
  UNIQUE KEY `fpath` (`fpath`),
  UNIQUE KEY `imdbid` (`imdbid`),
  CONSTRAINT `SourceID-FK` FOREIGN KEY (`SourceID`) REFERENCES `Sources` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Table structure for table `MovieXtras`
--

CREATE TABLE `MovieXtras` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MovieID` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `fpath` varchar(255) NOT NULL,
  `SourceID` int(11) NOT NULL,
  `extension` varchar(5) NOT NULL,
  `mimetype` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MovieID` (`MovieID`),
  CONSTRAINT `MovieIDx-FK` FOREIGN KEY (`MovieID`) REFERENCES `Movies` (`ID`),
  CONSTRAINT `SourceIDx-FK` FOREIGN KEY (`SourceID`) REFERENCES `Sources` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Tags`
--

CREATE TABLE `Tags` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

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
