<?php
class commands_AddBlock extends commands_AbstractChangedevCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		return "<moduleName> <blockName> [icon] [options]
where options in:
  --no-tag: do not create a tag for the block.";
	}

	function getOptions()
	{
		return array("--no-tag");
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "initialize a block";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		return count($params) >= 2;
	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	function getParameters($completeParamCount, $params, $options)
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
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Add block ==");

		$moduleName = $params[0];
		$blockName = ucfirst($params[1]);
		$icon = isset($params[2]) ? $params[2] : null;
		$createTag = !isset($options["no-tag"]);

		$this->loadFramework();
		$moduleGenerator = new builder_BlockGenerator($moduleName);
		$moduleGenerator->setAuthor($this->getAuthor());
		$blockPath = $moduleGenerator->generateBlock($blockName, $createTag, $icon);
		
		if ($createTag)
		{
			$this->getParent()->executeCommand("compile-tags");
		}
		$this->getParent()->executeCommand("clear-webapp-cache");
		
		$this->quitOk("Block '$blockName' added in module '$moduleName'.
Please now edit ".$blockPath.".");
	}
}