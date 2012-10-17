<?php

/**
 * Subclass for performing query and update operations on the 'setting' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SettingPeer extends BaseSettingPeer
{
  /**
   * Gets value for given key name
   *
   * @param string $name
   * @param mixed $default
   * 
   * @return string or default value
   */
  static public function getValue($name,$default = null)
  {
    $c = new Criteria();
    $c->add(SettingPeer::NAME, $name);
    
    if ($setting = SettingPeer::doSelectOne($c))
    {
      return $setting->getValue();
    }
    else
    {
      return $default;
    }
  }
  
  /**
   * Sets value for given key name
   *
   * @param string $name
   * @param mixed $value
   * 
   */
  static public function setValue($name,$value)
  {
    $c = new Criteria();
    $c->add(SettingPeer::NAME, $name);
    
    if (!$setting = SettingPeer::doSelectOne($c))
    {
      $setting = new Setting();
      $setting->setName($name);
    }
    
    $setting->setValue($value);
    $setting->save();
  }
}
