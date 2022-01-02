<?php

class box
{
	public string $boxId;                // String
	public string $boxName;              // String
	public int    $categoryId;           // Int
	public string $categoryName;         // String
	public int    $superCatId;           // Int
	public string $superCatName;         // String
	public int    $cannotBuy;            // Int
	public int    $isNewBox;             // Int
	public float  $sellPrice;            // Float / Int
	public float  $cashPrice;            // Float / Int
	public float  $exchangePrice;        // Float / Int
	public ?float $boxRating;            // Float / Int / Null
	public int    $outOfStock;           // Int
	public int    $ecomQuantityOnHand;   // Int

	function __construct(string         $boxId,
	                     string         $boxName,
	                     int            $categoryId,
	                     string         $categoryName,
	                     int            $superCatId,
	                     string         $superCatName,
	                     int            $cannotBuy,
	                     int            $isNewBox,
	                     float|int      $sellPrice,
	                     float|int      $cashPrice,
	                     float|int      $exchangePrice,
	                     float|int|null $boxRating,
	                     int            $outOfStock,
	                     int            $ecomQuantityOnHand)
	{
		$this->boxId              = $boxId;
		$this->boxName            = $boxName;
		$this->categoryId         = $categoryId;
		$this->categoryName       = $categoryName;
		$this->superCatId         = $superCatId;
		$this->superCatName       = $superCatName;
		$this->cannotBuy          = $cannotBuy;
		$this->isNewBox           = $isNewBox;
		$this->sellPrice          = (float) $sellPrice;
		$this->cashPrice          = (float) $cashPrice;
		$this->exchangePrice      = (float) $exchangePrice;
		$this->boxRating          = (float) $boxRating;
		$this->outOfStock         = $outOfStock;
		$this->ecomQuantityOnHand = $ecomQuantityOnHand;
	}

	function __toString() : string
	{
		return sprintf("%s [B:%f E:%f S:%f] (%d)",
		               $this->boxName,
		               $this->cashPrice,
		               $this->exchangePrice,
		               $this->sellPrice,
		               $this->ecomQuantityOnHand);
	}

	function getURL(string $country) : string
	{
		return sprintf("https://%s.webuy.com/product-detail/?id=%s",
		               $country,
		               $this->boxId);
	}

	function getImageMedium(string $country) : string
	{
		return sprintf("https://%s.static.webuy.com/product_images/%s/%s/%s_m.jpg",
		               $country,
		               $this->superCatName,
		               $this->categoryName,
		               $this->boxId);
	}
}

?>
