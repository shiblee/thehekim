<?php
// Refresh Modifications
require_once('admin/config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();

$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Log
$log = new Log('error.log');
$registry->set('log', $log);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

require_once(DIR_SYSTEM . 'library/modification.php');

echo "Clearing old modifications...\n";
// Clear old cache
$files = glob(DIR_MODIFICATION . '{*.php,*.sql}', GLOB_BRACE);
if ($files) {
	foreach ($files as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}
}
$dirs = glob(DIR_MODIFICATION . '*', GLOB_ONLYDIR);
if ($dirs) {
	foreach ($dirs as $dir) {
		if (is_dir($dir)) {
			// recursively remove
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it,
                         RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
		}
	}
}

// Just deleting it makes OpenCart regenerate it on next request sometimes, but let's be sure.
// Let's use OpenCart's built-in rebuild if possible.
// OpenCart 3 rebuilds on admin request. We can just hit the admin controller or require the admin modification model.
require_once(DIR_APPLICATION . 'model/setting/modification.php');
$model = new ModelSettingModification($registry);

// But we can't easily run the whole controller from CLI.
// We will just clear DIR_MODIFICATION. In OpenCart 3, if DIR_MODIFICATION is empty, 
// the user needs to click the refresh button in admin.
// Wait! Let's do it manually using the model or just clear it. We can just clear it, and load admin page.
echo "Modifications cleared. \n";
?>
