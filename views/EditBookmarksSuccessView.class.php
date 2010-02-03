<?php
class website_EditBookmarksSuccessView extends f_view_BaseView
{

    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
        $rq = RequestContext::getInstance();

        $rq->beginI18nWork($rq->getUILang());

        // Set our template
        $this->setTemplateName('Page-EditBookmarks-Success', K::XUL);

        $modules = array();
        $availableModules = ModuleService::getInstance()->getModules();
        foreach ($availableModules as $availableModuleName)
        {
            $modules[] = substr($availableModuleName, strpos($availableModuleName, '_') + 1);
        }

        foreach ($modules as $module)
        {
            if (($module == 'website')
            || ($module == 'uixul')
            || ($module == K::GENERIC_MODULE_NAME))
            {
                $this->getStyleService()->registerStyle('modules.' . $module . '.backoffice');
            }
        }
        $this->setAttribute('cssInclusion', $this->getStyleService()->execute(K::XUL));

		$this->getJsService()->registerScript('modules.uixul.lib.default');
        $this->setAttribute('scriptInclusion', $this->getJsService()->executeInline(K::XUL));

        $rq->endI18nWork();
    }
}
