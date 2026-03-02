<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== RECURSIVELY FIXING LOCALHOST IN JSON DATABASE ===\n";

function fixJSON($jsonStr) {
    if (empty($jsonStr)) return $jsonStr;
    
    // Quick string replace to catch everything inside the JSON string
    // This is safer than json_decode if it's partly corrupted or complex nested strings
    $changed = false;
    $newStr = str_replace('"http://localhost/"', '"http://localhost:8888/thehekim/"', $jsonStr);
    $newStr = str_replace('http:\/\/localhost\/', 'http:\/\/localhost:8888\/thehekim\/', $newStr);
    
    if ($newStr !== $jsonStr) {
        return $newStr;
    }
    return false;
}

$tables = [
    DB_PREFIX . 'journal3_setting' => ['setting_name', 'setting_value'],
    DB_PREFIX . 'journal3_skin_setting' => ['setting_name', 'setting_value'],
    DB_PREFIX . 'journal3_module' => ['module_name', 'module_data']
];

foreach ($tables as $tableName => $cols) {
    $nameCol = $cols[0];
    $valCol = $cols[1];
    
    $result = $mysqli->query("SELECT * FROM " . $tableName);
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $idCol = array_key_first($row); // get primary key name
            
            $fixed = fixJSON($row[$valCol]);
            if ($fixed !== false) {
                echo "Found bad link in Table: " . $tableName . " | Row ID: " . $row[$idCol] . " (" . $row[$nameCol] . ")\n";
                $stmt = $mysqli->prepare("UPDATE " . $tableName . " SET `" . $valCol . "` = ? WHERE `" . $idCol . "` = ?");
                $stmt->bind_param("ss", $fixed, $row[$idCol]);
                $stmt->execute();
            }
        }
    }
}

// Clear the cache manually again
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
rrmdir(DIR_STORAGE . 'cache/template');
rrmdir(DIR_STORAGE . 'cache/journal3');

$mysqli->close();
echo "Done!\n";
?>
