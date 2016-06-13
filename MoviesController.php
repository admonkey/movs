<?php

class MoviesController {

/*

    --
    -- PUBLIC METHODS
    --

    -- GENERAL
    getMovies($tagIDs = NULL)
    getMovie($id)

    -- AUTHENTICATED
    scoreMovie($movieID,$score)
    tagMovie($movieID,$tagID,$delete = false)

    -- ADMIN
    createSource($sourceData)
    getSource($sourceID)
    scanSource($sourceID)
    createMovie($data)

*/

  private $authenticated;
  private $admin;
  private $database;

  function __construct() {

    $this->authenticated = $this->authenticate();

    try {
      $this->database = new PDO("mysql:host=".DATABASE_SERVER.";dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
      // set the PDO error mode to exception
      $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      trigger_error('Connect Error: '.$e->getMessage(), E_USER_ERROR);
    }

  }

  // get movie collection
  public function getMovies($tagIDs = NULL) {

  }

  // get specific movie
  public function getMovie($id) {

    // TODO: check if restricted movie
    // if (empty($_SESSION["UNRESTRICTED"])) return false;

  }

  public function scoreMovie($movieID,$score) {

    if (!$this->authenticated) {
      trigger_error("Error: Must be logged in to score a movie.", E_USER_ERROR);
      return false;
    }

    return false;

  }

  public function tagMovie($movieID,$tagID,$delete = false) {

    if (!$this->authenticated) {
      trigger_error("Error: Must be logged in to tag a movie.", E_USER_ERROR);
      return false;
    }

    return false;

  }

  // create source
  public function createSource($sourceData) {

    if (!$this->admin) {
      trigger_error("Error: Only Admins Can Create Sources", E_USER_ERROR);
      return false;
    }

    if (empty($sourceData["sourcename"])) {
      trigger_error("Error: No sourcename specified", E_USER_ERROR);
      return false;
    }

    if (empty($sourceData["realsourcepath"])) {
      trigger_error("Error: No realsourcepath specified", E_USER_ERROR);
      return false;
    }

    if (empty($sourceData["websourcepath"])) {
      trigger_error("Error: No websourcepath specified", E_USER_ERROR);
      return false;
    }

    if(!is_dir($sourceData["realsourcepath"])) {
      trigger_error("Error: realsourcepath is not a directory", E_USER_ERROR);
      return false;
    }

    return $this->executeQuery("CALL insert_source(:sourcename,:realsourcepath,:websourcepath)", $sourceData);

  }

  // get source
  public function getSource($sourceID) {

    if (!$this->isPositiveNumber($sourceID)) return false;

    return $this->executeQuery("CALL get_source(:sourceID)", array("sourceID" => $sourceID));

  }

  // scan source
  public function scanSource($sourceID) {

    if (!$this->admin) {
      trigger_error("Error: Only Administrators can scan source.", E_USER_ERROR);
      return false;
    }

    $sourceData = $this->getSource($sourceID);
    if ($sourceData === false) {
      trigger_error("Error: Cannot get source.", E_USER_ERROR);
      return false;
    }

    $movies = $this->getMovs($sourceData['realsourcepath']);
    if ($movies === false) {
      trigger_error("Error: Cannot get movies.", E_USER_ERROR);
      return false;
    }

    return $movies;

  }

  // TODO: create movie
  public function createMovie($data) {

    if (!$this->admin) {
      trigger_error("Error: Only Administrators can create a movie.", E_USER_ERROR);
      return false;
    }

    if(empty($data['title'])) $data['title'] = $data['fname'];

    return $this->executeQuery("
      CALL insert_movie(
        :fname,
        :fpath,
        :sourceID,
        :extension,
        :mimetype,
        :audioCodec,
        :videoCodec,
        :title,
        :imdbid,
        :plot,
        :runtime,
        :year,
        :released,
        :awards,
        :country,
        :language,
        :rated,
        :director,
        :writer,
        :actors
      )
    ", $data);

    return false;

  }

  //
  // private utility functions
  //

  private function authenticate() {

    if (empty($_SESSION["USER_ID"])) return false;

    $this->admin = !empty($_SESSION["ADMIN"]);

    return true;

  }

  private function executeQuery($sql, $params = null) {

    $stmt = $this->database->prepare($sql);

    if (is_array($params)) {

      // get number of passed parameters for bind
      $temp = explode(":",$sql);
      for ($i=1;$i<count($temp);$i++) {
        $paramkeys []= rtrim($temp[$i],", \n)");
      }

      // if different count, then set remainders = null
      if (count($paramkeys) !== count($params)) {
        $paramkeys = array_diff_key(array_flip($paramkeys),$params);
        foreach ($paramkeys as &$paramkey) $paramkey = null;
        $params = array_merge($params,$paramkeys);
      }

      // prefix key with colon for bind statement ( :key )
      $params = array_combine(
          array_map(
            function($key){ return ':'.$key; },
            array_keys($params)),
          $params
      ); // thanks http://stackoverflow.com/a/2609278/4233593

    }

    if ($stmt->execute($params)) {
      if($stmt->rowCount() === 1) // return single-dimensional array
        if ($stmt->columnCount() === 1) // return just one value
          return $stmt->fetch()[0];
        else return $stmt->fetch();
      while ($row = $stmt->fetch()) { // else return multi-dimensional array
        $return []= $row;
      }
      return $return;
    }

    return false;

  }

  private function isPositiveNumber($num) {

    return ( !empty($num) && is_numeric($num) && $num > 0 );

  }

  private function getMovs($dir, $prefix = '') {
    $dir = rtrim($dir, '\\/');
    $extensions = array("mkv", "mp4", "avi", "mov");
    $result = array();

      foreach (scandir($dir) as $f) {
        if ($f !== '.' and $f !== '..') {
          if (is_dir("$dir/$f")) {
            $result = array_merge($result, $this->getMovs("$dir/$f", "$prefix$f/"));
          } else {
                  $ext = pathinfo($prefix.$f, PATHINFO_EXTENSION);
                  if (in_array($ext,$extensions)){
                          $result[] = array("fpath"=>$prefix.$f, "fname"=>$f, "extension"=>$ext);}
          }
        }
      }

    return $result;
  }

}
