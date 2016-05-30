<?php

include_once('_resources/credentials.inc.php');
$no_well_container = true;
// $page_title = "Home Page";
// $section_title = "Root Section";
require_once('_resources/header.inc.php');

echo "
  <h1>Welcome to $site_title</h1>
";

$_SESSION["ADMIN"] = true;

$theatre = new MoviesController();

$sourceData = array(
  "sourcename" => "tmp",
  "realsourcepath" => "/tmp",
  "websourcepath" => "/tmpweb"
);

//var_dump($theatre->createSource($sourceData));
var_dump($theatre->getSource(131));

// var_dump(bin2hex(openssl_random_pseudo_bytes(10)));



require_once('_resources/footer.inc.php');

?>
