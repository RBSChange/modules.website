<?php
class block_BlockService extends BaseService
{
	/**
	 * @var block_BlockService
	 */
	private static $instance;

	/**
	 * Stores the blocks label in an associative array; the key is the block name
	 * (ie. modules_news/newsList).
	 * @var Array
	 */
	private $blockLabelArray = array();

	/**
	 * Stores the declared blocks by module in an associative array; the key is
	 * the module name and the value is an array of block names.
	 * @var Array
	 */
	private $moduleBlockArray = array();

	/**
	 * Stores the <block/> elements of an XML file; the key is the block name
	 * and the value is a DOMElement.
	 * @var Array
	 */
	private $blockElements = array();

	/**
	 * @return block_BlockService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * Returns the label of the block named $blockName.
	 *
	 * @param String $blockName
	 * @return String
	 */
	public function getBlockLabelFromBlockName($blockName)
	{
		return $this->getBlockAttribute($blockName, 'label');
	}

	/**
	 * Returns the icon of the block named $blockName.
	 *
	 * @param String $blockName
	 * @return String
	 */
	public function getBlockIconFromBlockName($blockName)
	{
		return $this->getBlockAttribute($blockName, 'icon');
	}

	/**
	 * @param String $blockName
	 * @return DomElement or null if blockInfo could not be founded
	 */
	private function getBlockAttribute($blockName, $attributeName)
	{
		$blockInfo = $this->getBlockInfo($blockName);
		if ($blockInfo !== null)
		{
			$methodName = 'get' . ucfirst($attributeName);
			if (f_util_ClassUtils::methodExists($blockInfo, $methodName))
			{
				return f_util_ClassUtils::callMethodOn($blockInfo, $methodName);
			}
			return $blockInfo->getAttribute($attributeName);
		}
		return null;
	}

	/**
	 * @param String $moduleName
	 * @param Array Array of defined blocks in the given module (block names as strings).
	 */
	public function getDeclaredBlocksForModule($moduleName)
	{
		if ( ! isset($this->moduleBlockArray[$moduleName]) )
		{
			$filePath = $this->getBlockListPhpFilepath($moduleName);
			if (is_readable($filePath))
			{
				$blockIdArray = null;
				include $filePath;
				$this->moduleBlockArray[$moduleName] = $blockIdArray; // declared in the included file.
			}
			else
			{
				$this->moduleBlockArray[$moduleName] = array();
				if (Framework::isDebugEnabled())
				{
					Framework::debug(__METHOD__." module \"$moduleName\" seems to have no block, or they have not been compiled.");
				}
			}
		}
		return $this->moduleBlockArray[$moduleName];
	}

	/**
	 * @param String $blockName
	 * @return String
	 *
	 * @throws BlockException
	 */
	public function getBlockClassNameFromBlockName($blockName)
	{
		$matches = null;
		if (preg_match('/modules_([\w\d]+)_([\w\d]+)/', $blockName, $matches))
		{
			return $matches[1].'_Block'.ucfirst($matches[2]).'Action';
		}
		throw new BlockException('Could not determine class name for block: "'.$blockName.'". Wrong block name?');
	}

	/**
	 * @param String $blockName
	 * @param String $packageName
	 * @return block_BlockInfo
	 */
	public function getBlockInfo($blockName, $packageName = null)
	{
		if ($this->isSpecialBlock($blockName) && !is_null($packageName))
		{
			$className = $this->getSpecialBlockInfoClassNameFromBlockName($blockName, $packageName);
		}
		else
		{
			$className = $this->getBlockInfoClassNameFromBlockName($blockName);
		}
		if ($className !== null && f_util_ClassUtils::classExists($className))
		{
			return f_util_ClassUtils::callMethod($className, 'getInstance');
		}
		return null;
	}

