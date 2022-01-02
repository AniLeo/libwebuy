<?php

class category
{
	public int    $superCatId;            // Int
	public int    $categoryId;            // Int
	public string $categoryFriendlyName;  // String
	public int    $productLineId;         // Int
	public int    $totalBoxes;            // Int

	function __construct(int    $superCatId,
	                     int    $categoryId,
	                     string $categoryFriendlyName,
	                     int    $productLineId,
	                     int    $totalBoxes)
	{
		$this->superCatId           = $superCatId;
		$this->categoryId           = $categoryId;
		$this->categoryFriendlyName = $categoryFriendlyName;
		$this->productLineId        = $productLineId;
		$this->totalBoxes           = $totalBoxes;
	}

	function __toString() : string
	{
		return sprintf("[%d] [%d] [%d] %s (%d)",
		               $this->superCatId,
		               $this->productLineId,
		               $this->categoryId,
		               $this->categoryFriendlyName,
		               $this->totalBoxes);
	}
}

?>
