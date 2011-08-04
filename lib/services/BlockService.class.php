<?php
class block_BlockService extends BaseService
{
	/**
	 * @var block_BlockService
	 */
	private static $instance;


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
	 * @var array
	 */
	protected $blockActionClassByType;	
	protected $editorGridBlocType;
	protected $editorDropModelBlocType;
	protected $editorInsertBlocType;
	
	/**
	 * @throws BlockException
	 */
	protected function loadBlockActionClassByType()
	{
		if ($this->blockActionClassByType === null)
		{
			$filePath = f_util_FileUtils::buildChangeBuildPath('BlockActionClassByType.inc');
			if (!file_exists($filePath))
			{
				$this->blockActionClassByType = array();
				throw new BlockException('Please execute commande: compile-blocks');
			}
			include $filePath;
		}
	}
	
	/**
	 * @throws BlockException
	 */
	protected function loadEditorBlocType()
	{
		if ($this->editorGridBlocType === null)
		{
			$filePath = f_util_FileUtils::buildChangeBuildPath('BlockEditorInfos.inc');
			if (!file_exists($filePath))
			{
				$this->editorGridBlocType = array();
				$this->editorDropModelBlocType = array();
				$this->editorInsertBlocType = array();
				throw new BlockException('Please execute commande: compile-blocks');
			}
			include $filePath;
		}
	}	

	/**
	 * @param string $blockType
	 * @return string
	 */
	public function getBlockActionClassNameByType($blockType)
	{
		$key = strtolower($blockType);
		$this->loadBlockActionClassByType();
		if (isset($this->blockActionClassByType[$key]))
		{
			$className = $this->blockActionClassByType[$key];
			if (f_util_ClassUtils::classExists($className))
			{
				return $className;
			}
			Framework::error(__METHOD__ . ' Class ' . $className .' not found for: ' . $blockType);
		}
		else if (!$this->isSpecialBlock($blockType))
		{
			$className = $this->buildDefaultPhpBlockClass($blockType);
			if (f_util_ClassUtils::classExists($className))
			{
				if (Framework::isInfoEnabled())
				{
					Framework::info(__METHOD__ . ' Please add block configuration for: ' . $blockType);
				}
				return $className;
			}			
		}
		Framework::error(__METHOD__ . ' Undefined block type: ' . $blockType);
		if (Framework::isInfoEnabled())
		{
			Framework::info(f_util_ProcessUtils::getBackTrace());
		}
		return null;
	}
	

	
	/**
	 * @param string $blockType
	 * @return string
	 */
	public function getBlockConfigurationClassname($blockType)
	{
		$key = strtolower($blockType);
		$this->loadBlockActionClassByType();
		if (isset($this->blockActionClassByType[$key]))
		{
			list(,$moduleName,$name) = explode('_', $blockType);
			return $moduleName.'_Block'.ucfirst($name).'Configuration';
		}
		if (Framework::isInfoEnabled())
		{
			Framework::info(__METHOD__ . ' Configuration Class not found for :' . $blockType);
		}
		return null;
	}
	
	const BLOCK_TYPE_RICHTEXT = 'richtext';
	const BLOCK_TYPE_LAYOUT= 'layout';
	
	/**
	 * @param String $blockName
	 * @return Boolean
	 */
	public function isSpecialBlock($blockName)
	{
		$blockName = strtolower($blockName);
		return false
			|| self::BLOCK_TYPE_RICHTEXT === $blockName
			|| self::BLOCK_TYPE_LAYOUT === $blockName;
	}