	/**
	 * Returns the module that declares the given block, identified by $blockName.
	 *
	 * @param String $blockName
	 * @return String
	 *
	 * @throws BlockException
	 */
	public function getDeclaringModuleForBlock($blockName)
	{
		$matches = null;
		if (preg_match('/modules_([\w\d]+)_([\w\d]+)/', $blockName, $matches))
		{
			return $matches[1];
		}
		throw new BlockException("Could not determine the module that declares the block \"$blockName\".");
	}

	/**
	 * @param String $name
	 * @param String $type
	 * @param Integer $minOccurs
	 * @param Integer $maxOccurs
	 * @param String $defaultValue
	 * @param String $constraints
	 * @return block_BlockPropertyInfo
	 */
	public final function getNewBlockPropertyInfo($name, $type)
	{
		return new block_BlockPropertyInfo(
		$name,
		$type,
		0,								// min occurs
		1,								// max occurs
		'',								// dbMapping
		'',								// dbTable
		false,							// primary key ?
		false,							// cascade delete ?
		false,							// tree node ?
		false,							// array ?
		strpos($type, '/') !== false,	// document ?
		null,							// default value
			'',								// constraints
		false,							// localized
		false,							// indexed
		false,							// specificIndex
		null							// fromList
		);
	}

	const BLOCK_TYPE_EMPTY = 'empty';
	const BLOCK_TYPE_TEMPLATE = 'template';
	const BLOCK_TYPE_CONTENT = 'content';
	const BLOCK_TYPE_FREE = 'free';
	const BLOCK_TYPE_RICHTEXT = 'richtext';
	const BLOCK_TYPE_LAYOUT= 'layout';
	/**
	 * @param String $blockName
	 * @return Boolean
	 */
	function isSpecialBlock($blockName)
	{
		$blockName = strtolower($blockName);
		return false
		|| self::BLOCK_TYPE_EMPTY == $blockName
		|| self::BLOCK_TYPE_RICHTEXT == $blockName
		|| self::BLOCK_TYPE_TEMPLATE == $blockName
		|| self::BLOCK_TYPE_CONTENT == $blockName
		|| self::BLOCK_TYPE_FREE == $blockName
		|| self::BLOCK_TYPE_LAYOUT == $blockName
		;
	}

	/**
	 * @param String $blockName
	 * @return String
	 *
	 * @throws BlockException
	 */
	private function getBlockInfoClassNameFromBlockName($blockName)
	{
		$matches = null;

		if (preg_match('/modules_([\w\d]+)_([\w\d]+)/', $blockName, $matches))
		{
			return $matches[1].'_Block'.ucfirst($matches[2]).'Info';
		}
		throw new BlockException('Could not determine class name for block: "'.$blockName.'". Wrong block name?');
	}

	/**
	 * @param String $blockName
	 * @return String
	 *
	 * @throws BlockException
	 */
	private function getBlockConfigurationClassNameFromBlockName($blockName)
	{
		$matches = null;

		if (preg_match('/modules_([\w\d]+)_([\w\d]+)/', $blockName, $matches))
		{
			return $matches[1].'_Block'.ucfirst($matches[2]).'Configuration';
		}
		throw new BlockException('Could not determine class name for block: "'.$blockName.'". Wrong block name?');
	}

	/**
	 * @param String $blockName
	 * @param String $packageName
	 * @return String
	 *
	 * @throws BlockException
	 */
	private function getSpecialBlockInfoClassNameFromBlockName($blockName, $packageName)
	{
		return $this->getBlockInfoClassNameFromBlockName($packageName.'_'.$blockName);
	}

	/**
	 * @param String $blockName
	 * @return String
	 *
	 * @throws BlockException
	 */
	private function getShortBlockName($blockName, $ucfirst = false)
	{
		if ($this->isSpecialBlock($blockName))
		{
			return $blockName;
		}

		$matches = null;
		if (preg_match('/modules_([\w\d]+)_([\w\d]+)/', $blockName, $matches))
		{
			if ($ucfirst)
			{
				return ucfirst($matches[2]);
			}
			return $matches[2];
		}
		throw new BlockException('Could not determine short block name for block: "'.$blockName.'". Wrong block name?');
	}


