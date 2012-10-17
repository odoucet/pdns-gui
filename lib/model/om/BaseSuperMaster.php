<?php


abstract class BaseSuperMaster extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $ip;


	
	protected $nameserver;


	
	protected $account;


	
	protected $id;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getIp()
	{

		return $this->ip;
	}

	
	public function getNameserver()
	{

		return $this->nameserver;
	}

	
	public function getAccount()
	{

		return $this->account;
	}

	
	public function getId()
	{

		return $this->id;
	}

	
	public function setIp($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->ip !== $v) {
			$this->ip = $v;
			$this->modifiedColumns[] = SuperMasterPeer::IP;
		}

	} 
	
	public function setNameserver($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->nameserver !== $v) {
			$this->nameserver = $v;
			$this->modifiedColumns[] = SuperMasterPeer::NAMESERVER;
		}

	} 
	
	public function setAccount($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->account !== $v) {
			$this->account = $v;
			$this->modifiedColumns[] = SuperMasterPeer::ACCOUNT;
		}

	} 
	
	public function setId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = SuperMasterPeer::ID;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->ip = $rs->getString($startcol + 0);

			$this->nameserver = $rs->getString($startcol + 1);

			$this->account = $rs->getString($startcol + 2);

			$this->id = $rs->getInt($startcol + 3);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 4; 
		} catch (Exception $e) {
			throw new PropelException("Error populating SuperMaster object", $e);
		}
	}

	
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseSuperMaster:delete:pre') as $callable)
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
			$con = Propel::getConnection(SuperMasterPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			SuperMasterPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseSuperMaster:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseSuperMaster:save:pre') as $callable)
    {
      $affectedRows = call_user_func($callable, $this, $con);
      if (is_int($affectedRows))
      {
        return $affectedRows;
      }
    }


		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(SuperMasterPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseSuperMaster:save:post') as $callable)
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
					$pk = SuperMasterPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += SuperMasterPeer::doUpdate($this, $con);
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


			if (($retval = SuperMasterPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = SuperMasterPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getIp();
				break;
			case 1:
				return $this->getNameserver();
				break;
			case 2:
				return $this->getAccount();
				break;
			case 3:
				return $this->getId();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = SuperMasterPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getIp(),
			$keys[1] => $this->getNameserver(),
			$keys[2] => $this->getAccount(),
			$keys[3] => $this->getId(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = SuperMasterPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setIp($value);
				break;
			case 1:
				$this->setNameserver($value);
				break;
			case 2:
				$this->setAccount($value);
				break;
			case 3:
				$this->setId($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = SuperMasterPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setIp($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setNameserver($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setAccount($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setId($arr[$keys[3]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(SuperMasterPeer::DATABASE_NAME);

		if ($this->isColumnModified(SuperMasterPeer::IP)) $criteria->add(SuperMasterPeer::IP, $this->ip);
		if ($this->isColumnModified(SuperMasterPeer::NAMESERVER)) $criteria->add(SuperMasterPeer::NAMESERVER, $this->nameserver);
		if ($this->isColumnModified(SuperMasterPeer::ACCOUNT)) $criteria->add(SuperMasterPeer::ACCOUNT, $this->account);
		if ($this->isColumnModified(SuperMasterPeer::ID)) $criteria->add(SuperMasterPeer::ID, $this->id);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(SuperMasterPeer::DATABASE_NAME);

		$criteria->add(SuperMasterPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setIp($this->ip);

		$copyObj->setNameserver($this->nameserver);

		$copyObj->setAccount($this->account);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
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
			self::$peer = new SuperMasterPeer();
		}
		return self::$peer;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseSuperMaster:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseSuperMaster::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} 