<?php
interface website_tree_INode 
{
	/**
	 * @return array<string, string>
	 */
	function getAttibutes();
	
	
	/**
	 * @return string
	 *
	 */
	function getId();
	
	
	/**
	 * @return boolean
	 */
	function hasChildren();
}
