<?php
/**
 * Should be in website module
 */
class commands_CompileBlocks extends commands_AbstractChangeCommand
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
		return "cb";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "compile blocks";
	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	function getParameters($completeParamCount, $params, $options, $current)
	{
		return null;
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Compile blocks ==");
		$this->loadFramework();
		$bs = block_BlockService::getInstance();

		$bs->compileBlocks(array($this, 'showCurrentModule'));
		if ($this->hasError())
		{
			return $this->quitError("All blocks could not be compiled: ".$this->errorCount." errors");
		}
		$this->getParent()->executeCommand("clearWebappCache");
		return $this->quitOk("All blocks compiled successfully.");
	}

	/**
	 * @param String $moduleName
	 * @param Exception $exception
	 */
	function showCurrentModule($moduleName, $exception = null)
	{
		if ( ! is_null($exception) )
		{
			$this->errorMessage("$moduleName: failure\n".$exception->getMessage());
			$this->debugMessage($exception->getTraceAsString());
		}
		else
		{
			$this->message("$moduleName: success.");
		}
	}
}