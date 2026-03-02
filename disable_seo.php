<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== DISABLING SEO URLS ===\n";
$mysqli->query("UPDATE " . DB_PREFIX . "setting SET value = '0' WHERE `key` = 'config_seo_url'");
echo "Disabled.\n";

array_map('unlink', glob(DIR_STORAGE . 'cache/cache.*'));
$mysqli->close();
echo "Done!\n";
?>
