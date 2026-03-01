<?php
$host = '127.0.0.1';
$user = 'root';
$pass = ''; // User said password is blank
$db   = 'thehekim';
$port = 8889;

echo "Testing TCP/IP (127.0.0.1)...\n";
$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_error) {
    echo "TCP/IP Error: " . $mysqli->connect_error . "\n";
} else {
    echo "TCP/IP Success!\n";
}

$host2 = 'localhost';
echo "\nTesting Socket (localhost)...\n";
$mysqli2 = new mysqli($host2, $user, $pass, $db, $port, '/Applications/MAMP/tmp/mysql/mysql.sock');
if ($mysqli2->connect_error) {
    echo "Socket Error: " . $mysqli2->connect_error . "\n";
} else {
    echo "Socket Success!\n";
}

$pass_root = 'root'; // MAMP Default on Mac
echo "\nTesting with password 'root'...\n";
$mysqli3 = new mysqli($host, $user, $pass_root, $db, $port);
if ($mysqli3->connect_error) {
    echo "Pass 'root' Error: " . $mysqli3->connect_error . "\n";
} else {
    echo "Pass 'root' Success!\n";
}
?>
