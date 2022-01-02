<?php

class box {

	public $boxId;                // String
	public $boxName;              // String
	public $categoryId;           // Int
	public $categoryName;         // String
	public $superCatId;           // Int
	public $superCatName;         // String
	public $cannotBuy;            // Int
	public $isNewBox;             // Int
	public $sellPrice;            // Float / Int
	public $cashPrice;            // Float / Int
	public $exchangePrice;        // Float / Int
	public $boxRating;            // Float / Int
	public $outOfStock;           // Int
	public $ecomQuantityOnHand;   // Int

	function __construct(string $boxId, string $boxName, int $categoryId, string $categoryName,
	int $superCatId, string $superCatName, int $cannotBuy, int $isNewBox, $sellPrice, $cashPrice,
	$exchangePrice, $boxRating, int $outOfStock, int $ecomQuantityOnHand) {
		$this->boxId = $boxId;
		$this->boxName = $boxName;
		$this->categoryId = $categoryId;
		$this->categoryName = $categoryName;
		$this->superCatId = $superCatId;
		$this->superCatName = $superCatName;
		$this->cannotBuy = $cannotBuy;
		$this->isNewBox = $isNewBox;
		if (is_float($sellPrice) || is_int($sellPrice))
			$this->sellPrice = $sellPrice;
		if (is_float($cashPrice) || is_int($cashPrice))
			$this->cashPrice = $cashPrice;
		if (is_float($exchangePrice) || is_int($exchangePrice))
			$this->exchangePrice = $exchangePrice;
		if (is_float($boxRating) || is_int($boxRating))
			$this->boxRating = $boxRating;
		$this->outOfStock = $outOfStock;
		$this->ecomQuantityOnHand = $ecomQuantityOnHand;
	}

	function __toString() : string
	{
		return "{$this->boxName} [B:{$this->cashPrice} E:{$this->exchangePrice} S:{$this->sellPrice}] ($this->ecomQuantityOnHand)";
	}

	function getURL(string $country) : string
	{
		return "https://{$country}.webuy.com/product-detail/?id={$this->boxId}";
	}

	function getImageMedium(string $country) : string
	{
		return "https://{$country}.static.webuy.com/product_images/{$this->superCatName}/{$this->categoryName}/{$this->boxId}_m.jpg";
	}

}

?>
