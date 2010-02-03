<?php
class commands_GenerateBeanFrontofficeForm extends commands_AbstractChangedevCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		$usage = "<className> <outputFileName> [options]\n";
		$usage .= "where options in:";
		$usage .= "  --stdout: output the generated html to standard output \n";
		return $usage;
	}
	
	/**
	 * @return String
	 */
	function getDescription()
	{
		return "generate a simple frontoffice bean edition form template";
	}
	
	/**
	 * @return String[]
	 */
	function getOptions()
	{
		return array("--force --stdout");
	}
	
	function getParameters($completeParamCount, $params, $options, $current)
	{
		if ($completeParamCount == 0)
		{
			$this->loadFramework();
			return ClassResolver::getClassNames($current);
		}
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		if(isset($options['stdout']) && $options['stdout'] == true)
		{
			return count($params) == 1;
		}
		return count($params) == 2;
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->loadFramework();
		$beanClassName = $params[0];
		$destFile = $params[1];
		
		if (!f_util_ClassUtils::classExists($beanClassName))
		{
			return $this->quitError("$beanClassName does not exist!\n");
		}
		
		$reflectionClass = new ReflectionClass($beanClassName);
		$bean = BeanUtils::getNewBeanInstance($reflectionClass);
		$beanModel = BeanUtils::getBeanModel($bean);
		$generator = new builder_Generator('bean');
		$generator->assign('model', $beanModel);
		$generator->assign('beanClassName', $beanClassName);
		$beanInfo = explode("_", $beanClassName);
		$beanName = f_util_ArrayUtils::lastElement($beanInfo);
		$generator->assign('beanName', strtolower($beanName[0]).substr($beanName, 1));
		$result = $generator->fetch('BeanFrontofficeForm.tpl');
		if (isset($options['stdout']) && $options['stdout'] == true)
		{
			echo $result;
		}
		else
		{
			if (file_exists($destFile))
			{
				return $this->quitError("$destFile already exists... aborting\n");
			}
			f_util_FileUtils::write($destFile, $result);
			$this->quitOk($destFile." generated successfully");
		}
	}
}
