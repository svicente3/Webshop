<?php
session_start();
//We save the email that we get into our session
//$_SESSION['userLogged'] = $_GET["emailTo"];
$_SESSION['userLogged'] = $_GET["sLoginEmail"];
//include DB connection
include_once "DBconnection.php";
//Get the function passed
$function = $_GET['sFunction'];
//Get the parameters passed
$emailTo = $_GET["emailTo"];
$emailSubject = urldecode($_GET["emailSubject"]);
$emailMessage = urldecode($_GET["emailMessage"]);
$sLoginCode = $_GET["sLoginCode"];
$sLoginEmail = $_GET["sLoginEmail"];

//Choose which function we wanna execute
switch ($function) {
  	case "getCode":
	    getCode();
	    break;

	case "login":
	    login();
	    break;

	case "logout":
	    logout();
	    break;

	default:
	    echo $function;
}

//GET CODE
function getCode(){
	global  $emailTo, $emailSubject, $emailMessage, $conn;
	//Check if the email is in the database and is active
	$sql = "SELECT * FROM partners WHERE partner_email='$emailTo' AND partner_active='1'";
	$result = $conn->query($sql);
	//If the user is a PARTNER
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_array($result)){
			//Generate a login code
			$newCode = mt_rand(1000, 9999);  
			//SEND SMS
			$YOUR_PHONE_NUMBER = $row['partner_phone'];
			$YOUR_KEY = "nrEF-9Y4I-DG5m-61jJ";
			file_get_contents("http://iqvsiq.com/tekstea_v1/php-server/send-sms.php?do={%22secretKey%22:%22".$YOUR_KEY."%22,%22mobileNumber%22:%22".$YOUR_PHONE_NUMBER."%22,%22message%22:%22Login%20code%20is%20".$newCode."%22}");
			//SEND EMAIL
			$emailSubject = "Login partner code";
			$emailSubject = urlencode($emailSubject);
			$emailMessage=$emailMessage.$newCode." partner email: ".$row['partner_email']."  parner phone: ".$row['partner_phone'];
			$emailMessage=urlencode($emailMessage);
			file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");
			//UPDATE THE DB
			$sql="UPDATE partners SET partner_login_code=$newCode WHERE partner_email='$emailTo'";
			$result = $conn->query($sql);
			echo "true";
			//echo $_SESSION['userLogged'];
		}
	}
	//If the user is NOT a partner, we'll see if it's a customer
	else{
		$sql = "SELECT * FROM customers WHERE customer_email='$emailTo' AND customer_active='1'";
		$result = $conn->query($sql);
		//If it's a CUSTOMER
		if ($result->num_rows > 0) {
			while($row = mysqli_fetch_array($result)){
				//Generate a login code
				$newCode = mt_rand(1000, 9999); 
				//SEND SMS
				$YOUR_PHONE_NUMBER = $row['customer_phone'];
				$YOUR_KEY = "nrEF-9Y4I-DG5m-61jJ";
				file_get_contents("http://iqvsiq.com/tekstea_v1/php-server/send-sms.php?do={%22secretKey%22:%22".$YOUR_KEY."%22,%22mobileNumber%22:%22".$YOUR_PHONE_NUMBER."%22,%22message%22:%22Login%20code%20is%20".$newCode."%22}");
				//SEND EMAIL
				$emailSubject = "Login customer code";
				$emailSubject = urlencode($emailSubject);
				$emailMessage=$emailMessage.$newCode." customer email: ".$row['customer_email']."  customer phone: ".$row['customer_phone'];
				$emailMessage=urlencode($emailMessage);
				file_get_contents("http://iqvsiq.com/webshop2014/send-email.php?emailTo=saravicentejimenez@gmail.com&emailSubject=$emailSubject&emailMessage=$emailMessage");
				//UPDATE
				$sql="UPDATE customers SET customer_login_code=$newCode WHERE customer_email='$emailTo'";
				$result = $conn->query($sql);
				echo "true";
			}
		}
		//If it's either a customer or a partner
		else{
			echo "error";
		}
	}
}

//LOGIN
function login(){
	global $sLoginCode, $sLoginEmail, $conn;
	//We'll look for the user
	$sql="SELECT * FROM partners WHERE partner_email='$sLoginEmail' AND partner_login_code=$sLoginCode";
	$result = $conn->query($sql);
	//If it's a PARTNER
	if ($result->num_rows > 0) {
		echo "partner";
	}
	//If the user is NOT a partner, we'll see if it's a customer
	else{
		$sql = "SELECT * FROM customers WHERE customer_email='$sLoginEmail' AND customer_login_code=$sLoginCode";
		$result = $conn->query($sql);
		//If it's a CUSTOMER
		if ($result->num_rows > 0) {
			echo "customer";
		}
		//If it's either a customer or a partner
		else{
			echo "false";
		}
	}
}

function logout(){
	session_destroy();
	echo "true";
}

?>