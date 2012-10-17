<?php

/**
 * Subclass for representing a row from the 'template_record' table.
 *
 * 
 *
 * @package lib.model
 */ 
class TemplateRecord extends BaseTemplateRecord
{
}


sfPropelBehavior::add('TemplateRecord', array('audit'));
