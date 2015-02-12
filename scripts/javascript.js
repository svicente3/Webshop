var loginErrorCounter=0;
// GET AND DISPLAY ALL PRODUCTS
function displayProducts(){
	$(".single_product").remove();
	$.get("all-products.php?origin=login", function(sData){
		//console.log(sData);
		// Convert string to JSON object
        var oData = JSON.parse(sData);
		for(var i = 0 ; i < oData.products.length; i++){
			var soldOut = "";
			var categories = "";
			if(oData.products[i].active == 0){
				soldOut = '<span class="soldOut">SOLD OUT</span>';
			}
			for(var c = 0; c < oData.products[i].categories.length; c++){
				categories += oData.products[i].categories[c]+" ";
			}

			$("#products").append('<div class="'+categories+'single_product col-lg-4"><div class="edit_product" data-partnerKey="'+oData.products[i].partner+'" data-productId="'+oData.products[i].id+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div><div class="remove_product" data-partnerKey="'+oData.products[i].partner+'" data-productId="'+oData.products[i].id+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>'+soldOut+'<img class="img-circle" src="'+oData.products[i].image+'" alt="Generic placeholder image" style="width: 140px; height: 140px;"><h2>'+oData.products[i].name+'</h2><p class="product_description">'+oData.products[i].description+'</p><h3>'+oData.products[i].price+'kr.</h3></div>');	
		}
	});
}
//OPEN LOGIN/REGISTER MODAL
$(document).on("click", "#loginRegister" , function(){
	$('#loginRegisterModal').modal();
});
//GET CODE
$(document).on("click", "#btnGetCode" , function(){
	var sLoginEmail = $("#loginEmail").val();
	var sSubject = "LoginCode";
	var sMessage = "Code:";
	//console.log(sLoginEmail+sSubject+sMessage);
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/login.php?sFunction=getCode&emailTo="+sLoginEmail+"&emailSubject="+sSubject+"&emailMessage="+sMessage, function(sData){
		console.log(sData);

		if(sData == "true"){
			$("#loginBox").html('<h5>Login</h5><div><input style="display:none" id="loginEmail" type="text" value="'+sLoginEmail+'" placeholder="email"></div><div><input id="loginCode" type="text" value="" placeholder="Login code"></div><div><input id="btnLogin" class="btn btn-primary" type="button" value="Log in"></div>');
			console.log("true");
		}
		else{
			console.log("user not active!! "+sData);
			$("#loginEmail").effect("shake", {distance:5} );
		}
	});
});
//LOGIN 
$(document).on("click", "#btnLogin" , function(){
	var sLoginEmail = $("#loginEmail").val();
	var sLoginCode = $("#loginCode").val();
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/login.php?sFunction=login&sLoginCode="+sLoginCode+"&sLoginEmail="+sLoginEmail, function(sData){
		//console.log(sData);
		if(sData == "customer"){
			$('#loginRegisterModal').modal('toggle');
			//displayProducts();
			refreshProducts();
			$("#logOut").fadeIn();
			$("#loginRegister").fadeOut();
			//$(".btnBuy").fadeIn();
			console.log("you are a costumer");
		}
		else if(sData == "partner"){
			//displayProducts();
			$('#loginRegisterModal').modal('toggle');
			$("#addProductModal").fadeIn();
			$("#logOut").fadeIn();
			$("#addProductModal").fadeIn();
			$("#ordersList").fadeIn();
			$("#partnersList").fadeIn();
			$("#loginRegister").fadeOut();
			$("#editProducts").fadeIn();
			refreshProducts();
			//$("#products").prepend('<div id="showbtnbuy">XXXX</div>');
			//$(".btnBuy").fadeIn();
			//$(".btnBuy").html(sData);
			//$(".btnBuy").fadeOut();
			//showBuy();
			console.log("you are a PARTNER");
			
		}
		else{
			loginErrorCounter+=1;
			if(loginErrorCounter < 3){
				console.log("wrong code");
				$("#loginCode").effect("shake", {distance:5} );
			}
			else{
				console.log("error counter >= 3");
				$("#loginBox").html('<h1>ERROR</h1><p>You have tried 3 times.</p><p>Try to get a new code in later.</p>');
			}
		}
		
	});
});
//REGISTER
$(document).on("click", "#btnRegister" , function(){
	var sRegisterName = $("#registerName").val();
	var sRegisterEmail = $("#registerEmail").val();
	var sRegisterGender = $("#registerGender").val();
	sRegisterGender = sRegisterGender.toUpperCase();
	var sRegisterPhone = $("#registerPhone").val();
	if(sRegisterGender == "F"){
		sRegisterGender = 0;
	}
	else{
		sRegisterGender = 1;
	}
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/add-item.php?itemAdded=customer&sRegisterName="+sRegisterName+"&sRegisterEmail="+sRegisterEmail+"&sRegisterGender="+sRegisterGender+"sRegisterPhone="+sRegisterPhone, function(sData){
		//console.log(sData);
		if(sData == "true"){
			console.log("you're in");
			alert("registration done with success");
		}
		else{
			console.log("not registered");
		}
		
	});
});
//CATEGORY FILTER
$(document).on("click", "a.control" , function(){
	var show = this.id;
	if(show == 'showall'){
		$('.single_product').fadeIn('slow');
		return false;
	}
	else{
		$('#products > div.' + show).fadeIn('slow');
		$('#products > div:not(".'+show+'")').fadeOut('slow');
		return false;
	}
    
});

