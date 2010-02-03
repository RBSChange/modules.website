<?php
class website_InfoBlockSuccessView extends f_view_BaseView
{

    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
        
        $rq = RequestContext::getInstance();
        try 
        {
            $rq->beginI18nWork($rq->getUILang());
            
    		$this->getStyleService()
                ->registerStyle('modules.website.backoffice')
                ->registerStyle('modules.uixul.backoffice')
                ->registerStyle('modules.generic.backoffice')
                ->registerStyle('modules.website.infoblock');
            
            $this->setAttribute('cssInclusion', $this->getStyleService()->execute(K::XUL));
        
      
            $this->setTemplateName('Page-InfoBlock-Success', K::XUL);
            $blockType = $request->getParameter(K::COMPONENT_ACCESSOR);
            
           
            $this->setAttribute('blockType', $blockType);
    		$rq->endI18nWork();
        } 
        catch (Exception $e)
        {
            Framework::exception($e);
            $rq->endI18nWork($e);   
        }
    }
}
