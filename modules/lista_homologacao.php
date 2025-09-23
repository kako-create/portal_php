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
    ? "LISTAGEM DE INSCRIÇÕES HOMOLOGADAS - APÓS RECURSOS"
    : "LISTAGEM DE INSCRIÇÕES HOMOLOGADAS";

$cd_avaliacao_etapa = "1";
$tipo = "homologacao";
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

    $cargos = $db->callProcedure("sp_get_cargo", [$edital]);
    foreach ($cargos as $cargo) {

        $cd_cargo = $cargo['cd_cargo'];
        $cd_lotacao = $cargo['cd_lotacao'];
        $candidatos = $db->callProcedure("sp_get_homologacao", [$edital,$cd_cargo,$cd_lotacao]);
        $nomeArquivo = "ed_{$edital}_lst_homologacao_{$cd_cargo}_{$cd_lotacao}.pdf";
        $caminhoFinal = $diretorioSaida . "/" . $nomeArquivo;

        // monta linhas da tabela
        $rows = [];
        $i = 1;
        foreach ($candidatos as $cand) {
            $rows[] = [$cand['nu_inscricao'], $cand['nome_candidato'], $cand['nm_local_prova']];
            $i++;
        }

        $pdf = pdfInit();
        $pdf->setHeaderData($logoInstPath, $logoFepesePath, $concurso, $documento, $dim_contratante);
        $pdf->AddPage();
        pdfTable($pdf, ["Inscrição", "Nome", "Local Prova"], $rows, [10, 25, 90], ['C','L','L']);
        $pdf->Output($caminhoFinal,'F');

    }

}
