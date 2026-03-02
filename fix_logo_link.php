<?php
require_once('config.php');

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

echo "=== FIXING LOGO LINK IN JOURNAL MODULES ===\n";
// The JSON encoded string for the bad link might look like: "url":"http://localhost/" or escaped
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "journal3_module WHERE module_data LIKE '%http://localhost/%' OR module_data LIKE '%http:\/\/localhost\/%'");
while($row = $result->fetch_assoc()) {
    $data = json_decode($row['module_data'], true);
    $changed = false;
    
    // We can just text replace the raw module_data to be safe if it's deeply nested, 
    // but doing it cleanly is better if we know the structure.
    // Actually, a str_replace on the raw string is safest for catching all edge cases.
    $oldData = $row['module_data'];
    $newData = str_replace('http:\/\/localhost\/', 'http:\/\/localhost:8888\/thehekim\/', $oldData);
    $newData = str_replace('"http://localhost/"', '"http://localhost:8888/thehekim/"', $newData);
    
    if ($oldData !== $newData) {
        $stmt = $mysqli->prepare("UPDATE " . DB_PREFIX . "journal3_module SET module_data = ? WHERE module_id = ?");
        $stmt->bind_param("si", $newData, $row['module_id']);
        $stmt->execute();
        $stmt->close();
        echo "Replaced in Module ID: " . $row['module_id'] . "\n";
    }
}

echo "=== FIXING LOGO LINK IN JOURNAL SETTINGS ===\n";
$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "journal3_setting WHERE setting_value LIKE '%http://localhost/%' OR setting_value LIKE '%http:\/\/localhost\/%'");
while($row = $result->fetch_assoc()) {
    $oldData = $row['setting_value'];
    $newData = str_replace('http:\/\/localhost\/', 'http:\/\/localhost:8888\/thehekim\/', $oldData);
    $newData = str_replace('"http://localhost/"', '"http://localhost:8888/thehekim/"', $newData);
    
    if ($oldData !== $newData) {
        $stmt = $mysqli->prepare("UPDATE " . DB_PREFIX . "journal3_setting SET setting_value = ? WHERE setting_name = ? AND store_id = ?");
        $stmt->bind_param("ssi", $newData, $row['setting_name'], $row['store_id']);
        $stmt->execute();
        $stmt->close();
        echo "Replaced in Setting: " . $row['setting_name'] . "\n";
    }
}

array_map('unlink', glob(DIR_STORAGE . 'cache/cache.*'));
$mysqli->close();
echo "Done!\n";
?>
