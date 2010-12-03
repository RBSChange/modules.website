<?php
class builder_BlockGenerator extends builder_ModuleGenerator
{
	/**
	 * Generate a block : blocks.xml, blockAction, success template, tag, locales.
	 *
	 * @param String $blockName
	 * @param Boolean $genTag
	 * @param String $icon
	 * @return String the path of the generated PHP file
	 */
	public function generateBlock($blockName, $genTag, $icon)
	{
		$blockPath = $this->_generateBlockAction($blockName);
		$this->_generateBlocksxml($blockName, $icon);
		if ($genTag)
		{
			$this->_generateBlockTag($blockName, $icon);
		}
		block_BlockService::getInstance()->compileBlocksForPackage("modules_".$this->name);
		return $blockPath;
	}


	protected function _generateBlockTag($blockName, $icon)
	{
		$tagName = "contextual_website_website_modules_".$this->name."_".strtolower($blockName);
		$tagFile = realpath('./modules/'.$this->name.'/config').'/modules_website.page.tags.xml';
		if (file_exists($tagFile))
		{
			$dom = f_util_DOMUtils::fromPath($tagFile);
			if (!$dom->exists("//tag[. = '$tagName']"))
			{
				$result = $this->_getTpl('modules', 'pageTagsBlock.tpl', $blockName, $icon);
				$tag = f_util_DOMUtils::fromString($result);
				$tagElement = $tag->getElementsByTagName('tag')->item(0);
				$domElements = $dom->getElementsByTagName('tags')->item(0);
				$domElements->appendChild($dom->importNode($tagElement, true));

				echo "Updating $tagFile.\n";
				f_util_DOMUtils::save($dom, $tagFile);
			}
			else
			{
				echo "Tag $tagName already defined in $tagFile.\n";
			}
		}
		else
		{
			$result = $this->_getTpl('modules', 'pageTagsBlock.tpl', $blockName, $icon);
			echo "Generating $tagFile, creating $tagName tag.\n";
			f_util_FileUtils::write($tagFile, $result);
		}

		$localeId = strtolower($blockName)."-block";
		$localeFile = f_util_FileUtils::buildWebeditPath('modules', $this->name, 'locale', 'bo', 'tags.xml');
		if (file_exists($localeFile))
		{
			$dom = f_util_DOMUtils::fromPath($localeFile);
			if(!$dom->exists("//entity[@id = '$localeId']"))
			{
				$result = $this->_getTpl('modules', 'tagsLocaleBlock.tpl', $blockName, $icon);

				$locale = f_util_DOMUtils::fromString($result);
				$localeElement = $locale->getElementsByTagName('entity')->item(0);

				$domElements = $dom->getElementsByTagName('localization')->item(0);
				$domElements->appendChild($dom->importNode($localeElement, true));

				echo "Add locale $localeId in $localeFile.\n";
				f_util_DOMUtils::save($dom, $localeFile);
			}
			else
			{
				echo "Locale $localeId already in $localeFile.\n";
			}
		}
		else
		{
			$result = $this->_getTpl('modules', 'tagsLocaleBlock.tpl', $blockName, $icon);
			echo "Generating $localeFile, creating $localeId locale.\n";
			f_util_FileUtils::write($localeFile, $result);
		}
	}

	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockTemplateInfo()
	{
		return array('blocks', 'BlockAction.class.php.tpl');
	}
	
	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockSuccessViewInfo()
	{
		return array('blocks', 'BlockActionSuccess.html.tpl');
	}

	/**
	 * @param String $blockName
	 * @return String the path of the generated PHP file
	 */
	protected function _generateBlockAction($blockName)
	{
		$blockactionFile = f_util_FileUtils::buildWebeditPath('modules', $this->name, 'lib', 'blocks', 'Block'.$blockName.'Action.class.php');
		if(!file_exists($blockactionFile))
		{
			list($tplFolder, $tplName) = $this->getBlockTemplateInfo();
			$result = $this->_getTpl($tplFolder, $tplName, $blockName);
			echo "Generating $blockactionFile\n";
			f_util_FileUtils::write($blockactionFile, $result);
		}
		else
		{
			echo "$blockactionFile already exists\n";
		}
		ClassResolver::getInstance()->appendFile($blockactionFile);

		$blocktemplateFile = f_util_FileUtils::buildWebeditPath('modules', $this->name, 'templates', ucfirst($this->name).'-Block-'.$blockName.'-Success.all.all.html');
		if(!file_exists($blocktemplateFile))
		{
			list($tplFolder, $tplName) = $this->getBlockSuccessViewInfo();
			$result = $this->_getTpl($tplFolder, $tplName, $blockName, null, array("successViewPath" => $blocktemplateFile));
			echo "Generating $blocktemplateFile\n";
			f_util_FileUtils::write($blocktemplateFile, $result);
		}
		else
		{
			echo "$blocktemplateFile already exists\n";
		}

		return $blockactionFile;
	}

