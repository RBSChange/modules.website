<?php

class website_BlockRichtextInfo extends block_BlockInfo
{
	/**
	 * @var website_BlockRichtextInfo
	 */
	private static $instance;
	
	/**
	 * @return website_BlockRichtextInfo
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new website_BlockRichtextInfo();
		}
		return self::$instance;
	}
	
	protected function __construct()
	{
		parent::__construct(array('type' => 'richtext', 'icon' => 'richtext', 'label' => '&modules.uixul.bo.layout.RichTextBlock'));
	}
}