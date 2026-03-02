<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== INSPECTING JOURNAL 3 TABLES ===\n";
$tables = $mysqli->query("SHOW TABLES LIKE '" . DB_PREFIX . "journal3_%'");
while($row = $tables->fetch_array()) {
    $tableName = $row[0];
    echo "TABLE: " . $tableName . "\n";
    
    // If table is oc_journal3_header
    if ($tableName == DB_PREFIX . 'journal3_header') {
        $result = $mysqli->query("SELECT * FROM " . $tableName . " WHERE header_data LIKE '%localhost%' AND header_data NOT LIKE '%8888%'");
        while($item = $result->fetch_assoc()) {
            echo "FOUND BAD LOCALHOST IN HEADER ID: " . $item['header_id'] . "\n";
            print_r(substr($item['header_data'], 0, 100));
        }
    }
}

$mysqli->close();
?>
