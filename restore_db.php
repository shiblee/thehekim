<?php
require_once('admin/config.php');
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

echo "Truncating corrupted Journal 3 tables...\n";
$mysqli->query("TRUNCATE TABLE " . DB_PREFIX . "journal3_setting");
$mysqli->query("TRUNCATE TABLE " . DB_PREFIX . "journal3_skin_setting");
$mysqli->query("TRUNCATE TABLE " . DB_PREFIX . "journal3_module");

echo "Restoring original data...\n";
$sql = file_get_contents('restore_j3.sql');
if ($mysqli->multi_query($sql)) {
    do {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    echo "Restoration commands executed successfully!\n";
} else {
    echo "Multi-query execution failed: " . $mysqli->error . "\n";
}

// Clear Cache
function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
           rrmdir($dir. DIRECTORY_SEPARATOR .$object);
         else
           unlink($dir. DIRECTORY_SEPARATOR .$object); 
       } 
     }
     rmdir($dir); 
   } 
}
rrmdir(DIR_STORAGE . 'cache/journal3');
rrmdir(DIR_STORAGE . 'cache/template');

$mysqli->close();
?>
