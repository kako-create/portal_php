<?php 
require_once(BASE_PATH . "/bootstrap.php");
require_once(FPDF_PATH . "/WriteHTML.php");
require_once(INCLUDE_PATH . "/pdf_utils.php");

$cd_avaliacao_etapa = "1";
$tipo = "isentos";
$diretorioSaida = FILES_PATH . "/$tipo";

if (!is_dir($diretorioSaida)) {
    mkdir($diretorioSaida, 0775, true);
}

// busca lista de editais
$sqlListas = "SELECT edital, concurso FROM listagem_resultados WHERE cd_avaliacao_etapa = " . (int)$cd_avaliacao_etapa;
$listas = $db->query($sqlListas);

foreach ($listas as $candidatos_lista) {
    $edital = $candidatos_lista['edital'];
    $concurso = $candidatos_lista['concurso'];

    $candidatos = $db->callProcedure("sp_get_isento", [$cd_avaliacao_etapa,$edital]);

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

    $pdf = pdfInit($documento);
    pdfWriteTitle($pdf, "Concurso: {$concurso}");
    pdfTable($pdf, ["#", "Inscrição", "Nome", "Despacho"], $rows, [10, 25, 90, 65]);

    $nomeArquivo = "ed_{$edital}_lst_isentos.pdf";
    $caminhoFinal = $diretorioSaida . "/" . $nomeArquivo;
    $pdf->Output($caminhoFinal, 'F');

}