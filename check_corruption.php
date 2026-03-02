<?php
require_once('admin/config.php');
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$result = $mysqli->query("SELECT * FROM " . DB_PREFIX . "journal3_skin_setting WHERE skin_id = 1 AND setting_name = 'headerDesktop'");
if ($row = $result->fetch_assoc()) {
    $val = $row['setting_value'];
    echo "Raw DB length: " . strlen($val) . "\n";
    echo "Starts with: " . substr($val, 0, 20) . "\n";
    
    // Test if it is JSON
    $jsonObj = json_decode($val);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "Format: Valid JSON\n";
    } else {
        echo "Format: Not JSON (Error: " . json_last_error_msg() . ")\n";
        // Test if serialized
        $unser = @unserialize($val);
        if ($unser !== false || $val === 'b:0;') {
            echo "Format: Valid Serialized PHP\n";
        } else {
            echo "Format: CORRUPTED Serialized PHP!\n";
            // Is it supposed to be serialized?
            if (preg_match('/^[a-z]:[0-9]+:/is', $val)) {
                echo "Definitely corrupted serialized string detected.\n";
            }
        }
    }
}
$mysqli->close();
?>
