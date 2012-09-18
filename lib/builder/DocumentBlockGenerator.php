<?php
class builder_DocumentBlockGenerator extends builder_BlockGenerator
{
	/**
	 * @var f_persistentdocument_PersistentDocumentModel
	 */
	protected $documentModel;
	
	/**
	 * @var string
	 */
	protected $blockType;
	
	/**
	 * @var string
	 */
	protected $blockIcon = 'block';
	
	/**
	 * @var boolean
	 */
	protected $genTag;
	
	/**
	 * @param string $moduleName
	 * @param string $docName
	 */
	public function setDocument($moduleName, $docName)
	{
		$this->documentModel = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName("modules_" . $moduleName . "/" . $docName);
	}
	
	/**
	 * @param string $icon
	 */
	public function setBlockType($type)
	{
		$this->blockType = $type;
	}
	
	/**
	 * @param string $icon
	 */
	public function setBlockIcon($icon)
	{
		$this->blockIcon = $icon;
	}
	
	/**
	 * @return string the path of generated PHP class
	 */
	public function generate()
	{
		$tplPath = f_util_FileUtils::buildProjectPath("modules", "website", "templates", "builder", "documentblocks", "Block" . ucfirst($this->blockType) . "Action.php.tpl");
		if (!file_exists($tplPath))
		{
			throw new Exception("Unknown document block type " . $this->blockType . " ($tplPath)");
		}
		$infoPath = f_util_FileUtils::buildProjectPath("modules", "website", "templates", "builder", "documentblocks", "Block" . ucfirst($this->blockType) . "Action.php.inc");
		
		if (!file_exists($infoPath))
		{
			throw new Exception("$infoPath does not exists");
		}
		// Info Template can define $blockName && $genTag
		$blockName = null;
		$genTag = null;
		require ($infoPath);
		if (!isset($blockName))
		{
			throw new Exception("$infoPath does not define \$blockName variable");
		}
		
		$this->genTag = isset($genTag) && $genTag;
		return $this->generateBlock(ucfirst($blockName), $this->genTag, $this->blockIcon);
	}
	
	/**
	 * @return boolean
	 */
	public function hasTag()
	{
		return $this->genTag;
	}
	
	/**
	 * @return string[] [$folder, $tplName]
	 */
	protected function getBlockTemplateInfo()
	{
		return array('documentblocks', 'Block' . ucfirst($this->blockType) . 'Action.php.tpl');
	}
	
	/**
	 * @return string[] [$folder, $tplName]
	 */
	protected function getBlockSuccessViewInfo()
	{
		return array('documentblocks', 'Block' . ucfirst($this->blockType) . 'Success.html.tpl');
	}
	
	/**
	 * @return string[] [$folder, $tplName]
	 */
	protected function getBlocksXmlInfo()
	{
		return array('documentblocks', 'Block' . ucfirst($this->blockType) . '.xml.tpl');
	}
	
	/**
	 * @return array
	 */
	protected function getAdditionalTplVariables()
	{
		$vars = parent::getAdditionalTplVariables();
		$vars['documentModel'] = $this->documentModel;
		$vars['genTag'] = $this->genTag;
		return $vars;
	}
}