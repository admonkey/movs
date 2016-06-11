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

    $this->database = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if ($this->database->connect_error) trigger_error('Connect Error: '.$this->database->connect_error, E_USER_ERROR);

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

    $sql = "CALL insert_source (?,?,?)";

    if (!($stmt = $this->database->prepare($sql))) {
      trigger_error("Error: Prepare failed: (".$this->database->errno.") ".$this->database->error, E_USER_ERROR);
      return false;
    } else {

      $stmt->bind_param('sss', $sourceData["sourcename"], $sourceData["realsourcepath"], $sourceData["websourcepath"]);
      if (!$stmt->execute()) {
        trigger_error("Error: Execute failed: (".$stmt->errno.") ".$stmt->error, E_USER_ERROR);
        return false;

      } else {

        $stmt->bind_result($response);
        $stmt->fetch();
        $stmt->close();
        return $response;

      }

    }

  }

  // get source
  public function getSource($sourceID) {

    if (!$this->isPositiveNumber($sourceID)) return false;

    $sql = "CALL get_source(?)";

    if (!($stmt = $this->database->prepare($sql))) {
      trigger_error("Error: Prepare failed: (".$this->database->errno.") ".$this->database->error, E_USER_ERROR);
      return false;
    } else {

      $stmt->bind_param('i', $sourceID);
      if (!$stmt->execute()) {
        trigger_error("Error: Execute failed: (".$stmt->errno.") ".$stmt->error, E_USER_ERROR);
        return false;

      } else {

        $stmt->store_result();
        if($stmt->num_rows < 1){
          $stmt->close();
          return false;
        }
        $stmt->bind_result($arr["sourcename"],$arr["realsourcepath"],$arr["websourcepath"]);
        $stmt->fetch();
        $stmt->close();
        return $arr;

      }

    }

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
                          $result[] = array("fpath"=>$prefix.$f, "fname"=>$f);}
          }
        }
      }

    return $result;
  }

}