	//
	// Blocks compilation methods.
	//


	/**
	 * @return void
	 */
	public function compileBlocks($callback = null)
	{
		foreach (ModuleService::getInstance()->getModules() as $moduleName)
		{
			try
			{
				$this->compileBlocksForPackage($moduleName);
				if (is_array($callback) && count($callback) == 2)
				{
					f_util_ClassUtils::callMethodArgsOn($callback[0], $callback[1], array($moduleName, null));
				}
			}
			catch (Exception $e)
			{
				if (is_array($callback) && count($callback) == 2)
				{
					f_util_ClassUtils::callMethodArgsOn($callback[0], $callback[1], array($moduleName, $e));
				}
				Framework::exception($e);
			}
		}
	}

	private $specialBlockIndexArray;

	/**
	 * @return void
	 */
	private function resetSpecialBlockIndexArray()
	{
		$this->specialBlockIndexArray = array();
	}

	/**
	 * @param String $packageName
	 * @example compileBlocksForPackage('modules_mymodule')
	 * @return void
	 */
	public function compileBlocksForPackage($packageName)
	{
		$shortPackageName = substr($packageName, 0, 8) == 'modules_' ? substr($packageName, 8) : $packageName;
		$blockInfoArray = array();
		$blockFiles = FileResolver::getInstance()->setPackageName('modules_' . $shortPackageName)->getPaths('config/blocks.xml');
		if ($blockFiles === null)
		{
			$filePath = $this->getBlockListPhpFilepath($shortPackageName);
			if (file_exists($filePath))
			{
				f_util_FileUtils::unlink($filePath);
			}
			return;
		}

		$this->resetSpecialBlockIndexArray();

		// $blockFiles contains the 'blocks.xml' files, the generic module's one
		// being the first, the overriden one being the second.
		$blockFiles = array_reverse($blockFiles);
		foreach ($blockFiles as $blockFile)
		{
			$this->compileBlockFile($packageName, $blockFile, $blockInfoArray);
		}

		$blockIdArray = array();

		$templateDir = f_util_FileUtils::buildWebeditPath("modules", "website", "templates", "builder", "blocks");
		foreach ($blockInfoArray as $blockInfo)
		{
			$blockType = $blockInfo->getType();
			$blockId = $blockInfo->getId();

			// Generation de BlockInfo
			$infoClassName = $this->getBlockInfoClassNameFromBlockName($blockId);
			$destFilePath = f_util_FileUtils::buildChangeBuildPath('modules', $shortPackageName, 'blocks', $infoClassName . '.class.php');
			f_util_FileUtils::mkdir(dirname($destFilePath));

			$generator = new builder_Generator();
			$generator->setTemplateDir($templateDir);
			$generator->assign_by_ref('fullPackage', $packageName);
			$generator->assign_by_ref('package', $shortPackageName);
			$generator->assign_by_ref('className', $infoClassName);
			$generator->assign_by_ref('date', date('r'));
			$generator->assign('author', PROFILE);
			$generator->assign_by_ref('blockNameUCFirst', $this->getShortBlockName($blockType, true));
			$generator->assign_by_ref('blockInfo', $blockInfo);
				
			$generator->assign_by_ref('metas', serialize($blockInfo->getAllMetas()));
			if ($blockInfo->getTitleMetas())
			{
				$generator->assign('titleMetas', serialize($blockInfo->getTitleMetas()));
			}
			else
			{
				$generator->assign('titleMetas', false);
			}
			if ($blockInfo->getDescriptionMetas())
			{
				$generator->assign('descriptionMetas', serialize($blockInfo->getDescriptionMetas()));
			}
			else
			{
				$generator->assign('descriptionMetas', false);
			}
			if ($blockInfo->getKeywordsMetas())
			{
				$generator->assign('keywordsMetas', serialize($blockInfo->getKeywordsMetas()));
			}
			else
			{
				$generator->assign('keywordsMetas', false);
			}

			f_util_FileUtils::write($destFilePath, $generator->fetch('BlockInfo.class.php.tpl'), f_util_FileUtils::OVERRIDE);
			ClassResolver::getInstance()->appendToAutoloadFile($infoClassName, $destFilePath);

			// Generation de BlockConfiguration
			$infoClassName = $this->getBlockConfigurationClassNameFromBlockName($blockId);
			$destFilePath = f_util_FileUtils::buildChangeBuildPath('modules', $shortPackageName, 'blocks', $infoClassName . '.class.php');
			f_util_FileUtils::mkdir(dirname($destFilePath));

			$generator = new builder_Generator();
			$generator->setTemplateDir($templateDir);
			$generator->assign_by_ref('fullPackage', $packageName);
			$generator->assign('author', PROFILE);
			$generator->assign_by_ref('className', $infoClassName);
			$generator->assign_by_ref('blockInfo', $blockInfo);

			f_util_FileUtils::write($destFilePath, $generator->fetch('BlockConfiguration.class.php.tpl'), f_util_FileUtils::OVERRIDE);
			ClassResolver::getInstance()->appendToAutoloadFile($infoClassName, $destFilePath);

			$blockIdArray[] = $blockId;
		}

		$php = "<?php\n\$blockIdArray = ".var_export($blockIdArray, true) . ";\n?>";
		f_util_FileUtils::writeAndCreateContainer($this->getBlockListPhpFilepath($shortPackageName), $php, f_util_FileUtils::OVERRIDE);
	}