//BUY A PRODUCT
$(document).on("click", ".btnBuy" , function(){
	var idProductSold = $(this).attr("data-productId");
	var partnerKey = $(this).attr("data-partnerKey");
	//console.log(idProductSold);
	$.get("buy-product.php?idProductSold="+idProductSold+"&partnerKey="+partnerKey, function(sData){
		console.log("returned sData:: "+sData+" ::");
		//displayProductsAfterBuy();
		//If the product has been sold succesfully refresh the products
		if(sData == "true"){
			alert("Congratulations! You have just bought a product!");
			refreshProducts();
		}
		//If there has been an error during the buying
		else{
			console.log("ERROR BUYING THE PRODUCT");
			$('#errorBuying').modal();
		}

	});


});
//REFRESH PRODUCTS (AFTER BUYING)
function refreshProducts(){
	console.log("in refresh");
	$.get("all-products.php?origin=afterBuying", function(sData){
		//console.log("get"+sData+"!!!");
		$(".single_product").remove();
		console.log("products removed");
		// Convert string to JSON object
        var oData = JSON.parse(sData);

		for(var i = 0 ; i < oData.products.length; i++){
			
			var soldOut = "";
			var categories = "";
			if(oData.products[i].active == 0){
				soldOut = '<span class="soldOut">SOLD OUT</span>';
			}

			for(var c = 0; c < oData.products[i].categories.length; c++){
				categories += oData.products[i].categories[c]+" ";
			}
			
			$("#products").append('<div class="'+categories+' single_product col-lg-4"><div class="edit_product" data-partnerKey="'+oData.products[i].partner+'" data-productId="'+oData.products[i].id+'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div><div class="remove_product" data-partnerKey="'+oData.products[i].partner+'" data-productId="'+oData.products[i].id+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>'+soldOut+'<img class="img-circle" src="'+oData.products[i].image+'" alt="Generic placeholder image" style="width: 140px; height: 140px;"><h2>'+oData.products[i].name+'</h2><p class="product_description">'+oData.products[i].description+'</p><h3>'+oData.products[i].price+'kr.</h3><p><a class="btnBuy btn btn-default" role="button" data-partnerKey="'+oData.products[i].partner+'" data-productId="'+oData.products[i].id+'">Buy</a></p></div>');	
		}
	});
}
//OPEN ADD PRODUCT MODAL
$(document).on("click", "#addProductModal" , function(){
	$('#openAddProductModal').modal();
});
//ADD A PRODUCT
$(document).on("click", "#btnAddProduct" , function(){
	console.log("add");
	var newProductName = $("#newProductName").val();
	var newProductDescription = $("#newProductDescription").val();
	var newProductPrice = $("#newProductPrice").val();
	var newProductImage = $("#newProductImage").val();
	var newProductCategories = $("#newProductCategories").val();
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/add-item.php?itemAdded=product&newProductName="+newProductName+"&newProductDescription="+newProductDescription+"&newProductPrice="+newProductPrice+"&newProductImage="+newProductImage+"&newProductCategories="+newProductCategories, function(sData){
		console.log(sData);
		if(sData=="true"){
			alert("Congratulations! You have just added a new product!");
			refreshProducts();
		}
	});
});
//OPEN ADD PARTNER MODAL
$(document).on("click", "#addPartner" , function(){
	$('#addPartnerModal').modal();
});

