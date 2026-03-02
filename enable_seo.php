<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== RE-ENABLING SEO URLS ===\n";
$mysqli->query("UPDATE " . DB_PREFIX . "setting SET value = '1' WHERE `key` = 'config_seo_url'");

// Also let's put the common/home keyword back where it was just to be clean
$mysqli->query("INSERT INTO " . DB_PREFIX . "seo_url (store_id, language_id, query, keyword) VALUES (0, 1, 'common/home', '/') ON DUPLICATE KEY UPDATE keyword='/'");

array_map('unlink', glob(DIR_STORAGE . 'cache/cache.*'));
$mysqli->close();
echo "Done!\n";
?>
