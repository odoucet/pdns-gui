<?php

/**
 * default actions.
 *
 * @package    symfony
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class defaultActions extends MyActions
{
  /**
   * Error404
   * 
   */
  public function executeError404()
  {    
    if ($this->isAjax())
    {
      return $this->renderText(json_encode(
        array("success"=>false,"info"=>"404 Error")));
    }
  }
  
  public function executeNoajax()
  {
    return $this->renderJson(array("success"=>false,"info"=>'No Ajax here.')); 
  }
  
  public function executeAjaxonly()
  {
    return $this->renderText('Ajax requests only.');
  }

}
