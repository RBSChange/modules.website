<?php
// change:spaceless
// 
// <div change:spaceless""></div>

/**
 * @package website.lib.phptal
 */
class PHPTAL_Php_Attribute_CHANGE_spaceless extends ChangeTalAttribute
{
	/**
	 * @see ChangeTalAttribute::start()
	 */
	public function start()
	{
		$children = $this->tag->children;
		foreach ($children as $index => $child)
		{
			if ($child instanceof PHPTAL_Php_Text && trim($child->node->getValue()) === '')
			{
				//Framework::info(__METHOD__ . ' remove whitespace node ' . $index . ' ' . get_class($child));
				unset($children[$index]);
			}
		}
		$this->tag->children = array_values($children);
	}
}