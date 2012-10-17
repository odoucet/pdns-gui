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
 * Custom sfActions class
 * 
 * @ingroup customcore
 * @version    SVN: $Id: MyActions.class.php 3890 2008-12-15 20:39:33Z chris $
 */
class MyActions extends sfActions
{
  /**
   * Sends JSON error or returns sfView::SUCCESS
   */
  public function handleError()
  {
    return $this->sendJsonErrors();
  }
  
  /**
   * Shortcut to getRequest()->getMethod() == sfRequest::GET
   */
  public function isGET()
  {
    return $this->getRequest()->getMethod() == sfRequest::GET;
  }
  
  /**
   * Shortcut to sfRequest()->getMethod() == sfRequest::POST
   */
  public function isPOST()
  {
    return $this->getRequest()->getMethod() == sfRequest::POST;
  }
  
  /**
   * Renders JSON output
   * 
   * @param array $data
   */
  protected function renderJson($data)
  {
    return $this->renderText(json_encode($data));
  }
  
  /**
   * Sends ExtJS store data including MetaData
   * 
   * @param string $class Class name
   * @param array $data
   * 
   * @return string
   */
  public function renderStore($class,$data)
  {
    if (isset($data[0]))
    {
      $fields = array_keys($data[0]);
    }
    else
    {
      $obj = new $class;
      
      $fields = array_keys($obj->toArray(BasePeer::TYPE_FIELDNAME));
    }
    
    return $this->renderJson(array(
      "metaData" => array(
        "root"   => $class,
        "id"     => $fields[0],
        "fields" => $fields,
        "totalProperty" => "count"
      ),
      $class  => $data,
      "count" => count($data)
    ));
  }

 
  /**
   * Renders {success: false, info: No Ajax here}
   * 
   * @return string
   */
  protected function noAjax()
  {
    if ($this->isAjax())
    {
      $this->forward('default','noajax');
    }
  }
  
  /**
   * Ajax only
   * 
   * Redirects to "JavaScript error page" if 'frontend' and is not Ajax
   */
  protected function ajaxOnly()
  {
    if (!$this->isAjax())
    {
      $this->forward('default','ajaxonly');
    }
  }
  
  /**
   * Shortcut for $this->getRequest()->isXmlHttpRequest()
   * 
   * @return bool True or false
   */
  protected function isAjax()
  {
    if ($this->getRequest()->isXmlHttpRequest())
      return true;
    else
      return false;
  }
  
  /**
   * Sends all request error (eg. form validation) as JSON string
   * 
   */
  protected function sendJsonErrors()
  {
    $errors = array();
    foreach ($this->getRequest()->getErrors() as $key => $e)
    {
      $errors[$key] = $e;
    }
    return $this->renderText(
      json_encode(array('success'=>false,"errors"=>$errors)));
  }


  /**
   * Checks if given partial exists.
   *
   * @return  True of false.
   */
  protected function partialExists($name)
  {
    $directory = $this->getContext()->getModuleDirectory();
    if (is_readable($directory . DIRECTORY_SEPARATOR ."templates".
     DIRECTORY_SEPARATOR ."_". $name .".php"))
    return true;
    else return false;
  } 

}
?>
