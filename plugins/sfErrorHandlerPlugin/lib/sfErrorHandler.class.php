<?php
/*
 * This file is part of the sfErrorHandler plugin
 * (c) 2008-2009 Lee Bolding <lee@php.uk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfErrorHandler is the class that replaces the standard PHP error handler.
 * whenever an error is caught by the error handler, it is rethrown as an
 * sfLegacyErrorException - which extends sfException.
 *
 * @package    sfErrorHandlerPlugin
 * @see        sfLegacyErrorException
 * @author     Lee Bolding <lee@php.uk.com>
 * @version    SVN: $Id$
 */

class sfErrorHandler
{
  private static $instance = null;
  protected static $exception = null;
  private static $filtered_errors = array(E_WARNING, E_NOTICE, E_STRICT);

  private function __construct() {
    set_error_handler(array(__CLASS__, 'error_handler'));
  }

  public function destruct()
  {
    // restore error handler stack 
    restore_error_handler();
  }

  // factory method to return only instance of class
  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new sfErrorHandler();
    }
        
    return self::$instance;
  }

  public static function error_handler($code, $message, $file, $line, $context = null)
  {
    // if error has been supressed with an @
    if (error_reporting() == 0) {
        return;
    }
    
    // instantiate a LegacyErrorException ...
    $le = new sfLegacyErrorException($code, $message, $file, $line, $context);
    // now throw the exception
    throw $le;
  }
  
  
  // can't seem to throw an exception here due to always receiving an error :
  // Exception thrown without a stack frame in Unknown on line 0

  public static function fatal_error_handler($buffer)
  {
    $error = error_get_last();
    $output = '';
    
    // this should never happen, but if $error isn't an array, return false
    if (!is_array($error)) return false;
    
    // we can't specify a bitmask for error logging to ob_start, so we have
    // to manually filter... (we don't want anything that the error_handler can handle)
    if (in_array($error['type'], self::$filtered_errors) || $error['type'] >= E_USER_ERROR) return false;

    $is_ajax = sfContext::getInstance()->getRequest()->isXmlHttpRequest();
  
    $error_msg = '';
    foreach ($error as $info => $string)
    {
      // at the moment, pretty basic, but better than nothing, eh?
      $error_msg.= "{$info}: {$string}<br/>";
      //self::setFatalException(new Exception($error['message'], $error['type']));
      //throw new Exception($error['message'], $error['type']);
    }
    
    if (!sfConfig::get('sf_debug'))
    {
      if ($is_ajax)
      {
        $output = '500 Server error';
      }
      else
      {
        $output = file_get_contents(sfConfig::get('sf_plugins_dir').'/sfErrorHandlerPlugin/errors/error500.html');
      }
    }
    else
    {
      $output = $error_msg;
    }
    
    if ($is_ajax)
    {
      $output = json_encode(array("sfException"=>$output));
    }
    
    return $output;
  }
  
  /**
   * Returns true if we've thrown an exception
   *
   * This is a hack needed because PHP does not allow to throw exceptions after throwing class has been destroyed
   *
   * @return boolean
   */
  static public function hasFatalException()
  {
    return !is_null(self::$exception);
  }

  /**
   * Gets the exception if one was thrown 
   *
   * This is a hack needed because PHP does not allow to throw exceptions after throwing class has been destroyed
   *
   * @return Exception
   */
  static public function getFatalException()
  {
    return self::$exception;
  }

  /**
   * Sets an exception thrown by the fatal_error_handler
   *
   * This is a hack needed because PHP does not allow to throw exceptions after the throwing class has been destroyed
   *
   * @param Exception $e The exception thrown by fatal_error_handler
   */
  static public function setFatalException(Exception $e)
  {
    if (is_null(self::$exception))
    {
      self::$exception = $e;
    }
  }
  
  /**
   * Logs sfError into DB
   */
  static public function logExpection($caller, $exception)
  {
    $context = sfContext::getInstance();
    
    if ($context->getRequest()->isXmlHttpRequest())
    {

      echo json_encode(array("sfException"=>$exception->getMessage()));
      
      return true;
    }
  }
}

?>
