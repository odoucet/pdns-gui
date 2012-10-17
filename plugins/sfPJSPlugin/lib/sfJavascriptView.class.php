<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage view
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfJavascriptView.class.php 1059 2008-03-19 16:48:32Z chris $
 */
class sfJavascriptView extends sfPHPView
{
  protected $extension = '.pjs';

  public function configure()
  {
    $response = $this->getContext()->getResponse();

    $response->setParameter($this->moduleName.'_'.$this->actionName.'_layout', false, 'symfony/action/view');
    $response->setContentType('application/x-javascript');

    $this->setTemplate($this->actionName.$this->viewName.$this->getExtension());

    // Set template directory
    if (!$this->directory)
    {
      $this->setDirectory(sfLoader::getTemplateDir($this->moduleName, $this->getTemplate()));
    }
  }
}