	/**
	 * @param String $blockName
	 * @param String $packageName
	 * @return block_BlockInfo
	 */
	public function getBlockInfo($blockName, $packageName = null)
	{
		if (!$this->isSpecialBlock($blockName))
		{
			list(,$moduleName,$name) = explode('_', $blockName);
			$className = $moduleName.'_Block'.ucfirst($name).'Info';
			if ($className !== null && f_util_ClassUtils::classExists($className))
			{
				return f_util_ClassUtils::callMethod($className, 'getInstance');
			}
			Framework::warn(__METHOD__ . ' BlockInfo not found for: ' . $blockName);
			Framework::warn(f_util_ProcessUtils::getBackTrace());
		}
		else if ($blockName === self::BLOCK_TYPE_RICHTEXT)
		{
			 return new block_BlockInfo(array('type' => 'richtext', 'icon' => 'richtext', 'label' => '&modules.uixul.bo.layout.RichTextBlock'));
		}
		else if ($blockName === self::BLOCK_TYPE_LAYOUT)
		{
			 return new block_BlockInfo(array('type' => 'layout', 'columns' => 2, 'icon' => 'layout-2-columns', 'label' => '&modules.website.bo.blocks.Two-col'));
		}
		return null;
	}
	
	/**
	 * @return string[]
	 */
	public function getBlocksWithPropertyGrid()
	{
		$this->loadEditorBlocType();
		return  $this->editorGridBlocType;
	}


	/**
	 * @return string[]
	 */
	public function getBlocksToInsert()
	{
		$this->loadEditorBlocType();
		return  $this->editorInsertBlocType;
	}
	
	/**
	 * @return string[]
	 */
	public function getBlocksDocumentModelToInsert()
	{
		$this->loadEditorBlocType();
		return  $this->editorDropModelBlocType;
	}	
	
	//
	// Blocks compilation methods.
	//
	
	/**
	 * @return void
	 */
	public function compileBlocks($callback = null)
	{
		$blocLists = array();
		foreach (ModuleService::getInstance()->getPackageNames() as $packageName)
		{
			try
			{
				$this->loadBlockXMLConfig($packageName, $blocLists);
				if (is_array($callback) && count($callback) == 2)
				{
					f_util_ClassUtils::callMethodArgsOn($callback[0], $callback[1], array($packageName, null));
				}
			}
			catch (Exception $e)
			{
				if (is_array($callback) && count($callback) == 2)
				{
					f_util_ClassUtils::callMethodArgsOn($callback[0], $callback[1], array($packageName, $e));
				}
				Framework::exception($e);
			}
		}
				
		$this->computeInjection($blocLists);
		$this->computeAttribute($blocLists);
		
		
		$this->buildBlockActionClassByType($blocLists);
		
		$this->buildBlockEditorInfos($blocLists);
		
		$this->buildBlockConfiguration($blocLists);
		
		$this->buildBlockInfo($blocLists);
		
		//$this->debugInformation($blocLists);
	}
	
	private function debugInformation($blocLists)
	{
		$attributes = array();
		$parameters = array();
		$metas = array();
		foreach ($blocLists as $blockType => $blockInfos) 
		{
			foreach ($blockInfos as $pn => $val) 
			{
				if ($pn === 'parameters')
				{
					foreach ($val as $n => $v) 
					{
						foreach ($v as $n1 => $v1) 
						$parameters[$n1] = isset($parameters[$n1]) ? $parameters[$n1] + 1 : 1;
					}
				}
				else if ($pn === 'metas')
				{
					foreach ($val as $n => $v) 
					{
						$metas[$n] = isset($metas[$n]) ? $metas[$n] + 1 : 1;
					}					
				}
				else
				{
					$attributes[$pn] = isset($attributes[$pn]) ? $attributes[$pn] + 1 : 1;
				}
			}
		}
		print_r($attributes);
		print_r($parameters);
		print_r($metas);		
	}
	
	private function loadBlockXMLConfig($packageName, &$blocLists)
	{
		$moduleName = substr($packageName, 0, 8) == 'modules_' ? substr($packageName, 8) : $packageName;
		$blockInfoArray = array();
		$blockFiles = FileResolver::getInstance()->setPackageName('modules_' . $moduleName)->getPaths('config/blocks.xml');	
		if ($blockFiles === NULL) 
		{
			return;
		}
		$blockFiles = array_reverse($blockFiles);
		foreach ($blockFiles as $blockFile)
		{
			$this->parseXmlBlockFile($moduleName, $blockFile, $blocLists);
		}
	}
	
