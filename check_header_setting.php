<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== INSPECTING JOURNAL HEADER SETTINGS ===\n";
$result = $mysqli->query("SELECT setting_name FROM " . DB_PREFIX . "journal3_setting WHERE setting_name LIKE '%header%' OR setting_name LIKE '%logo%'");
while($row = $result->fetch_assoc()) {
    echo "SETTING: " . $row['setting_name'] . "\n";
}

$mysqli->close();
?>
