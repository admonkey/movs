DROP PROCEDURE IF EXISTS seed_database;

DROP PROCEDURE IF EXISTS insert_source;
DROP PROCEDURE IF EXISTS get_source;

DROP PROCEDURE IF EXISTS insert_movie;

DELIMITER $$

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

--
-- get source
--
CREATE PROCEDURE get_source (
  IN p_sourceid INT
)
this_procedure:BEGIN

  SELECT
    `sourcename`,
    `realsourcepath`,
    `websourcepath`
  FROM `Sources`
  WHERE `id` = p_sourceid;

END $$

----
---- MOVIES
----

--
-- create movie
--
CREATE PROCEDURE insert_movie (
  IN `p_fname` varchar(255),
  IN `p_fpath` varchar(255),
  IN `p_SourceID` int(11),
  IN `p_extension` varchar(5),
  IN `p_mimetype` varchar(50),
  IN `p_audioCodec` varchar(50),
  IN `p_videoCodec` varchar(50),
  IN `p_title` varchar(255),
  IN `p_imdbid` varchar(42),
  IN `p_plot` text,
  IN `p_runtime` int(11),
  IN `p_year` year(4),
  IN `p_released` date,
  IN `p_awards` text,
  IN `p_country` text,
  IN `p_language` text,
  IN `p_rated` varchar(10),
  IN `p_director` text,
  IN `p_writer` text,
  IN `p_actors` text
)
this_procedure:BEGIN

  INSERT INTO `Movies`
  (
    `fname`,
    `fpath`,
    `SourceID`,
    `extension`,
    `mimetype`,
    `audioCodec`,
    `videoCodec`,
    `title`,
    `imdbid`,
    `plot`,
    `runtime`,
    `year`,
    `released`,
    `awards`,
    `country`,
    `language`,
    `rated`,
    `director`,
    `writer`,
    `actors`
  )
  VALUES (
    `p_fname`,
    `p_fpath`,
    `p_SourceID`,
    `p_extension`,
    `p_mimetype`,
    `p_audioCodec`,
    `p_videoCodec`,
    `p_title`,
    `p_imdbid`,
    `p_plot`,
    `p_runtime`,
    `p_year`,
    `p_released`,
    `p_awards`,
    `p_country`,
    `p_language`,
    `p_rated`,
    `p_director`,
    `p_writer`,
    `p_actors`
  );

  SELECT LAST_INSERT_ID()
  AS 'movieID';

END $$

DELIMITER ;
