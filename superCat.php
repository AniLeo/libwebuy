<?php

class superCat
{
	public int    $superCatId;            // Int
	public string $superCatFriendlyName;  // String

	function __construct(int    $superCatId,
	                     string $superCatFriendlyName)
	{
		$this->superCatId           = $superCatId;
		$this->superCatFriendlyName = $superCatFriendlyName;
	}

	function __toString() : string
	{
		return sprintf("[%d] %s",
		               $this->superCatId,
		               $this->superCatFriendlyName);
	}
}

?>
