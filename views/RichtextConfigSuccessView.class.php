<?php
class website_RichtextConfigSuccessView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
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
				foreach ($richtextConf as $style)
				{
					echo "<Style name=\"".f_Locale::translate($style["label"])."\" element=\"".$style["tag"]."\">";
					if (isset($style["attributes"]))
					{
						foreach ($style["attributes"] as $attrName => $attrValue)
						{
							echo "<Attribute name=\"".$attrName."\" value=\"".$attrValue."\" />";
						}
					}
					echo "</Style>";
				}
				echo "</Styles>";
				$xml = ob_get_clean();
				header("Content-Length:".strlen($xml));
				echo $xml;
				die();
				break;
			case 'css' :
				header('Content-Type:text/css');
				$this->setTemplateName('RichtextCss-' . $configset, K::HTML);
				break;
			default :
				header('Content-Type:application/x-javascript');
				$this->setTemplateName('RichtextConfig-' . $configset, K::HTML);
				break;
		}
	}
}