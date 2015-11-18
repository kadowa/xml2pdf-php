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
		"css:",
		"html:",	// optional
);

$options = getopt("o:", $longopts);

$xml_fn = $options["xml"];
$xsl_fn = $options["xsl"];
$out_fn = isset($options["output"]) ? $options["output"] : $options["o"];
$css_fn = $options["css"];
$html_fn = isset($options["html"]) ? $options["html"] : NULL;

// Load source documents
$xml = new DOMDocument;
$xml->load($xml_fn);

$xsl = new DOMDocument;
$xsl->load($xsl_fn);

// Initialize and configure XSLT processor
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);

error_log("XML -> HTML ...");

$html = $proc->transformToDoc($xml);
// Save intermediate HTML (optional)
if ( isset($html_fn) ) { $html->save($html_fn); }
$html = $html->saveHTML();

error_log("... done");

// REMINDER: always import external stylsheets, otherwise mPDF is extremely slow
$css = file_get_contents($css_fn); // external css

error_log("HTML -> PDF ...");

$mpdf=new mPDF();
// PDF/A1-b compliance
$mpdf->PDFA = true;

// Convert HTML to PDF with CSS stylesheet
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output($out_fn);

error_log("... done");

exit;

?>
