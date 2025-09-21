<?php
require_once(BASE_PATH . "/bootstrap.php");

// Pasta fixa deste módulo
$tipo = "isentos";
$path = FILES_PATH . "/$tipo";

if (!is_dir($path)) {
    echo "<p>Pasta não encontrada.</p>";
    exit;
}

$files = scandir($path);

echo "<h2>Arquivos disponíveis - " . ucfirst($tipo) . "</h2>";
echo "<table class='table-arquivos'>";
echo "<tr><th>Arquivo</th><th>Abrir</th><th>Download</th></tr>";

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;

    $safeFile = urlencode($file);

    // Links: o tipo é fixo -> "isentos"
    $urlAbrir    = BASE_URL . "/download.php?tipo=$tipo&file=$safeFile";
    $urlDownload = BASE_URL . "/download.php?tipo=$tipo&file=$safeFile&download=1";

    echo "<tr>";
    echo "<td>$file</td>";
    echo "<td><a href='$urlAbrir' target='_blank'>Abrir</a></td>";
    echo "<td><a href='$urlDownload'>Download</a></td>";
    echo "</tr>";
}

echo "</table>";
