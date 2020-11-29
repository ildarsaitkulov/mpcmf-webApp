<?php

namespace mpcmf\apps\webAdministration\htdocs;

use mpcmf\system\application\applicationInstance;
use mpcmf\system\io\log;

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$filepath = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'];

if($_SERVER['PHP_SELF'] === '/favicon.ico') {
    return false;
}

$indexFile = __DIR__ . '/htdocs/index.php';

if($filepath !== $indexFile && file_exists($filepath) && !is_dir($filepath)) {

    return false;
}

require_once __DIR__ . '/loader.php';

$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';

$log = log::factory();
MPCMF_DEBUG && $log->addDebug("Path found: {$path}");

$_SERVER['SCRIPT_NAME'] = DIRECTORY_SEPARATOR . basename(__FILE__);
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

$class = "mpcmf\\apps\\defaultApp\\defaultApp";

$_SERVER['SCRIPT_NAME'] = '/';
$_SERVER['PHP_SELF'] = $path;
$_SERVER['REQUEST_URI'] = $path . (isset($_SERVER['QUERY_STRING']) ? "?{$_SERVER['QUERY_STRING']}" : '');
MPCMF_DEBUG && $log->addDebug('Instantiating application class');
$app = applicationInstance::getInstance();
$app->setApplication(new $class());
$app->run();