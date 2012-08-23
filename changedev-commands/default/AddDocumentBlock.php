<?php
class commands_AddDocumentBlock extends commands_AbstractChangedevCommand
{
	/**
	 * @return string
	 */
	public function getUsage()
	{
		$usage = "<moduleName> <documentName> <blockType>
Where <blockType> in:\n";
		foreach ($this->getValidTypes() as $type)
		{
			$usage .= "- ". $type."\n";
		}
		return $usage;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return "initialize a block for some specific work";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		if (count($params) === 3)
		{
			$moduleName = strtolower($params[0]);
			if (!file_exists(f_util_FileUtils::buildWebeditPath('modules', $moduleName)))
			{
				$this->errorMessage('Module "' . $moduleName . '" doesn\'t exist.');
				return false;
			}
			$docName = strtolower($params[1]);
			if (!file_exists(f_util_FileUtils::buildWebeditPath('modules', $moduleName, 'persistentdocument', $docName . '.xml')))
			{
				$this->errorMessage('Document "' . $moduleName . '/' . $docName . '" doesn\'t exist.');
				return false;
			}
			$type = $params[2];
			if (!in_array($type, $this->getValidTypes()))
			{
				$this->errorMessage('Invalid type "' . $type . '".');
				return false;
			}
			return true;
		}
		elseif (count($params) < 3)
		{
			$this->errorMessage('The 3 arguments are required.');
			return false;
		}
		else
		{
			$this->errorMessage('Too many arguments.');
			return false;
		}
	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	public function getParameters($completeParamCount, $params, $options, $current)
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
		if ($completeParamCount == 1)
		{
			$moduleName = $params[0];
			$docs = array();
			foreach (glob("modules/$moduleName/persistentdocument/*.xml") as $doc)
			{
				$docs[] = basename($doc, ".xml");
			}
			return $docs;
		}
		if ($completeParamCount == 2)
		{
			return $this->getValidTypes();
		}
		return null;
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$moduleName = strtolower($params[0]);
		$docName = strtolower($params[1]);
		$type = $params[2];

		$this->message("== Add $type document block for $moduleName/$docName ==");
		$this->loadFramework();
		$blockGenerator = new builder_DocumentBlockGenerator($moduleName);
		$blockGenerator->setDocument($moduleName, $docName);
		$blockGenerator->setBlockType($type);
		$blockPath = $blockGenerator->generate();

		$this->quitOk("Block of type $type added for document $docName in module '$moduleName'.
Please now edit ".$blockPath.".");
	}
	
	/**
	 * @param string $blockName
	 * @param string $icon
	 */
	protected function getBlocksXmlTpl($blockName, $icon)
	{
		return $this->_getTpl('documentblocks', $this->get, $blockName, $icon);
	}
	
	/**
	 * @return String[]
	 */
	private function getValidTypes()
	{
		$types = array();
		foreach (glob("modules/website/templates/builder/documentblocks/*.tpl") as $template)
		{
			$tplName = basename($template);
			$matches = array();
			if (preg_match("/^Block(.*)Action.php.tpl$/", $tplName, $matches))
			{
				$types[] = f_util_StringUtils::lcfirst($matches[1]);
			}
		}
		return $types;
	}
}