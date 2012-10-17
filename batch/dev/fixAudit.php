#!/usr/bin/php
<?php

//a:7:{s:8:"DomainId";i:152;s:4:"Name";s:25:"sip-voipdito.l7test.co.cc";s:4:"Type";s:1:"A";s:7:"Content";s:12:"62.232.50.62";s:3:"Ttl";i:120;s:4:"Prio";N;s:10:"ChangeDate";N;}

$template = array("DomainId"=>null,"Name"=>null,"Type"=>null,"Content"=>null,"Ttl"=>null,"Prio"=>null);

define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/../..'));
define('SF_APP',         'frontend');
define('SF_ENVIRONMENT', 'cli');
define('SF_DEBUG',       true);
 
require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
 
// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

$c = new Criteria();
$c->add(AuditPeer::OBJECT, 'Record');
$c->addGroupByColumn(AuditPeer::OBJECT_KEY);

foreach (AuditPeer::doSelect($c) as $group)
{
  echo "================= ".$group->getObjectKey()." ================\n";
  
  $c1 = new Criteria();
  $c1->add(AuditPeer::OBJECT, 'Record');
  $c1->add(AuditPeer::OBJECT_KEY,$group->getObjectKey());
  
  $prev_changes = $template;
  
  foreach (AuditPeer::doSelect($c1) as $audit)
  {
    echo "   ".$audit->getType()."\n";
    
    if ($audit->getType() == 'DELETE')
    {
      $changes = $prev_changes;
    }
    else
    {
    
      $current_changes = unserialize($audit->getObjectChanges());
      
      $changes = array_merge($prev_changes,$current_changes);
      
      $prev_changes = $changes;
    }
    
    $audit->setObjectChanges(serialize($changes));
    
    $audit->save();
    
  }
  
}

?>