	/**
	 * @param String $defaultSection
	 * @param String $blockFile
	 * @param array $blockPropertyInfoArray
	 */
	private function parseXmlBlockFile($defaultSection, $blockFile, &$blockInfoArray)
	{
		$domDoc = f_util_DOMUtils::fromPath($blockFile);
		$blockDomList = $domDoc->find('/blocks/block');
		$reservedParameterNames = array("type", "flex", "width", "cusecache");
		for ($blockIdx=0; $blockIdx < $blockDomList->length ; $blockIdx++)
		{
			$blockElm = $blockDomList->item($blockIdx);
			if (!$blockElm->hasAttribute('type') )
			{
				throw new BlockException("Invalid block definition: required attribute \"type\" is missing.");
			}
			
			$blockType = $blockElm->getAttribute('type');
			$blockNameParts = explode('_', $blockType);
			if (count($blockNameParts) === 1)
			{
				$blockId = $blockType;
			}
			else if (count($blockNameParts) === 3)
			{
				list(, $blockModuleName, $blockShortName) = $blockNameParts;
				$blockId = $blockType;
			}
			else
			{
				throw new BlockException("Invalid block type: $blockType.");
			}
			
			if (!isset($blockInfoArray[$blockId]) )
			{
				$blockInfoArray[$blockId] = array('type' => $blockType, 
					'section' => $defaultSection,
					'parameters' => array(), 
					'metas' => array());
			}
			
			$attributes = $blockElm->attributes;
			$length = $attributes->length;
			for ($attr = 0; $attr < $length; $attr++)
			{
				$attName = $attributes->item($attr)->name;
				if ($attName === 'display' || $attName === 'requiresNewEditor')
				{
					continue;
				}
				$attVal = $blockElm->getAttribute($attName);
				if ($attVal === 'false')
				{
					$attVal = false;
				}
				else if ($attVal === 'true')
				{
					$attVal = true;
				}
				
				if ($attName == "cache")
				{
					$cacheTime = null;
					if ($attVal == "true")
					{
						$cacheTime = CHANGE_CACHE_MAX_TIME;
					}
					elseif (is_numeric($attVal))
					{
						$cacheTime = $attVal;
					}
					$blockInfoArray[$blockId]['cacheTime'] = $cacheTime;
					
					if ($cacheTime !== null)
					{
						$blockInfoArray[$blockId]['parameters']['cusecache'] = array('name' => 'cusecache',
		      				'type' => 'Boolean', 'default-value' => true,
							'label' => '&modules.website.bo.blocks.Usecache;',
							'helptext' => '&modules.website.bo.blocks.Usecache-help;');
					}
				}
			
				$blockInfoArray[$blockId][$attName] = $attVal;
			}
			
			// If no label is explicitely set, generate it from the block type.
			if (!isset($blockInfoArray[$blockId]['label']) && !isset($blockInfoArray[$blockId]['labeli18n']))
			{
				list(, $moduleName, $blockName) = explode('_', strtolower($blockType));
				$blockInfoArray[$blockId]['labeli18n'] = strtolower("m.$moduleName.bo.blocks.$blockName.title");
			}
			
			$nodeParamList = $domDoc->find('parameters/parameter', $blockElm);
			for ($paramIdx=0; $paramIdx < $nodeParamList->length; $paramIdx++)
			{
				$paramElm = $nodeParamList->item($paramIdx);
				$paramName = $paramElm->getAttribute('name');				
				if (in_array(strtolower($paramName), $reservedParameterNames))
				{
					throw new BlockException("'$paramName' is a reserved block parameter name (block ".$blockType.")");  
				}
	
				if (!isset($blockInfoArray[$blockId]['parameters'][$paramName]))
				{
					$blockInfoArray[$blockId]['parameters'][$paramName] = array();
				}
				if (isset($blockInfoArray[$blockId]['__' . $paramName]))
				{
					$blockInfoArray[$blockId]['parameters'][$paramName]['default-value'] = $blockInfoArray[$blockId]['__' . $paramName];
					unset($blockInfoArray[$blockId]['__' . $paramName]);
				}	
				$attributes = $paramElm->attributes;
				$length = $attributes->length;
				for ($j = 0; $j < $length; ++$j)
				{
					$attName = $attributes->item($j)->name;
					if ($attName === 'list-id') {$attName = 'from-list';}
					
					$attVal = $paramElm->getAttribute($attName);
					if ($attVal === 'false')
					{
						$attVal = false;
					}
					else if ($attVal === 'true')
					{
						$attVal = true;
					}
					$blockInfoArray[$blockId]['parameters'][$paramName][$attName] = $attVal;
				}
			}
			
			$nodeMetaList = $domDoc->find('metas/meta', $blockElm);
			for ($metaIdx = 0; $metaIdx < $nodeMetaList->length; $metaIdx++)
			{
				$metaElem = $nodeMetaList->item($metaIdx);
				$metaName = $metaElem->getAttribute("name");
				$metaVal = $metaElem->hasAttribute("allow") ? $metaElem->getAttribute("allow") : true;
				$blockInfoArray[$blockId]['metas'][$metaName] = $metaVal;
				if (!isset($blockInfoArray[$blockId]['parameters']['enablemetas']))
				{
					$blockInfoArray[$blockId]['parameters']['enablemetas'] = array(
					    'name' => 'enablemetas',
						'type' => 'Boolean',
						'label' => '&modules.website.bo.blocks.Enablemetas;',
					    'helptext' => '&modules.website.bo.blocks.Enablemetas-help;',
					    'default-value' => true
					);
				}
			}
		}
	}

