<?php
/**
 * @author <{$author}>
 * @package <{$moduleName}>
 */
class <{$className}> extends block_BlockConfiguration
{
	/**
	 * @return string
	 */
	public function getRequestModule()
	{
		return "<{$blockInfo->getRequestModule()}>";
	}

	/**
	 * @return string
	 */
	public function getTemplateModule()
	{
		return "<{$blockInfo->getTemplateModule()}>";
	}
	
	/**
	 * @return string
	 */
	public function getBlockActionClassName()
	{
		return "<{$blockInfo->getBlockActionClassName()}>";
	}
	
	/**
	 * @return boolean
	 */
	public function isCacheEnabled()
	{
		return <{if $blockInfo->isCached()}>true<{else}>false<{/if}>;
	}
	
	/**
	 * @return integer
	 */
	public function getCacheTtl()
	{
		return <{$blockInfo->getCacheTime()}>;
	}
	
	/**
	 * @return array
	 */
	public function getConfiguredCacheKeys()
	{
		return <{$configuredCacheKeys}>;
	}
	
	/**
	 * @return array
	 */
	public function getConfiguredCacheDeps()
	{
		return <{$configuredCacheDeps}>;
	}
	
<{if $blockInfo->getBeforeAll()}>	
	/**
	 * @return boolean
	 */
	public function getBeforeAll()
	{
		return true;
	}
<{/if}>
<{if $blockInfo->getAfterAll()}>		
	/**
	 * @return boolean
	 */
	public function getAfterAll()
	{
		return true;
	}
<{/if}>
<{foreach from=$blockInfo->getParametersInfoArray() item=property}>
	<{if $property->getType() == "Boolean"}>

	/**
	 * @return <{$property->getPHPType()}>
	 */
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return $this->configurationArray['<{$property->getName()}>'] === 'true';
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}

	/**
	 * @return <{$property->getPHPType()}>
	 */	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
<{if $property->getDefaultValue()}>
		return true;
<{else}>
		return false;
<{/if}>
<{else}>
		return false;
<{/if}>
	}	
	<{elseif $property->getType() == "Integer"}>

	/**
	 * @return <{$property->getPHPType()}>
	 */
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return intval($this->configurationArray['<{$property->getName()}>']);
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}

	/**
	 * @return <{$property->getPHPType()}>
	 */	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
<{if $property->getDefaultValue() == null}>
		return null;
<{else}>
		return f_util_Convert::toInteger('<{$property->getDefaultValue()}>');
<{/if}>
<{else}>
		return 0;
<{/if}>
	}	
	<{elseif $property->getType() == "Double"}>

	/**
	 * @return <{$property->getPHPType()}>
	 */	
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return floatval($this->configurationArray['<{$property->getName()}>']);
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}

	/**
	 * @return <{$property->getPHPType()}>
	 */	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
		return f_util_Convert::toFloat('<{$property->getDefaultValue()}>');
<{else}>
		return 0.0;
<{/if}>
	}
	<{elseif $property->isDocument()}>
		<{if $property->isArray()}>

	/**
	 * @return <{$property->getPHPType()}>
	 */		
	public function <{$property->getPhpGetter()}>()
	{
		$result = array();
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			$ids = explode(',', $this->configurationArray['<{$property->getName()}>']);
			foreach ($ids as $id) 
			{
				if (is_numeric($id))
				{
					$result[] = DocumentHelper::getDocumentInstance($id);
				}
			}
		}
		return $result;
	}

	/**
	 * @return <{$property->getPHPType()}>
	 */	
	public function <{$property->getPhpGetter()}>Safe()
	{
		$result = array();
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			$ids = explode(',', $this->configurationArray['<{$property->getName()}>']);
			foreach ($ids as $id) 
			{
				$doc = DocumentHelper::getDocumentInstanceIfExists($id);
				if ($doc !== null)
				{
					$result[] = $doc;
				}
			}
		}
		return $result;
	}

	/**
	 * @return integer[]
	 */	
	public function <{$property->getPhpGetter()}>Ids()
	{
		$result = array();
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			$ids = explode(',', $this->configurationArray['<{$property->getName()}>']);
			foreach ($ids as $id) 
			{
				if (is_numeric($id))
				{
					$result[] = intval($id);
				}
			}
		}
		return $result;
	}	
		<{else}>

	/**
	 * @return <{$property->getPHPType()}>
	 */				
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			if (is_numeric($this->configurationArray['<{$property->getName()}>']))
			{
				return DocumentHelper::getDocumentInstance($this->configurationArray['<{$property->getName()}>']);
			}
		}
		return null;
	}

	/**
	 * @return <{$property->getPHPType()}>|NULL
	 */	
	public function <{$property->getPhpGetter()}>Safe()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return DocumentHelper::getDocumentInstanceIfExists($this->configurationArray['<{$property->getName()}>']);
		}
		return null;
	}

	/**
	 * @return integer|NULL
	 */
	public function <{$property->getPhpGetter()}>Id()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			if (is_numeric($this->configurationArray['<{$property->getName()}>']))
			{
				return intval($this->configurationArray['<{$property->getName()}>']);
			}
		}
		return null;
	}		
		<{/if}>	
	<{else}>

	/**
	 * @return string|NULL
	 */		
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return $this->configurationArray['<{$property->getName()}>'];
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}
	
	/**
	 * @return string|NULL
	 */		
	public function <{$property->getPhpGetter()}>AsHtml()
	{
<{if $property->getType() == "XHTMLFragment"}>
		return f_util_HtmlUtils::renderHtmlFragment($this-><{$property->getPhpGetter()}>());
<{else}>
		return f_util_HtmlUtils::textToHtml($this-><{$property->getPhpGetter()}>());
<{/if}>	
	}
	
	/**
	 * @return string|NULL
	 */		
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
		return <{$property->getDefaultValue()|@var_export:true}>;
<{else}>
		return null;
<{/if}>
	}	
	<{/if}>
<{/foreach}>

}