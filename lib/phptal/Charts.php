<?php
class PHPTAL_Php_Attribute_CHANGE_chart extends ChangeTalAttribute
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderChart($params, $ctx)
	{
		$chart = $params["chart"];
		unset($params["chart"]);
		foreach ($params as $key => $value)
		{
			$chart->setOption($key, $value);
		}

		return self::buildImg($chart, $params);
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}

	/**
	 * @param f_chart_Chart $chart
	 * @return unknown_type
	 */
	protected static function buildImg($chart, $params)
	{
		if (!isset($params["cacheTime"]))
		{
			$params["cacheTime"] = 300;
		}
		
		foreach ($params as $key => $value)
		{
			if (!is_object($value))
			{
				$chart->setOption($key, $value);
			}
		}
		
		$html = "<img src=\"".$chart->getUrl()."\" ";
		$html .= f_util_HtmlUtils::buildAttribute("alt", $chart->getTitle());
		$html .= " />";
		return $html;
	}

	protected static function getProducer($params)
	{
		$producer = null;
		if (isset($params["producer"]))
		{
			$producer = $params["producer"];
		}
		elseif (isset($params["producerClass"]))
		{
			$producer = f_util_ClassUtils::newInstanceSandbox($params["producerClass"], "f_chart_DataTableProducer");
		}
		return $producer;
	}

	protected static function getProducerParams($params)
	{
		if (isset($params["producerParams"]))
		{
			$producerParams = array();
			foreach (explode(";", $params["producerParams"]) as $producerParam)
			{
				$info = explode(" ", $producerParam);
				$producerParams[$info[0]] = $info[1];
			}
			return $producerParams;
		}
		return null;
	}
}

class PHPTAL_Php_Attribute_CHANGE_piechart extends PHPTAL_Php_Attribute_CHANGE_chart
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderPiechart($params, $ctx)
	{
		$producer = self::getProducer($params);
		$chart = new f_chart_PieChart($producer->getDataTable(self::getProducerParams($params)));

		return self::buildImg($chart, $params);
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}
class PHPTAL_Php_Attribute_CHANGE_linechart extends PHPTAL_Php_Attribute_CHANGE_chart
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderLinechart($params, $ctx)
	{
		$producer = self::getProducer($params);
		$chart = new f_chart_LineChart($producer->getDataTable(self::getProducerParams($params)));

		return self::buildImg($chart, $params);
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}
class PHPTAL_Php_Attribute_CHANGE_barchart extends PHPTAL_Php_Attribute_CHANGE_chart
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderBarchart($params, $ctx)
	{
		$producer = self::getProducer($params);
		$chart = new f_chart_BarChart($producer->getDataTable(self::getProducerParams($params)));

		return self::buildImg($chart, $params);
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}
class PHPTAL_Php_Attribute_CHANGE_datatable extends PHPTAL_Php_Attribute_CHANGE_chart
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderDatatable($params, $ctx)
	{
		if (isset($params["chart"]))
		{
			$chart = $params["chart"];
			$data = $chart->getDataTable();
			unset($params["chart"]);
		}
		else
		{
			$producer = self::getProducer($params);
			if ($producer !== null)
			{
				$data = $producer->getDataTable(self::getProducerParams($params));	
			}
		}
		
		if ($data !== null)
		{
			$table = new f_chart_Table($data, $params);
			return $table->getHTML();
		}
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}
class PHPTAL_Php_Attribute_CHANGE_producer extends ChangeTalAttribute
{
	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderProducer($params, $ctx)
	{

	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}