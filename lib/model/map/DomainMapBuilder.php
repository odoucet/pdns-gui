<?php



class DomainMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.DomainMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('domains');
		$tMap->setPhpName('Domain');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('MASTER', 'Master', 'string', CreoleTypes::VARCHAR, false, 20);

		$tMap->addColumn('LAST_CHECK', 'LastCheck', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('TYPE', 'Type', 'string', CreoleTypes::VARCHAR, true, 6);

		$tMap->addColumn('NOTIFIED_SERIAL', 'NotifiedSerial', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('ACCOUNT', 'Account', 'string', CreoleTypes::VARCHAR, false, 40);

	} 
} 