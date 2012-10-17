<?php
/*
 * This file is part of the sfErrorHandler plugin
 * (c) 2008-2009 Lee Bolding <lee@php.uk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfHardenedRenderingFilter is a replacement for the sfRenderingFilter
 * The sfHardenedRendderingFilter dynamically replaces the sfRenderingFilter for an app
 * when the sfErrorHandler is one of the enabled modules
 *
 * @package    sfErrorHandlerPlugin
 * @author     Lee Bolding <lee@php.uk.com>
 * @version    SVN: $Id$
 */

if (in_array('sfErrorHandler', sfConfig::get('sf_enabled_modules', array())))
{
  sfMixer::register('sfException:printStackTrace',   array('sfErrorHandler', 'logExpection'));
  
  sfConfig::set('sf_rendering_filter', array('sfHardenedRenderingFilter', array()));
}
