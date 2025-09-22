<?php
require_once FPDF_PATH . "/fpdf.php";

class PDFBase extends FPDF {
    public  $title;

    function setTitleText($title) {
        $this->title = $title;
    }

    function Header() {
        $this->SetFont('Arial','B',12);
        if ($this->title) {
            $this->Cell(0,10, $this->title, 0, 1, 'C');
        }
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

function pdfInit($title = '', $orientation = 'P', $unit = 'mm', $size = 'A4') {
    $pdf = new PDFBase($orientation, $unit, $size);
    $pdf->AliasNbPages();
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);
    if ($title) {
        $pdf->setTitleText($title);
    }
    return $pdf;
}

function pdfWriteTitle($pdf, $title) {
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial','',11);
}

function pdfTable($pdf, $headers, $rows, $colWidths = []) {
    if (empty($colWidths)) {
        $width = 190 / count($headers);
        $colWidths = array_fill(0, count($headers), $width);
    }

    // Cabeçalhos
    $pdf->SetFont('Arial','B',10);
    foreach ($headers as $i => $h) {
        $pdf->Cell($colWidths[$i], 7, $h, 1);
    }
    $pdf->Ln();

    // Linhas
    $pdf->SetFont('Arial','',9);
    foreach ($rows as $row) {
        foreach ($row as $i => $col) {
            $pdf->Cell($colWidths[$i], 6, $col, 1);
        }
        $pdf->Ln();
    }
}

function pdfOutput($pdf, $filename = 'documento.pdf', $download = true) {
    $pdf->Output($download ? 'D' : 'I', $filename);
}

function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . " MB";
    }
    return number_format($bytes / 1024, 1) . " KB";
}

function formatDateTime($timestamp) {
    return date("d/m/Y H:i", $timestamp);
}
