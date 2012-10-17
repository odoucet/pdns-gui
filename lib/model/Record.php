<?php

/**
 * Subclass for representing a row from the 'records' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Record extends BaseRecord
{
  public function needsCommit()
  {
    $connection = Propel::getConnection();
    
    $sql = sprintf("SELECT COUNT(%s) AS count FROM %s 
      WHERE %s = 'Record' 
      AND %s = %d 
      AND %s = %d 
      AND %s > '%s'",
    AuditPeer::ID, AuditPeer::TABLE_NAME,
    AuditPeer::OBJECT,
    AuditPeer::OBJECT_KEY, $this->getId(),
    AuditPeer::DOMAIN_ID, $this->getDomainId(),
    AuditPeer::CREATED_AT, date("Y-m-d H:i:s",MyTools::getLastCommit()));
    
    $statement = $connection->prepareStatement($sql);
    $resultset = $statement->executeQuery();
    
    $resultset->next();
    return $resultset->getInt('count');
  }
}

sfPropelBehavior::add('Record', array('audit'));
