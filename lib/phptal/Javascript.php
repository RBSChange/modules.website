<?php
/**
 * @package phptal.php.attribute
 * @author INTbonjF
 * 2007-11-07
 */
class PHPTAL_Php_Attribute_CHANGE_Javascript extends PHPTAL_Php_Attribute
{
    /**
     * Called before element printing.
     * Default implementation is for backwards compatibility only. Please always override both before() and after().
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		$pageContext = website_BlockController::getInstance()->getContext();
    	if ($pageContext !== null && $pageContext->inBackofficeMode())
    	{
		// This is a page rendered in Backoffice
    		return;
    	}
        // split attributes to translate
        $expressions = $codewriter->splitExpression($this->expression);

        // foreach attribute
        foreach ($expressions as $exp)
        {
            list($attribute, $value) = $this->parseSetExpression($exp);
            $attribute = trim($attribute);
            switch ($attribute)
            {
            	case 'src':
            		$src = $codewriter->evaluateExpression($value);
            		$code = '$jsService = website_JsService::newInstance();$jsService->registerScript('.$src.');';
					$codewriter->pushCode($code);
					$codewriter->doEchoRaw('$jsService->execute("html")');
					break;
            	case 'head':
            		$src = $codewriter->evaluateExpression($value);
            		$code = '$wp = $ctx->__get("website_page");if ($wp !== null) {$wp->addScript('.$src.');} else {website_JsService::getInstance()->registerScript('.$src.');};';
					$codewriter->pushCode($code);
					break;
            	default:
            		$array = $codewriter->evaluateExpression($value);
					$codewriter->pushHTML('<script type="text/javascript">');
		        	$codewriter->doEchoRaw('"var '.$attribute.' = " . f_util_StringUtils::JSONEncode('.$array.') . ";"');
					$codewriter->pushHTML('</script>');
            		break;
            }
        }
    }
    
    /**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {

    }
}