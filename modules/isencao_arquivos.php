<?php
require_once(__DIR__ . "/../bootstrap.php");

// Pasta fixa deste módulo
$tipo = "isentos";
$path = BASE_PATH . "/adm/files/$tipo";

if (!is_dir($path)) {
    echo "<p>Pasta não encontrada.</p>";
    exit;
}

$files = scandir($path);

echo "<h2>Arquivos disponíveis - " . ucfirst($tipo) . "</h2>";
echo "<table border='1' cellpadding='6' cellspacing='0'>";
echo "<tr><th>Arquivo</th><th>Abrir</th><th>Download</th></tr>";

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;

    $safeFile = urlencode($file);

    // Links: o tipo é fixo -> "isentos"
    $urlAbrir    = "adm/download.php?tipo=$tipo&file=$safeFile";
    $urlDownload = "adm/download.php?tipo=$tipo&file=$safeFile&download=1";

    echo "<tr>";
    echo "<td>$file</td>";
    echo "<td><a href='$urlAbrir' target='_blank'>Abrir</a></td>";
    echo "<td><a href='$urlDownload'>Download</a></td>";
    echo "</tr>";
}

echo "</table>";
