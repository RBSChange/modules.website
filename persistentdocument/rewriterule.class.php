<?php
/**
 * Class where to put your custom methods for document website_persistentdocument_rewriterule
 * @package modules.website.persistentdocument
 */
class website_persistentdocument_rewriterule extends website_persistentdocument_rewriterulebase 
{
	/**
	 * @return array
	 */
	public function getRuleData()
	{
		$data = $this->getDefinition();
		$ruleData = $data == null ? array() : unserialize($data);
		if (count($ruleData)) 
		{
			$ruleData['id'] = $this->getId();
		}
		else
		{
			$ruleData = $this->getDocumentService()->generateRuleData($this);
		}
		return $ruleData;
	}
	
	/**
	 * @param array $ruleData
	 */
	public function setRuleData($ruleData)
	{
		if (f_util_ArrayUtils::isEmpty($ruleData))
		{
			$this->setDefinition(null);
		}
		else
		{
			$this->setDefinition(serialize($ruleData));
		}
	}	
}