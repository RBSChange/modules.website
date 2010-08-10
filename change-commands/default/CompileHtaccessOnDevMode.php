<?php
class commands_CompileHtaccessOnDevMode extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		return "";
	}
	
	/**
	 * @return String
	 */
	function getDescription()
	{
		return "compile htaccess when dev mode activated or de-activated";
	}
	
	function isHidden()
	{
		return true;
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$oldAndCurrent = $this->getParent()->getEnvVar("commands_CompileConfig_oldAndCurrent");
		if ($oldAndCurrent === null)
		{
			return;
		}
		$old = $oldAndCurrent["old"];
		$current = $oldAndCurrent["current"];
		
		if ($old["defines"]["AG_DEVELOPMENT_MODE"] != $current["defines"]["AG_DEVELOPMENT_MODE"])
		{
			$this->loadFramework();
			$this->getParent()->executeCommand("compile-htaccess");
		}
	}
}