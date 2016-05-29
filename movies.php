<?php

require_once(__DIR__."/_resources/resources.inc.php");

class MoviesController {

  private $authenticated;
  private $database;

  function __construct() {

    $authenticated = $this->authenticate();

    $database = new mysqli(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
    if ($database->connect_error) trigger_error('Connect Error: '.$database->connect_error, E_USER_ERROR);

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

    if (empty($_SESSION["ADMIN"])) return false;

  }

  // create source
  public function scanSource($sourceID) {

    if (empty($_SESSION["ADMIN"])) return false;

  }

}
