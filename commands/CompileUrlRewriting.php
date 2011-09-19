<?php
class commands_CompileUrlRewriting extends c_ChangescriptCommand
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
		return "curl";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "compile URL rewriting rules";
	}

	/**
	 * @see c_ChangescriptCommand::getEvents()
	 */
	public function getEvents()
	{
		return array(
			array('target' => 'compile-all'),
		);
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Compile URL rewriting ==");
		
		$this->loadFramework();
		website_UrlRewritingService::getInstance()->buildRules();
		
		$this->quitOk('URL rewriting rules compiled successfully.');
	}
}