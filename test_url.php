<?php
define('VERSION', '3.0.3.9'); // Set dummy version to avoid error
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');

$registry = new Registry();

$loader = new Loader($registry);
$registry->set('load', $loader);

$config = new Config();
$config->load('default');
$config->load('catalog');
$registry->set('config', $config);

// Database Setup
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0'");
foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'], json_decode($result['value'], true));
	}
}

// Request and Server Variables
$request = new Request();
$registry->set('request', $request);

// URL
$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
$registry->set('url', $url);

// SEO URL Rewriter
if ($config->get('config_seo_url')) {
    require_once(DIR_APPLICATION . 'controller/startup/seo_url.php');
    $seo_url = new ControllerStartupSeoUrl($registry);
    $url->addRewrite($seo_url);
    echo "SEO URL Rewriter is ACTIVE.\n";
} else {
    echo "SEO URL Rewriter is INACTIVE.\n";
}

echo "Testing URL Builder:\n";
echo "Base: " . $url->link('common/home') . "\n";
echo "Product: " . $url->link('product/product', 'product_id=42') . "\n";

?>
