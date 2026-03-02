<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== CHECKING SYSTEM SETTINGS ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` IN ('config_url', 'config_ssl')");
while($row = $result->fetch_assoc()) {
    echo $row['key'] . ": " . $row['value'] . "\n";
}

$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "store");
while($row = $result->fetch_assoc()) {
    echo "STORE ID: " . $row['store_id'] . " URL: " . $row['url'] . " SSL: " . $row['ssl'] . "\n";
}

$mysqli->close();
?>
