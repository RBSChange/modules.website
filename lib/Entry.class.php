<?php

/**
 * A topic entry.
 *
 * @author  INTbonjF
 * @package modules_website
 * @date    09/02/2006
 */
class website_lib_Entry {
	public $label;
	public $id;
	public $attributes = array();
	public $type;
	public $componentType;
	public $pageRef;
	public $level = 0;
	public $href;
}