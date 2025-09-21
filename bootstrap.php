<?php
// Caminho físico no servidor
define("BASE_PATH", __DIR__);

// Subpastas importantes
define("CLASS_PATH", BASE_PATH . "/class");
define("INCLUDE_PATH", BASE_PATH . "/includes");
define("MODULE_PATH", BASE_PATH . "/modules");
define("ASSET_PATH", BASE_PATH . "/assets");

// URL base (pra CSS/JS/IMG no navegador)
$baseUrlPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrlPath = rtrim($baseUrlPath, '/');
define("BASE_URL", $baseUrlPath);

include_once(CLASS_PATH . "/Config.php");
include_once(CLASS_PATH . "/AppConfig.php");
include_once(CLASS_PATH . "/Database.php");

$db = new Database(DB_DRIVER);

date_default_timezone_set("America/Sao_Paulo");

