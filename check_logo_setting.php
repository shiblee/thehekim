<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== INSPECTING JOURNAL HEADER LOGO SETTINGS ===\n";
$result = $mysqli->query("SELECT setting_name, setting_value FROM " . DB_PREFIX . "journal3_setting WHERE setting_name LIKE '%logo%'");
while($row = $result->fetch_assoc()) {
    echo "SETTING: " . $row['setting_name'] . "\n";
    echo $row['setting_value'] . "\n\n";
}

echo "=== INSPECTING ALL HEADER MODULES ===\n";
// Sometimes the header is built from a module
$result = $mysqli->query("SELECT module_id, module_type, module_data FROM " . DB_PREFIX . "journal3_module WHERE module_type = 'header'");
while($row = $result->fetch_assoc()) {
    echo "HEADER MODULE: " . $row['module_id'] . "\n";
    $data = substr($row['module_data'], 0, 1000) . "...";
    echo $data . "\n\n";
}

$mysqli->close();
?>
