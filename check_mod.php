<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== OCMOD SEARCH ===\n";
$result = $mysqli->query("SELECT modification_id, name, xml FROM " . DB_PREFIX . "modification WHERE xml LIKE '%seo_url.php%'");
while($row = $result->fetch_assoc()) {
    echo "ID: " . $row['modification_id'] . "\nNAME: " . $row['name'] . "\n\n";
    // Echo the specific snippet patching common/home
    preg_match('/<file path="catalog\/controller\/startup\/seo_url.php".*?<\/file>/is', $row['xml'], $matches);
    if (!empty($matches)) {
        echo $matches[0] . "\n\n";
    }
}

$mysqli->close();
?>