	private function computeInjection(&$blocLists)
	{
		$injections = array();
		foreach ($blocLists as $blockType => $blockInfos)
		{
			if (!isset($blockInfos['inject'])) {continue;}
			$injectedType = $blockInfos['inject'];
			$injections[] = $blockType;
			if (!isset($blocLists[$injectedType]))
			{
				throw new Exception("Invalid injection type $injectedType for block $blockType");
			}
			$blocLists[$injectedType] = $this->mergeBloc($blocLists[$injectedType], $blockInfos);
		}
		foreach ($injections as $injection) 
		{
			unset($blocLists[$injection]);
		}
	}
	
	/**
	 * @param array $original
	 * @param array $override
	 */
	private function mergeBloc($original, $override)
	{
		foreach ($override as $key => $value) 
		{
			switch ($key) 
			{
				case 'type': 
					$original['injectedBy'] = $value;
					break;
				case 'phpBlockClass': 
					break;
				case 'inject': 
					if (isset($original['phpBlockClass']))
					{
						throw new Exception($original["type"] . " Already inject : " . $original['phpBlockClass']);
					}
					$original['phpBlockClass'] = $this->buildDefaultPhpBlockClass($override['type']);
					break;
				case 'metas':
					$original['metas'] = array_merge($original['metas'], $value);
					break;
				case 'parameters':
					foreach ($value as $pname => $pinfo) 
					{
						if (isset($original['parameters'][$pname]))
						{
							$original['parameters'][$pname] = array_merge($original['parameters'][$pname], $pinfo);
						}
						else
						{
							$original['parameters'][$pname] = $pinfo;
						}
					}
					break;
				default: 
					$original[$key] = $value; 
					break;
			}
		}
		return $original;
	}
	
	private function buildDefaultPhpBlockClass($blockType)
	{
		list( ,$moduleName, $name) = explode('_', $blockType);
		return $moduleName.'_Block'.ucfirst($name).'Action';
	}
	
