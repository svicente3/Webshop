<?php
session_start();
//include DB connexion
include_once "DBconnection.php";
//Get the parameters passed
$sCustomerEmail = $_GET["sCustomerEmail"];
$sPartnerEmail = $_GET["sPartnerEmail"];
$userRole = $_GET['userRole'];
//Choose the function we are gonna execute
switch ($userRole) {
  case "customer":
    activateCustomer();
    break;

  case "partner":
    activatePartner();
    break;

  default:
    echo $userRole;
}
//CUSTOMER ACTIVATION
function activateCustomer(){
	global $conn, $sCustomerEmail;
	$sql = "SELECT * FROM customers WHERE customer_email='$sCustomerEmail'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$sql = "UPDATE customers SET customer_active=1 WHERE customer_email='$sCustomerEmail'";
		$result = $conn->query($sql);
		//SEND EMAIL to the customer informing him/her
		$emailSubject = "Activation Customer Le Boutique";
		$emailSubject = urlencode($emailSubject);
		$emailMessage = "Thank you very much for joining our webshop with the email: $sCustomerEmail. Your user is now activated. We are so pleased to have you as one of our customers. Hope you enjoy it and thank you very much for your confidence in us. Best regards, Le Boutique.";
		$emailMessage=urlencode($emailMessage);
		file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");
		//SEND EMAIL to the SUPERADMIN(partner_id=1) also informing him/her
		$sql = "SELECT * FROM partners WHERE partner_id=1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_array($result)){
				$emailTo = $row['partner_email'];
				$emailSubject = "New customer activated";
				$emailSubject = urlencode($emailSubject);
				$emailMessage = "A new costumer has registered in your webshop with the email $sCustomerEmail.";
				$emailMessage=urlencode($emailMessage);
				file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=$emailTo&emailSubject=$emailSubject&emailMessage=$emailMessage");
			}
		}
		echo "customer activated";
	}
	else{
		echo "customer not able to activate";
	}
}
//PARTNER ACTIVATION
function activatePartner(){
	global $conn, $sPartnerEmail;
	//Look for the partner
	$sql = "SELECT * FROM partners WHERE partner_email='$sPartnerEmail'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)){
			//UPDATE the partner_active to 1
			$sql = "UPDATE partners SET partner_active=1 WHERE partner_email='$sPartnerEmail'";
			$result = $conn->query($sql);
			//SEND EMAIL to the partner to inform that has been activated
			$emailSubject = "Activation Partner Le Boutique";
			$emailSubject = urlencode($emailSubject);
			$emailMessage = "Thank you very much for becomming one of the partners from our webshop with the email: $sPartnerEmail. Your user is now activated. Remember that if we sell one of your products, we'll keep a 20% of its price. Hope you enjoy it and thank you very much for your confidence in us. Best regards, Le Boutique.";
			$emailMessage=urlencode($emailMessage);
			file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");
			//SEND SMS
			$YOUR_PHONE_NUMBER = $row['partner_phone'];
			$YOUR_KEY = "nrEF-9Y4I-DG5m-61jJ";
			$YOUR_SMS = "Dear partner, your account has already been activated. Best regards, Le Boutique.";
			$YOUR_SMS = urlencode($YOUR_SMS);
			file_get_contents("http://iqvsiq.com/tekstea_v1/php-server/send-sms.php?do={%22secretKey%22:%22".$YOUR_KEY."%22,%22mobileNumber%22:%22".$YOUR_PHONE_NUMBER."%22,%22message%22:%22".$YOUR_SMS."%22}");
			echo "partner activated";
		}
	}
	else{
		echo "partner not able to activate";
	}
}

?>