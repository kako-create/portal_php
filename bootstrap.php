<?php
// Caminho físico no servidor
define("BASE_PATH", __DIR__);

// Subpastas importantes
define("CLASS_PATH", BASE_PATH . "/class");
define("INCLUDE_PATH", BASE_PATH . "/includes");
define("MODULE_PATH", BASE_PATH . "/modules");
define('FILES_PATH', BASE_PATH . '/files');
define('FPDF_PATH', BASE_PATH . '/libs/fpdf');
define("IMAGE_PATH", dirname(BASE_PATH) . "/imagens");

// Detecta host e protocolo
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host     = $_SERVER['HTTP_HOST'];

// Caminho da pasta "adm" (onde o bootstrap está)
$dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$dir = preg_replace('#/modules.*$#', '', $dir); // se rodar módulo, remove o sufixo

define('BASE_URL', $protocol . '://' . $host . $dir);

include_once(CLASS_PATH . "/Config.php");
include_once(CLASS_PATH . "/AppConfig.php");
include_once(CLASS_PATH . "/Database.php");

$db = new Database(DB_DRIVER);

date_default_timezone_set("America/Sao_Paulo");

