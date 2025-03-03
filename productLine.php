<?php

class productLine
{
	public int    $superCatId;       // Int
	public int    $productLineId;    // Int
	public string $productLineName;  // String
	public int    $totalCategories;  // Int

	function __construct(int    $superCatId,
	                     int    $productLineId,
	                     string $productLineName,
	                     int    $totalCategories)
	{
		$this->superCatId      = $superCatId;
		$this->productLineId   = $productLineId;
		$this->productLineName = $productLineName;
		$this->totalCategories = $totalCategories;
	}

	function __toString() : string
	{
		return sprintf("[%d] [%d] %s (%d)",
		               $this->superCatId,
		               $this->productLineId,
		               $this->productLineName,
		               $this->totalCategories);
	}

	/** @param array<productLine> $array **/
	public static function IDsToString(array $array) : ?string
	{
		$ret = '[';

		foreach ($array as $productline)
			$ret .= $productline->productLineId.',';

		$ret = substr($ret, 0, -1);
		$ret .= ']';

		return $ret;
	}
}

?>
