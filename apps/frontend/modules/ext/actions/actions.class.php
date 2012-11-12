<?php
/**
 * ExtJS  interface.
 *
 * @ingroup gui
 */
class extActions extends MyActions
{
  /**
   * Executes index action
   */
  public function executeIndex()
  {
    $this->errors = array();
    
    if (!function_exists('json_encode'))
    {
      $this->errors[] = 'PHP JSON support missing - see <a href="http://uk3.php.net/manual/en/book.json.php" target="_blank">http://uk3.php.net/manual/en/book.json.php</a>';
    }
    
    if (function_exists('apache_get_modules')) if (!in_array('mod_rewrite',apache_get_modules()))
    {
      $this->errors[] = 'Apache mod_rewrite module missing';
    }
    
    if ($this->errors)
    {
      $this->setTemplate('missing-mods');
      return sfView::SUCCESS;
    }
    
    sfConfig::set('sf_web_debug', false);
    
    $this->noAjax();
  }

  /**
   * ExtJS application
   */
  public function executeApplication()
  {

  }
  
  /**
   * Audit
   */
  public function executeAudit()
  {
    $this->output = array();
    
    $c = new Criteria();
    // we want domain action too (like deletion)
    //$c->add(AuditPeer::OBJECT, 'Record');
    $c->addDescendingOrderByColumn(AuditPeer::ID);
    
    if ($search = $this->getRequestParameter('search'))
    {
      $c->add(AuditPeer::OBJECT_CHANGES, "%$search%", Criteria::LIKE);
    }
    
    $pager = new MyPager('Audit');
    $pager->setCriteria($c);
    $pager->init();
    
    return $this->renderJson($pager->toStore(true));
  }
  
  /**
   * Error
   * 
   * @todo Implement 'watchdog' table
   */
  public function executeError()
  {
    return $this->renderJson(array("success"=>true,"info"=>"Error logged."));
  }
}
