<?php

$app = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
define("URL", "http://".$_SERVER["SERVER_NAME"].$app);


require_once "nucleo/Autoload.php";
$autoload = new \nucleo\Autoload();

$aplicacion = new \nucleo\Aplicacion();