<?php
require_once FPDF_PATH . "/fpdf.php";
require_once CLASS_PATH . "/AppConfig.php";

class PDFBase extends FPDF {
    public $logoInst;
    public $logoFepese;
    public $nmConcurso;
    public $nmDocumento;
    public $dimContratante = [0,0];

    public function setHeaderData($logoInstFile, $logoFepeseFile, $nmConcurso, $nmDocumento, $dimContratante = "30X20") {
        // monta caminhos absolutos
        $this->logoInst       = $logoInstFile;
        $this->logoFepese     = $logoFepeseFile;

        $this->nmConcurso     = utf8_decode($nmConcurso);
        $this->nmDocumento    = utf8_decode($nmDocumento);
        $this->dimContratante = explode("X", $dimContratante);
    }

    function Header() {
        // Logo contratante
        if ($this->logoInst && file_exists($this->logoInst)) {
            $this->Image($this->logoInst, 14, 9, $this->dimContratante[0], $this->dimContratante[1], 'JPG');
        }
        // Logo FEPESE
        if ($this->logoFepese && file_exists($this->logoFepese)) {
            $this->Image($this->logoFepese, 180, 10, 20, 10, 'JPG');
        }

        // Texto do cabeçalho
        $this->SetFont('Arial','B',8);
        $this->SetY(10);
        $this->MultiCell(0, 4, $this->nmConcurso, 0, 'C', 0);
        $this->SetFont('Arial','B',11);
        $this->Cell(0, 5, "", 0, 1, 'C');
        $this->MultiCell(0, 4, $this->nmDocumento, 0, 'C', 0);
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

function pdfInit($orientation = 'P', $unit = 'mm', $size = 'A4') {
    $pdf = new PDFBase($orientation, $unit, $size);
    $pdf->SetMargins(10,10);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->SetFont("Arial", "", 8);
    $pdf->SetWidths(array(10,15,70,95));
    $pdf->SetAligns(array('C','R','L','L'));
    $pdf->CheckPageBreak(10);
    return $pdf;
}

function pdfTable($pdf, $headers, $rows, $colWidths = [], $colAligns = []) {
    if (empty($colWidths)) {
        $width = 190 / count($headers);
        $colWidths = array_fill(0, count($headers), $width);
    }

    if (empty($colAligns)) {
        $colAligns = array_fill(0, count($headers), 'L');
    }

    $printHeader = function() use ($pdf, $headers, $colWidths, $colAligns) {
        $pdf->SetFont('Arial','B',8);
        foreach ($headers as $i => $h) {
            $pdf->Cell($colWidths[$i], 5, utf8_decode($h), 1, 0, $colAligns[$i]);
        }
        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
    };

    $printHeader();

    $lineIndex = 0;
    $rowHeight = 5;

    foreach ($rows as $row) {
        $limite = $pdf->h - $pdf->bMargin;

        if ($pdf->GetY() + $rowHeight > $limite) {
            $pdf->AddPage();
            $printHeader();
        }

        if ($lineIndex % 2 == 0) {
            $pdf->SetFillColor(255,255,255);
        } else {
            $pdf->SetFillColor(230,230,230);
        }

        foreach ($row as $i => $col) {
            $align = isset($colAligns[$i]) ? $colAligns[$i] : 'L';
            $pdf->Cell($colWidths[$i], $rowHeight, utf8_decode($col), 1, 0, $align, true);
        }
        $pdf->Ln();
        $lineIndex++;
    }
}
