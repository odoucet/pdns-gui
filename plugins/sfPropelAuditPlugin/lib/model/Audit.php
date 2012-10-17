<?php

/**
 * Subclass for representing a row from the 'audit' table.
 *
 * 
 *
 * @package plugins.sfPropelAuditPlugin.lib.model
 */ 
class Audit extends BaseAudit
{
  public function toStore()
  {
    return array(
      "id"          => $this->getId(),
      "ip_address"  => $this->getRemoteIpAddress(),
      "object"      => $this->getObject(),
      "object_key"  => $this->getObjectKey(),
      "domain_id"   => $this->getDomainId(),
      "changes"     => unserialize($this->getObjectChanges()),
      "type"        => $this->getType(),
      "created_at"  => $this->getCreatedAt()
    );
  }
  
}