	/**
	 * @param unknown_type $blockName
	 * @param unknown_type $icon
	 * @return unknown_type
	 */
	protected function _generateBlocksxml($blockName, $icon)
	{
		$blocksFile = f_util_FileUtils::buildWebeditPath('modules', $this->name, 'config', 'blocks.xml');
		$blockType = "modules_".$this->name."_". f_util_StringUtils::lcfirst($blockName);
		if (file_exists($blocksFile))
		{
			$dom = f_util_DOMUtils::fromPath($blocksFile);
			if (!$dom->exists("//block[@type = '$blockType']"))
			{
				$result = $this->_getTpl('modules', 'blocks.tpl', $blockName, $icon);
				$block = f_util_DOMUtils::fromString($result);
				$blockElement = $block->getElementsByTagName('block')->item(0);
				$domElements = $dom->getElementsByTagName('blocks')->item(0);
				$domElements->appendChild($dom->importNode($blockElement, true));
				echo "Add $blockType in $blocksFile.\n";
				f_util_DOMUtils::save($dom, $blocksFile);
			}
			else
			{
				echo "$blockType already in $blocksFile.\n";
			}
		}
		else
		{
			$result = $this->_getTpl('modules', 'blocks.tpl', $blockName, $icon);
			echo "Generating $blocksFile, creating $blockType block entry.\n";
			f_util_FileUtils::write($blocksFile, $result);
		}

		$localeFile = f_util_FileUtils::buildWebeditPath('modules', $this->name, 'locale', 'bo', 'blocks.xml');
		$localeId = strtolower($blockName);
		if (file_exists($localeFile))
		{
			$dom = f_util_DOMUtils::fromPath($localeFile);
			if (!$dom->exists("//entity[@id = '$localeId']"))
			{
				$result = $this->_getTpl('modules', 'blocksLocale.tpl', $blockName);
				$locale = f_util_DOMUtils::fromString($result);
				$localeElement = $locale->getElementsByTagName('entity')->item(0);
				$domElements = $dom->getElementsByTagName('localization')->item(0);
				$domElements->appendChild($dom->importNode($localeElement, true));
				echo "Add $localeId locale in $localeFile.\n";
				f_util_DOMUtils::save($dom, $localeFile);
			}
			else
			{
				echo "Locale $localeId already in $localeFile.\n";
			}
		}
		else
		{
			$result = $this->_getTpl('modules', 'blocksLocale.tpl', $blockName);
			echo "Generating $localeFile, creating $localeId locale.\n";
			f_util_FileUtils::write($localeFile, $result);
		}
	}

	protected function _getTpl($folder, $tpl, $blockName, $icon = null, $additionalParams = null)
	{
		$templateDir = f_util_FileUtils::buildWebeditPath('modules', 'website', 'templates', 'builder', $folder);
		$generator = new builder_Generator();
		$generator->setTemplateDir($templateDir);
		$generator->assign('author', $this->author);
		// Deprecated name entry. Use blockName instead
		$generator->assign('name', $blockName);
		$generator->assign('blockName', $blockName);
		$generator->assign('module', $this->name);
		$generator->assign('icon', $icon);
		$generator->assign('date', $this->date);
		foreach ($this->getAdditionalTplVariables() as $key => $value)
		{
			$generator->assign($key, $value);
		}
		if ($additionalParams !== null)
		{
			foreach ($additionalParams as $key => $value)
			{
				$generator->assign($key, $value);
			}
		}
		$result = $generator->fetch($tpl);
		return $result;
	}

	protected function getAdditionalTplVariables()
	{
		return array();
	}
}