	private function computeAttribute(&$blocLists)
	{
		foreach ($blocLists as $blockType => $blockInfos)
		{
			$blockTypeParts = explode('_', $blockType);
			if (count($blockTypeParts) != 3) {continue;}
			
			list( ,$defaultModuleName, $name) = $blockTypeParts;
			if (!isset($blockInfos['phpBlockClass']))
			{
				$blocLists[$blockType]['phpBlockClass'] = $this->buildDefaultPhpBlockClass($blockType);
			}
			if (!isset($blockInfos['requestModule']))
			{
				$blocLists[$blockType]['requestModule'] = $defaultModuleName;
			}
			if (!isset($blockInfos['templateModule']))
			{
				$blocLists[$blockType]['templateModule'] = $defaultModuleName;
			}
			if (!isset($blockInfos['dropModels']))
			{
				$needle = 'modules_' . $defaultModuleName . '/' . $name;
				$modelNames =ModuleService::getInstance()->getDefinedDocumentModelNames($defaultModuleName);
				if (in_array($needle, $modelNames))
				{
					$blocLists[$blockType]['dropModels'] = $needle;
				}
				else
				{
					$blocLists[$blockType]['dropModels'] = '';
				}
			}
		}
	}
	
	private function buildBlockActionClassByType($blocLists)
	{
		$filePath = f_util_FileUtils::buildChangeBuildPath('BlockActionClassByType.inc');
		$array = array();
		foreach ($blocLists as $blockType => $blockInfos)
		{
			if (isset($blockInfos['phpBlockClass']))
			{
				$array[strtolower($blockType)] = $blockInfos['phpBlockClass'];
			}
		}
		$php = '<?php //Auto Generated at ' . date_Calendar::getInstance()->toString() . "\n" .
			'	$this->blockActionClassByType = ' . var_export($array, true) . ';';
		f_util_FileUtils::writeAndCreateContainer($filePath, $php, f_util_FileUtils::OVERRIDE);
	}
	
	private function buildBlockEditorInfos($blocLists)
	{
		$propertyGridBlocType = array();
		$dropModelsBlocType = array();
		$insertBlocType = array();
		
		$filePath = f_util_FileUtils::buildChangeBuildPath('BlockEditorInfos.inc');
		foreach ($blocLists as $blockType => $blockInfos)
		{
			if (!isset($blockInfos['phpBlockClass'])) {continue;}
			$dropModels = DocumentHelper::expandModelList($blockInfos['dropModels']);
			if (count($dropModels))
			{
				foreach ($dropModels as $dropModel) 
				{
					$dropModelsBlocType[$dropModel][] = $blockType;
				}
			}
			if (!isset($blockInfos['hidden']) || !$blockInfos['hidden'])
			{
				$insertBlocType[] = $blockType;
			}
			
			if (count($blockInfos['parameters']))
			{
				foreach ($blockInfos['parameters'] as $params) 
				{
					if (!isset($params['hidden']) || !$params['hidden'])
					{
						$propertyGridBlocType[] = $blockType;
						break;
					}
				}
			}
		}
		$php = '<?php //Auto Generated at ' . date_Calendar::getInstance()->toString() . "\n" .
			'	$this->editorGridBlocType = ' . var_export($propertyGridBlocType, true) . ";\n" .
			'	$this->editorDropModelBlocType = ' . var_export($dropModelsBlocType, true) . ";\n" .
			'	$this->editorInsertBlocType = ' . var_export($insertBlocType, true) . ";";
		
		f_util_FileUtils::writeAndCreateContainer($filePath, $php, f_util_FileUtils::OVERRIDE);
	}
	
