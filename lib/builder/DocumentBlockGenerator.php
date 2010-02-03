<?php
class builder_DocumentBlockGenerator extends builder_BlockGenerator
{
	/**
	 * @var f_persistentdocument_PersistentDocumentModel
	 */
	protected $documentModel;

	/**
	 * @var String
	 */
	protected $blockType;

	/**
	 * @var String
	 */
	protected $blockIcon;

	/**
	 * @var Boolean
	 */
	protected $genTag;

	function setDocument($moduleName, $docName)
	{
		$this->documentModel = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName("modules_".$moduleName."/".$docName);
	}

	function setBlockType($type)
	{
		$this->blockType = $type;
	}

	function setBlockIcon($icon)
	{
		$this->blockIcon = $icon;
	}

	/**
	 * @return String the path of generated PHP class
	 */
	function generate()
	{
		$tplPath = f_util_FileUtils::buildWebeditPath("modules", "website", "templates", "builder", "documentblocks", "Block".ucfirst($this->blockType)."Action.php.tpl");
		if (!file_exists($tplPath))
		{
			throw new Exception("Unknown document block type ".$this->blockType." ($tplPath)");
		}
		$infoPath = f_util_FileUtils::buildWebeditPath("modules", "website", "templates", "builder", "documentblocks", "Block".ucfirst($this->blockType)."Action.php.inc");

		if (!file_exists($infoPath))
		{
			throw new Exception("$infoPath does not exists");
		}
		// Info Template can define $blockName && $genTag
		require($infoPath);
		if (!isset($blockName))
		{
			throw new Exception("$infoPath does not define \$blockName variable");
		}

		$this->genTag = isset($genTag) && $genTag;
		return $this->generateBlock(ucfirst($blockName), $this->genTag, $this->blockIcon);
	}

	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockTemplateInfo()
	{
		return array('documentblocks', 'Block'.ucfirst($this->blockType).'Action.php.tpl');
	}

	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockSuccessViewInfo()
	{
		return array('documentblocks', 'Block'.ucfirst($this->blockType).'Success.html.tpl');
	}

	protected function getAdditionalTplVariables()
	{
		$vars = parent::getAdditionalTplVariables();
		$vars['documentModel'] = $this->documentModel;
		$vars['genTag'] = $this->genTag;
		return $vars;
	}
}