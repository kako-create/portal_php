<?php
define("FPDF_FONTPATH", "font/");
require_once("fpdf.php");

class PDF extends FPDF{
	var $B;
	var $I;
	var $U;
	var $HREF;

function PDF($orientation='P',$unit='mm',$format='A4'){
	//Call parent constructor
	$this->FPDF($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
}

function WriteHTML($html){
	//HTML parser
	$html=str_replace("\n",' ',$html);
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e){
		if($i%2==0){
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,$e);
			}
		else {
			//Tag
			if($e{0}=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else {
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
					if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
						$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag,$attr){
	//Opening tag
	if($tag=='B' or $tag=='I' or $tag=='U')
		$this->SetStyle($tag,true);
	if($tag=='A')
		$this->HREF=$attr['HREF'];
	if($tag=='BR')
		$this->Ln(5);
}

function CloseTag($tag){
	//Closing tag
	if($tag=='B' or $tag=='I' or $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
}

function SetStyle($tag,$enable){
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	if($this->$s>0)
	$style.=$s;
	$this->SetFont('',$style);
}

function PutLink($URL,$txt){
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}
function i25($xpos, $ypos, $code, $basewidth=1, $height=10){

    $wide = $basewidth;
    $narrow = $basewidth / 3 ;

    // wide/narrow codes for the digits
    $barChar['0'] = 'nnwwn';
    $barChar['1'] = 'wnnnw';
    $barChar['2'] = 'nwnnw';
    $barChar['3'] = 'wwnnn';
    $barChar['4'] = 'nnwnw';
    $barChar['5'] = 'wnwnn';
    $barChar['6'] = 'nwwnn';
    $barChar['7'] = 'nnnww';
    $barChar['8'] = 'wnnwn';
    $barChar['9'] = 'nwnwn';
    $barChar['A'] = 'nn';
    $barChar['Z'] = 'wn';

    // add leading zero if code-length is odd
    if(strlen($code) % 2 != 0){
        $code = '0' . $code;
    }

    #$this->SetFont('Arial','',9);
    #$this->Text($xpos, $ypos + $height + 4, $code);
    $this->SetFillColor(0);

    // add start and stop codes
    $code = 'AA'.strtolower($code).'ZA';

    for($i=0; $i<strlen($code); $i=$i+2){
        // choose next pair of digits
        $charBar = $code{$i};
        $charSpace = $code{$i+1};
        // check whether it is a valid digit
        if(!isset($barChar[$charBar])){
            $this->Error('Invalid character in barcode: '.$charBar);
        }
        if(!isset($barChar[$charSpace])){
            $this->Error('Invalid character in barcode: '.$charSpace);
        }
        // create a wide/narrow-sequence (first digit=bars, second digit=spaces)
        $seq = '';
        for($s=0; $s<strlen($barChar[$charBar]); $s++){
            $seq .= $barChar[$charBar]{$s} . $barChar[$charSpace]{$s};
        }
        for($bar=0; $bar<strlen($seq); $bar++){
            // set lineWidth depending on value
            if($seq{$bar} == 'n'){
                $lineWidth = $narrow;
            }else{
                $lineWidth = $wide;
            }
            // draw every second value, because the second digit of the pair is represented by the spaces
            if($bar % 2 == 0){
                $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
            }
            $xpos += $lineWidth;
        }
    }
}

function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

    $wide = $baseline;
    $narrow = $baseline / 3 ; 
    $gap = $narrow;

    $barChar['0'] = 'nnnwwnwnn';
    $barChar['1'] = 'wnnwnnnnw';
    $barChar['2'] = 'nnwwnnnnw';
    $barChar['3'] = 'wnwwnnnnn';
    $barChar['4'] = 'nnnwwnnnw';
    $barChar['5'] = 'wnnwwnnnn';
    $barChar['6'] = 'nnwwwnnnn';
    $barChar['7'] = 'nnnwnnwnw';
    $barChar['8'] = 'wnnwnnwnn';
    $barChar['9'] = 'nnwwnnwnn';
    $barChar['A'] = 'wnnnnwnnw';
    $barChar['B'] = 'nnwnnwnnw';
    $barChar['C'] = 'wnwnnwnnn';
    $barChar['D'] = 'nnnnwwnnw';
    $barChar['E'] = 'wnnnwwnnn';
    $barChar['F'] = 'nnwnwwnnn';
    $barChar['G'] = 'nnnnnwwnw';
    $barChar['H'] = 'wnnnnwwnn';
    $barChar['I'] = 'nnwnnwwnn';
    $barChar['J'] = 'nnnnwwwnn';
    $barChar['K'] = 'wnnnnnnww';
    $barChar['L'] = 'nnwnnnnww';
    $barChar['M'] = 'wnwnnnnwn';
    $barChar['N'] = 'nnnnwnnww';
    $barChar['O'] = 'wnnnwnnwn'; 
    $barChar['P'] = 'nnwnwnnwn';
    $barChar['Q'] = 'nnnnnnwww';
    $barChar['R'] = 'wnnnnnwwn';
    $barChar['S'] = 'nnwnnnwwn';
    $barChar['T'] = 'nnnnwnwwn';
    $barChar['U'] = 'wwnnnnnnw';
    $barChar['V'] = 'nwwnnnnnw';
    $barChar['W'] = 'wwwnnnnnn';
    $barChar['X'] = 'nwnnwnnnw';
    $barChar['Y'] = 'wwnnwnnnn';
    $barChar['Z'] = 'nwwnwnnnn';
    $barChar['-'] = 'nwnnnnwnw';
    $barChar['.'] = 'wwnnnnwnn';
    $barChar[' '] = 'nwwnnnwnn';
    $barChar['*'] = 'nwnnwnwnn';
    $barChar['$'] = 'nwnwnwnnn';
    $barChar['/'] = 'nwnwnnnwn';
    $barChar['+'] = 'nwnnnwnwn';
    $barChar['%'] = 'nnnwnwnwn';

    #$this->SetFont('Arial','',6);
    #$this->Text($xpos, $ypos + $height + 4, $code);
    $this->SetFillColor(0);

    $code = '*'.strtoupper($code).'*';
    for($i=0; $i<strlen($code); $i++){
        $char = $code[$i];
        if(!isset($barChar[$char])){
            $this->Error('Invalid character in barcode: '.$char);
        }
        $seq = $barChar[$char];
        for($bar=0; $bar<9; $bar++){
            if($seq[$bar] == 'n'){
                $lineWidth = $narrow;
            }else{
                $lineWidth = $wide;
            }
            if($bar % 2 == 0){
                $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
            }
            $xpos += $lineWidth;
        }
        $xpos += $gap;
    }
}


}
?>