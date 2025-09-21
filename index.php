<?php
include_once("bootstrap.php"); // prepara tudo, inclusive $db
include_once(INCLUDE_PATH . "/header.php");

$page = isset($_GET['go']) ? $_GET['go'] : "home";
$file = "modules/".$page.".php";

echo '<div id="conteudo">';
if(file_exists($file)) {
    include($file);
} else {
    echo "<h2>Página não encontrada</h2>";
}
echo '</div>';

include_once(INCLUDE_PATH . "/footer.php");
