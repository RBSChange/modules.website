<?php
/**
 * @package modules.website.lib.services
 */
class website_TalesAlternateClass implements PHPTAL_Tales
{
	/**
	 * alternateclass: modifier.
	 */
	static public function alternateclass($varName)
	{
		return '++$ctx->'.$varName.' %2 == 1 ? "odd" : "even"';
	}
}