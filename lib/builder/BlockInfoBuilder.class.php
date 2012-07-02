<?php
class website_BlockInfoBuilder
{
	/**
	 * @var block_BlockService
	 */
	private $bs;

	private $blockInfos;

	public function __construct($bs)
	{
		$this->bs = $bs;
	}

	/**
	 * @param array $blockInfos
	 * @return website_BlockInfoBuilder
	 */
	public function setBlocInfoArray($blockInfos)
	{
		$this->blockInfos = $blockInfos;
		return $this;
	}

	public function getRequestModule()
	{
		return $this->blockInfos['requestModule'];
	}

	public function getTemplateModule()
	{
		return $this->blockInfos['templateModule'];
	}

	public function isCached()
	{
		return isset($this->blockInfos['cache']) && ($this->blockInfos['cache'] === true || is_numeric($this->blockInfos['cache']));
	}

	public function getCacheTime()
	{
		return isset($this->blockInfos['cacheTime']) ? $this->blockInfos['cacheTime'] : 'null';
	}

	public function getType()
	{
		return $this->blockInfos['type'];
	}

	public function getBeforeAll()
	{
		return isset($this->blockInfos['beforeAll']) ? $this->blockInfos['beforeAll'] : false;
	}

	public function getAfterAll()
	{
		return isset($this->blockInfos['afterAll']) ? $this->blockInfos['afterAll'] : false;
	}

	public function getBlockActionClassName()
	{
		return $this->blockInfos['phpBlockClass'];
	}

	public function getVarExportInfo()
	{
		$array = array();
		foreach ($this->blockInfos as $name => $value)
		{
			if ($name !== 'parameters' && $name !== 'metas')
			{
				$array[$name] = $value;
			}
		}

		return var_export($array, true);
	}

	public function getParametersInfoArray()
	{
		$array = array();
		foreach ($this->blockInfos['parameters'] as $propertyInfoArray)
		{
			$array[] = new website_BlockInfoParameterBuilder($propertyInfoArray);
		}
		return $array;
	}

	public function getAttributes()
	{
		$array = array();
		foreach ($this->blockInfos['parameters'] as $name => $propertyInfoArray)
		{
			if (isset($propertyInfoArray['default-value']))
			{
				if (is_bool($propertyInfoArray['default-value']))
				{
					$array['__' . $name] = $propertyInfoArray['default-value'] ? 'true' : 'false';
				}
				else
				{
					$array['__' . $name] = $propertyInfoArray['default-value'];
				}
			}
		}
		return $array;
	}

	public function getSerializedMetas()
	{
		return serialize(array_keys($this->blockInfos['metas']));
	}

	public function getSerializedTitleMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'title') !== false)
			{
				$array[] = $name;
			}
		}
		return serialize($array);
	}

	public function getSerializedDescriptionMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'description') !== false)
			{
				$array[] = $name;
			}
		}
		return serialize($array);
	}

	public function getSerializedKeywordsMetas()
	{
		$array = array();
		foreach ($this->blockInfos['metas'] as $name => $allow)
		{
			if ($allow === true || strpos($allow, 'keywords') !== false)
			{
				$array[] = $name;
			}
		}
		return serialize($array);
	}
}