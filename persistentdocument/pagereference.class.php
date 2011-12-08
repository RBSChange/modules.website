<?php
class website_persistentdocument_pagereference extends website_persistentdocument_pagereferencebase 
{
	/**
	 * @return string
	 */
	public function getTreenodeLabel()
	{
		return $this->getDocumentService()->getPathOf(DocumentHelper::getDocumentInstance($this->getReferenceofid()));
	}
}