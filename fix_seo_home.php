<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== FIXING SEO URL FOR HOME ROUTE ===\n";
// The keyword '/' is causing it to generate a URL pointing to the root of the server instead of the OpenCart installation folder
$mysqli->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'common/home'");

echo "Deleted bad common/home route.\n";

array_map('unlink', glob(DIR_STORAGE . 'cache/cache.*'));
$mysqli->close();
echo "Done!\n";
?>
