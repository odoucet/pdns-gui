<?php

/**
 * Domain actions.
 *
 * @package    symfony
 * @subpackage host
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class domainActions extends MyActions
{
  /**
   * Search and replace
   */
  public function executeReplace()
  {
    $c = new Criteria();
    $c->add(RecordPeer::TYPE, $this->getRequestParameter('search_type'));
    $c->add(RecordPeer::CONTENT, $this->getRequestParameter('search_content'));
    
    $replaced = '';
    
    foreach (RecordPeer::doSelect($c) as $record)
    {
      $replaced.= $record->getDomain()->getName()." ".$record->getType()." ".$record->getContent().' => ';
      $replaced.= $this->getRequestParameter('replace_type')." ";
      $replaced.= $this->getRequestParameter('replace_content')."<br/>";
      
      $record->setType($this->getRequestParameter('replace_type'));
      $record->setContent($this->getRequestParameter('replace_content'));
      $record->save();
    }
    
    if ($replaced)
    {
      $info = "The following records has been replaced:<br/>".$replaced;
    }
    else
    {
      $info = "No replacements made.";
    }
    
    return $this->renderJson(array("success"=>true,"info"=>$info));
  }
  
  /**
   * Commits all changes to zone records (updates SOA serial)
   */
  public function executeCommit()
  {
    if ($this->commited)
    {
      $info = "Commited changes for the following domains:<br/>".implode("<br/>",$this->commited);
    }
    else
    {
      $info = "No changes to commit.";
    }
    
    return $this->renderJson(array("success"=>true,"info"=>$info));
  }
  
  public function validateCommit()
  {
    $this->commited = MyTools::commit();
    
    if (!is_array($this->commited))
    {
      $this->getRequest()->setError('commit',$this->commited);
      
      return false;
    }
    
    return true;
  }

  /**
   * List
   */
  public function executeList()
  {
    $this->output = array();
    
    $c = new Criteria();
    $c->addAscendingOrderByColumn(DomainPeer::NAME);
    
    foreach (DomainPeer::doSelect($c) as $domain)
    {
      $data = $domain->toArray(BasePeer::TYPE_FIELDNAME);
      $data['needs_commit'] = $domain->needsComit();
      $data['records'] = array();
      
      $this->output[] = $data;
    }
    
    if ($this->isAjax())
    {
      return $this->renderStore('Domain',$this->output);
    }
  }
  
  /**
   * List records
   */
  public function executeListrecords()
  {
    $domain = DomainPeer::retrieveByPK($this->getRequestParameter('id'));
    
    $this->forward404Unless($domain);
    
    $c = new Criteria();
    $c->addDescendingOrderByColumn(RecordPeer::TYPE);
    $c->addAscendingOrderByColumn(RecordPeer::NAME);
    
    $output = array();
    
    foreach ($domain->getRecords($c) as $record)
    {
      $record_data = $record->toArray(BasePeer::TYPE_FIELDNAME);
      
      $record_data['needs_commit'] = $record->needsCommit();
      
      $output[] = $record_data;
    }
    
    if ($this->isAjax())
    {
      return $this->renderJson(array('Record'=>$output));
    }
  }
  
  /**
   * Add
   */
  public function executeAdd()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST only."));
    }
    else
    {
      $name = $this->getRequestParameter('name');
      
      $domain = new Domain();
      $domain->setName($name);
      $domain->setType($this->template->getType());
      $domain->save();
      
      foreach ($this->template->getTemplateRecords() as $tr)
      {
        
        $record = new Record();
        $record->setDomainId($domain->getId());
        $record->setName(str_replace("%DOMAIN%",$name,$tr->getName()));
        $record->setType($tr->getType());
        
        if ($tr->getType() == 'SOA')
        {
          $content = str_replace("%DOMAIN%",$name,$tr->getContent());
          $content = str_replace("%SERIAL%",date("Ymd")."01",$content);
    	}
        elseif ($tr->getType() == 'NS' || $tr->getType() == 'MX')
    	{
    	  $content = str_replace("%DOMAIN%",$name,$tr->getContent());
    	}
        else
        {
          $content = $tr->getContent();
        }
        
        $record->setContent($content);
        $record->setTtl($tr->getTtl());
        $record->setPrio($tr->getPrio());
        $record->save();
      }
      
      return $this->renderJson(array("success"=>true,"info"=>"Domain added."));
    }
  }
  
  public function validateAdd()
  {
    if ($this->isPOST())
    {
      $c = new Criteria();
      $c->add(DomainPeer::NAME, $this->getRequestParameter('name'));
      
      if (DomainPeer::doSelectOne($c))
      {
        $this->getRequest()->setError('name','This name is already in use.');
        return false;
      }
      
      if (!$this->template = TemplatePeer::retrieveByPK($this->getRequestParameter('template_id')))
      {
        $this->getRequest()->setError('template_id','Invalid template id.');
        return false;
      }
    }
    
    return true;
  }
  
  /**
   * Edit
   */
  public function executeEdit()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST only."));
    }
    else
    {
      $ids = array();
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        if (!$record = RecordPeer::retrieveByPK($data['id']))
        {
          $record = new Record();
          $record->setDomainId($this->domain->getId());
        }
        
        $record->setName($data['name']);
        $record->setType($data['type']);
        $record->setContent($data['content']);
        $record->setTtl($data['ttl']);
        
        if ($data['type'] == 'MX' || $data['type'] == 'SRV')
        {
          $record->setPrio($data['prio']);
        }
        
        $record->save();
        
        $ids[] = $record->getId();
      }
      
      $c = new Criteria();
      $c->add(RecordPeer::DOMAIN_ID, $this->domain->getId());
      $c->add(RecordPeer::ID, $ids, Criteria::NOT_IN);
      
      foreach (RecordPeer::doSelect($c) as $record)
      {
        $record->delete();
      }
      
      return $this->renderJson(array("success"=>true,"info"=>"Domain updated."));
    }
  }
  
  public function validateEdit()
  {
    if ($this->isPOST())
    {
      if (!$this->domain = DomainPeer::retrieveByPK($this->getRequestParameter('id')))
      {
        $this->getRequest()->setError('id','Invalid domain id.');
        return false;
      }
      
      if (!is_array($this->getRequestParameter('record')))
      {
        $this->getRequest()->setError('record','record[] needs to be an array.');
        return false;
      }
      
      $i = 1;
    
      $SOA_count = 0;
      $NS_count = 0;
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        
        if (!isset($data['name']) || !isset($data['type']) || !isset($data['content']) 
          || !isset($data['ttl']))
        {
          $this->getRequest()->setError('record',"Row $i: some data is missing.");
          return false;
        }
        
        if (!$data['name'])
        {
          $this->getRequest()->setError('record',"Row $i: name can't be left blank.");
          return false;
        }
        
        if (!preg_match('/^([a-zA-Z0-9\*]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/',$data['name']) || strlen($data['name']) > 253)
        {
          $this->getRequest()->setError('record',"Row $i: invalid name (only letters, digits, underscore and hyphen allowed).");
          return false;
        }
        
        if (!array_key_exists($data['type'],sfConfig::get('app_record_type',array())))
        {
          $this->getRequest()->setError('record',"Row $i: invalid record type.");
          return false;
        }
        
        switch ($data['type'])
        {
          case 'SOA':
            if (!preg_match('/^[a-z0-9\.\-_]+\s[a-z0-9\.\-_]+\s[0-9\s]+/',$data['content']))
            {
              $this->getRequest()->setError('record',"Row $i: invalid SOA content.");
              return false;
            }
            break;
          case 'NS':
            if (!preg_match('/^[a-z0-9\.\-_]+$/',$data['content']))
            {
              $this->getRequest()->setError('record',"Row $i: invalid NS content.");
              return false;
            }
            break;
        }
        
        if (!preg_match('/^[0-9]+$/',$data['ttl']))
        {
          $this->getRequest()->setError('record',"Row $i: TTL has to be a number.");
          return false;
        }
        
        if ($data['ttl'] < 5 || $data['ttl'] > 86400)
        {
          $this->getRequest()->setError('record',"Row $i: TTL has to be in a range of 5-86400.");
          return false;
        }
        
        
        if ($data['type'] == 'MX' || $data['type'] == 'SRV')
        {
          
          if (!preg_match('/^[0-9]+$/',$data['prio']))
          {
            $this->getRequest()->setError('record',"Row $i: Prio has to be a number.");
            return false;
          }
          
          if ($data['prio'] < 0 || $data['prio'] > 1000)
          {
            $this->getRequest()->setError('record',"Row $i: Prio has to be in a range of 0-1000.");
            return false;
          }
        }
        
        if (!$data['content'])
        {
          $this->getRequest()->setError('record',"Row $i: Content can't be left blank.");
          return false;
        }
        
        if ($data['type'] == 'SOA') $SOA_count++;
        if ($data['type'] == 'NS') $NS_count++;
        
        $i++;
      }
      
      if ($SOA_count !== 1)
      {
        $this->getRequest()->setError('record',"Only one SOA record allowed.");
        return false;
      }
      
      if ($NS_count < 1 || $NS_count > 10)
      {
        $this->getRequest()->setError('record',"Number of NS records should be in a range of 1-10.");
        return false;
      }
    }
    
    return true;
  }
  

  /**
   * Delete
   */
  public function executeDelete()
  {
    $c = new Criteria();
    $c->add(RecordPeer::DOMAIN_ID, $this->domain->getId());
    
    RecordPeer::doDelete($c);
    $this->domain->delete();
    
    return $this->renderJson(array("success"=>true,"info"=>"Domain deleted."));
  }
  
  public function validateDelete()
  {
    if (!$this->domain = DomainPeer::retrieveByPK($this->getRequestParameter('id')))
    {
      $this->getRequest()->setError('id','Invalid domain id');
      return false;
    }
    
    return true;
  }
}
