<?php
require_once('admin/config.php');
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$res = $mysqli->query("SELECT * FROM `" . DB_PREFIX ."journal3_setting` WHERE `setting_name` = 'active_skin'");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