	private function buildBlockConfiguration($blocLists)
	{
		$templateDir = f_util_FileUtils::buildWebeditPath("modules", "website", "templates", "builder", "blocks");
		$blocWrapper = new block_blockInfoBuilder($this);
		$author = 'Auto Generated at '. date_Calendar::getInstance()->toString();
		foreach ($blocLists as $blockType => $blockInfos) 
		{
			if (!isset($blockInfos['phpBlockClass'])) {continue;}
			list(,$moduleName,$name) = explode('_', $blockType);
			$configClassName = $moduleName.'_Block'.ucfirst($name).'Configuration';
			$destFilePath = f_util_FileUtils::buildChangeBuildPath('modules', $moduleName, 'blocks', $configClassName . '.class.php');
			f_util_FileUtils::mkdir(dirname($destFilePath));
			$generator = new builder_Generator();
			$generator->setTemplateDir($templateDir);
			$generator->assign_by_ref('moduleName', $moduleName);
			$generator->assign_by_ref('author', $author);
			$generator->assign_by_ref('className', $configClassName);
			$generator->assign_by_ref('blockInfo', $blocWrapper->setBlocInfoArray($blockInfos));
			
			// Cache keys
			$configuredCacheKeys = isset($blockInfos['cache-key']) ? explode(",", $blockInfos['cache-key']) : array();
			$allowedKeyCacheName = array("cmpref", "page", "nav");
			foreach ($configuredCacheKeys as &$value)
			{
				$value = trim($value);
				if (!in_array($value, $allowedKeyCacheName))
				{
					throw new Exception("Unknown cache-key attribute value ".$value);
				}
			}
			$generator->assign('configuredCacheKeys', var_export($configuredCacheKeys, true));
			
			// Cache deps
			$configuredCacheDeps = isset($blockInfos['cache-deps']) ? explode(",", $blockInfos['cache-deps']) : array();
			$modelDeps = array();
			$otherDeps = array();
			foreach ($configuredCacheDeps as $value)
			{
				$modulesIndex = strpos($value, "modules_");
				if ($modulesIndex !== false && $modulesIndex < 2)
				{
					$modelDeps[] = $value;
				}
				else
				{
					$otherDeps[] = $value;
				}
			}
			$computedCacheDeps = array_merge(DocumentHelper::expandModelList(join(",", $modelDeps)), $otherDeps);
			$generator->assign('configuredCacheDeps', var_export($computedCacheDeps, true));
			
			f_util_FileUtils::write($destFilePath, $generator->fetch('BlockConfiguration.class.php.tpl'), f_util_FileUtils::OVERRIDE);
			ClassResolver::getInstance()->appendToAutoloadFile($configClassName, $destFilePath);	
		}
	}
	
	private function buildBlockInfo($blocLists)
	{
		$templateDir = f_util_FileUtils::buildWebeditPath("modules", "website", "templates", "builder", "blocks");
		$blocWrapper = new block_blockInfoBuilder($this);
		$author = 'Auto Generated at '. date_Calendar::getInstance()->toString();
		foreach ($blocLists as $blockType => $blockInfos) 
		{
			if (!isset($blockInfos['phpBlockClass'])) {continue;}	
			list(,$moduleName,$name) = explode('_', $blockType);
			$infoClassName = $moduleName.'_Block'.ucfirst($name).'Info';
			$destFilePath = f_util_FileUtils::buildChangeBuildPath('modules', $moduleName, 'blocks', $infoClassName . '.class.php');
			f_util_FileUtils::mkdir(dirname($destFilePath));
		
			$generator = new builder_Generator();
			$generator->setTemplateDir($templateDir);
			$generator->assign_by_ref('moduleName', $moduleName);
			$generator->assign('author', $author);
			$generator->assign_by_ref('className', $infoClassName);
			$generator->assign_by_ref('blockInfo', $blocWrapper->setBlocInfoArray($blockInfos));
			
			f_util_FileUtils::write($destFilePath, $generator->fetch('BlockInfo.class.php.tpl'), f_util_FileUtils::OVERRIDE);
			ClassResolver::getInstance()->appendToAutoloadFile($infoClassName, $destFilePath);
		}
	}
		
	//DEPRECATED
		
	/**
	 * @deprecated
	 */
	public function getBlockLabelFromBlockName($blockName)
	{
		return $this->getBlockAttribute($blockName, 'label');
	}

	/**
	 * @deprecated
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
}

class block_blockInfoBuilder
{
	/**
	 * @var block_BlockService
	 */
	private $bs;
	
	private $blockInfos;
	
	public function __construct($bs)
	{
		$this->bs = $bs;	
	}
	
