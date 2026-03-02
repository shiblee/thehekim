<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== INSPECTING OPENCART EVENTS ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "event WHERE status = 1");
while($row = $result->fetch_assoc()) {
    echo "TRIGGER: " . $row['trigger'] . " | ACTION: " . $row['action'] . "\n";
}

$mysqli->close();
?>
