<?php
error_reporting(E_ALL);

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/..'));

if (!function_exists('mysql_connect'))
{
  echo "Error: MySQL module not loaded?\n";
  exit(1);
}

$fp = fopen('php://stdin', 'r');

echo "\nThis script will install PowerDNS GUI on this server\n\n";
echo "*************** W A R N I N G !!! ******************\n";
echo "*                                                  *\n";
echo "*   All existing data in Powerdns MySQL database   *\n";
echo "*               WILL BE LOST.                      *\n";
echo "*       Make a backup copy before proceeding.      *\n";
echo "*                                                  *\n";
echo "****************************************************\n\n";
echo "Press ENTER to continue Ctrl-C to abort...\n";

fgets($fp, 1024);

echo "Enter MySQL host name: ";

$db_host = trim(fgets($fp, 1024));

echo "Enter MySQL database name: ";

$db_name = trim(fgets($fp, 1024));

echo "Enter MySQL user name: ";

$db_user = trim(fgets($fp, 1024));

$db_pass = prompt_silent("Enter MySQL password: ");

if (!$link = @mysql_connect($db_host,$db_user,$db_pass))
{
  echo "\nError: ".mysql_error()."\n\n";
  exit(1);
}

if (!@mysql_select_db($db_name))
{
  echo "\nError: ".mysql_error()."\n\n";
  exit(1);
}

str_replace_in_file(
  '/^      dsn:.*$/m',
  "      dsn: mysql://$db_user:$db_pass@$db_host/$db_name",
  SF_ROOT_DIR.'/config/databases.yml'
);

str_replace_in_file(
  '/^propel\.database\.createUrl  =.*$/m',
  "propel.database.createUrl  = mysql://$db_user:$db_pass@$db_host/",
  SF_ROOT_DIR.'/config/propel.ini'
);

str_replace_in_file(
  '/^propel\.database\.url        =.*$/m',
  "propel.database.url        = mysql://$db_user:$db_pass@$db_host/$db_name",
  SF_ROOT_DIR.'/config/propel.ini'
);

passthru(SF_ROOT_DIR.'/symfony propel-insert-sql');
passthru('php '.SF_ROOT_DIR.'/batch/load_data.php');
passthru(SF_ROOT_DIR.'/symfony fix-perms');

if (!@mysql_query("ALTER TABLE `$db_name`.`domains` ENGINE = InnoDB"))
{
  echo "\nError: ".mysql_error()."\n\n";
}

if (!@mysql_query("ALTER TABLE `$db_name`.`records` ENGINE = InnoDB"))
{
  echo "\nError: ".mysql_error()."\n\n";
}

echo "\n\n\n\nDatabase initialized...\n\n";

echo "\nNow you need to edit your PowerDNS config file\n";
echo "(most likely /etc/powerdns/pdns.d/pdns.local)\n";
echo "to set correct DB connection details\n\n";

echo "Add the following to your Apache configuration:\n\n";

$SF_ROOT_DIR = SF_ROOT_DIR;

$apache_conf=<<<EOD
<VirtualHost *:80>

  DocumentRoot $SF_ROOT_DIR/web

  DirectoryIndex index.php

  <Directory $SF_ROOT_DIR/web>
    AllowOverride All
  </Directory>

</VirtualHost>
EOD;

echo "------------------------------\n".$apache_conf."\n------------------------------\n\n";

echo "\n\nAnd make sure 'mod_rewrite' is enabled.\n\n";
/**
 * Interactively prompts for input without echoing to the terminal.
 * Requires a bash shell or Windows and won't work with
 * safe_mode settings (Uses `shell_exec`)
 */
function prompt_silent($prompt = "Enter Password:") {
  if (preg_match('/^win/i', PHP_OS)) {
    $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
    file_put_contents(
      $vbscript, 'wscript.echo(InputBox("'
      . addslashes($prompt)
      . '", "", "password here"))');
    $command = "cscript //nologo " . escapeshellarg($vbscript);
    $password = rtrim(shell_exec($command));
    unlink($vbscript);
    return $password;
  } else {
    $command = "/usr/bin/env bash -c 'echo OK'";
    if (rtrim(shell_exec($command)) !== 'OK') {
      trigger_error("Can't invoke bash");
      return;
    }
    $command = "/usr/bin/env bash -c 'read -s -p \""
      . addslashes($prompt)
      . "\" mypassword && echo \$mypassword'";
    $password = rtrim(shell_exec($command));
    echo "\n";
    return $password;
  }
}

function str_replace_in_file($search,$replace,$file)
{
  if (!$content = @file_get_contents($file))
  {
    echo "Error: failed to read $file.\n";
    exit(1);
  }
  
  $count = 0;
  
  $content = preg_replace($search,$replace,$content,-1,$count);
  
  if (!$count)
  {
    echo "Error: failed to configure DB connection string in $file.\n";
    exit(1);
  }
  
  if (!@file_put_contents($file,$content))
  {
    echo "Error: failed to read $file.\n";
    exit(1);
  }
}

?>
