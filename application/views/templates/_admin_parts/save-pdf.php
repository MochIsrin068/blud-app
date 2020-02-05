<?php
require_once('./vendor/autoload.php');

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


$htmlContent ='';
echo var_dump($data);
foreach ($data as $value) {
  $htmlContent = '<page style="font-size:6pt;">
		<div style="margin-top:5px">
			<p style="margin-left:10px">'.$value->identity_card_number.'</p>
			<p style="margin-left:10px">'.$value->address.'</p>
			<p style="margin-left:10px">'.$value->surename.'</p>
			<p style="margin-left:10px">'.$value->birthplace.'</p>
			<p style="margin-left:10px">'.$value->district.'</p>
			<p style="margin-left:10px">'.$value->rt.'</p>
			<p style="margin-left:10px">'.$value->rw.'</p>
		</div>
	</page>

  ';
}
try {
    ob_start();
    echo $htmlContent;
    $content = ob_get_clean();
    $html2pdf = new Html2Pdf('P', array(64,42), 'en');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->output('Akad.pdf');
} catch (Html2PdfException $e) {
    $html2pdf->clean();
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
?>
