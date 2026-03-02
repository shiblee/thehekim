<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Version
define('VERSION', '3.0.4.1');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	if (is_dir('../install')) {
		header('Location: ../install/index.php');
		exit;
	}
	else {
		echo 'The admin/config.php file is missing or invalid. Please restore admin/config.php if the site is already installed.';
		exit;
	}
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('admin');