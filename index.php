<?php
// Version
define('VERSION', '3.0.4.1');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	if (is_dir('install')) {
		header('Location: install/index.php');
		exit;
	}
	else {
		echo 'The config.php file is missing or invalid, and the install directory is not accessible. Please restore config.php if the site is already installed.';
		exit;
	}
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');