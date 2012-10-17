<?php


abstract class BaseTemplateRecord extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $template_id;


	
	protected $name;


	
	protected $type;


	
	protected $content;


	
	protected $ttl;


	
	protected $prio = 0;

	
	protected $aTemplate;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getTemplateId()
	{

		return $this->template_id;
	}

	
	public function getName()
	{

		return $this->name;
	}

	
	public function getType()
	{

		return $this->type;
	}

	
	public function getContent()
	{

		return $this->content;
	}

	
	public function getTtl()
	{

		return $this->ttl;
	}

	
	public function getPrio()
	{

		return $this->prio;
	}

	
	public function setId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::ID;
		}

	} 
	
	public function setTemplateId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->template_id !== $v) {
			$this->template_id = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::TEMPLATE_ID;
		}

		if ($this->aTemplate !== null && $this->aTemplate->getId() !== $v) {
			$this->aTemplate = null;
		}

	} 
	
	public function setName($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::NAME;
		}

	} 
	
	public function setType($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::TYPE;
		}

	} 
	
	public function setContent($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->content !== $v) {
			$this->content = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::CONTENT;
		}

	} 
	
	public function setTtl($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ttl !== $v) {
			$this->ttl = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::TTL;
		}

	} 
	
	public function setPrio($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->prio !== $v || $v === 0) {
			$this->prio = $v;
			$this->modifiedColumns[] = TemplateRecordPeer::PRIO;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->template_id = $rs->getInt($startcol + 1);

			$this->name = $rs->getString($startcol + 2);

			$this->type = $rs->getString($startcol + 3);

			$this->content = $rs->getString($startcol + 4);

			$this->ttl = $rs->getInt($startcol + 5);

			$this->prio = $rs->getInt($startcol + 6);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 7; 
		} catch (Exception $e) {
			throw new PropelException("Error populating TemplateRecord object", $e);
		}
	}

	
	public function delete($con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecord:delete:pre') as $callable)
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
			$con = Propel::getConnection(TemplateRecordPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			TemplateRecordPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	

    foreach (sfMixer::getCallables('BaseTemplateRecord:delete:post') as $callable)
    {
      call_user_func($callable, $this, $con);
    }

  }
	
	public function save($con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecord:save:pre') as $callable)
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
			$con = Propel::getConnection(TemplateRecordPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
    foreach (sfMixer::getCallables('BaseTemplateRecord:save:post') as $callable)
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


												
			if ($this->aTemplate !== null) {
				if ($this->aTemplate->isModified()) {
					$affectedRows += $this->aTemplate->save($con);
				}
				$this->setTemplate($this->aTemplate);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = TemplateRecordPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += TemplateRecordPeer::doUpdate($this, $con);
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


												
			if ($this->aTemplate !== null) {
				if (!$this->aTemplate->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aTemplate->getValidationFailures());
				}
			}


			if (($retval = TemplateRecordPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = TemplateRecordPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getTemplateId();
				break;
			case 2:
				return $this->getName();
				break;
			case 3:
				return $this->getType();
				break;
			case 4:
				return $this->getContent();
				break;
			case 5:
				return $this->getTtl();
				break;
			case 6:
				return $this->getPrio();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = TemplateRecordPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getTemplateId(),
			$keys[2] => $this->getName(),
			$keys[3] => $this->getType(),
			$keys[4] => $this->getContent(),
			$keys[5] => $this->getTtl(),
			$keys[6] => $this->getPrio(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = TemplateRecordPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setTemplateId($value);
				break;
			case 2:
				$this->setName($value);
				break;
			case 3:
				$this->setType($value);
				break;
			case 4:
				$this->setContent($value);
				break;
			case 5:
				$this->setTtl($value);
				break;
			case 6:
				$this->setPrio($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = TemplateRecordPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setTemplateId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setType($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setContent($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setTtl($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPrio($arr[$keys[6]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(TemplateRecordPeer::DATABASE_NAME);

		if ($this->isColumnModified(TemplateRecordPeer::ID)) $criteria->add(TemplateRecordPeer::ID, $this->id);
		if ($this->isColumnModified(TemplateRecordPeer::TEMPLATE_ID)) $criteria->add(TemplateRecordPeer::TEMPLATE_ID, $this->template_id);
		if ($this->isColumnModified(TemplateRecordPeer::NAME)) $criteria->add(TemplateRecordPeer::NAME, $this->name);
		if ($this->isColumnModified(TemplateRecordPeer::TYPE)) $criteria->add(TemplateRecordPeer::TYPE, $this->type);
		if ($this->isColumnModified(TemplateRecordPeer::CONTENT)) $criteria->add(TemplateRecordPeer::CONTENT, $this->content);
		if ($this->isColumnModified(TemplateRecordPeer::TTL)) $criteria->add(TemplateRecordPeer::TTL, $this->ttl);
		if ($this->isColumnModified(TemplateRecordPeer::PRIO)) $criteria->add(TemplateRecordPeer::PRIO, $this->prio);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(TemplateRecordPeer::DATABASE_NAME);

		$criteria->add(TemplateRecordPeer::ID, $this->id);

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

		$copyObj->setTemplateId($this->template_id);

		$copyObj->setName($this->name);

		$copyObj->setType($this->type);

		$copyObj->setContent($this->content);

		$copyObj->setTtl($this->ttl);

		$copyObj->setPrio($this->prio);


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
			self::$peer = new TemplateRecordPeer();
		}
		return self::$peer;
	}

	
	public function setTemplate($v)
	{


		if ($v === null) {
			$this->setTemplateId(NULL);
		} else {
			$this->setTemplateId($v->getId());
		}


		$this->aTemplate = $v;
	}


	
	public function getTemplate($con = null)
	{
		if ($this->aTemplate === null && ($this->template_id !== null)) {
						include_once 'lib/model/om/BaseTemplatePeer.php';

			$this->aTemplate = TemplatePeer::retrieveByPK($this->template_id, $con);

			
		}
		return $this->aTemplate;
	}


  public function __call($method, $arguments)
  {
    if (!$callable = sfMixer::getCallable('BaseTemplateRecord:'.$method))
    {
      throw new sfException(sprintf('Call to undefined method BaseTemplateRecord::%s', $method));
    }

    array_unshift($arguments, $this);

    return call_user_func_array($callable, $arguments);
  }


} 