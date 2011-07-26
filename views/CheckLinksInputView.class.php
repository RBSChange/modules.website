<?php
class website_CheckLinksInputView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$rq = RequestContext::getInstance();

		$this->setAttribute('lang', $rq->getLang());

		$rq->beginI18nWork($rq->getUILang());

		// Set our template
		$this->setTemplateName('Website-CheckLinks-Input', K::XUL);

		$modules = array();
		$availableModules = ModuleService::getInstance()->getPackageNames();
		foreach ($availableModules as $availableModuleName)
		{
			$modules[] = substr($availableModuleName, strpos($availableModuleName, '_') + 1);
		}

		foreach ($modules as $module)
		{
			if (($module == 'website') || ($module == 'uixul') || ($module == 'generic'))
			{
				$this->getStyleService()->registerStyle('modules.' . $module . '.backoffice');
			}
		}
		$this->setAttribute('cssInclusion', $this->getStyleService()->execute(K::XUL));

		$this->getJsService()->registerScript('modules.uixul.lib.default');
		$this->setAttribute('scriptInclusion', $this->getJsService()->executeInline(K::XUL));

		$this->setAttribute('ids', implode(', ', $request->getAttribute('ids')));

		$this->setAttribute('count', count($request->getAttribute('ids')));

		$rq->endI18nWork();
	}
}