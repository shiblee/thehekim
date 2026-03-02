<?php
$sourceFile = '/Users/mohammad/Downloads/TheHekim/thehekim.sql';
$targetFile = 'restore_j3.sql';

$handle = fopen($sourceFile, "r");
$out = fopen($targetFile, "w");

if ($handle && $out) {
    $inInsert = false;
    $bytes = 0;
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `oc_journal3_setting`") === 0 || 
            strpos($line, "INSERT INTO `oc_journal3_skin_setting`") === 0 || 
            strpos($line, "INSERT INTO `oc_journal3_module`") === 0) {
            $inInsert = true;
        }
        
        if ($inInsert) {
            fwrite($out, $line);
            $bytes += strlen($line);
            
            // Check if the statement ends
            $trimmed = trim($line);
            if (substr($trimmed, -1) === ';') {
                $inInsert = false;
                fwrite($out, "\n");
            }
        }
    }
    fclose($handle);
    fclose($out);
    echo "Extracted $bytes bytes of SQL data successfully.\n";
} else {
    echo "Error opening files.\n";
}
?>
