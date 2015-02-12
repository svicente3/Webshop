<?php
session_start();
//Get the parameters passed
$origin = $_GET['origin'];
//Choose the function we are gonna execute
switch ($origin) {
  case "login":
    getProductsFromPartners();
    break;

  case "afterBuying":
    getProductsFromJson();
    break;

  default:
    echo $origin;
}
//GET PARTNERS PRODUCTS (INIT)
function getProductsFromPartners(){
	//Create a json object where we'll push all the products we wanna display in our webshop
	//Will create and string and then turn it into a json object
	$sAllProducts = '{"products":[]}';
	$oAllProducts = json_decode($sAllProducts);
	//GET PRODUCTS FROM THE DIFFERENTS SOURCES
	//Get all the partners links
	$sPartnersLinks = file_get_contents("partners-links.json");
	// Convert the list into a json object 
	$oPartnersLinks = json_decode($sPartnersLinks);
	// Loop through each partner on the list
	foreach ($oPartnersLinks->partnersLinks as $sLink) {
		//JSON
		//Get the content from the link and turn it into a json object
		$sPartnerWebshopJson = file_get_contents($sLink."/webshop2014/products.json");
		$oPartnerWebshopJson = json_decode($sPartnerWebshopJson);
		//Push each of the products into the products array in our json object: $oAllProducts
		foreach($oPartnerWebshopJson->products as $oProduct){
			array_push($oAllProducts->products, $oProduct);
		} 
		//XML
		//Get the content from the link and turn it into a xml object
		$sPartnerWebshopXml = file_get_contents($sLink."/webshop2014/products.xml");
	    $oPartnerWebshopXml = simplexml_load_string($sPartnerWebshopXml);
	    //Push each of the products into the products array in our json object: $oAllProducts
	    $oProducts = $oPartnerWebshopXml->product;
	    foreach($oProducts as $oProduct){
	    	//I can push it this way because when I push the categories of the product they are pushed as
	    	//an object in the categories key. It will look like this ->   "categories":{"category":["accessories","man"]}   <-
	    	//array_push($oAllProducts->products, $oProduct);
	    	//So... we'll do an string with the information, turn it into an object and push it
	    	//But first, we'll loop through the categories and make an string with all of each product
	    	$sAllCategories = "";
	    	$firstCategory = "true";
	    	foreach ($oProduct->categories->category as $sCategory ){
	    		if( $firstCategory == "true"){
	    			$sAllCategories .= '"'.$sCategory.'"';
	    			$firstCategory = "false";
	    		}
	    		else{
	    			$sAllCategories .= ', "'.$sCategory.'"';
	    		}
	    	}
	    	$sNewProduct = '{"partner":"'.$oProduct->partner.'","id":"'.$oProduct->id.'", "name":"'.$oProduct->name.'","description":"'.$oProduct->description.'","image":"'.$oProduct->image.'","price":"'.$oProduct->price.'","active":"'.$oProduct->active.'","categories":['.$sAllCategories.']}';
	    	$oNewProduct = json_decode($sNewProduct);
	    	//Push the object into the products array in our json object: $oAllProducts
			array_push($oAllProducts->products, $oNewProduct);
	    }
	}  
	//GET OUR OWN PRODUCTS FROM OUR DATABASE
	//Create the connection with the database
	include_once "DBconnection.php";
	//Make the query for the products
	$sql = "SELECT * FROM products";	
	$result = $conn->query($sql);
	//Loop through products table
	while($row = mysqli_fetch_array($result)){
		//For each product/row in our table, we create an string ...
		$sProduct = '{"partner":"'.$row['product_partner_id'].'","id":"'.$row['product_id'].'", "name":"'.$row['product_name'].'","description":"'.$row['product_description'].'","image":"'.$row['product_image'].'","price":"'.$row['product_price'].'","active":"'.$row['product_active'].'","categories":['.$row['product_categories'].']}';
		//... to afterwards convert it into an object 
		$oProduct = json_decode($sProduct);
		//Push the object into the products array in our json object: $oAllProducts
		array_push($oAllProducts->products, $oProduct);
	}
	//Convert the object into a string 
	$sAllProducts = json_encode($oAllProducts);
	//Save it in a document
	file_put_contents("allProducts.json",$sAllProducts);
	//Return it
	echo $sAllProducts;
}
//GET PRODUCT FROM OUR JSON FILE
function getProductsFromJson(){
	$sAllProducts = file_get_contents("allProducts.json");
	echo $sAllProducts;
}


?>