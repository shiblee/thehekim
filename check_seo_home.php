<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== CHECKING SEO URLS FOR HOME ROUTE ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'common/home' OR keyword LIKE '%localhost%'");
while($row = $result->fetch_assoc()) {
    print_r($row);
}

$mysqli->close();
?>
