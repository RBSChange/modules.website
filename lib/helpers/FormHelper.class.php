<?php
class website_FormHelper
{
	/**
	 * @var PHPTAL_Context
	 */
	private static $talContext;
	
	/**
	 * @var String
	 */
	private static $formId;
	
	/**
	 * @var String
	 */
	private static $relKey;

	private static $wasCalled = false;

	/**
	 * Called on start of "change:form"
	 * @param array $params
	 * @param PHPTAL_Context $talContext
	 */
	public static function initialize($params, $talContext)
	{
		$html = "";

		self::$talContext = $talContext;
		$controller = website_BlockController::getInstance();
		self::$showFieldErrors = $params['showErrors'];
		self::$currentAction = $controller->getProcessedAction();
		self::$currentBlockId = self::$currentAction->getBlockId();
		self::$currentActionRequest = $controller->getRequest();
		self::$moduleName = (isset($params["module"])) ? $params["module"] : self::$currentAction->getModuleName();
		self::$context = $controller->getContext();

		if (!self::$wasCalled)
		{
			$useQTipHelp = f_util_Convert::toBoolean(Framework::getConfigurationValue("modules/website/forms-use-qtip-help"));
			$useQTipError = f_util_Convert::toBoolean(Framework::getConfigurationValue("modules/website/forms-use-qtip-error"));
			
			if (true && ($useQTipError || $useQTipHelp))
			{
				self::$context->addScript('modules.website.lib.js.jquery-qtip');

				$html .= "<script type=\"text/javascript\">jQuery(document).ready(function() {";
				if ($useQTipHelp)
				{
					$helpOptions = Framework::getConfigurationValue("modules/website/forms-qtip-help-options", "position: {
         corner: {
            target: 'topLeft',
            tooltip: 'bottomLeft'
         }
      },
      style: {
         name: 'cream',
         padding: '7px 13px',
         width: {
            max: 300,
            min: 0
         },
         tip: true
      }");
					$html .= "
	var qTipOptions = {
      $helpOptions
   };";
				}
				if ($useQTipError)
				{
					$errorOptions = Framework::getConfigurationValue("modules/website/forms-qtip-error-options", "position: {
         corner: {
            target: 'topRight',
            tooltip: 'bottomLeft'
         }
      },
      style: {
         name: 'red',
         padding: '7px 13px',
         width: {
            max: 300,
            min: 0
         },
         tip: true
      }");
					$html .= "
   var qTipErrorOptions = {
      $errorOptions
   };";
				}
				
				if ($useQTipHelp && $useQTipError)
				{
				$html .= "
   jQuery('form.change-form input[title], form.change-form textarea[title], form.change-form label[title]').each(function(i) {
   		var elem = jQuery(this);
   		elem.qtip(elem.hasClass('error') ? qTipErrorOptions : qTipOptions);
   });";
				}
				elseif ($useQTipError)
				{
					$html .= "
   jQuery('form.change-form label[title].error').each(function(i) {
   		var elem = jQuery(this);
   		elem.qtip(qTipErrorOptions);
   });";
				}
				elseif ($useQTipHelp)
				{
					$html .= "
   jQuery('form.change-form input[title], form.change-form textarea[title]').each(function(i) {
   		var elem = jQuery(this);
   		elem.qtip(qTipOptions);
   });";
				}
				$html .="
});</script>";
			}
			self::$wasCalled = true;
		}

		self::initBean($params);
		self::$formCounter++;
		if (isset($params["id"]))
		{
			$idParam = $params["id"];
		}
		else
		{
			$idParam = null;
		}
		self::$formId = self::getCurrentFormId($idParam);

		$formAttributes = array();
		$formAttributes["method"] = self::getValue($params, "method", "post");
		$formAttributes["action"] = self::getCurrentFormAction($idParam, $params);
		$formAttributes["id"] = self::$formId;
		$formAttributes["name"] = self::$formId;
		$formAttributes["enctype"] = self::getValue($params, "enctype", "multipart/form-data");
		$formAttributes["class"] = self::getValue($params, "class", "cmxform change-form");
		if (isset($params["onsubmit"]))
                {
                        $formAttributes["onsubmit"] = $params["onsubmit"];
                }
		self::addClassParam($formAttributes, "change-form");

		$html .= "<form" . f_util_HtmlUtils::buildAttributes($formAttributes).">";
		if (self::$relKey !== null)
		{
			$html .= "<div style=\"display: none;\"><input name=\"".self::$moduleName."Param[website_FormHelper_relkey]\" type=\"hidden\" ".f_util_HtmlUtils::buildAttribute("value", self::$relKey)."></a></div>";
		}
		return $html;
	}

	private static function getValue($params, $name, $defaultValue = "")
	{
		if (isset($params[$name]))
		{
			return $params[$name];
		}
		return $defaultValue;
	}

	/**
	 * Called on end of "change:form"
	 */
	public static function finalize()
	{
		self::$currentAction = null;
		self::$currentBlockId = null;
		self::$currentActionRequest = null;
		self::$moduleName = null;
		self::$context = null;
		self::$bean = null;
		self::$hasBean = false;
		self::$talContext = null;
		self::$formId = null;
		return "</form>";
	}

	/**
	 * @param String $idParam
	 * @param array<String, String> $params
	 * @return String
	 */
	private static function getCurrentFormAction($idParam = null, $params)
	{
		$action = "";
		if (isset($params["action"]))
		{
			$action .= $params["action"];
		}
		elseif (isset($params["tag"]))
		{
			$action .= LinkHelper::getTagUrl($params["tag"], RequestContext::getInstance()->getLang());
		}
		if ($idParam !== null && strpos($action, "#") === false)
		{
			return $action."#".self::$formId;
		}
		return $action;
	}

	/**
	 * @return String
	 */
	private static function getCurrentFormId($idParam = null)
	{
		$formId = self::$moduleName . '_' . self::$currentBlockId;
		if (!f_util_StringUtils::isEmpty($idParam))
		{
			self::$relKey = $idParam;
			$formId .= '_'.$idParam;
		}
		else
		{
			self::$relKey = null;
		}
		return $formId;
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderFieldlabel($params)
	{
		$propertyName = $params['name'];
		if (!self::$hasBean)
		{
			if (Framework::isDebugEnabled())
			{
				return "<strong>change:labelfield for property $propertyName has no attached bean</strong>";
			}
			return "";
		}

		if (!BeanUtils::hasProperty(self::$bean, $propertyName))
		{
			return "<strong>bean '".BeanUtils::getClassName(self::$bean)."' has no property $propertyName</strong>";
		}
		$beanPropertyInfo = BeanUtils::getPropertyInfo(self::$bean, $propertyName);
		if ($beanPropertyInfo->isRequired())
		{
			self::setDefaultValue("required", true, $params);
		}
		self::setDefaultValue('label', self::getLabelizedLocale($beanPropertyInfo->getLabelKey()), $params);
		self::setDefaultValue("id", self::buildFieldId($params['name']), $params);
		return self::buildLabel($params);
	}
	
	private static $labelParams;
	
	/**
	 * Warning: change:label is a surrounder => renderLabel does close label element
	 * @param array $params
	 * @return String
	 */
	public static function renderLabel($params)
	{
		self::setDefaultValue("id", self::buildFieldId($params['name']), $params);
		$labelCode = self::buildLabel($params, false);
		self::$labelParams = $params; 
		return $labelCode;
	}
	
	public static function endLabel()
	{
		$result = "";
		if (isset(self::$labelParams['required']) && self::$labelParams['required'] == true)
		{
			$title = LocaleService::getInstance()->transFo("&modules.website.frontoffice.this-field-is-mandatory;");
			$result .= ' <span class="requiredsymbol" '.f_util_HtmlUtils::buildAttribute("title", $title).'>*</span>';
		}
		self::$labelParams = null;
		
		return $result."</label>";
	}

	private static function getParameterName($fieldName)
	{
		$index = strpos($fieldName, "[");
		if ($index !== false)
		{
			return substr($fieldName, 0, $index);
		}
		return $fieldName;
	}

	/**
	 * TODO: refactor with buildSelectProperty
	 * @param Array $params
	 * @return String
	 */
	public static function renderSelectinput($params, $ctx)
	{
		self::setDefaultValue('required', false, $params);
		if (isset($params['absname']))
		{
			$name = $params['absname'];
		} else if (isset($params['name']))
		{
			$name = $params['name'];
		}
		self::buildNameAndId($params);
		if (!array_key_exists('value', $params))
		{
			$paramName = self::getParameterName($name);
			$value = self::buildPropertyValue($paramName);
			if ($value === null)
			{
				if (isset($params["multiple"]))
				{
					// TODO: deprecate values & default-values: unique entry point !
					if (isset($params["values"]))
					{
						$value = $params["values"];
					}
					elseif (isset($params["default-values"]))
					{
						$value = $params["default-values"];
					}
					elseif (isset($params["default-value"]))
					{
						$value = $params["default-value"];
					}
					if ($value !== null && is_string($value))
					{
						$value = explode(",", $value);
					}
				}
				elseif (isset($params["default-value"]))
				{
					$value = $params["default-value"];
				}
			}
			if (is_array($value))
			{
				$value = array_flip($value);
			}
			$params['value'] = $value;
		}
		$result = '';

		// FIX#785: Add a hidden field to always have a value for this field in the request.
		if (isset($params['multiple']))
		{
			$hiddenParams = $params;
			$hiddenParams['value'] = '';
			$hiddenParams['id'] = '';
			$hiddenParams['ignoreErrors'] = true;
			unset($hiddenParams['multiple']);
			$result .= self::renderHiddeninput($hiddenParams);
		}

		if (self::isLabeled($params))
		{
			$result .= self::buildLabel($params);
		}

		$result .= '<select ';
		foreach ($params as $name => $value)
		{
			if (isset(self::$includeAttributes[$name]) && $name != 'value')
			{
				if ($name == 'name' && isset($params["multiple"]))
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $value . '[]') . ' ';
				}
				else
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $value) . ' ';
				}
			}
		}
		$result .= '>';

		if (isset($params['listId']))
		{
			$list = list_ListService::getInstance()->getByListId($params['listId']);
			$listArray = array();
			if ($list !== null)
			{
				foreach ($list->getItems() as $listItem)
				{
					$listArray[$listItem->getValue()] = $listItem->getLabel();
				}
			}
		}
		elseif (isset($params['documentList']))
		{
			$listArray = array();
			$documentList = self::contextSafeGet($params['documentList'], $ctx);
			if ($documentList !== null)
			{
				foreach ($documentList as $document)
				{
					$listArray[$document->getId()] = $document->getLabel();
				}
			}
		}
		elseif (isset($params['documentIdList']))
		{
			$listArray = array();
			$documentList = self::contextSafeGet($params['documentIdList'], $ctx);
			if ($documentList !== null)
			{
				foreach ($documentList as $documentId)
				{
					$listArray[$documentId] = DocumentHelper::getDocumentInstance($documentId)->getLabel();
				}
			}
		}
		elseif (isset($params['list']))
		{
			$listArray = $params['list'];
		}
		else
		{
			$listArray = array();
		}

		if (!isset($params["nopreamble"]) && !isset($params["multiple"]) && !array_key_exists("", $listArray))
		{
			$tmpArray = array("" => LocaleService::getInstance()->transFO("m.website.frontoffice.selectoption"));
			foreach ($listArray as $key => $value)
			{
				$tmpArray[$key] = $value;
			}
			$listArray = $tmpArray;
		}
		return $result . self::buildArrayOptions($listArray, $params['value']) . '</select>' . self::buildFieldErrors($params);
	}

	/**
	 * @param String $name
	 * @param PHPTAL_Context $ctx
	 * @return mixed
	 */
	private static function contextSafeGet($name, $ctx = null)
	{
		if ($ctx === null)
		{
			$ctx = self::$talContext;
			if ($ctx === null)
			{
				// FIXME ?
				return null;
			}
		}
		$oldThrow = $ctx->__nothrow;
		$ctx->__nothrow = true;
		$value = $ctx->__get($name);
		$ctx->__nothrow = $oldThrow;
		return $value;
	}

	/**
	 * @param String $name
	 * @param PHPTAL_Context $ctx
	 * @return mixed
	 */
	private static function contextSet($name, $value, $ctx = null)
	{
		if ($ctx === null)
		{
			$ctx = self::$talContext;
			if ($ctx === null)
			{
				throw new Exception("PHPTal context is unreachable");
			}
		}
		$ctx->__set($name, $value);
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderRichtextinput($params)
	{
		$name = self::buildNameAndId($params);
		if (!array_key_exists('value', $params))
		{
			$value = self::getFieldValue($name);
			if ($value === null && isset($params['default-value']))
			{
				$value = $params['default-value'];
			}
			$params['value'] = $value;
		}

		$returnValue = '';
		if (self::isLabeled($params))
		{
			$returnValue .= self::buildLabel($params);
		}

		self::setDefaultValue('editor', 'wysiwyg', $params);
		switch ($params['editor'])
		{
			case 'wysiwyg' :
				self::setDefaultValue('width', '450px', $params);
				self::setDefaultValue('height', '300px', $params);
				self::setDefaultValue('configset', 'ChangeDefault', $params);
				$returnValue .= self::buildRichtextinput($params);
				break;

			case 'bbcode' :
				$returnValue .= self::buildBBeditorinput($params);
				break;
		}
		return $returnValue;
	}
	
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderBbcodeinput($params)
	{
		$name = self::buildNameAndId($params);
		if (!array_key_exists('value', $params))
		{
			$value = self::getFieldValue($name);
			if ($value === null && isset($params['default-value']))
			{
				$value = $params['default-value'];
			}
			$params['value'] = $value;
		}

		$returnValue = '';
		if (self::isLabeled($params))
		{
			if (f_util_StringUtils::endsWith($name, 'AsBBCode'))
			{
				$params['shortInputName'] = substr($params['shortInputName'], 0, -8);
			}
			$returnValue .= self::buildLabel($params);
			$params['shortInputName'] = $name;
			$params['labeled'] = false;
		}

		$returnValue .= website_BBCodeEditor::getInstance()->buildEditor($params, self::$context);
		return $returnValue;
	}

	/**
	 * @param array $params
	 * @return String
	 */
	private function buildRichtextinput($params)
	{
		$name = $params['name'];
		if (self::$context !== null && self::$context->hasAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false))
		{
			return '<div style="background-color:#EEE;border:1px solid #CCC;width:' . $params['width'] . ';height:' . $params['height'] . '"><p>' . LocaleService::getInstance()->transFO('m.website.frontoffice.form.richtext') . '</p></div>';
		}
		$editor = new FCKeditor($name);
		$editor->ToolbarSet = 'Change';
		$editor->Config['CustomConfigurationsPath'] = '/index.php?module=website&action=RichtextConfig&configset=' . $params['configset'];
		$editor->Value = $params['value'];
		$editor->Width = $params['width'];
		$editor->Height = $params['height'];
		return $editor->CreateHtml();
	}

	/**
	 * @param array $params
	 * @return String
	 */
	private function buildBBeditorinput($params)
	{
		return website_BBCodeService::getInstance()->buildEditor($params, self::$context);
	}

	private static $documentPickerCalled = false;

	/**
	 * @param array<String, mixed> $params
	 * @param PHPTAL_Context $ctx
	 * @return String the XHTML output
	 */
	public static function renderDocumentpicker($params, $ctx)
	{
		// TODO: refactor with renderField
		$propertyName = $params['name'];
		if (!self::$hasBean)
		{
			if (Framework::isDebugEnabled())
			{
				return "<strong>change:documentpicker for property $propertyName has no attached bean</strong>";
			}
			return "";
		}

		if (!BeanUtils::hasProperty(self::$bean, $propertyName))
		{
			return "<strong>bean '".BeanUtils::getClassName(self::$bean)."' has no property $propertyName</strong>";
		}

		$beanPropertyInfo = BeanUtils::getPropertyInfo(self::$bean, $propertyName);
		switch ($beanPropertyInfo->getDocumentType())
		{
			case "modules_website/page":
				$chooserType = 'Page';
				break;
			case "modules_media/media":
			case "modules_media/file":
				$chooserType = 'File';
				break;
			default:
				throw new Exception("Unsupported document type ".$beanPropertyInfo->getDocumentType());
		}

		self::setDefaultValue('label', self::getLabelizedLocale($beanPropertyInfo->getLabelKey()), $params);
		self::setDefaultValue('required', $beanPropertyInfo->isRequired(), $params);
		self::setDefaultValue('help', $beanPropertyInfo->getHelpKey(), $params);
		// END TODO: refactor with renderField

		if (!array_key_exists('value', $params))
		{
			$value = self::buildPropertyValue($propertyName);
		}
		
		self::setDefaultValue('width', '640', $params);
		self::setDefaultValue('height', '400', $params);

		self::setDefaultValue('previewWidth', '1024', $params);
		self::setDefaultValue('previewHeight', '600', $params);

		$html = "";
		$ls = LocaleService::getInstance();
		// Some javascript, only for the first call of change:documentPicker
		// TODO: non javascript working version
		if (!self::$documentPickerCalled)
		{
			// Name of javascript callback is "SetUrl" for FCKBrowser compatibility
			$html .= "<script type=\"text/javascript\">
function SetUrl(fileUrl, width, height, fileAlt, fileKey, fileLabel, property)
{
	var previewLink = document.createElement('a');
	previewLink.setAttribute('href', fileUrl);
	previewLink.setAttribute('title', fileLabel);
	previewLink.className = 'iframe document-preview link';
	previewLink.appendChild(document.createTextNode(fileLabel));
	
	var removeLink = document.createElement('a');
	removeLink.setAttribute('href', '#');
	removeLink.className = 'button';
	removeLink.appendChild(document.createTextNode('".$ls->transFO("m.website.frontoffice.picker.remove")."'));
	
	var newElem = document.createElement('li');
	newElem.appendChild(previewLink);
	newElem.appendChild(document.createTextNode(' '));
	newElem.appendChild(removeLink);
	
	if (!document.all)
	{
		// This does not work for non image documents on IE ...
		jQuery(previewLink).fancybox({
			'frameWidth' : ".$params['previewWidth'].",
			'frameHeight' : ".$params['previewHeight'].",
			'zoomSpeedIn' : 0,
			'zoomSpeedOut' : 0,
			'hideOnContentClick' : false,
			'centerOnScroll' : false
	  	});
	}
	else
	{
		previewLink.setAttribute('target', '_blank');
	}
	
	jQuery(removeLink).click(function() {
		Documentpicker_removeFromPicker(property, fileKey);
		return false;
	});
	
	var label = document.getElementById(property+'_label');
	if (label != null)
	{
		document.getElementById(property).value = fileKey;
		var elems = document.getElementById(property+'_label');
		if (elems.childNodes.length > 0)
		{
			elems.replaceChild(newElem, elems.childNodes.item(0));
		}
		else
		{
			elems.appendChild(newElem);
		}
		jQuery.fn.fancybox.close();
	}
	else
	{
		var propertyElem = document.getElementById(property); 
		if (propertyElem.value == '')
		{
			propertyElem.value = fileKey;
		}
		else
		{
			var ids = propertyElem.value.split(',');
			for (var i = 0; i < ids.length; i++) if (ids[i] == fileKey) return;
			propertyElem.value += ','+fileKey;
		}
		document.getElementById(property+'_labels').appendChild(newElem);
	}
}

function Documentpicker_removeFromPicker(property, id)
{
	if (!confirm('".$ls->transFO("m.website.frontoffice.picker.remove-confirm")."'))
	{
		return;
	}
	var propertyElem = document.getElementById(property);
	var ids = propertyElem.value.split(',');
	var newIds = [];
	var removeIndex = null;
	for (var i = 0; i < ids.length; i++)
	{
		if (ids[i] != id)
		{
			newIds.push(ids[i]);
		}
		else
		{
			removeIndex = i;
		}
	}
	propertyElem.value = newIds.join(',');
	var label = document.getElementById(property+'_label');
	if (removeIndex != null)
	{
		if (label != null)
		{
			label.innerHTML = '';
		}
		else
		{
			var labels = document.getElementById(property+'_labels');
			var liElem = labels.getElementsByTagName('li').item(removeIndex);
			liElem.parentNode.removeChild(liElem);
		}
	}
}

jQuery(document).ready(function() {
	jQuery('a.picker-choose').fancybox({
		'frameWidth' : ".$params['width'].",
		'frameHeight' : ".$params['height'].",
		'zoomSpeedIn' : 0,
		'zoomSpeedOut' : 0,
		'hideOnContentClick' : false,
		'centerOnScroll' : false
	  });

	jQuery('a.document-preview').fancybox({
		'frameWidth' : ".$params['previewWidth'].",
		'frameHeight' : ".$params['previewHeight'].",
		'zoomSpeedIn' : 0,
		'zoomSpeedOut' : 0,
		'hideOnContentClick' : false,
		'centerOnScroll' : false
	  });
});
</script>"; // TODO: OK, [preview]width and [preview]height are only taken from the first documentPicker ...
			self::$documentPickerCalled = true;
		}

		self::buildNameAndId($params);
		$value = BeanUtils::getProperty(self::$bean, $propertyName);

		// The label
		$params["onclick"] = "jQuery('#".$params["id"]."_choose').click();";
		$html .= self::buildLabel($params);
		unset($params["onclick"]);

		// Hidden input with id(s) comma separated
		$html .= "<input type=\"hidden\" name=\"".$params["name"]."\" id=\"".$params["id"]."\"";
		if ($beanPropertyInfo->getCardinality() == 1)
		{

			if ($value !== null)
			{
				$html .= " value=\"".$value->getId()."\"";
			}
		}
		elseif (f_util_ArrayUtils::isNotEmpty($value))
		{
			$html .= " value=\"".join(",", DocumentHelper::getIdArrayFromDocumentArray($value))."\"";
		}
		$html .= " />";

		// The "choose" button
		$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		$url = 'http://'.$currentWebsite->getDomain(). '/fckeditorbrowser/browser.html?Type='.$chooserType.'&Connector=%2Findex.php%3Fmodule%3Dwebsite%26action%3DRichtextConnector&property='.urlencode($params['id']);
		$html .= "<a id=\"".$params["id"]."_choose\" class=\"iframe picker-choose button\" href=\"".$url."\">".$ls->transFO("m.website.frontoffice.picker.choose")."</a>";

		// Ul for currently associated document(s)
		$multiple = $beanPropertyInfo->getCardinality() != 1;
		$ulId = $params["id"]."_".(($multiple) ? "labels" : "label");
		$html .= "<ul class=\"documentpicker-elements".(($multiple)? " multiple" : "")."\" id=\"".$ulId."\">";
		if ($beanPropertyInfo->getCardinality() == 1)
		{
			if ($value !== null)
			{
				$html .= self::getDocumentPickerElem($value, $params["id"]);
			}
		}
		elseif (f_util_ArrayUtils::isNotEmpty($value))
		{
			foreach ($value as $document)
			{
				$html .= self::getDocumentPickerElem($document, $params["id"]);
			}
		}
		$html .= "</ul>";

		return $html;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param String $propertyId
	 * @return String
	 */
	private static function getDocumentPickerElem($document, $propertyId)
	{
		$ls = LocaleService::getInstance();
		$onclick = "Documentpicker_removeFromPicker('".$propertyId."', ".$document->getId()."); return false;";
		return "<li><a href=\"".LinkHelper::getDocumentUrl($document)."\" ".f_util_HtmlUtils::buildAttribute("title", $document->getLabel())." class=\"iframe document-preview link\">".$document->getLabel()."</a> "
		."<a href=\"#\" class=\"button\" ".f_util_HtmlUtils::buildAttribute("onclick", $onclick).">".$ls->transFO("m.website.frontoffice.picker.remove")."</a></li>";
	}

	/**
	 * Render a change:field attached to a given document model
	 *
	 * @param array $params
	 * @return String
	 */
	public static function renderField($params, $ctx)
	{
		$propertyName = $params['name'];

		if (!self::$hasBean)
		{
			if (Framework::isDebugEnabled())
			{
				return "<strong>change:field for property $propertyName has no attached bean</strong>";
			}
			return "";
		}

		if ($propertyName == "beanId")
		{
			self::setDefaultValue('value', self::$bean->getBeanId(), $params);
			return self::renderHiddeninput($params);
		}

		if (!BeanUtils::hasProperty(self::$bean, $propertyName))
		{
			return "<strong>bean '".BeanUtils::getClassName(self::$bean)."' has no property $propertyName</strong>";
		}

		$beanPropertyInfo = BeanUtils::getPropertyInfo(self::$bean, $propertyName);
		self::setDefaultValue("labeled", true, $params);
		if ($params["labeled"])
		{
			self::setDefaultValue('label', self::getLabelizedLocale($beanPropertyInfo->getLabelKey()), $params);
		}
		self::setDefaultValue('required', $beanPropertyInfo->isRequired(), $params);
		self::setDefaultValue('help', $beanPropertyInfo->getHelpKey(), $params);

		if ($beanPropertyInfo->hasList())
		{
			$list = $beanPropertyInfo->getList();
			if ($list === null)
			{
				return "<strong>could not find required list for property ".$propertyName."</strong>";
			}
			$listItems = $list->getItems();
			if (isset($params['display']) && $params['display'] == 'checkbox')
			{
				$hiddenParams = $params;
				$hiddenParams['value'] = "";
				$hiddenParams['ignoreErrors'] = true;
				return self::renderHiddeninput($hiddenParams).self::buildCheckboxProperty($propertyName, $listItems, $params);
			}
			else
			{
				if ($beanPropertyInfo->getCardinality() != 1)
				{
					$params["nopreamble"] = "true";
					$params["multiple"] = "true";
					self::setDefaultValue("size", "5", $params);
				}
				return self::buildSelectProperty($propertyName, $listItems, $params);
			}
		}
		return self::buildInputProperty($propertyName, $params, $ctx);
	}

	/**
	 * @var Boolean
	 */
	private static $renderUploadfieldCalled = false;

	/**
	 * @param array $params
	 * @param String $class
	 */
	private static function addClassParam(&$params, $class)
	{
		if (!isset($params['class']))
		{
			$params['class'] = $class;
		}
		else
		{
			$classes = explode(" ", $params["class"]);
			if (!in_array($class, $classes))
			{
				$classes[] = $class;
				$params['class'] = join(" ", $classes);
			}
		}
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderUploadfield($params)
	{
		$name = $params["name"];
		if (!self::$hasBean)
		{
			throw new Exception("change:uploadfield not usable without an attached bean ");
		}
		elseif (!BeanUtils::hasProperty(self::$bean, $name))
		{
			throw new Exception("change:uploadfield: could not find property $name in bean ".BeanUtils::getClassName(self::$bean));
		}

		$beanPropertyInfo = BeanUtils::getBeanPropertyInfo(self::$bean, $name);

		$code = "";
		$params["name"] = $name."_new";
		self::setDefaultValue("labeled", true, $params);
		if ($params["labeled"])
		{
			self::setDefaultValue('label', self::getLabelizedLocale($beanPropertyInfo->getLabelKey()), $params);
		}
		self::setDefaultValue('help', $beanPropertyInfo->getHelpKey(), $params);
		$params["onchange"] = "Change_UploadField_FileChanged(this, '".($name."_add")."');";
		$propErrors = self::getErrorsForProperty($name);
		if (f_util_ArrayUtils::isNotEmpty($propErrors))
		{
			self::addClassParam($params, "error");
			$propErrorMsg = "";
			foreach ($propErrors as $propError)
			{
				$propErrorMsg .= "- ".$propError."\n";
			}
			$params["errorMsg"] = $propErrorMsg;
		}
		$code .= self::renderFileinput($params);
		$params["name"] = $name;
		$ls = LocaleService::getInstance();
		
		$monoValued = $beanPropertyInfo->getCardinality() == 1;
		if (!$monoValued)
		{
			// Submit input to add file without validation
			if (!isset($params["action"]))
			{
				// This will be ok for users that do not care about
				// validation error messages (or form without validation)
				$params["action"] = null;
			}
			
			$addInputParams = array("name" => $params["action"], 
				"value" => $ls->transFO("m.website.frontoffice.addupload", array('ucf')), 
				"title" => $ls->transFO("m.website.frontoffice.addupload-help", array('ucf')), 
				"id" => $name."_add");

			// Class attribute
			if (isset($params["class"]))
			{
				$addInputParams["class"] = $params["class"];
				unset($params["class"]);
			}
			if (isset($addInputParams["class"]))
			{
				$addClasses = explode(" ", $addInputParams["class"]);
			}
			else
			{
				$addClasses = array();
			}
			$addClasses[] = "uploadfield-addbutton";
			$addInputParams["class"] = join(" ", $addClasses);

			$code .= ' '.self::renderSubmit($addInputParams);
		}
		
		$code .= '<input type="hidden" name="'.self::buildInputName("WEBSITE_POST_POPULATE_FILTERS[".$name."]").'" value="media_FileBeanPopulateFilter"/>';
		
		if (!self::$renderUploadfieldCalled)
		{
			self::setDefaultValue("previewWidth", "800", $params);
			self::setDefaultValue("previewHeight", "600", $params);

			$code .= "<script type=\"text/javascript\">
				if (!document.all) jQuery(document).ready(function() {
					jQuery('a.fancypreview').fancybox({
						'frameWidth' : ".$params['previewWidth'].",
						'frameHeight' : ".$params['previewHeight'].",
						'zoomSpeedIn' : 0,
						'zoomSpeedOut' : 0,
						'hideOnContentClick' : false,
						'centerOnScroll' : false
	  			});});
	  			jQuery(document).ready(function() {
	  				jQuery('input.uploadfield-addbutton').each(function (i) {
	  					this.disabled = true;
	  				});
	  				
	  				jQuery('ul.uploads input[type = checkbox]').each(function (i) {
	  					jQuery(this).hide();
	  					var labelElem = jQuery(this.nextSibling).hide();
	  					var removeAnchor = document.createElement('a');
	  					removeAnchor.setAttribute('href', '#');
	  					removeAnchor.className = 'button';
	  					removeAnchor.appendChild(document.createTextNode(labelElem.text()));
	  					removeAnchor.setAttribute('title', this.getAttribute('title'));
	  					var space = document.createTextNode(' ');
	  					labelElem.after(space);
	  					jQuery(space).after(removeAnchor);
	  					
	  					var checkboxInput = this;
	  					jQuery(removeAnchor).click(function() {
							Change_UploadField_RemoveElem(checkboxInput);
							return false;
						});
	  				});
	  			});
	  			function Change_UploadField_FileChanged(fileInput, newInputId)
	  			{
	  				document.getElementById(newInputId).disabled = (fileInput.value == '');
	  			}
	  			
	  			function Change_UploadField_RemoveElem(checkboxInput)
	  			{
	  				if (!confirm('".$ls->transFO("m.website.frontoffice.upload.remove-confirm")."'))
	  				{
	  					return;
	  				}
	  				var liElem = checkboxInput.parentNode;
	  				var current = liElem;
	  				while (current.nextSibling != null)
	  				{
	  					current = current.nextSibling;
	  					jQuery(current).find('input[type = hidden]').each(function (i) {
	  						var matches = this.getAttribute('name').match(/^(.*)\\[(.*)\\]$/);
	  						var newName = matches[1]+'['+(parseInt(matches[2])-1)+']';
	  						this.setAttribute('name', newName);
	  					});
	  				}
	  				liElem.parentNode.removeChild(liElem);
	  			}
	  			</script>";
			self::$renderUploadfieldCalled = true;
		}

		$values = self::getFieldValue($name);
		if ($monoValued)
		{
			if ($values !== null)
			{
				$value = $values;
				$code .= '<ul class="uploads" id="'.($name."_uploads").'">';
				$deleteLocale = $ls->transFO("m.website.frontoffice.deleteUpload", array('ucf'));
				$deleteHelpLocale = $ls->transFO("m.website.frontoffice.deleteUpload-help", array('ucf'));
				$deleteName = $name."_delete";

				$document = DocumentHelper::getDocumentInstance($value);
				$code .= '<li>';
				$hiddenName = self::buildInputName($name);
				$code .= '<input type="hidden" name="'.$hiddenName.'" value="'.$value.'"/>';
				$code .= '<a '.f_util_HtmlUtils::buildAttribute("title", $document->getLabel()." : ".$document->getFilename()).' class="fancypreview link" '.f_util_HtmlUtils::buildAttribute("href", LinkHelper::getDocumentUrl($document)).'>'.$document->getFilename().'</a>';
				$deleteElemName = self::buildInputName($deleteName);
				$code .= '<input id="'.$deleteElemName.'" '.f_util_HtmlUtils::buildAttribute('title', $deleteHelpLocale).' type="checkbox" name="'.$deleteElemName.'" />';
				$code .= '<label for="'.$deleteElemName.'">'.$deleteLocale.'</label>';
				$code .= '</li>';
				$code .= "</ul>";
			}
		}
		elseif (f_util_ArrayUtils::isNotEmpty($values))
		{
			$code .= '<ul class="uploads">';
			$deleteLocale = $ls->transFO("m.website.frontoffice.deleteUpload", array('ucf'));
			$deleteHelpLocale = $ls->transFO("m.modules.website.frontoffice.deleteUpload-help", array('ucf'));
			$index = 0;

			foreach ($values as $value)
			{
				$deleteName = $name."_delete[$index]";

				$document = DocumentHelper::getDocumentInstance($value);
				$code .= '<li>';
				$hiddenName = self::buildInputName($name."[$index]");
				$code .= '<input type="hidden" name="'.$hiddenName.'" value="'.$value.'"/>';
				$code .= '<a '.f_util_HtmlUtils::buildAttribute("title", $document->getLabel()." : ".$document->getFilename()).' class="fancypreview link" '.f_util_HtmlUtils::buildAttribute("href", LinkHelper::getDocumentUrl($document)).'>'.$document->getFilename().'</a>';
				$deleteElemName = self::buildInputName($deleteName);
				$code .= '<input id="'.$deleteElemName.'" '.f_util_HtmlUtils::buildAttribute('title', $deleteHelpLocale).' type="checkbox" name="'.$deleteElemName.'" />';
				$code .= '<label for="'.$deleteElemName.'">'.$deleteLocale.'</label>';
				$code .= '</li>';
				$index++;
			}
			$code .= "</ul>";
		}

		return $code;
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderTextinput($params)
	{
		self::setDefaultValue('class', 'textfield', $params);
		return self::renderInputByType("text", $params);
	}

	public static function renderDurationinput($params)
	{
		self::setDefaultValue('class', 'textfield', $params);
		self::$context->addScript('modules.website.lib.js.durationPicker');
		return self::renderInputByType("text", $params) . "<script type=\"text/javascript\">//<![CDATA[\njQuery(document).ready(function(){durationPicker(jQuery('#" . $params['id'] . "'));});\n//]]></script>";
	}

	public static function renderDocumentinput($params, $ctx)
	{
		$result = "";
		$name = self::buildNameAndId($params);
		if ($params["cardinality"] != 1)
		{
			// multiple
			if (!array_key_exists('value', $params))
			{
				$values = self::getFieldValue($name);
			}
			else
			{
				$values = $params['value'];
			}
			if (f_util_ArrayUtils::isNotEmpty($values))
			{
				$params["value"] = array_flip($values);
			}
			else
			{
				$params["value"] = null;
			}
			$params["multiple"] = "true";
			self::setDefaultValue("size", "5", $params);
		}
		else
		{
			// single document
			if (!array_key_exists('value', $params))
			{
				$params["value"] = self::getFieldValue($name);
			}
		}
		$ds = f_persistentdocument_DocumentService::getInstanceByDocumentModelName($params["documentType"]);
		$query = $ds->createQuery()
		->add(Restrictions::in("publicationstatus", array(f_persistentdocument_PersistentDocument::STATUS_DRAFT, f_persistentdocument_PersistentDocument::STATUS_ACTIVE, f_persistentdocument_PersistentDocument::STATUS_PUBLISHED)))
		->addOrder(Order::std());
		$options = array();
		foreach ($query->find() as $document)
		{
			$options[$document->getId()] = $document->getLabel();
		}
		$params["list"] = $options;
		$result .= self::renderSelectinput($params, $ctx);
		return $result;
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderPasswordinput($params)
	{
		self::setDefaultValue('class', 'textfield', $params);
		return self::renderInputByType("password", $params);
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderFileinput($params)
	{
		return self::renderInputByType("file", $params);
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderHiddeninput($params)
	{
		$params['labeled'] = false;
		if (isset($params["hidden"]))
		{
			unset($params["hidden"]);
		}
		return self::renderInputByType('hidden', $params);
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderTextarea($params)
	{
		self::setDefaultValue('rows', 10, $params);
		self::setDefaultValue('cols', 60, $params);
		return self::renderInputByType('textarea', $params);
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderDateinput($params)
	{
		$params['size'] = 10;
		$params['maxlength'] = 10;
		$params['class'] = 'textfield date-picker';
		// TODO: unifiy
		$ls = LocaleService::getInstance();
		$format = $ls->transFO("m.form.frontoffice.datepicker.format");
		$dateFormat = date_DateFormat::getDateFormatForLang(RequestContext::getInstance()->getLang());
		if (!isset($params['startdate']))
		{
			$params['startdate'] = "1901-01-01";
		}

		$params['startdate'] = date_DateFormat::format(date_Calendar::getInstance($params['startdate']), $dateFormat);

		self::addDatePickerScript();
		$datePickerParam = '{startDate:"' . $params['startdate'] . '"';
		if (isset($params['enddate']))
		{
			$datePickerParam .= ', endDate:"' . date_DateFormat::format(date_Calendar::getInstance($params['enddate']), $dateFormat) . '"';

		}
		$datePickerParam .= '}';
		return self::renderInputByType("text", $params) . '<span>' . $format . "</span><script type=\"text/javascript\">//<![CDATA[\njQuery(document).ready(function(){jQuery('[id=" . $params['id'] . "]').datePicker($datePickerParam);});\n//]]></script>";
	}

	/**
	 * @param Array $params
	 */
	public static function renderListmultifield($params)
	{
		$beanProperties = explode(',', $params['names']);
		$listItems = null;
		$class = ((isset($params["class"]))? $params["class"] : "listmultifield");

		echo '<table class="'.$class.'">';
		$firstProperty = f_util_ArrayUtils::firstElement($beanProperties);
		$propName = trim($firstProperty);
		$beanPropertyInfo = BeanUtils::getPropertyInfo(self::$bean, $propName);
		if ($beanPropertyInfo->hasList() && $listItems == null)
		{
			$listItems = $beanPropertyInfo->getList()->getItems();
			echo '<thead><tr><th></th>';
			foreach ($listItems as $item)
			{
				echo '<th scope="col">' . $item->getLabel() . '</th>';
			}
			echo '</tr></thead>';
		}
		echo '<tbody>';
		$i = 0;
		foreach ($beanProperties as $beanProperty)
		{
			$propName = trim($beanProperty);
			$beanPropertyInfo = BeanUtils::getPropertyInfo(self::$bean, $propName);
			$params['label'] = self::getLabelizedLocale($beanPropertyInfo->getLabelKey());
			$params['required'] = $beanPropertyInfo->isRequired();
			$params['useFor'] = false;
			echo '<tr class="row' . ($i % 2) . '">';
			echo '<th scope="row">' . self::buildLabel($params) . '</th>';
			foreach ($listItems as $item)
			{
				$name = self::buildInputName($propName, false);
				$value = $item->getValue();
				// The clickable-cell class is used in by the survey module to check the
				// radio button by clicking anywhere in the table cell.
				echo '<td class="clickable-cell"><input type="radio" ' . f_util_HtmlUtils::buildAttribute('name', $name);
				echo ' ' . f_util_HtmlUtils::buildAttribute('value', $value);
				if (self::getFieldValue($propName) == $value)
				{
					echo ' checked="checked"';
				}
				echo '/></td>';
					
			}
			echo '</tr>';

			if (self::$showFieldErrors && self::hasErrorsForProperty($propName))
			{
				echo '<tr class="errors"><td colspan="' . (count($listItems) + 1) . '">';
				echo self::buildPropertyErrors($propName);
				echo '</td></tr>';
			}
			$i++;
		}
		echo '</tbody></table>';
	}

	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderSubmit($params)
	{
		$name = self::buildNameAndId($params, "submit");
		$fullSubmitName = self::buildInputName(website_BlockAction::SUBMIT_PARAMETER_NAME, false) . '[' . self::$currentBlockId . '][' . $name . ']';
		$params["type"] = "submit";
		$params["name"] = $fullSubmitName;
		$value = self::getSubmitLabelFromParameters($params);
		if (!f_util_StringUtils::isEmpty($value))
		{
			$params["value"] = $value;
		}
		return self::renderInputCode($params);
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public static function renderErrors($params)
	{
		return self::_renderMessages($params, website_BlockAction::BLOCK_ERRORS_ATTRIBUTE_KEY, "errors");
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public static function renderMessages($params)
	{
		return self::_renderMessages($params, website_BlockAction::BLOCK_MESSAGES_ATTRIBUTE_KEY, "messages");
	}

	/**
	 * @param array $params
	 * @return string
	 */
	private static function _renderMessages($params, $ctxKey, $className)
	{
		$controller = website_BlockController::getInstance();
		if (self::$context === null)
		{
			$context = $controller->getContext();
		} 
		else
		{
			$context = self::$context;
		}

		if (!$context->hasAttribute($ctxKey))
		{
			return "";
		}
		self::setDefaultValue('mode', 'block', $params);

		if (isset($params['relKey']))
		{
			$ctxKey .= "_relative";
		}

		$blockErrors = $context->getAttribute($ctxKey, array());
		ob_start();
		echo '<ul class="'.$className.'">';
		
		// Do not use the local variables to be able to work outside from a change:form.
		if ($controller->getRequest() != null && $params['mode'] == 'block')
		{
			$currentBlockId = $controller->getProcessedAction()->getBlockId();
			if (isset($params['relKey']))
			{
				$currentBlockId .= "_".$params['relKey'];
			}
			if (isset($params['fields']))
			{
				$fields = explode(",", $params['fields']);
				$errors = array();
				foreach ($fields as $fieldName)
				{
					$fieldErrors = self::getErrorsForProperty($fieldName);
					foreach ($fieldErrors as $fieldError)
					{
						$errors[] = $fieldError;
					}
				}
			} else
			{
				$errors = isset($blockErrors[$currentBlockId]) ? $blockErrors[$currentBlockId] : array();
			}
			$errorsCount = count($errors);
			for ($i = 0; $i < $errorsCount; $i++)
			{
				echo self::buildListItem($errors[$i], $i == 0, $i == $errorsCount - 1);
			}
		} else
		{
			foreach ($blockErrors as $errors)
			{
				$errorsCount = count($errors);
				for ($i = 0; $i < $errorsCount; $i++)
				{
					echo self::buildListItem($errors[$i], $i == 0, $i == $errorsCount - 1);
				}
			}
		}
		echo "</ul>";
		return ob_get_clean();
	}

	/**
	 * @param Array $params
	 * @return String
	 */
	public static function renderBooleaninput($params)
	{
		$result = '';
		$params['type'] = 'radio';

		if (!array_key_exists('value', $params))
		{
			$value = self::buildPropertyValue($params['name']);
			if ($value === null && isset($params['default-value']))
			{
				$value = $params['default-value'];
			}
			$params['value'] = $value;
		}

		self::buildNameAndId($params);

		if (isset($params['trueLabel']))
		{
			$trueLabel = $params['trueLabel'];
		}
		else
		{
			$trueLabel = "&modules.uixul.bo.general.Yes;";
		}

		if (isset($params['falseLabel']))
		{
			$falseLabel = $params['falseLabel'];
		}
		else
		{
			$falseLabel = "&modules.uixul.bo.general.No;";
		}

		if (self::isLabeled($params))
		{
			$oldId = $params['id'];
			$params['id'] = $params['id'].'_true';
			$result = self::buildLabel($params);
			$params['id'] = $oldId;
		}

		// The labels for radio buttons must be always rendernd ans as not required.
		$oldRequiredValue = (isset($params['required'])) ? $params['required'] : null;
		$params['required'] = false;
		$oldLabeledValue = (isset($params['labeled'])) ? $params['labeled'] : null;
		$params['labeled'] = true;
		$ls = LocaleService::getInstance();
		$cKey = $ls->cleanOldKey($trueLabel);
		if ($cKey !== false) {$trueLabel = $ls->transFO($cKey);}
		$cKey = $ls->cleanOldKey($falseLabel);
		if ($cKey !== false) {$falseLabel = $ls->transFO($cKey);}
		
		$result .= self::buildRadio("true", $trueLabel, $params, true);
		$result .= self::buildRadio("false", $falseLabel, $params, true);
		if (isset($params["unknownValue"]))
		{
			if (isset($params['unknownLabelKey']))
			{
				$unknownLabel = $params['unknownLabel'];
			}
			elseif (isset($params['unknownLabel']))
			{
				$unknownLabel = $params['unknownLabel'];
			}
			else
			{
				$unknownLabel = $ls->transFO("m.uixul.bo.general.unknown", array('ucf'));
			}
			$result .= self::buildRadio('', $unknownLabel, $params);
		}
		
		if ($oldRequiredValue === null)
		{
			unset($params['required']);
		}
		else
		{
			$params['required'] = $oldRequiredValue;
		}
		if ($oldLabeledValue === null)
		{
			unset($params['labeled']);
		}
		else
		{
			$params['labeled'] = $oldLabeledValue;
		}

		return $result;
	}

	public static function renderRadioInput($params)
	{
		$name = self::buildNameAndId($params);
		$value = self::getFieldValue($name);
		$radioValue = $params["value"];
		$params["value"] = $value;
		self::setDefaultValue('label', '', $params);
		echo self::buildRadio($radioValue, $params["label"] , $params);
	}
	
	public static function renderCheckboxInput($params)
	{
		$name = self::buildNameAndId($params);
		$value = self::getFieldValue($name);
		$radioValue = $params["value"];
		$params["value"] = $value;
		self::setDefaultValue('label', '', $params);
		echo self::buildCheckbox($radioValue, $params["label"] , $params);
	}

	/**
	 * @param String $radioValue
	 * @param String $radioLabel
	 * @param array<String, String> $params
	 * @return String
	 */
	private static function buildRadio($radioValue, $radioLabel, $params, $ignoreErrors = false)
	{
		$realRadioValue = f_util_Convert::fixDataType($radioValue);
		$realValue = f_util_Convert::fixDataType($params["value"]);
		if (isset($params['checked']))
		{
			// do nothing
		}
		else if ($realRadioValue === $realValue)
		{
			$params['checked'] = "checked";
		}
		else
		{
			unset($params['checked']);
		}
		$params['ignoreErrors'] = $ignoreErrors;
		$params['label'] = $radioLabel;
		$params['id'] .= "_".$radioValue;
		$params['value'] = $radioValue;
		if (!isset($params['class']))
		{
			$params['class'] = "option-label";
		}
		return self::renderInputByType('radio', $params);
	}
	
	/**
	 * @param String $radioValue
	 * @param String $radioLabel
	 * @param array<String, String> $params
	 * @return String
	 */
	private static function buildCheckbox($radioValue, $radioLabel, $params, $ignoreErrors = false)
	{
		$realRadioValue = f_util_Convert::fixDataType($radioValue);
		$realValue = f_util_Convert::fixDataType($params["value"]);
		if (isset($params['checked']))
		{
			// do nothing
		}
		else if ($realRadioValue === $realValue)
		{
			$params['checked'] = "checked";
		}
		else
		{
			unset($params['checked']);
		}
		$params['ignoreErrors'] = $ignoreErrors;
		$params['label'] = $radioLabel;
		$params['id'] .= "_".$radioValue;
		$params['value'] = $radioValue;
		if (!isset($params['class']))
		{
			$params['class'] = "option-label";
		}
		return self::renderInputByType('checkbox', $params);
	}

	/**
	 * @param String $msg
	 * @param Boolean $isFirst
	 * @param Boolean $isLast
	 * @return String
	 */
	private function buildListItem($msg, $isFirst = false, $isLast = false)
	{
		$string = "";
		$cssClass = array();
		if ($isFirst)
		{
			$cssClass[] = "first";
		}

		if ($isLast)
		{
			$cssClass[] = "last";
		}

		if (count($cssClass) > 0)
		{
			$string .= '<li ' . f_util_HtmlUtils::buildAttribute('class', implode(' ', $cssClass)) . '>';
		} else
		{
			$string .= '<li>';
		}
		return $string . $msg . '</li>';
	}

	/**
	 * @param String $localeKey
	 * @return String
	 */
	private function getLabelizedLocale($localeKey)
	{
		$ls = LocaleService::getInstance();
		$cleanKey = $ls->cleanOldKey($localeKey);
		if ($cleanKey !== false)
		{
			return $ls->transFO($cleanKey, array('ucf','lab','html'));
		}
		return $localeKey;
	}

	/**
	 * Add the required scripts for the date picker
	 */
	private static function addDatePickerScript()
	{
		if (!self::$datePickerScriptAdded)
		{
			$page = self::$context;
			$page->addScript('modules.form.lib.js.form');
			self::$datePickerScriptAdded = true;
		}
	}

	/**
	 * @param unknown_type $type
	 * @param unknown_type $params
	 * @return unknown
	 */
	private static function renderInputByType($type, &$params)
	{
		$name = self::buildNameAndId($params);
		$params["type"] = $type;
		if (!array_key_exists('value', $params))
		{
			$value = self::getFieldValue($name);
			if ($value === null && isset($params['default-value']))
			{
				$value = $params['default-value'];
			}
			$params['value'] = $value;
		}
		if (self::isLabeled($params))
		{
			return self::buildLabel($params) . self::renderInputCode($params);
		}
		return self::renderInputCode($params);
	}

	/**
	 * @param String $name
	 * @param Boolean $isAbsoluteName
	 */
	private static function buildInputName($name, $isAbsoluteName = false)
	{
		if ($isAbsoluteName)
		{
			return $name;
		}
		$index = strpos($name, "[");
		if ($index !== false)
		{
			return self::$moduleName . 'Param[' . substr($name, 0, $index) . ']'.substr($name, $index);
		}
		return self::$moduleName . 'Param[' . $name . ']';
	}

	/**
	 * @param String $name
	 * @return String
	 */
	private static function buildFieldId($name)
	{
		if (($index = strpos($name, "[")) !== false)
		{
			$name = substr($name, 0, $index);
		}
		return self::$formId . '_' . $name;
	}

	/**
	 * @param Array $params
	 * @param Boolean $unsetWhenDone
	 * @return String
	 */
	private static function getLabelFromParameters(&$params, $unsetWhenDone = true)
	{
		$value = '';
		if (isset($params['evaluatedlabel']))
		{
			$value = $params['evaluatedlabel'];
		}
		else if (isset($params['labeli18n']))
		{
			$value = LocaleService::getInstance()->transFO($params['labeli18n'], array('ucf', 'lab', 'html'));
			if ($unsetWhenDone)
			{
				unset($params['labeli18n']);
			}
		}
		else if (isset($params['label']))
		{
			$ls = LocaleService::getInstance();
			$cKey = $ls->cleanOldKey($params['label']);
			$value = ($cKey === false) ? $params['label'] : $ls->transFO($cKey, array('ucf', 'lab', 'html'));
			if ($unsetWhenDone)
			{
				unset($params['label']);
			}
		}
		else if (self::$hasBean && isset($params["shortInputName"]) && BeanUtils::hasProperty(self::$bean, $params["shortInputName"]))
		{
			$propertyInfo = BeanUtils::getPropertyInfo(self::$bean, $params["shortInputName"]);
			$key = $propertyInfo->getLabelKey();
			if ($key !== null)
			{
				$ls = LocaleService::getInstance();
				$ckey = $ls->cleanOldKey($key);
				if ($ckey !== false)
				{
					$value = $ls->transFO($ckey, array('ucf', 'lab', 'html'));
				}
			}
		}
		return $value;
	}

	/**
	 * @param Array $params
	 * @param Boolean $unsetWhenDone
	 * @return String
	 */
	private static function getSubmitLabelFromParameters(&$params, $unsetWhenDone = true)
	{
		$value = '';
		if (isset($params['evaluatedlabel']))
		{
			$value = $params['evaluatedlabel'];
		}
		else if (isset($params['labeli18n']))
		{
			$value = LocaleService::getInstance()->transFO($params['labeli18n'], array('ucf', 'attr', 'html'));
			if ($unsetWhenDone)
			{
				unset($params['labeli18n']);
			}
		}
		else if (isset($params['label']))
		{
			$ls = LocaleService::getInstance();
			$cKey = $ls->cleanOldKey($params['label']);
			$value = ($cKey === false) ? $params['label'] : $ls->transFO($cKey, array('ucf', 'attr', 'html'));
			if ($unsetWhenDone)
			{
				unset($params['label']);
			}
		}
		return $value;
	}

	/**
	 * @param String $name the property name
	 * @return Mixed
	 */
	private static function getFieldValue($name)
	{
		if (self::$hasBean)
		{
			return self::buildPropertyValue($name);
		}
		if (self::$currentAction === null)
		{
			throw new Exception('No current action... Do you use a PHPTAL from extension outside from a <form change:form="" ...></form>?');
		}
		return self::$currentAction->findParameterValue($name);
	}

	// TODO: includeAttributes by type !
	private static $includeAttributes = array("readonly" => true , "rows" => true , "cols" => true , "size" => true , "maxlength" => true , "minlength" => true , "value" => true , "label" => true , "labeli18n" => true, "checked" => true , "selected" => true , "for" => true , "type" => true , "name" => true , "id" => true , "class" => true , "hidden" => true , "disabled" => true , "onclick" => true, "style" => true, "onchange" => true, "multiple" => true, "title" => true);

	/**
	 * @param Array $params
	 * @return String
	 */
	private function renderInputCode($params)
	{
		if (isset($params['label']))
		{
			unset($params['label']);
		}
		if (isset($params['labeli18n']))
		{
			unset($params['labeli18n']);
		}
		if (isset($params['help']))
		{
			$ls = LocaleService::getInstance();
			$key = $params['help'];
			unset($params['help']);
			$ckey = $ls->cleanOldKey($key);
			if ($ckey !== false)
			{
				$title = $ls->transFO($ckey);
				if ($title !== $ckey)
				{
					$params['title'] = $title;
				}
			}
			else
			{
				$params['title'] = $key;
			}
		}
		$result = "";
		if ($params['type'] == 'textarea')
		{
			unset($params['type']);
			$result .= '<textarea ';
			foreach ($params as $name => $val)
			{
				if (strpos($name, 'data-') === 0 || (isset(self::$includeAttributes[$name]) && $name != 'value'))
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $val) . ' ';
				}
			}
			$result .= '>' . htmlspecialchars($params['value']) . '</textarea>';
		}
		else
		{
			$result .= '<input ';
			foreach ($params as $name => $val)
			{
				if (strpos($name, 'data-') === 0 || isset(self::$includeAttributes[$name]))
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $val) . ' ';
				}
			}
			$result .= '/>';
		}
		return $result . self::buildFieldErrors($params);
	}

	/**
	 * @param Array $params
	 * @return String
	 */
	private static function buildFieldErrors($params)
	{
		if (isset($params['ignoreErrors']) && $params['ignoreErrors'])
		{
			return '';
		}
		$propertyName = self::buildNameAndId($params);
		if (self::$showFieldErrors && self::hasErrorsForProperty($propertyName))
		{
			return self::buildPropertyErrors($propertyName);
		}
		return '';
	}

	private static function buildPropertyErrors($propertyName)
	{
		$result = '<ul class="field-errors errors">';
		$errors = self::getErrorsForProperty($propertyName);
		$errorsCount = count($errors);
		for ($i = 0; $i < $errorsCount; $i++)
		{
			// FIXME: should the locale be ucfirst ?
			$result .= self::buildListItem(ucfirst($errors[$i]), $i == 0, $i == $errorsCount - 1);
		}
		return $result . '</ul>';
	}

	/**
	 * @param Array $params
	 * @param Boolean $close
	 * @return String
	 */
	private function buildLabel($params, $close = true)
	{
		self::setDefaultValue('useFor', true, $params);
		$label = self::getLabelFromParameters($params);
		$result = '<label ';
		if ($params['useFor'])
		{
			$result .= f_util_HtmlUtils::buildAttribute("for", $params["id"]);
		}

		if (isset($params['onclick']))
		{
			$result .= ' '.f_util_HtmlUtils::buildAttribute("onclick", $params["onclick"]);
		}

		if (isset($params['style']))
		{
			$result .= ' '.f_util_HtmlUtils::buildAttribute("style", $params["style"]);
		}
		$classes = array();
		
		$name = self::buildNameAndId($params);
		if (!isset($params["ignoreErrors"]))
		{
			$propErrors = self::getErrorsForProperty($name);
			/*if (isset($params['relatedname']))
			{
				$propErrors = array_merge($propErrors, self::getErrorsForProperty($params['relatedname']));
			}*/
			if (f_util_ArrayUtils::isNotEmpty($propErrors))
			{
				$classes[] = "error";
				$propErrorMsg = "";
				foreach ($propErrors as $propError)
				{
					$propErrorMsg .= "- ".$propError."\n";
				}
				$params["errorMsg"] = $propErrorMsg;
			}
		}

		if (isset($params["errorMsg"]))
		{
			$result .= ' '.f_util_HtmlUtils::buildAttribute("title", $params["errorMsg"]);
		}

		if (isset($params["class"]))
		{
			$classes[] = $params["class"];
		}
		if (isset($params['required']) && $params['required'] == true)
		{
			$classes[] = "required";
			$result .= ' class="' . join(" ", $classes) . '">' . $label;
			if ($close)
			{
				$title = LocaleService::getInstance()->transFO('m.website.frontoffice.this-field-is-mandatory');
				$result .= ' <span class="requiredsymbol" '.f_util_HtmlUtils::buildAttribute("title", $title).'>*</span>';
			}
		}
		else
		{
			if (count($classes) > 0)
			{
				$result .= ' class="' . join(" ", $classes) . '"';
			}
			$result .= '>' . $label;
		}
		
		if ($close)
		{
			$result .= '</label>';
		}
		return $result;
	}

	/**
	 * @param String $propertyName
	 * @return String
	 */
	private static function buildPropertyValue($propertyName)
	{
		$invalidProperties =  self::$currentActionRequest->getAttribute('invalidProperties');
		$value = null;
		if (isset($invalidProperties[$propertyName]))
		{
			return $invalidProperties[$propertyName];
		}
		else if (self::$hasBean)
		{
			try 
			{
				$value = BeanUtils::getProperty(self::$bean, $propertyName);
				if (BeanUtils::hasProperty(self::$bean, $propertyName))
				{
					$property = BeanUtils::getPropertyInfo(self::$bean, $propertyName);
					$converter = $property->getConverter();
					if ($converter !== null)
					{
						$value = $converter->convertFromBeanToRequestValue($value);
					}
				}
				return $value;
			}
			catch (Exception $e)
			{
				// Unexisting getter.
			}
		}
		return self::$currentAction->findParameterValue($propertyName);
	}

	/**
	 * TODO: refactor with renderSelectInput
	 * @param String $propertyName
	 * @param list_Item[] $listItems
	 * @param array $params
	 * @return String
	 */
	private function buildSelectProperty($propertyName, $listItems, &$params)
	{
		self::buildNameAndId($params);
		if (!array_key_exists('value', $params))
		{
			if (isset($params['multiple']))
			{
				$defaultValue = (isset($params['default-values'])) ? explode(',', $params['default-values']) : null;
			}
			else
			{
				$defaultValue = (isset($params['default-value'])) ? $params['default-value'] : null;
			}
			$params['value'] = self::buildPropertyValue($propertyName, $defaultValue);
		}

		$result = '';
		if (self::isLabeled($params))
		{
			$result = self::buildLabel($params);
		}
		$result .= '<select ';
		foreach ($params as $name => $value)
		{
			if (isset(self::$includeAttributes[$name]) && $name != 'value' && $name != 'label' && $name != 'labeli18n')
			{
				if ($name == 'name' && isset($params["multiple"]))
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $value . '[]') . ' ';
				}
				else
				{
					$result .= f_util_HtmlUtils::buildAttribute($name, $value) . ' ';
				}
			}
		}
		$result .= '>';
		if (!isset($params['nopreamble']))
		{
			$result .= '<option '.f_util_HtmlUtils::buildAttribute('value', '') . '></option>';
		}
		return $result . self::buildOptions($listItems, $params['value']) . '</select>' . self::buildFieldErrors($params);
	}

	/**
	 * @param Array $params
	 * @return String
	 */
	private function buildNameAndId(&$params, $defaultValue = 'defaultFieldName')
	{
		if (isset($params["shortInputName"]))
		{
			return $params["shortInputName"];
		}

		$isAbsolute = false;
		if (isset($params['evaluatedname']))
		{
			$name = $params['evaluatedname'];
		} else if (isset($params['absname']))
		{
			$isAbsolute = true;
			$name = $params['absname'];
		} else if (isset($params['name']))
		{
			$name = $params['name'];
		} else
		{
			$name = "";
		}
		if (f_util_StringUtils::isEmpty($name))
		{
			$name = $defaultValue;
		}

		$params["shortInputName"] = $name;
		$params["name"] = self::buildInputName($name, $isAbsolute);
		self::setDefaultValue('id', self::buildFieldId($name), $params);
		return $name;
	}

	/**
	 * @param String $listId
	 * @return Array
	 */
	private function getListItems($listId)
	{
		$list = list_ListService::getInstance()->getByListId($listId);
		if ($list === null)
		{
			throw new Exception("list $list does not exist!");
		}
		return $list->getItems();
	}

	/**
	 * @param Mixed $listItems
	 * @param Mixed $value
	 * @return String
	 */
	private function buildArrayOptions($listItems, $value)
	{
		$result = '';
		if (is_array($value))
		{
			foreach ($listItems as $itemValue => $itemName)
			{
				$result .= '<option ';
				if (isset($value[$itemValue]))
				{
					$result .= f_util_HtmlUtils::buildAttribute('selected', 'selected') . ' ';
				}
				$result .= f_util_HtmlUtils::buildAttribute('value', $itemValue) . '>' . self::escapeText($itemName) . '</option>';
			}
		}
		else
		{
			foreach ($listItems as $itemValue => $itemName)
			{
				$result .= '<option ';
				if ($itemValue == $value)
				{
					$result .= f_util_HtmlUtils::buildAttribute('selected', 'selected') . ' ';
				}
				$result .= f_util_HtmlUtils::buildAttribute('value', $itemValue) . '>' . self::escapeText($itemName) . '</option>';
			}
		}

		return $result;
	}

	/**
	 * @param String $text
	 * @return String
	 */
	private static function escapeText($text)
	{
		return str_replace(array("<" , ">"), array("&lt;" , "&gt;"), $text);
	}

	/**
	 * @param Array $listItems
	 * @param Mixed $value
	 * @return String
	 */
	private static function buildOptions($listItems, $value)
	{
		if (is_object($value))
		{
			if ($value instanceof list_persistentdocument_valueditem)
			{
				$value = $value->getValue();
			} elseif ($value instanceof list_persistentdocument_item)
			{
				$value = $value->getId();
			}
		}
		$result = '';
		if (is_array($value))
		{
			if (f_util_ArrayUtils::firstElement($value) instanceof f_mvc_Bean)
			{
				$values = array();
				foreach ($value as $val)
				{
					$values[] = $val->getBeanId();
				}
			}
			else
			{
				$values = $value;
			}
				
			foreach ($listItems as $item)
			{
				$result .= '<option ';
				if (in_array($item->getValue(), $values))
				{
					$result .= f_util_HtmlUtils::buildAttribute('selected', 'selected') . ' ';
				}

				$result .= f_util_HtmlUtils::buildAttribute('value', $item->getValue()) . '>' . self::escapeText(ucfirst($item->getLabel())) . '</option>';
			}
		}
		else
		{
			foreach ($listItems as $item)
			{
				$result .= '<option ';
				if ($item->getValue() == $value)
				{
					$result .= f_util_HtmlUtils::buildAttribute('selected', 'selected') . ' ';
				}

				$result .= f_util_HtmlUtils::buildAttribute('value', $item->getValue()) . '>' . self::escapeText(ucfirst($item->getLabel())) . '</option>';
			}
		}

		return $result;
	}
	
	
	public static function renderDateCombo($params, $ctx)
	{
		self::addDatePickerScript();
		$ls = LocaleService::getInstance();

		$months = array("1 (".$ls->transFO("f.date.date.abbr.january").")",
						"2 (".$ls->transFO("f.date.date.abbr.february").")",
						"3 (".$ls->transFO("f.date.date.abbr.march").")",
						"4 (".$ls->transFO("f.date.date.abbr.april").")",
						"5 (".$ls->transFO("f.date.date.abbr.may").")",
						"6 (".$ls->transFO("f.date.date.abbr.june").")",
						"7 (".$ls->transFO("f.date.date.abbr.july").")",
						"8 (".$ls->transFO("f.date.date.abbr.august").")",
						"9 (".$ls->transFO("f.date.date.abbr.september").")",
						"10 (".$ls->transFO("f.date.date.abbr.october").")",
						"11 (".$ls->transFO("f.date.date.abbr.november").")",
						"12 (".$ls->transFO("f.date.date.abbr.december").")"
						);

		$thisYear = date_Calendar::now()->getYear();
		$years = array();
		for ($i = $thisYear;$i >= 1901; $i-- )
		{
			$years[$i] = $i;
		}
		
		$days = array();
		for ($i = 1; $i <= 31; $i++ )
		{
			$days[$i] = $i;
		}
		
		self::buildNameAndId($params);
		$params['id'] = str_replace('.', '', $params['id']);
		$html = array(
			self::renderInputByType("text", $params),
			self::buildSelectInputForDateCombo('day', $days, $params['id']),
			self::buildSelectInputForDateCombo('month',$months, $params['id']),
			self::buildSelectInputForDateCombo('year', $years, $params['id']),
			'<script type="text/javascript">
     			 jQuery(document).ready(function() {
     			 
     			 		var dateStr = jQuery("#'.  $params['id'] . '").val();
     			 		if (dateStr.length > 0)
     			 		{
	         				var date = Date.fromString(dateStr);
	         				jQuery("#'.  $params['id'] . '_year").val(date.getFullYear());
	         				jQuery("#'.  $params['id'] . '_month").val(date.getMonth());
	         				jQuery("#'.  $params['id'] . '_day").val(date.getDate());
	         			}
	         			jQuery("#'.  $params['id'] . '_year").show();
	         			jQuery("#'.  $params['id'] . '_month").show();
	         			jQuery("#'.  $params['id'] . '_day").show();
	         			jQuery("#'.  $params['id'] . '").hide();
      			});
			</script>'
			);
			
		return implode("", $html);
	}
	
	private static function buildSelectInputForDateCombo($name, $optionsArray, $baseId)
	{
		$selectId = $baseId . "_" .$name;
		$result = array();
		$result[] = '<select name="'.$name.'" id="' . $selectId.'" style="display:none"><option value=""></option>';
			
			foreach ($optionsArray as $key => $option)
			{
				$result[] = '<option value="'.$key.'">'.$option.'</option>';
			}
		$result[] = '</select><script type="text/javascript">//<![CDATA[ 
			jQuery("#' . $selectId . '").change(function() {
				var date = new Date(jQuery("#'.  $baseId . '_year").val(), jQuery("#'.  $baseId . '_month").val(), jQuery("#'.  $baseId . '_day").val());
				jQuery("#'.  $baseId . '").val(date.asString());
			});
		//]]></script>';
		return implode('', $result);
	}

	/**
	 * @param PropertyInfo $property
	 * @param array $params
	 * @return String
	 */
	private static function buildInputProperty($propertyName, &$params, $ctx)
	{
		if (isset($params['renderer']))
		{
			list($className, $methodName) = explode('::', $params['renderer']);
			return f_util_ClassUtils::callMethodArgs($className, $methodName, array($params, $ctx));
		}
		$property = BeanUtils::getPropertyInfo(self::$bean, $propertyName);
		switch ($property->getType())
		{
			case BeanPropertyType::BOOLEAN:
				$renderMethodName = 'renderBooleaninput';
				break;
			case BeanPropertyType::LOB:
			case BeanPropertyType::LONGSTRING:
				$renderMethodName = 'renderTextarea';
				break;
			case BeanPropertyType::XHTMLFRAGMENT:
				$renderMethodName = 'renderRichtextinput';
				break;
				// TODO: distinguish Date and DateTime
			case BeanPropertyType::DATETIME:
			case BeanPropertyType::DATE:
				$renderMethodName = 'renderDateInput';
				break;
			case BeanPropertyType::DOCUMENT:
				$renderMethodName = 'renderDocumentInput';
				$params["documentType"] = $property->getDocumentType();
				$params["cardinality"] = $property->getCardinality();
				break;
			default:
				$renderMethodName = 'renderTextinput';
				break;
		}

		if (isset($params["hidden"]) && $params["hidden"] == true)
		{
			unset($params["label"]);
			unset($params["labeli18n"]);
			return self::renderHiddeninput($params);
		}

		return self::$renderMethodName($params, $ctx);
	}

	private static function isLabeled($params)
	{
		if (isset($params["labeled"]))
		{
			return $params["labeled"];
		}
		if (isset($params["label"]) || isset($params["labeli18n"]) || isset($params["evaluatedlabel"]))
		{
			return true;
		}

		return false;
	}

	private static function setDefaultValue($name, $value, &$params)
	{
		if (!isset($params[$name]))
		{
			$params[$name] = $value;
		}
	}

	/**
	 * @var website_BlockAction
	 */
	private static $currentAction;

	/**
	 * @var String
	 */
	private static $currentBlockId;
	private static $moduleName;
	private static $actionName;
	private static $formCounter = 0;

	/**
	 * @var website_BlockActionRequest
	 */
	private static $currentActionRequest;

	/**
	 * @var website_Page
	 */
	private static $context;
	private static $datePickerScriptAdded = false;

	/**
	 * @var f_mvc_Bean
	 */
	private static $bean;

	/**
	 * @var ReflectionClass
	 */
	private static $beanReflectionClass;
	private static $hasBean = false;

	private static $showFieldErrors = false;

	/**
	 * @param Array $params
	 */
	private static function initBean($params)
	{
		$reflectionClass = null;
		$beanInstance = null;
		if (isset($params['beanName']))
		{
			$beanName = $params['beanName'];
			$beanInstance = self::getBeanByName($beanName);
		}

		if (isset($params['beanClass']) && $beanInstance === null)
		{
			if (f_util_ClassUtils::classExists($params['beanClass']))
			{
				$beanClassName = $params['beanClass'];
				$index = strrpos($beanClassName, '_');
				if ($index !== false)
				{
					// default beanName
					substr($beanClassName, $index);
					$dummyBeanName = strtolower($beanClassName[$index+1]).substr($beanClassName, $index+2);
					$beanInstance = self::getBeanByName($dummyBeanName);
					if (!isset($params['beanName']))
					{
						$beanName = $dummyBeanName;
					}
				}

				if ($beanInstance !== null)
				{
					BeanUtils::assertInstanceOf($beanInstance, $beanClassName);
				}
				else
				{
					$reflectionClass = new ReflectionClass($params['beanClass']);
					$beanId = self::getParameterValue(website_BlockController::BEAN_DOCUMENT_ID_PARAMETER);
					if ($beanId !== null)
					{
						$beanInstance = BeanUtils::getBeanInstance($reflectionClass, $beanId);
					}
					else
					{
						$beanInstance = BeanUtils::getNewBeanInstance($reflectionClass);
					}
						
					self::contextSet($beanName, $beanInstance);
				}
			}
			else
			{
				throw new Exception("There is no class named ".$params['beanClass']);
			}
		}
		if ($beanInstance !== null)
		{
			self::$bean = $beanInstance;
			self::$hasBean = true;
		}
	}

	/**
	 * @param String $beanName
	 * @return f_mvc_Bean
	 */
	private static function getBeanByName($beanName)
	{
		$beanInstance = self::contextSafeGet($beanName);
		if ($beanInstance === null)
		{
			$beanInstance = self::$currentActionRequest->getAttribute($beanName);
		}
		if ($beanInstance !== null && is_object($beanInstance))
		{
			return BeanUtils::getBean($beanInstance);
		}
		return null;
	}

	/**
	 * @param String $name
	 * @return Mixed
	 */
	private static function getParameterValue($name)
	{
		$value = self::contextSafeGet($name);
		if ($value === null && self::$currentActionRequest->hasAttribute($name))
		{
			$value = self::$currentActionRequest->getAttribute($name);
		}
		return $value;
	}

	/**
	 * @param String $propertyName
	 * @return Boolean
	 */
	private static function hasErrorsForProperty($propertyName)
	{
		$ctxKey = website_BlockAction::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY;
		if (self::$relKey !== null)
		{
			$ctxKey .= "_relative";
		}
		$blockKey = self::$currentBlockId;
		$propertyErrors = self::$context->getAttribute($ctxKey, array());
		return isset($propertyErrors[$blockKey]) && isset($propertyErrors[$blockKey][$propertyName]);
	}

	/**
	 * @param String $propertyName
	 * @return Array()
	 */
	private static function getErrorsForProperty($propertyName)
	{
		if (self::hasErrorsForProperty($propertyName))
		{
			$propertyErrors = self::$context->getAttribute(website_BlockAction::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY, array());
			return $propertyErrors[self::$currentBlockId][$propertyName];
		}
		return array();
	}

	/**
	 * @param String $property
	 * @param list_Item[] $listItems
	 * @param array $params
	 * @return String
	 */
	private function buildCheckboxProperty($propertyName, $listItems, &$params)
	{
		self::buildNameAndId($params);
		if (!array_key_exists('value', $params))
		{
			$value = self::buildPropertyValue($propertyName);
			if ($value === null && isset($params['default-value']))
			{
				$value = explode(",", $params['default-value']);
			}
			$params['value'] = $value;
		}
		$inputName = $params['name'];
		$result = "";
		if (self::isLabeled($params))
		{
			$params["useFor"] = false;
			$result .= self::buildLabel($params);
		}
		$result .= '<ul class="list-multiple">';
		foreach ($listItems as $item)
		{
			$itemValue = $item->getValue();

			$inputId = $params['id'] . '_' . $itemValue;
			$result .= '<li><input type="checkbox" ';
			if (is_array($params['value']) && in_array($itemValue, $params['value']))
			{
				$result .= 'checked="checked" ';
			}
			$result .= f_util_HtmlUtils::buildAttribute('value', $itemValue) . ' ' . f_util_HtmlUtils::buildAttribute('name', $inputName . '[]') . ' '. f_util_HtmlUtils::buildAttribute('id', $inputId) . '/>';
			$result .='<label ' . f_util_HtmlUtils::buildAttribute('for', $inputId) .  ' >' .  self::escapeText(ucfirst($item->getLabel())) . '</label></li>';
		}
		return $result . '</ul>'.self::buildFieldErrors($params);
	}
}
