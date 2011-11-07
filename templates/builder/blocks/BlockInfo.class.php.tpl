<?php
/**
 * @author <{$author}>
 * @package <{$moduleName}>
 */
class <{$className}> extends block_BlockInfo
{
	/**
	 * @var <{$className}>
	 */
	private static $instance;
	
	/**
	 * @return <{$className}>
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new <{$className}>();
		}
		return self::$instance;
	}
	
	protected function __construct()
	{
		parent::__construct(<{$blockInfo->getVarExportInfo()}>);

<{foreach from=$blockInfo->getAttributes() item=value key=name}>
		$this->setAttribute("<{$name}>", <{$value|@var_export:true}>);
<{/foreach}>
		
<{foreach from=$blockInfo->getParametersInfoArray() item=parameterInfo}>
		$this->addNewBlockPropertyInfo(<{$parameterInfo->getVarExportInfo()}>);
<{/foreach}>
		
		$this->metas = unserialize('<{$blockInfo->getSerializedMetas()}>');
		$this->titleMetas = unserialize('<{$blockInfo->getSerializedTitleMetas()}>');
		$this->descriptionMetas = unserialize('<{$blockInfo->getSerializedDescriptionMetas()}>');
		$this->keywordsMetas = unserialize('<{$blockInfo->getSerializedKeywordsMetas()}>');
	}
}