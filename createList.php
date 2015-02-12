<?php 
session_start();
//include DB connection
include_once "DBconnection.php";
//Get the parameters passed
$sListOf = $_GET["sListOf"];
$searchValue = $_GET['searchValue'];
//Choose of what do we want to make the list
switch ($sListOf) {
	case "orders":
	    createOrdersList();
	    break;

	case "partners":
	    createPartnersList();
	    break;

	case "search":
	    search();
	    break;

	default:
    	echo $sListOf;
}

function search(){
	global $conn, $searchValue;
	$searchList="";
	$sql = "SELECT * FROM partners WHERE partner_email LIKE '%$searchValue%'";
	$result = $conn->query($sql);
	//If we have a result
	while($row = mysqli_fetch_array($result)){
		$searchList .= '<tr class="single_partner"><td>'.$row['partner_id'].'&nbsp&nbsp</td><td>'.$row['partner_email'].'&nbsp&nbsp</td><td>'.$row['partner_name'].'&nbsp&nbsp</td><td>'.$row['partner_url'].'&nbsp&nbsp</td></tr>';
	}
	echo $searchList;
}
//ORDERS LIST
function createOrdersList(){
	global $conn;
	$sOrdersList="";
	$sql = "SELECT * FROM orders";
	$result = $conn->query($sql);
	//If we have a result
	while($row = mysqli_fetch_array($result)){
		$orderId = $row['order_id'];
		$orderClientEmail = $row['order_client_email'];
		$orderPartnerEmail = $row['order_partner_email'];
		$orderDate = $row['order_date'];
		$productId = $row['order_product_id'];

		$sOrdersList .= '<tr class="single_order"><td>'.$orderId.'&nbsp&nbsp</td><td>'.$orderClientEmail.'&nbsp&nbsp</td><td>'.$orderPartnerEmail.'&nbsp&nbsp</td><td>'.$productId.'&nbsp&nbsp</td><td>'.$orderDate.'</td></tr>';
	}
	//Return all the orders
	echo $sOrdersList;
}
//PARTNERS LIST
function createPartnersList(){
	global $conn;
	$sPartnersList="";
	$sql = "SELECT * FROM partners";
	$result = $conn->query($sql);
	//If we have a result
	while($row = mysqli_fetch_array($result)){

		$sPartnersList .= '<tr class="single_partner"><td>'.$row['partner_id'].'&nbsp&nbsp</td><td>'.$row['partner_email'].'&nbsp&nbsp</td><td>'.$row['partner_name'].'&nbsp&nbsp</td><td>'.$row['partner_url'].'&nbsp&nbsp</td></tr>';
	}
	//Return all the partners
	echo $sPartnersList;
}

?>