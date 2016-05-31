DROP PROCEDURE IF EXISTS seed_database;
DROP PROCEDURE IF EXISTS insert_source;
DROP PROCEDURE IF EXISTS get_source;

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


DELIMITER ;
