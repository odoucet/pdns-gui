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
 * @subpackage helper
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: PJSHelper.php 1059 2008-03-19 16:48:32Z chris $
 */
function use_pjs($uri, $absolute = false, $options = array())
{
  sfPJSHelper::use_pjs($uri, $absolute, $options);
}

function pjs_path($uri, $absolute = false, $options = array())
{
  return sfPJSHelper::pjs_path($uri, $absolute, $options);
}

class sfPJSHelper
{
  static public function use_pjs($uri, $absolute = false, $options = array())
  {
    use_javascript(self::pjs_path($uri, $absolute, $options));
  }

  static public function pjs_path($uri, $absolute = false, $options = array())
  {
    $urlArguments = '';
    if (false !== $pos = strpos($uri, '?'))
    {
      $urlArguments = '&'.substr($uri, $pos + 1);
      $uri = substr($uri, 0, $pos);
    }

    list($module, $action) = explode('/', $uri);

    $url = sprintf('@javascript?target_module=%s&target_action=%s'.$urlArguments, $module, $action);
    $query_string = isset($options['query_string']) ? '?'.$options['query_string'] : '';

    return url_for($url, $absolute).$query_string;
  }
}
