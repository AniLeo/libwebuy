<?php

class productLine {

	public $superCatId;       // Int
	public $productLineId;    // Int
	public $productLineName;  // String
	public $totalCategories;  // Int

	function __construct(int $superCatId, int $productLineId, string $productLineName, int $totalCategories)
	{
		$this->superCatId = $superCatId;
		$this->productLineId = $productLineId;
		$this->productLineName = $productLineName;
		$this->totalCategories = $totalCategories;
	}

	function __toString() : string
	{
		return "[{$this->superCatId}] [{$this->productLineId}] {$this->productLineName} ($this->totalCategories)";
	}

	public static function IDsToString(array $array) : string
	{
		$ret = '[';
		foreach ($array as $productline) {
			if (is_null($productline->productLineId))
				return null;
			$ret .= $productline->productLineId.',';
		}
		$ret = substr($ret, 0, -1);
		$ret .= ']';

		return $ret;
	}

}

?>
