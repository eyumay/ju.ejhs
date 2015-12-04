<?php
// Get important: content type AND filename
$filename = $_GET['filename'];
$contenttype = $_GET['type'];
// We'll be outputting a PDF
header("Content-type: $contenttype");

// It will be called downloaded.pdf
header("Content-Disposition: attachment; filename=$filename");

// The PDF source is in original.pdf
readfile("$filename");
?>
