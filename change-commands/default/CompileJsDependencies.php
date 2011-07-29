<?php
class commands_CompileJsDependencies extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 */
	public function getUsage()
	{
		return "";
	}
	
	public function getAlias()
	{
		return "cjs";
	}

	/**
	 * @return String
	 */
	public function getDescription()
	{
		return "compile javascript dependencies files";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$this->message("== Compile javascript dependencies ==");
		
		$this->loadFramework();
		website_JsService::getInstance()->compileScriptDependencies();

		$this->quitOk("Javascript dependencies compiled successfully");
	}
}