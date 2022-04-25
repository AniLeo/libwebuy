<?php
include_once "superCat.php";
include_once "productLine.php";
include_once "category.php";
include_once "box.php";

class Webuy
{
	public bool       $debug = false; // Throws exceptions on null returns
	public CurlHandle $cr;            // Shared cURL resource

	function __construct(bool $debug = false)
	{
		$this->cr    = curl_init();
		$this->debug = $debug;
	}

	function __destruct()
	{
		curl_close($this->cr);
	}

	public function getURL(string $country) : ?string
	{
		// Subdomain validity check
		$allowed = ["uk", "pt", "es"];

		if (!in_array($country, $allowed))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		return "https://wss2.cex.{$country}.webuy.io/v3/";
	}

	/** @return array<string, string|int> **/
	public function curlURL(string $url) : ?array
	{
		// Return result as raw output
		curl_setopt($this->cr, CURLOPT_RETURNTRANSFER, true);
		// Point cURL resource to the URL
		curl_setopt($this->cr, CURLOPT_URL, $url);
		// Enable compression
		curl_setopt($this->cr, CURLOPT_ENCODING, "gzip");

		$ret["result"] = curl_exec($this->cr);

		if (is_bool($ret["result"]) || curl_errno($this->cr))
		{
			if ($this->debug) throw new Exception("Debug: cURL execution failed");
			return null;
		}

		$ret["httpcode"] = curl_getinfo($this->cr, CURLINFO_HTTP_CODE);

		// Reset given cURL resource after usage
		curl_reset($this->cr);

		return $ret;
	}

	/** @return array<mixed> **/
	public function callAPI(string $country, string $endpoint) : ?array
	{
		global $profiler;

		$url = $this->getURL($country);

		if (is_null($url))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		// Add endpoint to API URL
		$url .= $endpoint;

		if ($this->debug)
			$profiler->add("callAPI: {$url}");

		$result = $this->curlURL($url);

		if (is_null($result))
		{
			if ($this->debug) throw new Exception("Debug: Null result");
			return null;
		}

		if ($result["httpcode"] !== 200)
		{
			if ($this->debug) throw new Exception("Debug: Null return ({$result["httpcode"]})");
			return null;
		}

		$json = json_decode((string) $result["result"], true);

		if (!$json)
		{
			if ($this->debug) throw new Exception("Debug: JSON decoding failed");
			return null;
		}

		if ($json["response"]["ack"] !== "Success")
		{
			if ($this->debug) throw new Exception("Debug: Null return ({$json["response"]["ack"]})");
			return null;
		}

		if (!empty($json["response"]["error"]["code"]))
		{
			if ($this->debug) throw new Exception("Debug: Null return ({$json["response"]["error"]["code"]})");
			return null;
		}

		// Reset given cURL resource after usage
		curl_reset($this->cr);

		return $json;
	}

	/** @return array<int, superCat> **/
	public function getSuperCats(string $country) : ?array
	{
		$json = $this->callAPI($country, "supercats");

		if (is_null($json))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		$a_superCat = array();

		foreach ($json["response"]["data"]["superCats"] as $id => $array)
		{
			$array["superCatId"] = (int) $array["superCatId"];
			$a_superCat[$array["superCatId"]] = new superCat($array["superCatId"],
			                                                 $array["superCatFriendlyName"]);
		}

		return $a_superCat;
	}

	/** @return array<int, productLine> **/
	public function getProductLines(string $country) : ?array
	{
		$json = $this->callAPI($country, "productlines");

		if (is_null($json))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		$a_productLine = array();

		foreach ($json["response"]["data"]["productLines"] as $id => $array)
		{
			$array["productLineId"] = (int) $array["productLineId"];
			$a_productLine[$array["productLineId"]] = new productLine($array["superCatId"],
			                                                          $array["productLineId"],
			                                                          $array["productLineName"],
			                                                          $array["totalCategories"]);
		}

		return $a_productLine;
	}

	/** @return array<int, category> **/
	public function getCategories(string $country, string $search) : ?array
	{
		$json = $this->callAPI($country, "categories?{$search}");

		if (is_null($json))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		$a_productLine = array();

		foreach ($json["response"]["data"]["categories"] as $id => $array)
		{
			$array["categoryId"] = (int) $array["categoryId"];
			$a_productLine[$array["categoryId"]] = new category($array["superCatId"],
			                                                    $array["categoryId"],
			                                                    $array["categoryFriendlyName"],
			                                                    $array["productLineId"],
			                                                    $array["totalBoxes"]);
		}

		return $a_productLine;
	}

