<?php

include_once('_resources/credentials.inc.php');
$no_well_container = true;
// $page_title = "Home Page";
// $section_title = "Root Section";
require_once('_resources/header.inc.php');

echo "
  <h1>Welcome to $site_title</h1>
";

$theatre = new MoviesController();

require_once('_resources/footer.inc.php');

?>
