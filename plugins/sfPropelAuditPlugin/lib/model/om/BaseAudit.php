<?php


abstract class BaseAudit extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $remote_ip_address;


	
	protected $object;


	
	protected $object_key;


	
	protected $domain_id;


	
	protected $object_changes;


	
	protected $query;


	
	protected $type;


	
	protected $created_at;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getID()
	{

		return $this->id;
	}

	
	public function getRemoteIpAddress()
	{

		return $this->remote_ip_address;
	}

	
	public function getObject()
	{

		return $this->object;
	}

	
	public function getObjectKey()
	{

		return $this->object_key;
	}

	
	public function getDomainId()
	{

		return $this->domain_id;
	}

	
	public function getObjectChanges()
	{

		return $this->object_changes;
	}

	
	public function getQuery()
	{

		return $this->query;
	}

	
	public function getType()
	{

		return $this->type;
	}

	
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
						$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	
	public function setID($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = AuditPeer::ID;
		}

	} 
	
	public function setRemoteIpAddress($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->remote_ip_address !== $v) {
			$this->remote_ip_address = $v;
			$this->modifiedColumns[] = AuditPeer::REMOTE_IP_ADDRESS;
		}

	} 
	
	public function setObject($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object !== $v) {
			$this->object = $v;
			$this->modifiedColumns[] = AuditPeer::OBJECT;
		}

	} 
	
	public function setObjectKey($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object_key !== $v) {
			$this->object_key = $v;
			$this->modifiedColumns[] = AuditPeer::OBJECT_KEY;
		}

	} 
	
	public function setDomainId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->domain_id !== $v) {
			$this->domain_id = $v;
			$this->modifiedColumns[] = AuditPeer::DOMAIN_ID;
		}

	} 
	
	public function setObjectChanges($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->object_changes !== $v) {
			$this->object_changes = $v;
			$this->modifiedColumns[] = AuditPeer::OBJECT_CHANGES;
		}

	} 
	
	public function setQuery($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->query !== $v) {
			$this->query = $v;
			$this->modifiedColumns[] = AuditPeer::QUERY;
		}

	} 
	
	public function setType($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = AuditPeer::TYPE;
		}

	} 
	
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = AuditPeer::CREATED_AT;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->remote_ip_address = $rs->getString($startcol + 1);

			$this->object = $rs->getString($startcol + 2);

			$this->object_key = $rs->getString($startcol + 3);

			$this->domain_id = $rs->getInt($startcol + 4);

			$this->object_changes = $rs->getString($startcol + 5);

			$this->query = $rs->getString($startcol + 6);

			$this->type = $rs->getString($startcol + 7);

			$this->created_at = $rs->getTimestamp($startcol + 8, null);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 9; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Audit object", $e);
		}
	}

	
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseAudit:delete:pre') as $callable)
    {
      $ret = call_user_func($callable, $this, $con);
      if ($ret)
      {
        return;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(AuditPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			AuditPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseAudit:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseAudit:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


    if ($this->isNew() && !$this->isColumnModified(AuditPeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(AuditPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseAudit:save:post') as $callable)
    {
      call_user_func($callable, $this, $con, $affectedRows);
    }

			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = AuditPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setID($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += AuditPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = AuditPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = AuditPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getID();
				break;
			case 1:
				return $this->getRemoteIpAddress();
				break;
			case 2:
				return $this->getObject();
				break;
			case 3:
				return $this->getObjectKey();
				break;
			case 4:
				return $this->getDomainId();
				break;
			case 5:
				return $this->getObjectChanges();
				break;
			case 6:
				return $this->getQuery();
				break;
			case 7:
				return $this->getType();
				break;
			case 8:
				return $this->getCreatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = AuditPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getID(),
			$keys[1] => $this->getRemoteIpAddress(),
			$keys[2] => $this->getObject(),
			$keys[3] => $this->getObjectKey(),
			$keys[4] => $this->getDomainId(),
			$keys[5] => $this->getObjectChanges(),
			$keys[6] => $this->getQuery(),
			$keys[7] => $this->getType(),
			$keys[8] => $this->getCreatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = AuditPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setID($value);
				break;
			case 1:
				$this->setRemoteIpAddress($value);
				break;
			case 2:
				$this->setObject($value);
				break;
			case 3:
				$this->setObjectKey($value);
				break;
			case 4:
				$this->setDomainId($value);
				break;
			case 5:
				$this->setObjectChanges($value);
				break;
			case 6:
				$this->setQuery($value);
				break;
			case 7:
				$this->setType($value);
				break;
			case 8:
				$this->setCreatedAt($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = AuditPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setID($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setRemoteIpAddress($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setObject($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setObjectKey($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDomainId($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setObjectChanges($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setQuery($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setType($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(AuditPeer::DATABASE_NAME);

		if ($this->isColumnModified(AuditPeer::ID)) $criteria->add(AuditPeer::ID, $this->id);
		if ($this->isColumnModified(AuditPeer::REMOTE_IP_ADDRESS)) $criteria->add(AuditPeer::REMOTE_IP_ADDRESS, $this->remote_ip_address);
		if ($this->isColumnModified(AuditPeer::OBJECT)) $criteria->add(AuditPeer::OBJECT, $this->object);
		if ($this->isColumnModified(AuditPeer::OBJECT_KEY)) $criteria->add(AuditPeer::OBJECT_KEY, $this->object_key);
		if ($this->isColumnModified(AuditPeer::DOMAIN_ID)) $criteria->add(AuditPeer::DOMAIN_ID, $this->domain_id);
		if ($this->isColumnModified(AuditPeer::OBJECT_CHANGES)) $criteria->add(AuditPeer::OBJECT_CHANGES, $this->object_changes);
		if ($this->isColumnModified(AuditPeer::QUERY)) $criteria->add(AuditPeer::QUERY, $this->query);
		if ($this->isColumnModified(AuditPeer::TYPE)) $criteria->add(AuditPeer::TYPE, $this->type);
		if ($this->isColumnModified(AuditPeer::CREATED_AT)) $criteria->add(AuditPeer::CREATED_AT, $this->created_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(AuditPeer::DATABASE_NAME);

		$criteria->add(AuditPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getID();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setID($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRemoteIpAddress($this->remote_ip_address);

		$copyObj->setObject($this->object);

		$copyObj->setObjectKey($this->object_key);

		$copyObj->setDomainId($this->domain_id);

		$copyObj->setObjectChanges($this->object_changes);

		$copyObj->setQuery($this->query);

		$copyObj->setType($this->type);

		$copyObj->setCreatedAt($this->created_at);


		$copyObj->setNew(true);

		$copyObj->setID(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new AuditPeer();
		}
		return self::$peer;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseAudit:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseAudit::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} 