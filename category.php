<?php

class category {

	public $superCatId;            // Int
	public $categoryId;            // Int
	public $categoryFriendlyName;  // String
	public $productLineId;         // Int
	public $totalBoxes;            // Int

	function __construct(int $superCatId, int $categoryId, string $categoryFriendlyName, int $productLineId, int $totalBoxes)
	{
		$this->superCatId = $superCatId;
		$this->categoryId = $categoryId;
		$this->categoryFriendlyName = $categoryFriendlyName;
		$this->productLineId = $productLineId;
		$this->totalBoxes = $totalBoxes;
	}

	function __toString() : string
	{
		return "[{$this->superCatId}] [{$this->productLineId}] [{$this->categoryId}] {$this->categoryFriendlyName} ($this->totalBoxes)";
	}

	public static function IDsToString(array $array) :  string
	{
		$ret = '[';
		foreach ($array as $category) {
			if (is_null($category->categoryId))
				return null;
			$ret .= $category->categoryId.',';
		}
		$ret = substr($ret, 0, -1);
		$ret .= ']';

		return $ret;
	}

}

?>
