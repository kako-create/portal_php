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
echo "<tr><th>Arquivo</th><th>Tamanho</th><th>Modificação</th><th>Abrir</th><th>Download</th></tr>";

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;

    $safeFile = urlencode($file);
    $fullPath = $path . "/" . $file;
    $size = filesize($fullPath);
    if ($size >= 1048576) {
        $sizeFmt = number_format($size / 1048576, 2) . " MB";
    } else {
        $sizeFmt = number_format($size / 1024, 1) . " KB";
    }

    $mtime = filemtime($fullPath);
    $dataFmt = date("d/m/Y H:i", $mtime);    

    // Links: o tipo é fixo -> "isentos"
    $urlAbrir    = BASE_URL . "/download.php?tipo=$tipo&file=$safeFile";
    $urlDownload = BASE_URL . "/download.php?tipo=$tipo&file=$safeFile&download=1";

    echo "<tr>";
    echo "<td><a href='$urlAbrir' target='_blank'>$file</a></td>";
    echo "<td>$sizeFmt</td>";    
    echo "<td>$dataFmt</td>";        
    echo "<td><a href='$urlAbrir' target='_blank'>Abrir</a></td>";
    echo "<td><a href='$urlDownload'>Download</a></td>";
    echo "</tr>";
}

echo "</table>";
