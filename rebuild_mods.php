<?php
// Rebuild OpenCart Modifications via CLI/standalone
define('VERSION', '3.0.3.9'); // Set dummy version to avoid error
require_once('admin/config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();

$config = new Config();
$config->load('default');
$config->load('admin');
$registry->set('config', $config);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

$log = new Log('error.log');
$registry->set('log', $log);

$loader = new Loader($registry);
$registry->set('load', $loader);

$request = new Request();
$registry->set('request', $request);

$document = new Document();
$registry->set('document', $document);

$event = new Event($registry);
$registry->set('event', $event);

$response = new Response();
$registry->set('response', $response);

$session = new Session('file');
$session->start();
$session->data['user_token'] = 'cli_token';
$registry->set('session', $session);

$language = new Language('en-gb');
$registry->set('language', $language);

$url = new Url(HTTP_SERVER, HTTP_SERVER);
$registry->set('url', $url);

class MockUser {
    public function hasPermission($key, $value) {
        return true;
    }
}
$registry->set('user', new MockUser());

require_once(DIR_APPLICATION . 'controller/marketplace/modification.php');
$controller = new ControllerMarketplaceModification($registry);

try {
    $controller->refresh();
    echo "Modifications Rebuilt!";
} catch (Exception $e) {
    echo "Done rebuilding, script halted safely.";
}
?>