	/**
	 * @param String $moduleName
	 * @return String
	 */
	private function getBlockListPhpFilepath($moduleName)
	{
		return f_util_FileUtils::buildChangeBuildPath('modules', $moduleName, 'blocks', 'blockList.php');
	}

	/**
	 * @param String $packageName
	 * @param String $blockFile
	 * @param Array<block_BlockInfo> $blockPropertyInfoArray
	 */
	private function compileBlockFile($packageName, $blockFile, &$blockInfoArray)
	{
		$domDoc = f_util_DOMUtils::fromPath($blockFile);
		$nodeList = $domDoc->find('/blocks/block');
		for ($i=0 ; $i < $nodeList->length ; $i++)
		{
			$blockElm = $nodeList->item($i);
			if ( ! $blockElm->hasAttribute('type') )
			{
				throw new BlockException("Invalid block definition: required attribute \"type\" is missing.");
			}

			// Retrieve basic block's information such as label, icon, ... from
			// the <block/> element's attributes.
			$blockType = $blockElm->getAttribute('type');
			$blockId = $this->getNextBlockId($packageName, $blockType);
			if ( ! isset($blockInfoArray[$blockId]) )
			{
				$blockInfoArray[$blockId] = new block_BlockInfo();
			}
			$blockInfoArray[$blockId]->setId($blockId);
			$blockInfoArray[$blockId]->setType($blockType);
			if ($blockElm->hasAttribute('label'))
			{
				$blockInfoArray[$blockId]->setLabel($blockElm->getAttribute('label'));
			}
			if ($blockElm->hasAttribute('editable'))
			{
				$blockInfoArray[$blockId]->setEditable($blockElm->getAttribute('editable') == "true");
			}
			$this->completeBlockInfo($blockInfoArray[$blockId], $blockElm);

			// Retrieve block's parameters.
			$this->completeBlockInfoWithParameters($blockInfoArray[$blockId], $blockElm);
				
			// Retrieve blocs's metas
			$this->completeBlockInfoWithMetas($blockInfoArray[$blockId], $blockElm);
		}
	}

