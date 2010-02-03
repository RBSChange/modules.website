<?php
/**
 * @author <{$author}>
 * @package <{$fullPackage}>
 */
class <{$className}> extends block_BlockConfiguration
{
<{foreach from=$blockInfo->getParametersInfoArray() item=property}>
	<{if $property->getType() == "Boolean"}>	
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return $this->configurationArray['<{$property->getName()}>'] === 'true';
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}
	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
<{if $property->getDefaultValue() == 'true'}>
		return true;
<{else}>
		return false;
<{/if}>
<{else}>
		return false;
<{/if}>
	}	
	<{elseif $property->getType() == "Integer"}>
	
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return intval($this->configurationArray['<{$property->getName()}>']);
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}
	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
		return f_util_Convert::toInteger('<{$property->getDefaultValue()}>');
<{else}>
		return 0;
<{/if}>
	}	
	<{elseif $property->getType() == "Double"}>
	
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return floatval($this->configurationArray['<{$property->getName()}>']);
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}
	
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
		<{else}>
				
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
		<{/if}>	
	<{else}>	
	
	public function <{$property->getPhpGetter()}>()
	{
		if ($this->hasConfigurationParameter('<{$property->getName()}>'))
		{
			return $this->configurationArray['<{$property->getName()}>'];
		}
		return $this-><{$property->getPhpGetter()}>DefaultValue();
	}
	
	public function <{$property->getPhpGetter()}>AsHtml()
	{
		return f_util_HtmlUtils::textToHtml($this-><{$property->getPhpGetter()}>());
	}
	
	public function <{$property->getPhpGetter()}>DefaultValue()
	{
<{if $property->hasDefaultValue()}>
		return '<{$property->getDefaultValue()}>';
<{else}>
		return null;
<{/if}>
	}	
	<{/if}>
<{/foreach}>
}