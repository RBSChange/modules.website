<?php
class commands_CompileUrlRewriting extends commands_AbstractChangeCommand
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
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Compile URL rewriting ==");
		
		$this->loadFramework();
		$parser = website_urlrewriting_RulesParser::getInstance();
		$parser->compile(true);
		
		$this->quitOk('URL rewriting rules compiled successfully.');
	}
}