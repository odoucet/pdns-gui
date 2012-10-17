<?php
 
define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/..'));
define('SF_APP',         'frontend');
define('SF_ENVIRONMENT', 'cli');
define('SF_DEBUG',       true);
 
require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
 
// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

$data = new sfPropelData();
$data->loadData(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'fixtures');
 
?>
