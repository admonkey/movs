DROP PROCEDURE IF EXISTS seed_database;
DROP PROCEDURE IF EXISTS insert_source;

DELIMITER $$

--
-- seed database
--
CREATE PROCEDURE seed_database()
this_procedure:BEGIN

  --
  -- Dumping data for table `Users`
  --

  INSERT INTO `Users` VALUES (1,'public','$2y$10$dL0MFPt1HaJ0DDhi6Hp3MuyTGukQUsijUIqw9.woGma13IUqKkNei',NULL),(2,'root','$2y$10$pcHwCUR9DcM/QLSjHH32R.OUAqYlHihv0osq8xmCluZBlG5x4fk.2','?restricted=on&new=on&unbanned=on'),(3,'family','$2y$10$6K49JWvCP8WkgwrALQ1pYe.nix006lvYnqH8gAX94g26FKJxRYCJi','?new=on');

  --
  -- Dumping data for table `Tags`
  --

  INSERT INTO `Tags` (`Name`) VALUES ('Restricted'),('Watched'),('Starred'),('Banned'),('Action'),('Adventure'),('Animation'),('Biography'),('Comedy'),('Crime'),('Documentary'),('Drama'),('Family'),('Fantasy'),('Film-Noir'),('History'),('Horror'),('Music'),('Musical'),('Mystery'),('Romance'),('Sci-Fi'),('Sport'),('Thriller'),('War'),('Western');

  --
  -- Dumping data for table `Roles`
  --

  INSERT INTO `Roles` VALUES (1,'admin'),(2,'unrestricted');

END $$

--
-- insert source
--
CREATE PROCEDURE insert_source (
  IN p_sourcename varchar(255),
  IN p_realsourcepath varchar(255),
  IN p_websourcepath varchar(255)
)
this_procedure:BEGIN

  INSERT INTO `Sources` (
    `sourcename`,
    `realsourcepath`,
    `websourcepath`
  ) VALUES (
    p_sourcename,
    p_realsourcepath,
    p_websourcepath
  );

  SELECT LAST_INSERT_ID()
  AS 'sourceID';

END $$

DELIMITER ;
