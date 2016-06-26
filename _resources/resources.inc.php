<?php

// SSL redirect
if ( !empty($require_ssl) ) {
	if(!isset($_SERVER['HTTPS'])) header('location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']); 
}

// paths
$path_real_root = dirname(__DIR__);
$path_real_difference = str_replace($path_real_root, '', $_SERVER['SCRIPT_FILENAME']);
$path_web_root = str_replace($path_real_difference, '', $_SERVER['SCRIPT_NAME']);

// variable definitions
include_once((__DIR__) . '/credentials.inc.php');

// login with full path
if(!empty($login_page))
  $login_page = "$path_web_root$login_page";

// custom functions
function valid_positive_integer($supposed_positive_integer){
  return ( !empty($supposed_positive_integer) && is_numeric($supposed_positive_integer) && $supposed_positive_integer > 0 );
}

// http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php#answer-7168986
function substr_startswith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function mysqlo_prepare_input($text){

	$text = htmlentities($text);

 	// bold
	$text = str_replace("&lt;b&gt;","<b>",$text);
	$text = str_replace("&lt;/b&gt;","</b>",$text);
	// italics
	$text = str_replace("&lt;i&gt;","<i>",$text);
	$text = str_replace("&lt;/i&gt;","</i>",$text);
	// underline
	$text = str_replace("&lt;u&gt;","<u>",$text);
	$text = str_replace("&lt;/u&gt;","</u>",$text);
	// strikethrough
	$text = str_replace("&lt;s&gt;","<s>",$text);
	$text = str_replace("&lt;/s&gt;","</s>",$text);

	return mysql_real_escape_string($text);

}

define("DATABASE_SERVER", $database_server);
define("DATABASE_USERNAME", $database_username);
define("DATABASE_PASSWORD", $database_password);
define("DATABASE_NAME", $database_name);

require_once(__DIR__."/../MoviesController.php");


// MySQL
if ( !empty($include_mysqli) ) {

  $mysqli_connection = new mysqli($database_server, $database_username, $database_password, $database_name);
  if ($mysqli_connection->connect_error) {
      die($mysqli_connection->connect_error);
  } else $mysqli_connected = true;

} elseif ( !empty($include_mysqlo) ) {

  // connection
  /*
	using deprecated mysql_connect()
	will need to upgrade to mysqli ASAP
	until then, silence server error notice
  */
  $mysqlo_connection = @mysql_connect($database_server, $database_username, $database_password);

  // use database
  $mysqlo_selected = mysql_select_db($database_name,$mysqlo_connection);
  
  if ($mysqlo_connection && $mysqlo_selected)
    $mysqlo_connected = true;

}
?>
