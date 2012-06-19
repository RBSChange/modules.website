<?php
class commands_AddBlock extends c_ChangescriptCommand
{
	/**
	 * @return string
	 */
	function getUsage()
	{
		return "<moduleName> <blockName> [icon] [options]
where options in:
  --tag: create a tag for the block.";
	}

	function getOptions()
	{
		return array("--no-tag");
	}

	/**
	 * @return string
	 */
	function getDescription()
	{
		return "initialize a block";
	}

	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		return count($params) >= 2;
	}

	/**
	 * @param integer $completeParamCount the parameters that are already complete in the command line
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return string[] or null
	 */
	function getParameters($completeParamCount, $params, $options, $current)
	{
		if ($completeParamCount == 0)
		{
			$components = array();
			foreach (glob("modules/*", GLOB_ONLYDIR) as $module)
			{
				$components[] = basename($module);
			}
			return $components;
		}
		return null;
	}

	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Add block ==");

		$moduleName = $params[0];
		$blockName = ucfirst($params[1]);
		$icon = isset($params[2]) ? $params[2] : null;
		$createTag = isset($options["tag"]);

		$this->loadFramework();
		$moduleGenerator = new builder_BlockGenerator($moduleName);
		$moduleGenerator->setAuthor($this->getAuthor());
		$blockPath = $moduleGenerator->generateBlock($blockName, $createTag, $icon);
		
		if ($createTag)
		{
			$this->executeCommand("compile-tags");
		}
		$this->executeCommand("compile-locales", array($moduleName));
				
		$this->quitOk("Block '$blockName' added in module '$moduleName'.
Please now edit ".$blockPath.".");
	}
}