#!/usr/bin/php -q
<?php
$extensions = array(".js",".pjs");

error_reporting(E_ALL);

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/../..'));

foreach ($extensions as $ext)
{
  $files = array();

  exec("find ".SF_ROOT_DIR."/apps -name '*$ext'",$files);

  foreach ($files as $file)
  {
    check_js($file);
  }
}

function check_js($file)
{
  if (!$js = file_get_contents($file))
  {
    die("\nError: failed to read $file\n");
  }
  
  $js = str_replace(array("\r","\n"," "),array("","",""),$js);
  
  foreach (array(",}",",]",",)","console.log(") as $search)
  {
    if ($search == 'console.log(' && preg_match('/miframe-debug\.js$|storeLoader\.js$/',$file)) continue;
    
    check_char($js,$search,$file);
  }
  
  echo ".";
}

function check_char($string,$chr,$file)
{
  $pos = strpos($string,$chr);
  
  if ($pos)
  {
    die("\nError: found ,] in $file\nDetails: ".substr($string,$pos-15, 30)."\n");
  }
}

echo "All done.\n";
