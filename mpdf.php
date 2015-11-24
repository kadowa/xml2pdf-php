#!/usr/bin/php
<?php

/*
 * Convert XML to PDF with mpdf.
 */

include('/workspace/xml2pdf/mpt/static/tools/mpdf60/mpdf.php');

$longopts  = array(
		"output:",
		"css:",
		"html:",
);

$options = getopt("o:", $longopts);

$out_fn = isset($options["output"]) ? $options["output"] : $options["o"];
$html_fn = $options["html"];

$html = file_get_contents($html_fn);

// REMINDER: always import external stylsheets, otherwise mPDF is extremely slow
$css = file_get_contents($css_fn); // external css

error_log("HTML -> PDF ...");

$mpdf=new mPDF('utf-8');
// PDF/A1-b compliance
$mpdf->PDFA = true;

// Convert HTML to PDF with CSS stylesheet
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html,2);
$mpdf->Output($out_fn);

error_log("... done");

exit;

?>
