<?php
class website_PHPTAL_CHANGE
{
	/**
	 * @param PHPTAL_Namespace_CHANGE $namespaceCHANGE
	 */
	public static function addAttributes($namespaceCHANGE)
	{
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('block', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('cache', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('currentlink', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('currentpagelink', 30));
		
		// Forms
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('submit', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('textinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('hiddeninput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('dateinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('booleaninput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('radioinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('checkboxinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('richtextinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('bbcodeinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('errors', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('messages', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('form', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('field', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('textarea', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('fieldlabel', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('label', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('passwordinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('fileinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('selectinput', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('listmultifield', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('uploadfield', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('documentpicker', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('durationinput', 30));
		
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('tabs', 32));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('tab', 32));
		
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('link', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('menu', 9));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('menuitem', 9));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('paginator', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('popup', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('richtext', 30));
		
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('chart', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('piechart', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('linechart', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('barchart', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('datatable', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('producer', 30));
		
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('code', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('h', 29));
		
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('actionlink', 30));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeSurround('permission', 7));
		$namespaceCHANGE->addAttribute(new PHPTAL_NamespaceAttributeReplace('loadhandler', 30));
	}
}
