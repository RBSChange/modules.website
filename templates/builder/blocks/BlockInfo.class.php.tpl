<?php
/**
 * @author <{$author}>
 * @package <{$fullPackage}>
 */
class <{$className}> extends block_BlockInfo
{
	/**
	 * @var <{$className}>
	 */
	private static $instance;

	/**
	 * Constructor of <{$className}>
	 */
	private function __construct()
	{
		$this->setId("<{$blockInfo->getId()}>");
		$this->setType("<{$blockInfo->getType()}>");
		$this->setLabel("<{$blockInfo->getLabel()}>");
		$this->setIcon("<{$blockInfo->getIcon()}>");
<{if $blockInfo->getImage() }>
		$this->setImage("<{$blockInfo->getImage()}>");
<{/if}>
<{if $blockInfo->getColor() }>
		$this->setColor("<{$blockInfo->getColor()}>");
<{/if}>
<{if $blockInfo->getEditable()}>
		$this->setEditable(true);
<{/if}>
<{if $blockInfo->getRef() }>
		$this->setRef("<{$blockInfo->getRef()}>");
<{/if}>
<{if $blockInfo->isHidden() }>
		$this->setHidden(true);
<{/if}>
<{if $blockInfo->requiresNewEditor() }>
		$this->setRequiresNewEditor(true);
<{/if}>
<{if $blockInfo->getDashboard() }>
		$this->setDashboard(true);
<{/if}>
<{if $blockInfo->hasContent() }>
$content = <<< EOD
<{$blockInfo->getContent()}>
EOD;
		$this->setContent($content);
<{/if}>

<{foreach from=$blockInfo->getAttributes() item=value key=name}>
		$this->setAttribute("<{$name}>", "<{$value}>");
<{/foreach}>

		$bs = block_BlockService::getInstance();
		$this->parametersInfoArray = array
		(
<{foreach from=$blockInfo->getParametersInfoArray() item=parameterInfo}>
		$bs->getNewBlockPropertyInfo('<{$parameterInfo->getName()}>', '<{$parameterInfo->getType()}>')
		->setLabel('<{$parameterInfo->getLabel()}>')
		->setHelpText('<{$parameterInfo->getHelpText()}>')
<{if $parameterInfo->hasListId()}>
		->setListId('<{$parameterInfo->getListId()}>')
<{/if}>
<{if $parameterInfo->getHidden()}>
		->setHidden(true)
<{/if}>
<{if $parameterInfo->hasDefaultValue()}>
		->setDefaultValue($this->getAttribute('__<{$parameterInfo->getName()}>'))
<{/if}>
<{if $parameterInfo->getMinOccurs()}>
		->setMinOccurs(<{$parameterInfo->getMinOccurs()}>)
<{/if}>
		->setMaxOccurs(<{$parameterInfo->getMaxOccurs()}>)
<{foreach from=$parameterInfo->getExtendedAttributeArray() key=name item=value}>
		->setExtendedAttribute('<{$name}>', '<{$value}>')
<{/foreach}>
		,
<{/foreach}>
		);
		
<{if $metas}>
		$this->metas = unserialize('<{$metas}>');
<{else}>
		$this->metas = array();
<{/if}>
<{if $titleMetas}>
		$this->titleMetas = unserialize('<{$titleMetas}>');
<{else}>
		$this->titleMetas = array();
<{/if}>
<{if $descriptionMetas}>
		$this->descriptionMetas = unserialize('<{$descriptionMetas}>');
<{else}>
		$this->descriptionMetas = array();
<{/if}>
<{if $keywordsMetas}>
		$this->keywordsMetas = unserialize('<{$keywordsMetas}>');
<{else}>
		$this->keywordsMetas = array();
<{/if}>
	}
	
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
}