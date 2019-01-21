<?php
session_start(); //starts new or resume existing session
$message = '';
$error='';

if (isset($_POST['save']) AND $_POST['save'] == 'Save') {
	//chek the token
	$badToken = true;
	if (empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
		$message = 'sorry, try it again. There was a security issue';
		$badToken = true;
	} else {
			$badToken = false;
			unset($_SESSION['token']);
			
			//connect to the database with the username and password
			//localhost is your machine
			//default on mysql is 'root' and ''
			//database already created on mysql os Grocery
			define ("HOSTNAME", "localhost");
			define ("MYSQLUSER", "root");
			define ("MYSQLPASS", "");
			define ("MYSQLDB", "grocery");
			
			//make connection to database using the variables we set above
			$connection = @new mysqli (HOSTNAME, MYSQLUSER, MYSQLPASS, MYSQLDB);
			if ($connection->connect_error){
					die('Connect Error :   ' . $connection->connect_error);
				} else {
						//Get the data from the html form to the variables: $item_name, $item_desc, $item_price, $curr_qty
						$item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						
						$item_desc = filter_input(INPUT_POST, 'item_desc', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						
						$item_price = (float) $_POST['item_price'];
						
						$curr_qty = (int) $_POST['curr_qty'];
						
						//validation to make sure the user has entered data
						if (!(trim($item_name))) {
								$error .= "You must enter an item name <br />";
							}
						if (!(trim($item_desc))) {
								$error .= "You must enter an item description <br />";
							}
						if (!(trim($item_price))) {
								$error .= "You must enter an item price <br />";
							}
						if (!(trim($curr_qty))) {
								$error .= "You must enter a current quantity <br />";
							}
						if ($error) {
								$message .= $error;
							}
						else {
								//prepare the data
								if (get_magic_quotes_gpc()) {
										$item_name = stripslashes($item_name);
										$item_desc = stripslashes($item_desc);
									}
								$item_name = $connection->real_escape_string($item_name);
								$item_desc = $connection->real_escape_string($item_desc);
								$item_price = (float) $item_price;
								$curr_qty = (int) $curr_qty;
								
								//after all the we finally get to set up the query
								//we use NULL because our id field is 'auto increment'
								
								$query = "INSERT INTO grocery_inventory VALUES ('NULL', '$item_name', '$item_desc', $item_price, $curr_qty)";
								
								//$query = "INSERT INTO grocery_inventory VALUES ('NULL', 'Orange', 'Tangy', 0.60, 12)";
								
								//run the query and display appropriate message
								if (!$result = $connection->query($query)) {
										$message .="Unable to add rows<br />";
									}
									else {
											$message .= "Row Successfully added<br />";
										}
							}
					}

		}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>CRUD Example</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!-- stylesheets -->
	<link rel="stylesheet" href="style.css" type="text/css" />

	<!-- javascripts -->
	<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

</head>

<body>
	<section id="content">
		<div class="container">
			<section id="introp" class="cf">
				<div class="grid-full">
					<h1 class="heading">Create Record Example</h1>
				</div>
			</section>
		</div>
			<section id="grid" class="clearfix">
				<div class="grid-full">
					<h2 class="sub-heading">SQL Insert Example</h2>
				</div>
				<div class="cf show-grid">
					<div class="row">
						<div class="grid-1">
							<a href= "index.html">Home</a>
						</div>
					</div>
					<div class="row">
						<div class="grid-half">
							<h1>Data Entry</h1>
							<p><?php echo $message; ?><p>
							
							<form action="createRecord.php" method="post" name="maint" id="maint">
								
								<fieldset class="maintform">
									<legend>Add a Row</legend>
									<ul>
										<li><label for="item_name">item_name</label><br />
											<input type="text" name="item_name" id="item_name" />
										</li>
										<li><label for="item_desc">item_desc</label><br />
											<input type="text" name="item_desc" id="item_desc" />
										</li>
										<li><label for="item_price">item_price</label><br />
											<input type="text" name="item_price" id="item_price" />
										</li>
										<li><label for="curr_qty">curr_qty</label><br />
											<input type="text" name="curr_qty" id="curr_qty" />
										</li>
									</ul>

									<?php 
										//Create token
										$salt = 'SomeSalt';
										$token = sha1(mt_rand(1, 1000000) . $salt);
										$_SESSION['token'] = $token;
									?>
									<input type='hidden' name='token' value='<?php echo $token; ?>' />
									
									<input type="submit" name="save" value="Save" />
									<a class="cancel" href="createRecord.php">Cancel</a>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</section>
			<footer class="grid-full"><p>Insert Example</p></footer>
	</section>
</body>
</html>		