	/** @return array<string, box> **/
	public function getBoxes(string $country, string $search) : ?array
	{
		$a_boxes = array();
		$size = 50;

		for ($i = 1; $i < $size; $i += 50)
		{
			$url = "boxes?{$search}&firstRecord={$i}&count=50&sortBy=boxname&sortOrder=asc";
			$json = $this->callAPI($country, $url);

			if (is_null($json))
			{
				if ($this->debug) throw new Exception("Debug: Null return");
				return null;
			}

			// No results found
			if (is_null($json["response"]["data"]))
				return null;

			$size = $json["response"]["data"]["totalRecords"];

			// Missing size variable
			if (!is_int($size) || $size < 0)
			{
				if ($this->debug) throw new Exception("Debug: Null return");
				return null;
			}

			foreach ($json["response"]["data"]["boxes"] as $id => $array)
			{
				$array["boxId"] = (string) $array["boxId"];
				$a_boxes[$array["boxId"]] = new box($array["boxId"],
				                                    $array["boxName"],
				                                    $array["categoryId"],
				                                    $array["categoryName"],
				                                    $array["superCatId"],
				                                    $array["superCatName"],
				                                    $array["cannotBuy"],
				                                    $array["isNewBox"],
				                                    $array["sellPrice"],
				                                    $array["cashPrice"],
				                                    $array["exchangePrice"],
				                                    $array["boxRating"],
				                                    $array["outOfStock"],
				                                    $array["ecomQuantityOnHand"]);
			}

		}

		return $a_boxes;
	}

	public function getBox(string $country, string $boxId) : ?box
	{
		$url = "boxes/{$boxId}/detail";
		$json = $this->callAPI($country, $url);

		if (is_null($json))
		{
			if ($this->debug) throw new Exception("Debug: Null return");
			return null;
		}

		$array = $json["response"]["data"]["boxDetails"][0];

		return new box($array["boxId"],
		               $array["boxName"],
		               $array["categoryId"],
		               $array["categoryName"],
		               $array["superCatId"],
		               $array["superCatName"],
		               $array["cannotBuy"],
		               $array["isNewBox"],
		               $array["sellPrice"],
		               $array["cashPrice"],
		               $array["exchangePrice"],
		               $array["boxRating"],
		               $array["outOfStock"],
		               $array["ecomQuantityOnHand"]);
	}

	/** @return array<string, array<string, string|int>> **/
	public function getStores(string $country, string $boxId) : ?array
	{
		// Supported countries
		if ($country !== "pt")
			return null;

		$a_locations = array(
			"Aveiro" => array("latitude" => 40.6405055, "longitude" => -8.6537539),
			"Braga" => array("latitude" => 41.5454486, "longitude" => -8.426506999999999),
			"Coimbra" => array("latitude" => 40.2033145, "longitude" => -8.4102573),
			"Faro" => array("latitude" => 37.0193548, "longitude" => -7.9304397),
			"Guarda" => array("latitude" => 40.5383482, "longitude" => -7.266131499999998),
			"Lisboa" => array("latitude" => 38.7222524, "longitude" => -9.1393366),
			"Porto" => array("latitude" => 41.1579438, "longitude" => -8.629105299999999),
			"Setúbal" => array("latitude" => 38.5254047, "longitude" => -8.8941),
			"Viana do Castelo" => array("latitude" => 41.6918275, "longitude" => -8.8344101),
			"Vila Real" => array("latitude" => 41.3010351, "longitude" => -7.7422354),
			"Viseu" => array("latitude" => 40.72764189999999, "longitude" => -7.9157099)
		);

		$ret = array();

		foreach ($a_locations as $name => $coordinates)
		{
			$url = "boxes/{$boxId}/neareststores?latitude={$coordinates["latitude"]}&longitude={$coordinates["longitude"]}";
			$json = $this->callAPI($country, $url);

			if (!isset($json["response"]["data"]["nearestStores"]))
				return null;

			$a_stores = $json["response"]["data"]["nearestStores"];

			foreach ($a_stores as $store)
			{
				$store["storeName"] = (string) $store["storeName"];

				// Already on array
				if (isset($ret[$store["storeName"]]))
					continue;

				$ret[$store["storeName"]] = array(
					"storeId" => $store["storeId"],
					"storeName" => $store["storeName"],
					"quantityOnHand" => $store["quantityOnHand"]
				);
			}
		}

		return $ret;
	}
}
