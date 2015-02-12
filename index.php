<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LE BOUTIQUE</title>
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Carousel -->
  <link href="css/carousel.css" rel="stylesheet">
	<link type="text/css" href="css/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
	<!-- Custom Style -->
	<link href="css/style.css" rel="stylesheet">
</head>
<body onload="displayProducts()">
  <?php
  session_start();
  //$_SESSION['userLogged']="hello";
  echo "i".$_SESSION['userLogged'];
  ?>
  <div class="navbar-wrapper">
    <div class="container">
      <nav class="mainBar navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <a class="navbar-brand" href="#"><h2>LE BOUTIQUE</h2></a>
          </div>
          <div id="loginRegister" class="iconMenu"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></div>
          <div id="logOut" class="iconMenu"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></div>
          <div id="addProductModal" class="iconMenu"><span class="glyphicon glyphicon-plus" exclamation-sign"" aria-hidden="true"></span></div>
          <div id="ordersList" class="iconMenu"><span class="glyphicon glyphicon-tasks" exclamation-sign"" aria-hidden="true"></span></div>
          <div id="partnersList" class="iconMenu"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
          <div id="editProducts" class="iconMenu"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></div>
        </div>
      </nav>
    </div>
  </div>
  <!-- Carousel
  ================================================== -->
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="css/images/slider01.jpg" alt="First slide">
        <div class="container">
          <div class="carousel-caption">
            <h1>Best WebShop Ever!</h1>
            <p>Now you can buy without going out home.</p>
          </div>
        </div>
      </div>
      <div class="item">
        <img src="css/images/slider02.jpg" alt="Second slide">
        <div class="container">
          <div class="carousel-caption">
            <h1>With the best Products!</h1>
            <p>Here you will find all the products you need and they will be the best.</p>
          </div>
        </div>
      </div>
      <div class="item">
        <img src="css/images/slider03.jpg" alt="Third slide">
        <div class="container">
          <div class="carousel-caption">
            <h1>It is very easy!</h1>
            <p>And it is also one of the fastest services on the actual online world!</p>
          </div>
        </div>
      </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div><!-- /.carousel -->
  <!-- CATEGORIES
  ================================================== -->
  <div id="filters" class="container">
    <a href="#" id="showall" class="control btn btn-primary">All</a>
    <a href="#" id="woman" class="control btn btn-primary">Woman</a>
    <a href="#" id="man" class="control btn btn-primary">Man</a>
    <a href="#" id="sweaters" class="control btn btn-primary">Sweaters</a>
    <a href="#" id="shoes" class="control btn btn-primary">Shoes</a>
    <a href="#" id="tshirts" class="control btn btn-primary">T-shirts</a>
    <a href="#" id="accessories" class="control btn btn-primary">Accessories</a>
  </div>
  <!-- MODALS
  ================================================== -->
  <!--MODAL LOGIN REGISTER-->
  <div id="loginRegisterModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">LOGIN OR REGISTER</h4>
        </div>
        <div class="modal-body">
          <div id="loginBox">
            <h5>Get a code to login</h5>
            <div><input id="loginEmail" type="text" value="saravicentejimenez@gmail.com" placeholder="email"></div>
            <div><input id="btnGetCode" class="btn btn-primary" type="button" value="Get Code"></div>
          </div>
          <div id="registerBox">
            <h5>Not registered yet?</h5>
            <div><input id="registerName" type="text" value="Sara Vicente" placeholder="Name and lastname"></div>
            <div><input id="registerEmail" type="text" value="saravicentejimenez@gmail.com" placeholder="Email"></div>
            <div><input id="registerGender" type="text" value="F" placeholder="Write F or M"></div>
            <div><input id="registerPhone" type="text" value="42802827" placeholder="Phone number"></div>
            <div><input id="btnRegister" class="btn btn-primary" type="button" value="Register" data-dismiss="modal"></div>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL EDIT PRODUCT-->
  <div id="editProductModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">EDIT PRODUCT</h4>
        </div>
        <div class="modal-body">
          <h5>Product information (DO NOT LEAVE BLANC SPACES)</h5>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL PARTNERS LIST-->
  <div id="partnersModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">PARTNERS</h4>
        </div>
        <div class="modal-body">
          <h5>These are the list of the partners</h5><input id="searchBox" type="text" value="" placeholder="Search Partner"><input id="searchBoxBtn" class="btn btn-primary" type="button" value="Search">
          <table id="allPartners">
            <tr>
              <th>ID&nbsp&nbsp</th>
              <th>EMAIL&nbsp&nbsp</th>
              <th>NAME&nbsp&nbsp</th>
              <th>URL</th>
            </tr>
          </table>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL ORDERS LIST-->
  <div id="ordersModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">ORDERS</h4>
        </div>
        <div class="modal-body">
          <h5>These are the list of the orders</h5>
          <table id="allOrders">
            <tr>
              <th>ID&nbsp&nbsp</th>
              <th>CLIENT&nbsp&nbsp</th>
              <th>VENDOR&nbsp&nbsp</th>
              <th>PRODUCT ID&nbsp&nbsp</th>
              <th>DATE</th>
            </tr>
          </table>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL ADD PRODUCT-->
  <div id="openAddProductModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">ADD PRODUCT</h4>
        </div>
        <div class="modal-body">
          <h5>Product information</h5>
          <div><input id="newProductName" type="text" value="" placeholder="Product name"></div>
          <div><input id="newProductDescription" type="text" value="" placeholder="Product description"></div>
          <div><input id="newProductPrice" type="text" value="" placeholder="Product price"></div>
          <div><input id="newProductImage" type="text" value="" placeholder="Product image link"></div>
          <div><input id="newProductCategories" type="text" value="" placeholder='"categoryName", "categoryName"'></div>
          <div><input id="btnAddProduct" class="btn btn-primary" type="button" value="Add Product" data-dismiss="modal"></div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL ADD PARTNER-->
  <div id="addPartnerModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">ADD PARTNER</h4>
        </div>
        <div class="modal-body">
          <h5>Needed information</h5>
          <div><input id="newPartnerName" type="text" value="New Partner" placeholder="Partner name"></div>
          <div><input id="newPartnerEmail" type="text" value="" placeholder="Partner email"></div>
          <div><input id="newPartnerUrl" type="text" value="" placeholder="Partner Url"></div>
          <div><input id="newPartnerKey" type="text" value="" placeholder="Partner Key"></div>
          <div><input id="newPartnerPhone" type="text" value="" placeholder='Partner phone'></div>
          <div><input id="btnAddPartner" class="btn btn-primary" type="button" value="Add Partner" data-dismiss="modal"></div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!--MODAL ERROR BUYING-->
  <div id="errorBuying" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title">ERROR</h4>
        </div>
        <div class="modal-body">
          <p>We are sorry but your bought has not been successful. Please try it again later.</p>
          <p>Thank you</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- PRODUCTS
  ================================================== -->
  
  <div id="products" class="products container marketing">
  
  </div><!-- /products -->
  <!-- END PRODUCTS
  ================================================== -->
  <div class="container marketing">
    <hr class="featurette-divider">
    <!-- FOOTER -->
    <footer>
      <p class="pull-right"><a href="#">Back to top</a></p>
      <p>&copy; 2014 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      <p id="addPartner"><a href="#">Want to be one of our partners?</a></p>
    </footer>
  </div><!-- /.container -->
	<!-- END OF CONTENT / START OF SCRIPT -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="scripts/bootstrap.min.js"></script>
  <!-- jQueryUI -->
  <script src="scripts/jquery-ui.min.js"></script>
	<!-- CUSTOM JAVASCRIPT-->
	<script src="scripts/javascript.js"></script>
</body>
</html>