<?php
/**
 * @author inthause
 * @package framework.block
 */
class block_BlockConfiguration
{	
	/**
	 * @var array
	 */
	protected $configurationArray = array();
	
	/**
	 * @return array
	 */
	public final function getConfigurationParameters()
	{
		return $this->configurationArray;
	}
	
	public final function setConfigurationParameter($name, $value)
	{
		if (is_array($value) && f_util_ArrayUtils::isEmpty($value))
		{
			return;
		}
		$this->configurationArray[$name] = $value;
	}
	
	/**
	 * @param String $parameterName
	 * @param Mixed $defaultValue
	 * @return Mixed
	 */
	public final function getConfigurationParameter($parameterName, $defaultValue = null)
	{
		if (isset($this->configurationArray[$parameterName]))
		{
			return $this->configurationArray[$parameterName];
		}
		return $defaultValue;
	}
	
	/**
	 * @param String $parameterName
	 * @return Boolean
	 */
	public final function hasConfigurationParameter($parameterName)
	{
		return isset($this->configurationArray[$parameterName]);
	}
	
	/**
	 * @param String $parameterName
	 * @return Boolean
	 */
	public final function hasNonEmptyConfigurationParameter($parameterName)
	{
		if (isset($this->configurationArray[$parameterName]))
		{
			$value = $this->configurationArray[$parameterName];
			if (is_array($value) && f_util_ArrayUtils::isNotEmpty($value))
			{
				return true;
			}
			else if (!f_util_StringUtils::isEmpty($this->configurationArray[$parameterName]))
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @return string
	 */
	public function getBlockId()
	{
		return $this->getConfigurationParameter('blockId', '');
	}
	
	/**
	 * @return string
	 */	
	public function getRequestModule()
	{
		return null;
	}
	
	/**
	 * @return string
	 */	
	public function getTemplateModule()
	{
		return null;
	}
	
	/**
	 * @return boolean
	 */
	public function getBeforeAll()
	{
		return false;
	}
	
	/**
	 * @return boolean
	 */
	public function getAfterAll()
	{
		return false;
	}
}