<?php
error_reporting(E_ALL);

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/..'));
define('SF_APP',         'frontend');
define('SF_ENVIRONMENT', 'cli');
define('SF_DEBUG',       true);
 
require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
 
// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

$connection = $databaseManager->getDatabase('propel')->getConnection();

$db_config = $databaseManager->getDatabase('propel')->getConfiguration();
$db_name = $db_config['propel']['datasources']['propel']['connection']['database'];

$upgraded = false;

// change table engine to InnoDB
foreach (array("records","domains") as $table)
{
  $sql = "SHOW TABLE STATUS WHERE name = '$table'";
  
  try {
    $statement = $connection->prepareStatement($sql);
    $resultset = $statement->executeQuery();
  } catch (Exception $e) {
    innoDBError($sql,$e);
    break;
  }
  
  $resultset->next();
  
  $engine = $resultset->getString('Engine');
  
  if ($engine == 'InnoDB')
  {
    echo "Table $table engine is InnoDB - OK\n";
  }
  else
  {
    echo "Table $table - changing engine to InnoDB...\n";
    
    $sql = "ALTER TABLE `$db_name`.`$table` ENGINE = InnoDB";
    
    try {
      $statement = $connection->prepareStatement($sql);
      $statement->executeQuery();
    } catch (Exception $e) {
      innoDBError($sql,$e);
      break;
    }
    
    $upgraded = true;
  }
}

// set record_type setting
if (!SettingPeer::getValue('record_type'))
{
  echo "Settings->record_type missing, updating...\n";
  
  $types = array(
    "A" => 1,
    "AAAA" => 0,
    "AFSDB" => 0,
    "CERT" => 0,
    "CNAME" => 1,
    "DNSKEY" => 0,
    "DS" => 0,
    "HINFO" => 0,
    "KEY" => 0,
    "LOC" => 0,
    "MX" => 1,
    "NAPTR" => 1,
    "NS" => 1,
    "NSEC" => 0,
    "PTR" => 1,
    "RP" => 0,
    "RRSIG" => 0,
    "SOA" => 1,
    "SPF" => 1,
    "SSHFP" => 0,
    "SRV" => 1,
    "TXT" => 1
  );
  
  SettingPeer::setValue('record_type',serialize($types));
  
  $upgraded = true;
}

if ($upgraded)
{
  echo "Clearing cache...\n";
  
  passthru(SF_ROOT_DIR."/symfony cc");
  
  echo "Done.\n";
}
else
{
  echo "Your Power DNS GUI is up to date. Nothing to upgrade.\n";
}

function innoDBError($sql,$e)
{
  echo $e->getMessage()."\n\n";
  echo "Please make sure 'records' and 'domain' tables engine is set to InnoDB\n\n";
}

?>
