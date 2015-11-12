#!/usr/bin/php
<?php

/*
 * Convert XML to PDF with mpdf.
 */

include('../mpdf60/mpdf.php');

$longopts  = array(
		"output:",
		"xml:",
		"xsl:",
		"css:",		// optional
);

$options = getopt("o:", $longopts);

$xml_fn = $options["xml"];
$xsl_fn = $options["xsl"];
$out_fn = isset($options["output"]) ? $options["output"] : $options["o"];
$css_fn = $options["css"];

// Load source documents
$xml = new DOMDocument;
$xml->load($xml_fn);

$xsl = new DOMDocument;
$xsl->load('../../stylesheets/jats-html.xsl');

// Initialize and configure XSLT processor
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

error_log("XML -> HTML ...");

$html = $proc->transformToDoc($xml);
$html = $html->saveHTML();
//$html->save("test.html");

error_log("... done");

// REMINDER: always import external stylsheets, otherwise mPDF is extremely slow
$css = file_get_contents($css_fn); // external css

error_log("HTML -> PDF ...");

$mpdf=new mPDF();
# PDF/A1-b compliance
$mpdf->PDFA = true;

$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output($out_fn);

error_log("... done");

exit;

?>
