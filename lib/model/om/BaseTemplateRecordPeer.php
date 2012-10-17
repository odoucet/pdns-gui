<?php


abstract class BaseTemplateRecordPeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'template_record';

	
	const CLASS_DEFAULT = 'lib.model.TemplateRecord';

	
	const NUM_COLUMNS = 7;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'template_record.ID';

	
	const TEMPLATE_ID = 'template_record.TEMPLATE_ID';

	
	const NAME = 'template_record.NAME';

	
	const TYPE = 'template_record.TYPE';

	
	const CONTENT = 'template_record.CONTENT';

	
	const TTL = 'template_record.TTL';

	
	const PRIO = 'template_record.PRIO';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'TemplateId', 'Name', 'Type', 'Content', 'Ttl', 'Prio', ),
		BasePeer::TYPE_COLNAME => array (TemplateRecordPeer::ID, TemplateRecordPeer::TEMPLATE_ID, TemplateRecordPeer::NAME, TemplateRecordPeer::TYPE, TemplateRecordPeer::CONTENT, TemplateRecordPeer::TTL, TemplateRecordPeer::PRIO, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'template_id', 'name', 'type', 'content', 'ttl', 'prio', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'TemplateId' => 1, 'Name' => 2, 'Type' => 3, 'Content' => 4, 'Ttl' => 5, 'Prio' => 6, ),
		BasePeer::TYPE_COLNAME => array (TemplateRecordPeer::ID => 0, TemplateRecordPeer::TEMPLATE_ID => 1, TemplateRecordPeer::NAME => 2, TemplateRecordPeer::TYPE => 3, TemplateRecordPeer::CONTENT => 4, TemplateRecordPeer::TTL => 5, TemplateRecordPeer::PRIO => 6, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'template_id' => 1, 'name' => 2, 'type' => 3, 'content' => 4, 'ttl' => 5, 'prio' => 6, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
	);

	
	public static function getMapBuilder()
	{
		include_once 'lib/model/map/TemplateRecordMapBuilder.php';
		return BasePeer::getMapBuilder('lib.model.map.TemplateRecordMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = TemplateRecordPeer::getTableMap();
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
		return str_replace(TemplateRecordPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(TemplateRecordPeer::ID);

		$criteria->addSelectColumn(TemplateRecordPeer::TEMPLATE_ID);

		$criteria->addSelectColumn(TemplateRecordPeer::NAME);

		$criteria->addSelectColumn(TemplateRecordPeer::TYPE);

		$criteria->addSelectColumn(TemplateRecordPeer::CONTENT);

		$criteria->addSelectColumn(TemplateRecordPeer::TTL);

		$criteria->addSelectColumn(TemplateRecordPeer::PRIO);

	}

	const COUNT = 'COUNT(template_record.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT template_record.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = TemplateRecordPeer::doSelectRS($criteria, $con);
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
		$objects = TemplateRecordPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return TemplateRecordPeer::populateObjects(TemplateRecordPeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doSelectRS:doSelectRS') as $callable)
    {
      call_user_func($callable, 'BaseTemplateRecordPeer', $criteria, $con);
    }


		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			TemplateRecordPeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = TemplateRecordPeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

	
	public static function doCountJoinTemplate(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(TemplateRecordPeer::TEMPLATE_ID, TemplatePeer::ID);

		$rs = TemplateRecordPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinTemplate(Criteria $c, $con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doSelectJoin:doSelectJoin') as $callable)
    {
      call_user_func($callable, 'BaseTemplateRecordPeer', $c, $con);
    }


		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TemplateRecordPeer::addSelectColumns($c);
		$startcol = (TemplateRecordPeer::NUM_COLUMNS - TemplateRecordPeer::NUM_LAZY_LOAD_COLUMNS) + 1;
		TemplatePeer::addSelectColumns($c);

		$c->addJoin(TemplateRecordPeer::TEMPLATE_ID, TemplatePeer::ID);
		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = TemplateRecordPeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);

			$omClass = TemplatePeer::getOMClass();

			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol);

			$newObject = true;
			foreach($results as $temp_obj1) {
				$temp_obj2 = $temp_obj1->getTemplate(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
										$temp_obj2->addTemplateRecord($obj1); 					break;
				}
			}
			if ($newObject) {
				$obj2->initTemplateRecords();
				$obj2->addTemplateRecord($obj1); 			}
			$results[] = $obj1;
		}
		return $results;
	}


	
	public static function doCountJoinAll(Criteria $criteria, $distinct = false, $con = null)
	{
		$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(TemplateRecordPeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$criteria->addJoin(TemplateRecordPeer::TEMPLATE_ID, TemplatePeer::ID);

		$rs = TemplateRecordPeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}


	
	public static function doSelectJoinAll(Criteria $c, $con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doSelectJoinAll:doSelectJoinAll') as $callable)
    {
      call_user_func($callable, 'BaseTemplateRecordPeer', $c, $con);
    }


		$c = clone $c;

				if ($c->getDbName() == Propel::getDefaultDB()) {
			$c->setDbName(self::DATABASE_NAME);
		}

		TemplateRecordPeer::addSelectColumns($c);
		$startcol2 = (TemplateRecordPeer::NUM_COLUMNS - TemplateRecordPeer::NUM_LAZY_LOAD_COLUMNS) + 1;

		TemplatePeer::addSelectColumns($c);
		$startcol3 = $startcol2 + TemplatePeer::NUM_COLUMNS;

		$c->addJoin(TemplateRecordPeer::TEMPLATE_ID, TemplatePeer::ID);

		$rs = BasePeer::doSelect($c, $con);
		$results = array();

		while($rs->next()) {

			$omClass = TemplateRecordPeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj1 = new $cls();
			$obj1->hydrate($rs);


					
			$omClass = TemplatePeer::getOMClass();


			$cls = Propel::import($omClass);
			$obj2 = new $cls();
			$obj2->hydrate($rs, $startcol2);

			$newObject = true;
			for ($j=0, $resCount=count($results); $j < $resCount; $j++) {
				$temp_obj1 = $results[$j];
				$temp_obj2 = $temp_obj1->getTemplate(); 				if ($temp_obj2->getPrimaryKey() === $obj2->getPrimaryKey()) {
					$newObject = false;
					$temp_obj2->addTemplateRecord($obj1); 					break;
				}
			}

			if ($newObject) {
				$obj2->initTemplateRecords();
				$obj2->addTemplateRecord($obj1);
			}

			$results[] = $obj1;
		}
		return $results;
	}

	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return TemplateRecordPeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doInsert:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseTemplateRecordPeer', $values, $con);
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

		$criteria->remove(TemplateRecordPeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		
    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doInsert:post') as $callable)
    {
      call_user_func($callable, 'BaseTemplateRecordPeer', $values, $con, $pk);
    }

    return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doUpdate:pre') as $callable)
    {
      $ret = call_user_func($callable, 'BaseTemplateRecordPeer', $values, $con);
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
			$comparison = $criteria->getComparison(TemplateRecordPeer::ID);
			$selectCriteria->add(TemplateRecordPeer::ID, $criteria->remove(TemplateRecordPeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);
	

    foreach (sfMixer::getCallables('BaseTemplateRecordPeer:doUpdate:post') as $callable)
    {
      call_user_func($callable, 'BaseTemplateRecordPeer', $values, $con, $ret);
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
			$affectedRows += BasePeer::doDeleteAll(TemplateRecordPeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(TemplateRecordPeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof TemplateRecord) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(TemplateRecordPeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(TemplateRecord $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(TemplateRecordPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(TemplateRecordPeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(TemplateRecordPeer::DATABASE_NAME, TemplateRecordPeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = TemplateRecordPeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(TemplateRecordPeer::DATABASE_NAME);

		$criteria->add(TemplateRecordPeer::ID, $pk);


		$v = TemplateRecordPeer::doSelect($criteria, $con);

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
			$criteria->add(TemplateRecordPeer::ID, $pks, Criteria::IN);
			$objs = TemplateRecordPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseTemplateRecordPeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			require_once 'lib/model/map/TemplateRecordMapBuilder.php';
	Propel::registerMapBuilder('lib.model.map.TemplateRecordMapBuilder');
}