	/**
	 * @param array $blockInfos
	 * @return block_blockInfoBuilder
	 */
	public function setBlocInfoArray($blockInfos)
	{
		$this->blockInfos = $blockInfos;
		return $this;
	}
	
	public function getRequestModule()
	{
		return $this->blockInfos['requestModule'];
	}

	public function getTemplateModule()
	{
		return $this->blockInfos['templateModule'];
	}
	
	public function isCached()
	{
		return isset($this->blockInfos['cache']) && ($this->blockInfos['cache'] === true || is_numeric($this->blockInfos['cache']));
	}
	
	public function getCacheTime()
	{
		return isset($this->blockInfos['cacheTime']) ? $this->blockInfos['cacheTime'] : 'null';
	}
	
	public function getType()
	{
		return $this->blockInfos['type'];
	}
	
	public function getBeforeAll()
	{
		return isset($this->blockInfos['beforeAll']) ? $this->blockInfos['beforeAll'] : false;
	}	

	public function getAfterAll()
	{
		return isset($this->blockInfos['afterAll']) ? $this->blockInfos['afterAll'] : false;
	}

	public function getBlockActionClassName()
	{
		return $this->blockInfos['phpBlockClass'];
	}
	
	public function getVarExportInfo()
	{
		$array = array();
		foreach ($this->blockInfos as $name => $value) 
		{
			if ($name !== 'parameters' && $name !== 'metas')
			{
				$array[$name] = $value;
			}
		}
		
		return var_export($array, true);
	}
	
	public function getParametersInfoArray()
	{
		$array = array();
		foreach ($this->blockInfos['parameters'] as $propertyInfoArray) 
		{
			$array[] = new block_blockInfoParameterBuilder($propertyInfoArray);
		}
		return $array;
	}
	
	public function getAttributes()
	{
		$array = array();
		foreach ($this->blockInfos['parameters'] as $name => $propertyInfoArray) 
		{
			if (isset($propertyInfoArray['default-value']))
			{
				if (is_bool($propertyInfoArray['default-value']))
				{
					$array['__'.$name] = $propertyInfoArray['default-value'] ? 'true' : 'false';
				}
				else
				{
					$array['__'.$name] = $propertyInfoArray['default-value'];
				}
			}
		}
		return $array;
	}
	
	public function getSerializedMetas()
	{
		return serialize(array_keys($this->blockInfos['metas']));
	}
	
	public function getSerializedTitleMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'title') !== false) {$array[] = $name;}
		} 
		return serialize($array);
	}	

	public function getSerializedDescriptionMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'description') !== false) {$array[] = $name;}
		} 
		return serialize($array);
	}
	
	public function getSerializedKeywordsMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'keywords') !== false) {$array[] = $name;}
		} 
		return serialize($array);
	}
}

class block_blockInfoParameterBuilder
{
	private $propertyInfoArray;
	
	public function __construct($propertyInfoArray)
	{
		$this->propertyInfoArray = $propertyInfoArray;
		if (!isset($this->propertyInfoArray['type'])) {$this->propertyInfoArray['type'] = 'String';}
	}
	
	public function getName()
	{
		return $this->propertyInfoArray['name'];
	}
	
	public function getType()
	{
		return $this->propertyInfoArray['type'];
	}
	
	public function getPhpGetter()
	{
		return 'get' .ucfirst($this->getName());
	}
	
	public function hasDefaultValue()
	{
		return isset($this->propertyInfoArray['default-value']);
	}
	
	public function getDefaultValue()
	{
		return isset($this->propertyInfoArray['default-value']) ? $this->propertyInfoArray['default-value'] : null;
	}
	
	public function isDocument()
	{
		return strpos($this->getType(), 'modules_') === 0;
	}
	
	public function isArray()
	{
		return $this->isDocument() && isset($this->propertyInfoArray['max-occurs']) && $this->propertyInfoArray['max-occurs'] != 1;
	}
	
	public function getVarExportInfo()
	{
		return var_export($this->propertyInfoArray, true);
	}
}