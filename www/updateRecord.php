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
						
						$s_item_name = filter_input(INPUT_POST, 's_item_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						
						$item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						
						$item_desc = filter_input(INPUT_POST, 'item_desc', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						
						$item_price = (float) $_POST['item_price'];
						
						$curr_qty = (int) $_POST['curr_qty'];
						
						//validation to make sure the user has entered data
						if (!(trim($s_item_name))) {
								$error .= "You must enter an item name, for the row to be updated <br />";
							}
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
										$s_item_name = stripslashes($s_item_name);
										$item_name = stripslashes($item_name);
										$item_desc = stripslashes($item_desc);
									}
								$s_item_name = $connection->real_escape_string($s_item_name);
								$item_name = $connection->real_escape_string($item_name);
								$item_desc = $connection->real_escape_string($item_desc);
								$item_price = (float) $item_price;
								$curr_qty = (int) $curr_qty;
								
								//after all the we finally get to set up the query
								//we use NULL because our id field is 'auto increment'
								//$query = "UPDATE grocery_inventory SET item_name='Apples', item_desc='Green', item_price=1.00, curr_qty=100 WHERE item_name='banana'";
								$query = "UPDATE grocery_inventory SET item_name = '$item_name', item_desc = '$item_desc', item_price = $item_price, curr_qty = $curr_qty WHERE item_name = '$s_item_name'";
							
								
								//run the query and display appropriate message
								if (!$result = $connection->query($query)) {
										$message .="Unable to update rows<br />";
									}
									else {
											$message .= "Row Successfully Updated<br />";
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
					<h1 class="heading">Update Record Example</h1>
				</div>
			</section>
		</div>
			<section id="grid" class="clearfix">
				<div class="grid-full">
					<h2 class="sub-heading">SQL Update Example</h2>
				</div>
				<div class="cf show-grid">
					<div class="row">
						<div class="grid-1">
							<a href= "index.html">Home</a>
						</div>
					</div>
					
					<form action="updateRecord.php" method="post" name="maint" id="maint">
					<div class="row">
						<div class="grid-half">
							<h1>Select Record</h1>
							<p><?php echo $message; ?><p>
							
							
								<fieldset class="maintform">
									<legend>Find Record</legend>
									<ul>
										<li><label for="s_item_name">Item to be updated</label><br />
											<input type="text" name="s_item_name" id="s_item_name" />
										</li>
										
									</ul>

									<?php 
										//Create token
										$salt = 'SomeSalt';
										$token = sha1(mt_rand(1, 1000000) . $salt);
										$_SESSION['token'] = $token;
									?>
									<input type='hidden' name='token' value='<?php echo $token; ?>' />
									
									<!-- <input type="submit" name="save" value="Save" /> -->
									<!-- <a class="cancel" href="updateRecord.php">Cancel</a> -->
								</fieldset>
								
						</div>
						<div class="grid-half">
							<h1>Data Update</h1>
							
							
							
								
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
									<a class="cancel" href="updateRecord.php">Cancel</a>
								</fieldset>
							
						</div>
					</div>
					</form>
					
				</div>
			</section>
			<footer class="grid-full"><p>Insert Example</p></footer>
	</section>
</body>
</html>		