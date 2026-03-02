<?php
require_once('admin/config.php');
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "=== Skin Settings ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "journal3_skin_setting WHERE `setting_name` LIKE '%header%' OR `setting_name` LIKE '%skin%'");
while($row = $result->fetch_assoc()) {
    echo "ID: " . $row['skin_id'] . " | Set: " . $row['setting_name'] . " | Val: " . substr($row['setting_value'], 0, 100) . "\n";
}

echo "\n=== General Settings ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "journal3_setting WHERE `setting_name` LIKE '%header%' OR `setting_name` LIKE '%skin%'");
while($row = $result->fetch_assoc()) {
    echo "Store: " . $row['store_id'] . " | Set: " . $row['setting_name'] . " | Val: " . substr($row['setting_value'], 0, 100) . "\n";
}

$mysqli->close();
?>
