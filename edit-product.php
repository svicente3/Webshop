<?php
session_start();
//Include DB connection
include_once "DBconnection.php";
//echo "$".$_SESSION['userLogged']."$";
//Get the parameters passed
$idProduct = $_GET['idProduct'];
$partnerKey = $_GET['partnerKey'];
$newProductName = $_GET['newProductName'];
$newProductDescription = $_GET['newProductDescription'];
$newProductPrice = $_GET['newProductPrice'];
$sFunction = $_GET["sFunction"];
//Declare a variable we'll need later
$inputsNewProduct = "";
//Choose the function we wanna execute
switch ($sFunction) {
	 case "openEditModal":
	    openEditModal();
    	break;

	case "saveChanges":
	    saveChanges();
	    break;

	case "removeProduct":
	    removeProduct();
	    break;

  default:
    echo $sFunction;
}
//OPEN EDIT PRODUCT MODAL
function openEditModal(){
	global $conn, $idProduct, $partnerKey;
	//Get the document with all the products
	$sAllProducts = file_get_contents("allProducts.json");
	// Convert the list into a json object 
	$oAllProducts = json_decode($sAllProducts);
	//Loop through all the products to find the one we wanna edit
	foreach($oAllProducts->products as $oProduct){
		if($oProduct->id == $idProduct && $oProduct->partner == $partnerKey){
			$inputsNewProduct = '<div><input id="newProductName" type="text" value="'.$oProduct->name.'" placeholder="New product name"></div><div><input id="newProductDescription" type="text" value="'.$oProduct->description.'" placeholder="New peoduct description"></div><div><input id="newProductPrice" type="text" value="'.$oProduct->price.'" placeholder="New product price"></div><div><input id="saveChanges" class="btn btn-primary" type="button" value="Save changes" data-productId="'.$oProduct->id.'" data-partnerKey="'.$oProduct->partner.'"></div>';
		}
	}
	//Return the data
	echo $inputsNewProduct;	
}
//SAVE CHANGES IN THE PRODUCT
function saveChanges(){
	global $conn, $idProduct, $partnerKey, $newProductName, $newProductPrice, $newProductDescription;
	//Get the document with all the products
	$sAllProducts = file_get_contents("allProducts.json");
	// Convert the list into a json object 
	$oAllProducts = json_decode($sAllProducts);
	//Loop through all the products to find the that one 
	foreach($oAllProducts->products as $oProduct){
		if($oProduct->id == $idProduct && $oProduct->partner == $partnerKey){
			//If it's my own product ($partnerKey == 0)
			if($partnerKey == 0){
				$sql = "UPDATE products SET product_name='$newProductName', product_description='$newProductDescription', product_price='$newProductPrice' WHERE product_id='$idProduct'";
				$result = $conn->query($sql);
			}
			$oProduct->name = $newProductName;
			$oProduct->description = $newProductDescription;
			$oProduct->price = $newProductPrice;
			//Save it back to the document
			$sAllProducts = json_encode($oAllProducts);
			file_put_contents("allProducts.json",$sAllProducts);
			//Return true
			echo "true";
		}
	}
}
//REMOVE PRODUCT
function removeProduct(){
	global $conn, $idProduct, $partnerKey;
	//Get the document with all the products
	$sAllProducts = file_get_contents("allProducts.json");
	// Convert the list into a json object 
	$oAllProducts = json_decode($sAllProducts);
	//Loop through all the products to find the that one 
	foreach($oAllProducts->products as $oProduct){
		if($oProduct->id == $idProduct && $oProduct->partner == $partnerKey){
			//If it's my own product ($partnerKey == 0)
			if($partnerKey == 0){
				$sql = "UPDATE products SET product_active=0 WHERE product_id='$idProduct'";
				$result = $conn->query($sql);
			}
			$oProduct->active = 0;
			//Save it back to the document
			$sAllProducts = json_encode($oAllProducts);
			file_put_contents("allProducts.json",$sAllProducts);
			//Return true
			echo "true";
		}
	}
}

?>