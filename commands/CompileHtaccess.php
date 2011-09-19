<?php
class commands_CompileHtaccess extends c_ChangescriptCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		return "";
	}
	
	function getAlias()
	{
		return "caccess";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "generate htaccess instructions";
	}
	
	/**
	 * @see c_ChangescriptCommand::getEvents()
	 */
	public function getEvents()
	{
		return array(
			array('target' => 'compile-config'),
		);
	}	

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Compile htaccess instructions ==");
		
		$this->loadFramework();
		ApacheService::getInstance()->compileHtaccess();

		$this->quitOk("Htaccess file generated successfully");
	}
}