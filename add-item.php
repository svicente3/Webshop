<?php 
session_start();
include_once "DBconnection.php";
//product info
$newProductName = $_GET["newProductName"];
$newProductDescription = $_GET["newProductDescription"];
$newProductPrice = $_GET["newProductPrice"];
$newProductImage = $_GET["newProductImage"];
$newProductCategories = $_GET["newProductCategories"];
//customer info
$sRegisterName = $_GET["sRegisterName"];
$sRegisterEmail = $_GET["sRegisterEmail"];
$sRegisterGender = $_GET["sRegisterGender"];
$sRegisterPhone = $_GET["sRegisterPhone"];
//partner info
$newPartnerName = $_GET["newPartnerName"];
$newPartnerEmail = $_GET["newPartnerEmail"];
$newPartnerUrl = $_GET["newPartnerUrl"];
$newPartnerKey = $_GET["newPartnerKey"];
$newPartnerPhone = $_GET["newPartnerPhone"];
//"item" we add
$itemAdded = $_GET['itemAdded'];
//Choose the item we are gonna add
switch ($itemAdded) {
  case "product":
    addProduct();
    break;

  case "customer":
    addCustomer();
    break;

  case "partner":
    addPartner();
    break;

  default:
    echo $itemAdded;
}
//ADD PRODUCT
function addProduct(){
	global $newProductCategories, $newProductPrice, $newProductImage, $newProductDescription, $newProductName, $conn;
	//INSERT product in our DB
	$sql = "INSERT INTO `products` (`product_id`, `product_price`, `product_name`, `product_description`, `product_image`, `product_active`, `product_partner_id`, `product_categories`) VALUES (NULL, '$newProductPrice', '$newProductName', '$newProductDescription', '$newProductImage', '1', '0', '$newProductCategories')";
	$result = $conn->query($sql);
	$lastId=0;
	$sql = "SELECT LAST(product_id) AS LastProductId FROM products";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)){
			$lastId = $row['product_id'];
		}
	}
	
	$sAllProducts = file_get_contents("allProducts.json");
	// Convert the list into a json object 
	$oAllProducts = json_decode($sAllProducts);
	$sProduct= '{"partner":"0","id":"'.$lastId.'","name":"'.$newProductName.'","description":"'.$newProductDescription.'","image":"'.$newProductImage.'","price":"'.$newProductPrice.'","active":"1","categories":['.$newProductCategories.']}';
	$oProduct = json_decode($sProduct);
	//Push the object into the products array in our json object: $oAllProducts
	array_push($oAllProducts->products, $oProduct);
	//Convert the object into a string 
	$sAllProducts = json_encode($oAllProducts);
	//Save it in a document
	file_put_contents("allProducts.json",$sAllProducts);
	echo "true";
}
//ADD CUSTOMER
function addCustomer(){
	global  $sRegisterName, $sRegisterEmail, $sRegisterGender, $sRegisterPhone, $conn;
	//INSERT customer into our DB 
	$sql = "INSERT INTO customers (`customer_id`, `customer_name`, `customer_email`, `customer_password`, `customer_gender`, `customer_phone`, `customer_active`) VALUES (NULL, '$sRegisterName', '$sRegisterEmail', 'a', '$sRegisterGender', '$sRegisterPhone', '0');";
	$result = $conn->query($sql);
	//SEND EMAIL to our customer in order to activate the account
	$emailSubject = "Customer registration Le Boutique";
	$emailSubject = urlencode($emailSubject);
	$emailMessage = "Dear $sRegisterName, you have just registered in our webshop with the email $sRegisterEmail. In order to activate you account, please go to the following link: http://localhost/KEA/XML_DB/FINAL_WEBSHOP/activate-account.php?userRole=customer&sCustomerEmail=$sRegisterEmail ";
	$emailMessage=urlencode($emailMessage);
	file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");
	echo "true";
}
//ADD PARTNER
function addPartner(){
	global $newPartnerName, $newPartnerEmail, $newPartnerUrl, $newPartnerKey, $newPartnerPhone, $conn;
	//INSERT partner into our DB
	$sql = "INSERT INTO `partners` (`partner_id`, `partner_name`, `partner_email`, `partner_url`, `partner_comission`, `partner_key`, `partner_active`, `partner_type`, `partner_phone`, `partner_login_code`) VALUES (NULL, '$newPartnerName', '$newPartnerEmail', '$newPartnerUrl', '20', '$newPartnerKey', '0', '0', '$newPartnerPhone', '');";
	$result = $conn->query($sql);
	//SEND EMAIL to the superadmin(partner_id=1) in order to activate the partner
	$sql = "SELECT * FROM partners WHERE partner_id=1";
	$result = $conn->query($sql);
	while($row = mysqli_fetch_array($result)){
		$superAdminEmail = $row["partner_email"];
		$emailSubject = "Partner registration Le Boutique";
		$emailSubject=urlencode($emailSubject);
		$emailMessage = "Dear super admin, the email $newPartnerEmail wants to register as one of our PARTNERS. In order to activate him/her account, please go to the following link: http://localhost/KEA/XML_DB/FINAL_WEBSHOP/activate-account.php?userRole=partner&sPartnerEmail=$newPartnerEmail ";
		$emailMessage=urlencode($emailMessage);
		file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=$superAdminEmail&emailSubject=$emailSubject&emailMessage=$emailMessage");
	}
	echo "true";
}

?>