<?php
session_start();
include_once "DBconnection.php";
//echo "$".$_SESSION['userLogged']."$";
//Info we need for the order later
$order_client_email = $_SESSION['userLogged'];
$order_partner_email;
$idProductSold = $_GET['idProductSold'];
$partnerKey = $_GET['partnerKey'];
$order_partner_id;
//Get the document with all the products
$sAllProducts = file_get_contents("allProducts.json");
// Convert the list into a json object 
$oAllProducts = json_decode($sAllProducts);
//Loop through all the products to find the  one that we have sold
foreach($oAllProducts->products as $oProduct){
	global $conn, $idProductSold, $order_partner_id, $order_customer_id, $partnerKey;
	//We'll look for it pointing at the id's of the products and see which one matches
	if($oProduct->id == $idProductSold && $oProduct->partner == $partnerKey){
		//Once we have found the product we'll look from wich partner is it
		$iPartnerKey=$oProduct->partner;
		//And once we know the partner, we'll look in our database for it's information: email, comission,...
		$sql = "SELECT * FROM partners WHERE partner_key=$iPartnerKey";
		$result = $conn->query($sql);
		//Once we have found the partner in our database
		while($row = mysqli_fetch_array($result)){
			//We set the partner_EMAIL into the variable for the order
			$order_partner_email = $row['partner_email'];
			$order_partner_id = $row['partner_id'];
			
			//We calculate the porcentatge we keep and the benefit our partner gets
			$ownBenefit = $oProduct->price * $row['partner_comission'] / 100;
			$partnerBenefit = $oProduct->price - $ownBenefit;
			//Compose the email subject and message
			$emailSubject = "Partner product sold";
			$emailSubject = urlencode($emailSubject);
			$emailMessage = "Dear partner ".$row['partner_name']." (".$row['partner_email']."), we have just sold one of your products in our webshop. The product information that has been sold is the following: Name: ".$oProduct->name."  Description: ".$oProduct->description."  Product link: ".$oProduct->image.". As we acorded to keep ".$row['partner_comission']."% of the total price of each of the products, the earning information is the following: Original price: ".$oProduct->price."Kr.  Price you earn: ".$partnerBenefit."  Price we keep: ".$ownBenefit."  Thank you very much for your confidence in us. Best regards, Le Boutique.";
			$emailMessage = urlencode($emailMessage);
			file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");	
			//SEND EMAIL also to the customer
			$emailSubject = "Customer product bought";
			$emailSubject = urlencode($emailSubject);
			$emailMessage = "Dear customer ".$order_client_email.", you have just bought one of our products in our webshop. The product information that you have bought is the following: Name: ".$oProduct->name."  Description: ".$oProduct->description."  Product link: ".$oProduct->image."  Price: ".$oProduct->price."Kr.  Thank you very much for your confidence in us. Best regards, Le Boutique.";
			$emailMessage = urlencode($emailMessage);
			file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");	
		}
		//Afterwards we are gonna save the order in our table
		$idProductSold = $_GET['idProductSold'];
		$order_date = date('Y-m-d');
		$sql = "INSERT INTO `webshopp`.`orders` (`order_id`, `order_client_email`, `order_partner_email`, `order_product_id`, `order_date`) VALUES (NULL, '$order_client_email', '$order_partner_email', '$idProductSold', '$order_date');";
		$result = $conn->query($sql);
		//Now we have to change product_active to 0 because it's no longer avaliable
		$oProduct->active =0;
		//If the product is ours (partner_id=1) we'll also update it in our database
		if($order_partner_id == 1){
			$sql = "UPDATE products SET product_active=0 WHERE product_id=$idProductSold";
			$result = $conn->query($sql);
		}
		$sAllProducts = json_encode($oAllProducts);
		file_put_contents("allProducts.json",$sAllProducts);
	}// END if($oProduct->id == $idProductSold)
} 
echo "true";

?>