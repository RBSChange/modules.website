<?php
/**
 * commands_website_CompileBbcodes
 * @package modules.website.command
 */
class commands_website_CompileBbcodes extends commands_AbstractChangeCommand
{
	/**
	 * @return String
	 */
	public function getUsage()
	{
		return "";
	}

	/**
	 * @return String
		 */
	public function getDescription()
	{
		return "compile BBCodes infos";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	public function _execute($params, $options)
	{
		$this->message("== Compile BBCodes ==");
		$this->loadFramework();
		
		$dirPath = f_util_FileUtils::buildChangeBuildPath('modules', 'website', 'lib', 'bbcode');
		f_util_FileUtils::mkdir($dirPath);
		
		$filePath = f_util_FileUtils::buildChangeBuildPath('modules', 'website', 'lib', 'bbcode', 'BBCodeEditor.js');
		$tagSets = website_BBCodeEditor::getInstance()->compile();
		$generator = new builder_Generator();
		$generator->setTemplateDir(f_util_FileUtils::buildWebeditPath('modules', 'website', 'templates', 'builder', 'bbcodes'));
		$generator->assign('tagSets', $tagSets);
		$result = $generator->fetch('BBCodeEditor.js');
		f_util_FileUtils::write($filePath, $result, f_util_FileUtils::OVERRIDE);

		$this->quitOk("Command successfully executed");
	}
}