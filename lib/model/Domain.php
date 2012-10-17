<?php

/**
 * Subclass for representing a row from the 'domains' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Domain extends BaseDomain
{
  /**
   * Gets timestamp when domain was created
   */
  public function getCreatedAt()
  {
    $c = new Criteria();
    $c->add(AuditPeer::OBJECT, 'Domain');
    $c->add(AuditPeer::TYPE, 'ADD');
    $c->add(AuditPeer::OBJECT_KEY, $this->getId());
    $c->addAscendingOrderByColumn(AuditPeer::ID);
    
    $audit = AuditPeer::doSelectOne($c);
    
    return $audit->getCreatedAt();
  }
  
  public function needsComit()
  { 
    $connection = Propel::getConnection();
    
    $sql = sprintf("SELECT COUNT(%s) AS count FROM %s WHERE %s = 'Record' AND %s = %d AND %s > '%s'"
    ,AuditPeer::ID, AuditPeer::TABLE_NAME, AuditPeer::OBJECT, AuditPeer::DOMAIN_ID, $this->getId(),
    AuditPeer::CREATED_AT,date("Y-m-d H:i:s",MyTools::getLastCommit()));
    
    $statement = $connection->prepareStatement($sql);
    $resultset = $statement->executeQuery();
    
    $resultset->next();
    return $resultset->getInt('count');
  }
}

sfPropelBehavior::add('Domain', array('audit'));
