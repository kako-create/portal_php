<?php
#
#   Grave esse script com o nome de pdf.php
#
require_once ("fpdf.php");
class PDF extends FPDF {
	var $nome;          // nome do relatorio
	var $cabecalho;     // cabecalho para as colunas

	
    function PDF($or = 'P') { // Construtor: Chama a classe FPDF
        $this->FPDF($or);
    }

    function SetCabecalho($cab) { // define o cabecalho
        $this->cabecalho = $cab;
    }

    function SetName($nomerel) { // nomeia o relatorio
        $this->nome = $nomerel;
    }
    function Header() {
        $this->AliasNbPages(); // Define o numero total de paginas para a macro {nb}
        $this->Image('logo.jpg', 5, 5, 70); // importa uma imagem
        $this->SetFont('Arial', 'B', 12);  
        $this->SetX(80);
        $this->Cell($this->GetStringWidth($this->nome), 10, $this->nome);
        $this->SetFont('Arial', '', 10);
        $this->SetX(-30);
        $this->Cell(30, 10, "Página: ".$this->PageNo()."/{nb}", 0, 1); // imprime página X/Total de Páginas
        $this->SetX(-10);
        $this->line(10, 18, $this->GetX(), 18); // Desenha uma linha
        if ($this->cabecalho) { // Se tem o cabecalho, imprime
            $this->SetFont('Arial', '', 10);
            $this->SetX(10);
            $this->Cell($this->GetStringWidth($this->cabecalho), 5, $this->cabecalho, 0, 1);
        }
        $this->SetXY(10, 25);
    }

    function Footer() { // Rodapé : imprime a hora de impressao e Copyright
        $this->SetXY(-10, -5);
        $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);
        $this->SetX(0);
        $this->SetFont('Courier', 'BI', 8);
        $data = strftime("%d/%m/%Y às %T");
        $this->Cell(100, 6, "(c)CMA - Impresso : ".$data, 0, 0, 'R');
    }


  


	}
?> 
