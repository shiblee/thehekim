<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== SEARCHING ALL TABLES FOR LOCALHOST WITHOUT 8888 ===\n";

$tables = $mysqli->query("SHOW TABLES LIKE '" . DB_PREFIX . "%'");
while($row = $tables->fetch_array()) {
    $tableName = $row[0];
    
    // Get all text/varchar columns
    $columns = [];
    $colsResult = $mysqli->query("SHOW COLUMNS FROM " . $tableName);
    while($col = $colsResult->fetch_assoc()) {
        if (strpos(strtolower($col['Type']), 'text') !== false || strpos(strtolower($col['Type']), 'char') !== false || strpos(strtolower($col['Type']), 'longtext') !== false) {
            $columns[] = $col['Field'];
        }
    }
    
    foreach($columns as $col) {
        // Find 'http://localhost' or similar without 8888
        $query = "SELECT * FROM " . $tableName . " WHERE `" . $col . "` LIKE '%localhost%' AND `" . $col . "` NOT LIKE '%8888%'";
        $dataResult = $mysqli->query($query);
        if ($dataResult && $dataResult->num_rows > 0) {
            echo "FOUND in Table: " . $tableName . " | Column: " . $col . " -> " . $dataResult->num_rows . " rows affected\n";
            while($dataRow = $dataResult->fetch_assoc()) {
                 $idCol = array_key_first($dataRow); // just grab first column which is usually ID
                 echo "   Row " . $idCol . ": " . $dataRow[$idCol] . "\n";
                 // Let's do a hard replace right here for safety!
                 $oldText = $dataRow[$col];
                 $newText = str_replace('http:\/\/localhost\/', 'http:\/\/localhost:8888\/thehekim\/', $oldText);
                 $newText = str_replace('"http://localhost/"', '"http://localhost:8888/thehekim/"', $newText);
                 
                 // If it's just http://localhost without trailing slash
                 $newText = str_replace('"http://localhost"', '"http://localhost:8888/thehekim/"', $newText);
                 $newText = str_replace('http:\/\/localhost"', 'http:\/\/localhost:8888\/thehekim\/"', $newText);
                 
                 if ($oldText !== $newText) {
                     $stmt = $mysqli->prepare("UPDATE " . $tableName . " SET `" . $col . "` = ? WHERE `" . $idCol . "` = ?");
                     $stmt->bind_param("ss", $newText, $dataRow[$idCol]);
                     $stmt->execute();
                     echo "   Fixed!\n";
                 }
            }
        }
    }
}

array_map('unlink', glob(DIR_STORAGE . 'cache/cache.*'));
$mysqli->close();
echo "Done!\n";
?>
