<?php
require_once(__DIR__."/credentials.local.inc.php");
define("DATABASE_SERVER", $database_server);
define("DATABASE_USERNAME", $database_username);
define("DATABASE_PASSWORD", $database_password);
define("DATABASE_NAME", $database_name);
require_once(__DIR__."/../MoviesController.php");
