<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== CHECKING FOR ANY LOCALHOST IN JOURNAL MODULES ===\n";
$result = $mysqli->query("SELECT module_id, module_type FROM " . DB_PREFIX . "journal3_module WHERE module_data LIKE '%localhost%' AND module_data NOT LIKE '%8888%'");
while($row = $result->fetch_assoc()) {
    echo "Found in Module ID: " . $row['module_id'] . " (Type: " . $row['module_type'] . ")\n";
}

echo "=== CHECKING FOR ANY LOCALHOST IN JOURNAL SETTINGS ===\n";
$result = $mysqli->query("SELECT setting_name, store_id FROM " . DB_PREFIX . "journal3_setting WHERE setting_value LIKE '%localhost%' AND setting_value NOT LIKE '%8888%'");
while($row = $result->fetch_assoc()) {
    echo "Found in Setting: " . $row['setting_name'] . " (Store ID: " . $row['store_id'] . ")\n";
}

$mysqli->close();
?>
