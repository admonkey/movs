<?php

class MoviesController {

  private $authenticated;
  private $database;

  function __construct() {

    $authenticated = $this->authenticate();

    $this->database = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if ($this->database->connect_error) trigger_error('Connect Error: '.$this->database->connect_error, E_USER_ERROR);

  }

  private function authenticate() {

    if (empty($_SESSION["USER_ID"])){
      //trigger_error("Not Logged In", E_USER_WARNING);
      return false;
    } else return true;

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

    if (!$authenticated) return false;

  }

  public function tagMovie($movieID,$tagID,$delete = false) {

    if (!$authenticated) return false;

  }

  // create source
  public function createSource($sourceData) {

    if (empty($_SESSION["ADMIN"])) {
      trigger_error("Error: Only Admins Can Create Sources", E_USER_WARNING);
      return false;
    }

    if (empty($sourceData["sourcename"])) {
      trigger_error("Error: No sourcename specified", E_USER_WARNING);
      return false;
    }

    if (empty($sourceData["realsourcepath"])) {
      trigger_error("Error: No realsourcepath specified", E_USER_WARNING);
      return false;
    }

    if (empty($sourceData["websourcepath"])) {
      trigger_error("Error: No websourcepath specified", E_USER_WARNING);
      return false;
    }

    if(!is_dir($sourceData["realsourcepath"])) {
      trigger_error("Error: realsourcepath is not a directory", E_USER_WARNING);
      return false;
    }

    $sql = "CALL insert_source (?,?,?)";

    if (!($stmt = $this->database->prepare($sql))) {
      trigger_error("Prepare failed: (".$this->database->errno.") ".$this->database->error, E_USER_WARNING);
      return false;
    } else {

      $stmt->bind_param('sss', $sourceData["sourcename"], $sourceData["realsourcepath"], $sourceData["websourcepath"]);
      if (!$stmt->execute()) {
        trigger_error("Execute failed: (".$stmt->errno.") ".$stmt->error, E_USER_WARNING);
        return false;

      } else {

        $stmt->store_result();
        $stmt->bind_result($response);
        $stmt->fetch();
        return $response;

      }

    }

  }

  // get source
  public function getSource($sourceID) {

    if (!$this->isPositiveNumber($sourceID)) return false;

    $sql = "
      SELECT
        `sourcename`,
        `realsourcepath`,
        `websourcepath`
      FROM `Sources`
      WHERE `id` = ?
    ";

    if (!($stmt = $this->database->prepare($sql))) {
      trigger_error("Prepare failed: (".$this->database->errno.") ".$this->database->error, E_USER_WARNING);
      return false;
    } else {

      $stmt->bind_param('i', $sourceID);
      if (!$stmt->execute()) {
        trigger_error("Execute failed: (".$stmt->errno.") ".$stmt->error, E_USER_WARNING);
        return false;

      } else {

        $stmt->store_result();
        $stmt->bind_result($arr["sourcename"],$arr["realsourcepath"],$arr["websourcepath"]);
        $stmt->fetch_array(MYSQLI_ASSOC);
        return $arr;

      }

    }

  }

  // create source
  public function scanSource($sourceID) {

    if (empty($_SESSION["ADMIN"])) return false;

  }

  //
  // private utility functions
  //

  private function isPositiveNumber($num) {

    return ( !empty($num) && is_numeric($num) && $num > 0 );

  }

}
