<?php



class AuditMapBuilder {

	
	const CLASS_NAME = 'plugins.sfPropelAuditPlugin.lib.model.map.AuditMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('audit');
		$tMap->setPhpName('Audit');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'ID', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('REMOTE_IP_ADDRESS', 'RemoteIpAddress', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('OBJECT', 'Object', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('OBJECT_KEY', 'ObjectKey', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('DOMAIN_ID', 'DomainId', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('OBJECT_CHANGES', 'ObjectChanges', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('QUERY', 'Query', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('TYPE', 'Type', 'string', CreoleTypes::VARCHAR, false, 255);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, false, null);

	} 
} 