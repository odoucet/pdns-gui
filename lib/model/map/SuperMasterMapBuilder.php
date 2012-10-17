<?php



class SuperMasterMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.SuperMasterMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('supermasters');
		$tMap->setPhpName('SuperMaster');

		$tMap->setUseIdGenerator(true);

		$tMap->addColumn('IP', 'Ip', 'string', CreoleTypes::VARCHAR, true, 25);

		$tMap->addColumn('NAMESERVER', 'Nameserver', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('ACCOUNT', 'Account', 'string', CreoleTypes::VARCHAR, false, 40);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

	} 
} 