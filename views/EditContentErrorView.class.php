<?php
class website_EditContentErrorView extends f_view_BaseView
{

    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
        $this->setTemplateName('Website-EditContent-Error', K::XUL);

        $this->setAttribute('error', f_Locale::translateUI("&modules.website.bo.general.Page-edition-error-message-" . $request->getAttribute('error') . ";"));

        if ($request->hasAttribute('document'))
        {
        	$document = $request->getAttribute('document');

        	$content = self::normalizeContent($document->getContentForLang(RequestContext::getInstance()->getLang()));

        	$this->setAttribute('content', $content);
        }

        $modules = array('generic', 'uixul', 'website');
        $ss = website_StyleService::getInstance();
        foreach ($modules as $module)
        {
			$ss->registerStyle('modules.' . $module . '.backoffice');
        }
        $this->setAttribute('cssInclusion', $ss->execute(K::XUL));
    }

    public static function normalizeContent($content)
    {
        $content = preg_replace('/blockwidth="[0-9]+%"/i', '', $content);
        $content = str_replace('&nbsp;', ' ', $content);
        $content = str_replace('class="preview"', '', $content);
        $content = str_replace('&', '&amp;', htmlentities($content));
        $content = str_replace('&amp;lt;', "&lt;", $content);
        $content = str_replace('&amp;gt;', '&gt;', $content);
        $content = str_replace('&amp;quot;', '&quot;', $content);
        $content = str_replace('&amp;nbsp;', ' ', $content);
        return $content;
    }

}