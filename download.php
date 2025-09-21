<?php
require_once (__DIR__ . "/bootstrap.php");

// Pastas permitidas
$pastasPermitidas = [
    "isentos"    => FILES_PATH . "/isentos",
    "recursos"   => FILES_PATH . "/recursos",
    "relatorios" => FILES_PATH . "/relatorios",
];

// Parâmetros
$tipo    = $_GET['tipo'] ?? null;
$arquivo = $_GET['file'] ?? null;

if (!$tipo || !$arquivo || !isset($pastasPermitidas[$tipo])) {
    http_response_code(400);
    exit("Parâmetros inválidos.");
}

$baseDir = realpath($pastasPermitidas[$tipo]);
$arquivoSeguro = basename($arquivo);
$caminho = realpath($baseDir . "/" . $arquivoSeguro);

// Segurança
if (!$caminho || strpos($caminho, $baseDir) !== 0 || !file_exists($caminho)) {
    http_response_code(404);
    exit("Arquivo não encontrado.");
}

$mime = mime_content_type($caminho);

$forcarDownload = isset($_GET['download']) && $_GET['download'] == 1;

if (!$forcarDownload && $mime === "application/pdf") {
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=\"" . $arquivoSeguro . "\"");
} else {
    header("Content-Type: $mime");
    header("Content-Disposition: attachment; filename=\"" . $arquivoSeguro . "\"");
}
header("Content-Length: " . filesize($caminho));
readfile($caminho);
exit;