//ADD A PARTNER
$(document).on("click", "#btnAddPartner" , function(){
	console.log("add");
	var newPartnerName = $("#newPartnerName").val();
	var newPartnerEmail = $("#newPartnerEmail").val();
	var newPartnerUrl = $("#newPartnerUrl").val();
	var newPartnerKey = $("#newPartnerKey").val();
	var newPartnerPhone = $("#newPartnerPhone").val();
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/add-item.php?itemAdded=partner&newPartnerName="+newPartnerName+"&newPartnerEmail="+newPartnerEmail+"&newPartnerUrl="+newPartnerUrl+"&newPartnerKey="+newPartnerKey+"&newPartnerPhone="+newPartnerPhone, function(sData){
		console.log(sData);
		if(sData=="true"){
			alert("Congratulations! You have just been added as one of our partners! You'll recieve a message as soon as you become activated");
			displayProducts();
		}
	});
});
//OPEN EDIT PRODUCT MODAL
$(document).on("click", ".edit_product" , function(){
	var idProduct = $(this).attr("data-productId");
	var partnerKey = $(this).attr("data-partnerKey");
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/edit-product.php?sFunction=openEditModal&idProduct="+idProduct+"&partnerKey="+partnerKey, function(sData){
		//console.log(sData);
		if(sData == "false"){
			alert("There was an error. Try it again later.");
		}
		else{
			$("#editProductModal .modal-body").empty();
			$("#editProductModal .modal-body").append(sData);
		}
	});
	$('#editProductModal').modal();
});
//SAVE CHANGES EDIT PRODUCT
$(document).on("click", "#saveChanges" , function(){
	var idProduct = $(this).attr("data-productId");
	var partnerKey = $(this).attr("data-partnerKey");
	var newProductName = $("#newProductName").val();
	var newProductDescription = $("#newProductDescription").val();
	var newProductPrice = $("#newProductPrice").val();
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/edit-product.php?sFunction=saveChanges&idProduct="+idProduct+"&partnerKey="+partnerKey+"&newProductName="+newProductName+"&newProductDescription="+newProductDescription+"&newProductPrice="+newProductPrice, function(sData){
		console.log(sData);
		if(sData == "true"){
			$('#editProductModal').modal('toggle');
			alert("Changes saved!");
			refreshProducts();
			$(".edit_product").fadeIn();
			$(".remove_product").fadeIn();
			console.log("ff");
		}
		else{
			$('#editProductModal').modal('toggle');
			alert("There was an error. Try it again later please.");
		}
	});
	$('#editProductModal').modal();
});
//REMOVE PRODUCT
$(document).on("click", ".remove_product" , function(){
	var idProduct = $(this).attr("data-productId");
	var partnerKey = $(this).attr("data-partnerKey");
	console.log(idProduct+" "+partnerKey);
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/edit-product.php?sFunction=removeProduct&idProduct="+idProduct+"&partnerKey="+partnerKey, function(sData){
		//console.log(sData);
		if(sData == "true"){
			alert("Product inactive!");
			refreshProducts();
			$(".edit_product").fadeToggle();
			$(".remove_product").fadeToggle();
			console.log("ff");
			console.log("aa");
		}
		else{
			alert("There was an error. Try it again later please.");
		}
	});
	
});
//ORDERS LIST
$(document).on("click", "#ordersList" , function(){
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/createList.php?sListOf=orders", function(sData){
		//console.log('orders:'+sData);
		$(".single_order").remove();
		$("#ordersModal #allOrders").append(sData);
	});
	$('#ordersModal').modal();
});
//PARTNERS LIST
$(document).on("click", "#partnersList" , function(){
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/createList.php?sListOf=partners", function(sData){
		console.log('partners:'+sData);
		$(".single_partner").remove();
		$("#partnersModal #allPartners").append(sData);

	});
	$('#partnersModal').modal();
});
//SHOW EDIT PENCIL
$(document).on("click", "#editProducts" , function(){
	$(".edit_product").fadeToggle();
	$(".remove_product").fadeToggle();
});
//SEARCH BOX
$(document).on("click", "#searchBoxBtn" , function(){
	var searchValue = $("#searchBox").val();
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/createList.php?sListOf=search&searchValue="+searchValue, function(sData){
		console.log(sData);
		$(".single_partner").remove();
		$("#partnersModal #allPartners").append(sData);
	});
});
//LOG OUT
$(document).on("click", "#logOut" , function(){
	$.get("http://localhost/KEA/XML_DB/FINAL_WEBSHOP/login.php?sFunction=logout", function(sData){
		console.log(sData);
		if(sData == "true"){
			$("#addProductModal").fadeOut();
			$("#logOut").fadeOut();
			$("#addProductModal").fadeOut();
			$("#ordersList").fadeOut();
			$("#partnersList").fadeOut();
			$("#editProducts").fadeOut();
			$(".btnBuy").fadeOut();
			$("#loginRegister").fadeIn();
		}
	});
});
