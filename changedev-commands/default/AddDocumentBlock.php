<?php
class commands_AddDocumentBlock extends commands_AbstractChangedevCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		$usage = "<moduleName> <documentName> <blockType> [icon].
Where <blockType> in:\n";
		foreach ($this->getValidTypes() as $type)
		{
			$usage .= "- ". $type."\n";
		}
		return $usage;
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "initialize a block for some specific work";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		return count($params) >= 3;
	}

	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
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
	function _execute($params, $options)
	{
		$moduleName = $params[0];
		$docName = $params[1];
		$type = $params[2];
		if (!in_array($type, $this->getValidTypes()))
		{
			throw new Exception("Unknown type $type!");
		}

		$this->message("== Add $type document block for $moduleName/$docName ==");
		
		if (isset($params[3]))
		{
			$icon = $params[3];
		}
		else 
		{
			switch ($type)
			{
				case 'list': 
					$icon = 'list-block';
					break;

				case 'detail': 
					$model = f_persistentdocument_PersistentDocumentModel::getInstance($moduleName, $docName);
					$icon = $model->getIcon();
					break;
					
				default:
					$icon = '';
					break;
			}
		}

		$this->loadFramework();
		$blockGenerator = new builder_DocumentBlockGenerator($moduleName);
		$blockGenerator->setAuthor($this->getAuthor());
		$blockGenerator->setDocument($moduleName, $docName);
		$blockGenerator->setBlockType($type);
		$blockGenerator->setBlockIcon($icon);
		$blockPath = $blockGenerator->generate();
		
		if ($blockGenerator->hasTag())
		{
			$this->getParent()->executeCommand("compile-tags");
		}

		$this->quitOk("Block of type $type added for document $docName in module '$moduleName'.
Please now edit ".$blockPath.".");
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