<?php


abstract class BaseAuditPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'audit';

	
	const CLASS_DEFAULT = 'plugins.sfPropelAuditPlugin.lib.model.Audit';

	
	const NUM_COLUMNS = 9;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'audit.ID';

	
	const REMOTE_IP_ADDRESS = 'audit.REMOTE_IP_ADDRESS';

	
	const OBJECT = 'audit.OBJECT';

	
	const OBJECT_KEY = 'audit.OBJECT_KEY';

	
	const DOMAIN_ID = 'audit.DOMAIN_ID';

	
	const OBJECT_CHANGES = 'audit.OBJECT_CHANGES';

	
	const QUERY = 'audit.QUERY';

	
	const TYPE = 'audit.TYPE';

	
	const CREATED_AT = 'audit.CREATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('ID', 'RemoteIpAddress', 'Object', 'ObjectKey', 'DomainId', 'ObjectChanges', 'Query', 'Type', 'CreatedAt', ),
		BasePeer::TYPE_COLNAME => array (AuditPeer::ID, AuditPeer::REMOTE_IP_ADDRESS, AuditPeer::OBJECT, AuditPeer::OBJECT_KEY, AuditPeer::DOMAIN_ID, AuditPeer::OBJECT_CHANGES, AuditPeer::QUERY, AuditPeer::TYPE, AuditPeer::CREATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'remote_ip_address', 'object', 'object_key', 'domain_id', 'object_changes', 'query', 'type', 'created_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('ID' => 0, 'RemoteIpAddress' => 1, 'Object' => 2, 'ObjectKey' => 3, 'DomainId' => 4, 'ObjectChanges' => 5, 'Query' => 6, 'Type' => 7, 'CreatedAt' => 8, ),
		BasePeer::TYPE_COLNAME => array (AuditPeer::ID => 0, AuditPeer::REMOTE_IP_ADDRESS => 1, AuditPeer::OBJECT => 2, AuditPeer::OBJECT_KEY => 3, AuditPeer::DOMAIN_ID => 4, AuditPeer::OBJECT_CHANGES => 5, AuditPeer::QUERY => 6, AuditPeer::TYPE => 7, AuditPeer::CREATED_AT => 8, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'remote_ip_address' => 1, 'object' => 2, 'object_key' => 3, 'domain_id' => 4, 'object_changes' => 5, 'query' => 6, 'type' => 7, 'created_at' => 8, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'plugins/sfPropelAuditPlugin/lib/model/map/AuditMapBuilder.php';
		return BasePeer::getMapBuilder('plugins.sfPropelAuditPlugin.lib.model.map.AuditMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = AuditPeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(AuditPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(AuditPeer::ID);

		$criteria->addSelectColumn(AuditPeer::REMOTE_IP_ADDRESS);

		$criteria->addSelectColumn(AuditPeer::OBJECT);

		$criteria->addSelectColumn(AuditPeer::OBJECT_KEY);

		$criteria->addSelectColumn(AuditPeer::DOMAIN_ID);

		$criteria->addSelectColumn(AuditPeer::OBJECT_CHANGES);

		$criteria->addSelectColumn(AuditPeer::QUERY);

		$criteria->addSelectColumn(AuditPeer::TYPE);

		$criteria->addSelectColumn(AuditPeer::CREATED_AT);

	}

	const COUNT = 'COUNT(audit.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT audit.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(AuditPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(AuditPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = AuditPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}
	
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = AuditPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return AuditPeer::populateObjects(AuditPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseAuditPeer:doSelectRS:doSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseAuditPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			AuditPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = AuditPeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return AuditPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseAuditPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseAuditPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(AuditPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseAuditPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseAuditPeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseAuditPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseAuditPeer', $values, $con);
      if (false !== $ret)
      {
        return $ret;
      }
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(AuditPeer::ID);
			$selectCriteria->add(AuditPeer::ID, $criteria->remove(AuditPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseAuditPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseAuditPeer', $values, $con, $ret);
    }

    return $ret;
  }

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; 		try {
									$con->begin();
			$affectedRows += BasePeer::doDeleteAll(AuditPeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(AuditPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Audit) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(AuditPeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public static function doValidate(Audit $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(AuditPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(AuditPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(AuditPeer::DATABASE_NAME, AuditPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = AuditPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(AuditPeer::DATABASE_NAME);

		$criteria->add(AuditPeer::ID, $pk);


		$v = AuditPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(AuditPeer::ID, $pks, Criteria::IN);
			$objs = AuditPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseAuditPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'plugins/sfPropelAuditPlugin/lib/model/map/AuditMapBuilder.php';
	Propel::registerMapBuilder('plugins.sfPropelAuditPlugin.lib.model.map.AuditMapBuilder');
}
