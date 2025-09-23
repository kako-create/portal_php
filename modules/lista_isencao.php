<h2>Gerando listas</h2>
<?php 
header('Content-Type: text/html; charset=iso-8859-1');
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once(BASE_PATH . "/bootstrap.php");
require_once(FPDF_PATH . "/WriteHTML.php");
require_once(INCLUDE_PATH . "/pdf_utils.php");

$logoInstPath   = str_replace('\\', '/', PDF_LOGO_CLIENT);
$logoFepesePath = str_replace('\\', '/', PDF_LOGO_FEPESE);

$tp = !empty($_GET['tp']) ? $_GET['tp'] : '';
$cd_avaliacao_etapa = ($tp === 'ar') ? 2 : 1;
$documento = ($tp === 'ar')
    ? "Listagem de Requerimento de Isenção do Pagamento da Taxa de Inscrição - Após Recursos"
    : "Listagem de Requerimento de Isenção do Pagamento da Taxa de Inscrição";

$cd_avaliacao_etapa = "1";
$tipo = "isentos";
$diretorioSaida = FILES_PATH . "/$tipo";

if (!is_dir($diretorioSaida)) {
    mkdir($diretorioSaida, 0775, true);
}

// busca lista de editais
$sqlListas = "SELECT edital, concurso, dim_contratante_jpg FROM listagem_resultados WHERE cd_avaliacao_etapa = " . (int)$cd_avaliacao_etapa;
$listas = $db->query($sqlListas);

foreach ($listas as $candidatos_lista) {
    $edital = $candidatos_lista['edital'];
    $concurso = $candidatos_lista['concurso'];
    $dim_contratante = $candidatos_lista['dim_contratante_jpg'];
    $candidatos = $db->callProcedure("sp_get_isento", [$cd_avaliacao_etapa,$edital]);
    $nomeArquivo = "ed_{$edital}_lst_isentos.pdf";
    $caminhoFinal = $diretorioSaida . "/" . $nomeArquivo;

    // monta linhas da tabela
    $rows = [];
    $i = 1;
    foreach ($candidatos as $cand) {
        $despacho = ($cand['situacao'] === 'deferido')
            ? "Deferido."
            : "Indeferido. " . $cand['observacao'];

        $rows[] = [$i, $cand['nu_inscricao'], $cand['nome_candidato'], $despacho];
        $i++;
    }

$pdf = pdfInit();
$pdf->setHeaderData($logoInstPath, $logoFepesePath, $concurso, $documento, $dim_contratante);
$pdf->AddPage();
pdfTable($pdf, ["#", "Inscrição", "Nome", "Despacho"], $rows, [10, 25, 90, 65], ['C','C','L','L']);
$pdf->Output($caminhoFinal,'F');


}


