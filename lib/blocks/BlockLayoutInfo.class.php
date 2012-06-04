<?php

class website_BlockLayoutInfo extends block_BlockInfo
{
	/**
	 * @var website_BlockLayoutInfo
	 */
	private static $instance;
	
	/**
	 * @return website_BlockLayoutInfo
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new website_BlockLayoutInfo();
		}
		return self::$instance;
	}
	
	protected function __construct()
	{
		parent::__construct(array('type' => 'layout', 'columns' => 2, 'icon' => 'layout-2-columns', 'label' => '&modules.website.bo.blocks.Two-col'));
	}
}