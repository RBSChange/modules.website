<?php
class website_EditContentSuccessView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$document = $request->getAttribute('document');
		$ps = website_PageService::getInstance();
		$pageContent = $ps->getContentForEdition($document);
		website_WebsiteModuleService::getInstance()->setCurrentPageId($document->getId());
		
		$this->setTemplateName('Website-EditContent-Success', K::XUL);
		$this->setAttribute('pageContent', $pageContent);
		$this->setAttribute('editorType', website_PageRessourceService::getInstance()->getTemplateType());
		
		$link = LinkHelper::getUIChromeActionLink('website', 'GetEditContentStylesheets')
			->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
		$this->setAttribute('allStyleUrl', '<?xml-stylesheet href="' . $link->getUrl() . '" type="text/css"?>');

		$link = LinkHelper::getUIChromeActionLink('website', 'GetEditContentStylesheets')
			->setQueryParameter('cmpref', $document->getId())
			->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
		$this->setAttribute('cssInclusion', '<?xml-stylesheet href="' . $link->getUrl() . '" type="text/css"?>');
		
		// include JavaScript
		$this->getJsService()->registerScript('modules.website.lib.editcontent');
		$this->setAttribute('scriptInclusion', $this->getJsService()->executeInline(K::XUL));
		
		$link = LinkHelper::getUIChromeActionLink('uixul', 'GetAdminJavascripts')
			->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
			$this->setAttribute('scriptlibrary', '<script type="application/x-javascript" src="' . $link->getUrl() . '"/>');
				
		$this->setAttribute('PAGEID', $document->getId());
		$this->setAttribute('PAGELANG', RequestContext::getInstance()->getLang());	
		$this->setAttribute('PAGEVERSION', $document->getDocumentversion());

		$ps = website_PageService::getInstance();
		$ancestors = $ps->getAncestorsOf($document);
		$path = '';
		
		if (($document->getDocumentModelName() == 'modules_website/pageversion'))
		{
			array_pop($ancestors);
		}
		
		$ancestors = array_reverse($ancestors);		
		array_pop($ancestors);		
		foreach ($ancestors as $ancestor)
		{
			$path = ' > ' . f_Locale::translate($ancestor->getLabel()) . $path;
		}		
		$path .= ' > ' . $document->getLabel();
		
		$this->setAttribute('PAGEPATH', f_util_StringUtils::quoteDouble(trim($path)));		
		$this->setAttribute('pageName', f_util_StringUtils::quoteDouble($document->getLabel()));
	}
	
	public static function normalizeContent($content)
	{
		$content = preg_replace('/blockwidth="[0-9]+%"/i', '', $content);
		$content = str_replace('&nbsp;', ' ', $content);
		$content = str_replace('class="preview"', '', $content);
		return $content;
	}
}