<?php

include('../mpdf60/mpdf.php');

// Load source documents
$xml = new DOMDocument;
$xml->load('../files/test.xml');

$xsl = new DOMDocument;
$xsl->load('../stylesheets/jats-html.xsl');

// Initialize and configure XSLT processor
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

error_log("XML -> HTML ...");

$html = $proc->transformToDoc($xml);
$html = $html->saveHTML();
//$html->save("test.html");

error_log("... done");

// REMINDER: always import external stylsheets, otherwise mPDF is extremely slow
$css = file_get_contents('../stylesheets/jats-preview.css'); // external css

error_log("HTML -> PDF ...");

$mpdf=new mPDF();
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output('../files/test.php.pdf','F');

error_log("... done");

exit;

?>
