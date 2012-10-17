<?php
/*
 * This file is part of the sfErrorHandler plugin
 * (c) 2008-2009 Lee Bolding <lee@php.uk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfLegacyErrorException represents a PHP error which has been rethrown as an
 * exception by sfErrorHanlder.
 *
 * @package    sfErrorHandlerPlugin
 * @see        sfErrorHandler
 * @author     Lee Bolding <lee@php.uk.com>
 * @version    SVN: $Id$
 */

class sfLegacyErrorException extends sfException
{
  private $context = null;

  public function __construct($code, $message, $file, $line, $context = null)
  {
    parent::__construct($message, $code);

    $this->context  = $context;
    $this->file     = $file;
    $this->line     = $line;
  }
}

?>