	/**
	 * @param String $packageName
	 * @param String $blockType
	 * @return String
	 */
	private function getNextBlockId($packageName, $blockType)
	{
		if ($this->isSpecialBlock($blockType))
		{
			if ( ! isset($this->specialBlockIndexArray[$blockType]) )
			{
				$this->specialBlockIndexArray[$blockType] = 1;
			}
			else
			{
				$this->specialBlockIndexArray[$blockType]++;
			}
			return $packageName . '_' . $blockType . $this->specialBlockIndexArray[$blockType];
		}
		return $blockType;
	}

	/**
	 * @param block_BlockInfo $blockInfo
	 * @param DOMElement $blockElm
	 */
	private function completeBlockInfo($blockInfo, $blockElm)
	{
		// Parse <block/> element's attributes.
		$attributes = $blockElm->attributes;
		$length = $attributes->length;
		for ($i = 0; $i < $length; ++$i)
		{
			$attribute = $attributes->item($i)->name;
			$methodName = $this->getSetterForAttribute($attribute);
			if (f_util_ClassUtils::methodExists($blockInfo, $methodName))
			{
				f_util_ClassUtils::callMethodArgsOn(
				$blockInfo,
				$methodName,
				array($blockElm->getAttribute($attribute))
				);
			}
			else
			{
				$blockInfo->setAttribute($attribute, $blockElm->getAttribute($attribute));
			}
		}

		// Explode deprecated display attribute.
		if ($blockInfo->hasAttribute('display'))
		{
			$parameters = f_util_HtmlUtils::parseStyleAttributes($blockInfo->getAttribute('display'));
			foreach ($parameters as $key => $value)
			{
				$blockInfo->setAttribute('__' . $key, $value);
			}
			$blockInfo->setAttribute('display', null);
		}

		// Find block content.
		// New blocks should have a <content/> element that contains the default
		// block content or the layout for template-based blocks.
		$nodeList = $blockElm->getElementsByTagName('content');
		if ($nodeList->length == 1)
		{
			$blockInfo->setContent(trim($nodeList->item(0)->nodeValue));
		}
		else if ($blockInfo->getType() == self::BLOCK_TYPE_TEMPLATE)
		{
			// For older template-based blocks, the <wlayout/> element is a direct
			// child of the <block/> element.
			$xpath = new DOMXPath($blockElm->ownerDocument);
			$xpath->registerNamespace('xul', 'http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul');
			$nodeList = $xpath->query('xul:wlayout', $blockElm);
			if ($nodeList->length == 1)
			{
				$blockInfo->setContent($blockElm->ownerDocument->saveXML($nodeList->item(0)));
			}
			else if (Framework::isDebugEnabled())
			{
				Framework::debug(__METHOD__." found a \"template\" block without <wlayout/> element.");
			}
		}
		else if ($this->isSpecialBlock($blockInfo->getType()))
		{
			// For all other special blocks (free, richtext and empty), we search
			// for a content that is the direct <block/> element's value.
			$blockInfo->setContent(trim($blockElm->nodeValue));
		}
	}

	/**
	 * @param block_BlockInfo $blockInfo
	 * @param DOMElement $blockElm
	 */
	private function completeBlockInfoWithMetas($blockInfo, $blockElem)
	{
		$xpath = new DOMXPath($blockElem->ownerDocument);
		$nodeList = $xpath->query('metas/meta', $blockElem);
		for ($i = 0; $i < $nodeList->length; $i++)
		{
			$metaElem = $nodeList->item($i);
			$blockInfo->addMeta($metaElem->getAttribute("name"),
			$metaElem->hasAttribute("allow") ? $metaElem->getAttribute("allow") : null);
		}

		if ($blockInfo->hasMeta())
		{
			$parameterInfo = $this->getNewBlockPropertyInfo("enablemetas", "Boolean");
			$parameterInfo->setDefaultValue('true');
			$parameterInfo->setLabel('&modules.website.bo.blocks.Enablemetas;');
			$parameterInfo->setHelpText('&modules.website.bo.blocks.Enablemetas-help;');
			$blockInfo->setAttribute('__enablemetas', 'true');
			$blockInfo->addParameterInfo($parameterInfo);
		}
	}

