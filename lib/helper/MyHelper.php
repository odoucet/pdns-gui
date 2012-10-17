<?php

/**
 * Returns Ext.log('string') in 'dev' environment
 * 
 * @param string $string
 *
 * return string
 */
function ext_log($string)
{
  if (SF_ENVIRONMENT == 'dev')
    if (strpos($string,"+"))
      return "if (typeof console != 'undefined') console.log(".$string.");";
    else
      return "if (typeof console != 'undefined') console.log('".$string."');";
  else
    return '';
}



?>
