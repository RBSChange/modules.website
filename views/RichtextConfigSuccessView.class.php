<?php
class website_RichtextConfigSuccessView extends change_View
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$configset = $request->getAttribute('configset');
		$subset = $request->getAttribute('subset');
		switch ($subset)
		{
			case 'styles' :
				header('Content-Type:text/xml');
				$richtextConf = uixul_RichtextConfigService::getInstance()->getConfigurationArray();
				ob_start();
				echo '<?xml version="1.0" encoding="utf-8" ?><Styles>';
				echo "<Style name=\"Paragraphe\" element=\"p\"><Attribute name=\"class\" value=\"normal\" /></Style>";
				$ls = LocaleService::getInstance();
				foreach ($richtextConf as $style)
				{
					$label = $style->hasAttribute('labeli18n') ? $ls->trans($style->getAttribute('labeli18n'), array('ucf')) : $style->getAttribute('label');
					echo "<Style name=\"" . $label . "\" element=\"" . $style["tag"] . "\">";
					echo "<Attribute name=\"class\" value=\"" . $style["class"] . "\" />";
					if (!$style["block"])
					{
						echo "<Attribute name=\"block\" value=\"false\" />";
					}
					echo "</Style>";
				}
				echo "</Styles>";
				$xml = ob_get_clean();
				header("Content-Length:" . strlen($xml));
				echo $xml;
				die();
				break;
			case 'css' :
				header('Content-Type:text/css');
				$this->setTemplateName('RichtextCss-' . $configset, 'html');
				break;
			default :
				header('Content-Type:application/x-javascript');
				$this->setTemplateName('RichtextConfig-' . $configset, 'html');
				break;
		}
	}
}