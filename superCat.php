<?php

class superCat {

	public $superCatId;            // Int
	public $superCatFriendlyName;  // String

	function __construct(int $superCatId, string $superCatFriendlyName)
	{
		$this->superCatId = $superCatId;
		$this->superCatFriendlyName = $superCatFriendlyName;
	}

	function __toString() : string
	{
		return "[{$this->superCatId}] {$this->superCatFriendlyName}";
	}

}

?>
