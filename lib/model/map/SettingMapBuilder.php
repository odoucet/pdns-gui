<?php



class SettingMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.SettingMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('setting');
		$tMap->setPhpName('Setting');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('NAME', 'Name', 'string', CreoleTypes::VARCHAR, true, 255);

		$tMap->addColumn('VALUE', 'Value', 'string', CreoleTypes::LONGVARCHAR, false, null);

	} 
} 