	/**
	 * @param block_BlockInfo $blockInfo
	 * @param DOMElement $blockElm
	 */
	private function completeBlockInfoWithParameters($blockInfo, $blockElm)
	{
		$xpath = new DOMXPath($blockElm->ownerDocument);
		$nodeList = $xpath->query('parameters/parameter', $blockElm);
		for ($i=0 ; $i<$nodeList->length ; $i++)
		{
			$paramElm = $nodeList->item($i);
			$name = $paramElm->getAttribute('name');


			if ($blockInfo->hasParameterInfo($name) )
			{
				$parameterInfo = $blockInfo->getParameterInfo($name);
			}
			else
			{
				$parameterInfo = $this->getNewBlockPropertyInfo($name, $paramElm->getAttribute('type'));
			}

			if ($blockInfo->hasAttribute('__' . $name))
			{
				$parameterInfo->setDefaultValue($blockInfo->getAttribute('__' . $name));
			}

			$attributes = $paramElm->attributes;
			$length = $attributes->length;
			for ($j = 0; $j < $length; ++$j)
			{
				$attribute = $attributes->item($j)->name;

				// Accept 'from-list' attribute just like in document model properties.
				$attributeForSetter = ($attribute == 'from-list') ? 'list-id' : $attribute;
				$methodName = $this->getSetterForAttribute($attributeForSetter);
				if (f_util_ClassUtils::methodExists($parameterInfo, $methodName))
				{
					f_util_ClassUtils::callMethodArgsOn($parameterInfo, $methodName, array($paramElm->getAttribute($attribute)));
				}
				else if ($attribute !== "name" && $attribute !== "type")
				{
					$parameterInfo->setExtendedAttribute($attribute, $paramElm->getAttribute($attribute));
				}
			}

			if ($parameterInfo->hasDefaultValue())
			{
				$blockInfo->setAttribute('__' . $name, $parameterInfo->getDefaultValue());
			}

			if (strlen($parameterInfo->getLabel()) == 0)
			{
				$parameterInfo->setLabel('&modules.'.$blockInfo->getModule().'.bo.blocks.'.$this->getShortBlockName($blockInfo->getType()).'.'.ucfirst($parameterInfo->getName()).';');
			}
			if (strlen($parameterInfo->getHelpText()) == 0)
			{
				$parameterInfo->setHelpText('&modules.'.$blockInfo->getModule().'.bo.blocks.'.$this->getShortBlockName($blockInfo->getType()).'.'.ucfirst($parameterInfo->getName()).'-help;');
			}

			$blockInfo->addParameterInfo($parameterInfo);
		}
	}

	/**
	 * The full list of blocks having a property grid returned as an array of their names
	 *
	 * @return Array<String>
	 */
	public function getBlocksWithPropertyGrid()
	{
		$modules = array();
		$availableModules = ModuleService::getInstance()->getModules();
		foreach ($availableModules as $availableModuleName)
		{
			$availableShortModuleName = substr($availableModuleName, strpos($availableModuleName, '_') + 1);
			$modules[] = $availableShortModuleName;
		}
		$blocksWithPropertyGrid = array();
		foreach ($modules as $module)
		{
			// Module blocks :
			foreach ($this->getDeclaredBlocksForModule($module) as $blockName)
			{
				$blockInfo = $this->getBlockInfo($blockName, $module);
				if ($blockInfo->hasPropertyGrid())
				{
					$blocksWithPropertyGrid[] = $blockName;
				}
			}
		}
		return $blocksWithPropertyGrid;
	}

	/**
	 * @param String $attribute
	 * @return String
	 */
	private function getSetterForAttribute($attribute)
	{
		return 'set' . str_replace(' ', '', ucwords(str_replace('-', ' ', $attribute)));
	}

}
