<?php

include_once('_resources/credentials.inc.php');
$no_well_container = true;
// $page_title = "Home Page";
// $section_title = "Root Section";
require_once('_resources/header.inc.php');

echo "
  <h1>Welcome to $site_title</h1>
";

try {
    $dbh = new PDO("mysql:host=$database_server;dbname=$database_name", $database_username, $database_password);
    foreach($dbh->query('SELECT * FROM  `Sources` ') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

require_once('_resources/footer.inc.php');

?>
