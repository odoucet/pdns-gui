<?php
/**
 * This file is part of pdns-gui.
 * (c) 2009 Chris Maciejewski.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @file
 * @package  symfony
 */

/**
 * Custom MyPager class
 * 
 * @ingroup customcore
 * @version    SVN: $Id: MyController.class.php 18 2009-05-09 06:56:47Z chris $
 */
class MyPager extends sfPropelPager
{
  private $default_maxPerPage = 10;
  
  /**
   * Overwrites base __construct method and sets $page and $rpp variables
   */
  public function __construct($class, $maxPerPage = null)
  {
    $request = sfContext::getInstance()->getRequest();
    
    if ($request->isXmlHttpRequest())
    {
      if (!$maxPerPage)
      {
        $maxPerPage = $request->getParameter('limit',$this->default_maxPerPage);
        
        if (!preg_match('/^[1-9][0-9]+$/',$maxPerPage))
        {
          $maxPerPage = $this->default_maxPerPage;
        }
      }
      
      $page = $request->getParameter('start',0) / $maxPerPage + 1;
    }
    else
    {
      if (!$maxPerPage)
      {
        $maxPerPage = $this->default_maxPerPage;
        
        if (!preg_match('/^[1-9][0-9]+$/',$maxPerPage))
        {
          $maxPerPage = $this->default_maxPerPage;
        }
      }
      
      $page = $request->getParameter('page', 1);
    }
    
    $this->setPage($page);
    
    parent::__construct($class, $maxPerPage);
  }
  
  /**
   * Returns Pager results in ExtJs store format
   * 
   * @param bool $meta If set to true, returns meta data too
   * 
   * @return array
   */
  public function toStore($meta = true)
  {
    $output = array();
    foreach ($this->getResults() as $r)
    {
      $output[] = $r->toStore();
    }
    
    if ($meta)
    {
      if (isset($output[0]))
      {
        $fields = array_keys($output[0]);
      }
      else
      {
        $class = $this->getClass();
        $obj = new $class;
        
        $fields = array_keys($obj->toStore());
      }
      
      return array(
        "metaData" => array(
          "root"  => $this->getClass(),
          "id"    => $fields[0],
          "fields" => $fields,
          "totalProperty" => "count"
        ),
        $this->getClass() => $output,
        "count" => $this->getNbResults()
      );
    }
    else
    {
      return array("count"=>$this->getNbResults(),$this->getClass()=>$output);
    }
  }
  
}
