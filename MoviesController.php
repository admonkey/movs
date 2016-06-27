<?php

class MoviesController {

/*

    --
    -- PUBLIC METHODS
    --
    -- functionName($parameters = DEFAULTS) returns
    --

    -- GENERAL
    TODO: getMovies($tagIDs = NULL) array, false on failure
    TODO: getMovie($id) array, false on failure
    TODO: login($username,$password) boolean
    isLoggedIn() boolean
    isAdmin() boolean

    -- AUTHENTICATED
    logout() boolean
    TODO: scoreMovie($movieID,$score) boolean
    TODO: tagMovie($movieID,$tagID,$delete = false)) boolean

    -- ADMIN
    createSource($sourceData) boolean
    getSource($sourceID) array, false on failure
    scanSource($sourceID) array, false on failure
    findNewMovies($filelist) array, string for 1 result, false on failure
    createMovie($data) boolean

    --
    -- PRIVATE FUNCTIONS
    --

    __construct() MoviesController
    sec_session_start() boolean
    checkIfAlreadyAuthenticated() boolean
    executeQuery($sql, $params = null) array, string/int, false on failure
    isPositiveNumber($num) boolean
    getMovs($dir, $prefix = '') array

*/

private $database;
private $authenticated;
private $username;
private $admin;


//
// GENERAL METHODS
//


/**
*  TODO: get movie collection
*  optionally filtered by tags
*  returns false on failure
*
*  @return array
*/
public function getMovies($tagIDs = NULL)
{
        return false;
}


/**
*  TODO: get specific movie
*
*  @return array
*/
public function getMovie($id){

  // check if restricted movie

  return false;

}


/**
*  TODO: authenticate user against database credentials or LDAP
*
*  @return boolean
*/
public function login($username,$password) {

  // logout if already logged in
  $logged_out = !$this->authenticated;
  if (!$logged_out) $logged_out = $this->logout();

  if ($logged_out) {

    // check if LDAP domain

    // hash pass

    // get ADMIN status

    // set session vars

    $userData = $this->executeQuery(
      'CALL login(:username,:password)',
      array(
        'username'=>$username,
        'password'=>$password
      )
    );

    if (!empty($userData['username'])) {
      $this->username = $userData['username'];
      
    }

  } else return false;

}


/**
*  get user logged in status
*
*  @return boolean
*/
public function isLoggedIn() {

  return $this->authenticated;

}


/**
*  get user admin status
*
*  @return boolean
*/
public function isAdmin() {

  return $this->admin;

}


//
// AUTHENTICATED METHODS
//


/**
*  logout
*
*  @return boolean
*/
public function logout() {

  // Unset all session values 
  $_SESSION = array();

  // get session parameters 
  $params = session_get_cookie_params();

  // Delete the actual cookie. 
  setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

  // Destroy session 
  return session_destroy();

//   return true;

}


/**
*  TODO: save a user's movie rating
*
*  @return boolean
*/
public function scoreMovie($movieID,$score){

  if (!$this->authenticated)
    trigger_error("Error: Must be logged in to score a movie.", E_USER_ERROR);

  return false;

}


/**
*  TODO: save a tag for a movie
*
*  @return boolean
*/
public function tagMovie($movieID,$tagID,$delete = false,$global = false){

  if (!$this->authenticated)
    trigger_error("Error: Must be logged in to tag a movie.", E_USER_ERROR);

  if($global && !$this->admin)
    trigger_error("Error: Only admins can create global tags.", E_USER_ERROR);

  return false;

}


//
// ADMIN METHODS
//


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


// find new movies
public function findNewMovies($filelist){

  return $this->executeQuery("CALL find_new_movies(:movielist)", array("movielist"=>$filelist));

}

// create movie
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
// PRIVATE UTILITY FUNCTIONS
//


function __construct() {

  $this->authenticated = $this->checkIfAlreadyAuthenticated();

  try {
    $this->database = new PDO("mysql:host=".DATABASE_SERVER.";dbname=".DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    // set the PDO error mode to exception
    $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    trigger_error('Connect Error: '.$e->getMessage(), E_USER_ERROR);
  }

}


private function checkIfAlreadyAuthenticated() {

  if (!$this->sec_session_start()) return false;

  if (empty($_SESSION["USER_ID"])) return false;

  $this->username = $_SESSION["USERNAME"];
  $this->admin = !empty($_SESSION["ADMIN"]);

  return true;

}


private function sec_session_start() {

  if (session_status() === PHP_SESSION_NONE) {

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        trigger_error("ERROR: Could not initiate a safe session (ini_set)", E_USER_ERROR);
    }

    // Set a custom session name
    session_name('sec_session_id');

    // If TRUE cookie will only be sent over secure connections.
    // http://php.net/manual/en/session.configuration.php#ini.session.cookie-secure
    $secure = false;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

    // Start the PHP session
    if (!session_start()) return false;

    // regenerated the session, delete the old one.
    if (!session_regenerate_id()) return false;

  } else return true;

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

  if ($stmt->execute($params) && $stmt->rowCount() > 0) {
    if($stmt->rowCount() === 1){ // return single-dimensional array
      if ($stmt->columnCount() === 1){ // return just one value
        return $stmt->fetch()[0];
      } else { return $stmt->fetch(); }
    }
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


} // end class
