<?php
interface f_mvc_Request
{
	/**
	 * @param string $name
	 * @param string $defaultValue
	 * @return string the value of the parameter or $defaultValue
	 */
	function getParameter($name, $defaultValue = null);
	
	/**
	 * @return array<String, array<String>>
	 */
	function getParameters();
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	function hasParameter($name);
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	function hasNonEmptyParameter($name);
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	function setAttribute($name, $value);
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	function getAttribute($name, $defaultValue = null);
	
	/**
	 * @return array<String, mixed>
	 */
	function getAttributes();
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	function hasAttribute($name);
}