<?php
define ("MYSQLUSER", "root");
define ("MYSQLPASS", "");
define ("HOSTNAME", "localhost");
define ("MYSQLDB", "grocery");

//make connection to database
$connection = @new mysqli(HOSTNAME, MYSQLUSER, MYSQLPASS, MYSQLDB);
if ($connection->connect_error){
	die ('COnnection Error: ' . $connection->connect_error);
}
else {
	echo 'Successful connection to MySQL <br/>';
	
	//set up the query
	$query = "SELECT * FROM grocery_inventory";
	
	//run the query
	$result_obj = '';
	$result_obj = $connection->query($query);
	
	$sql = "SHOW COLUMNS FROM grocery_inventory";
	$result = mysqli_query($connection,$sql);
	while ($row = mysqli_fetch_array($result)){
		echo $row['Field']."<br>";
	}
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Retrieve Record Example</title>
		<meta name="viewport" content="width=device-width,initialscale=1.0"/>
		<!-- Stylesheets -->
		<link rel="stylesheet" href="style.css" type="text/css"/>
		<!-- Javascripts -->
		<!-- [if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	</head>

	<body>
		
		<section id="content">
			<div class="container">
				<section id="introp" class="cf">
					<div class="grid-full">
						<h1 class="heading">Retrieve Record Example </h1>
					</div>
				</section>
			
				<section id="grid" class="clearfix">
					<div class="grid-full">
						<h2 class="sub-heading">SQL Select Example</h2>
					</div>
					<div class="cf show-grid">
					
						<div class="row">
							<div class="grid-1">
							<a href= "index.html">Home</a>
							</div>
						</div>
						
						<div class="row">
							<div class="grid-half">
							
								<?php
								
									//read the results
									//loop through the results, row by row
									//reading each row into an associate array
									while ($result = $result_obj->fetch_array(MYSQLI_ASSOC)){
										//collect the array
										$items[] = $result;
									}
									
									//print array when done
									echo '<pre>';
									print_r ($items);
									echo '</pre>';
									
									//print array when done 
									foreach ($items as $item){
										//echo $item['id'] . ':' . $item['curr_qty'];
										echo $item['id'] . ':' . $item['item_name'] . ':' . $item['curr_qty'];
										echo '<br />';
									}
								?>
							</div>
							
							<div class="grid-half">
								<h2>Groceries</h2>
								
								<form action="retrieveRecord.php" method="post" name="maint" id="maint">
									
									<table>
										</thead>
										<tbody>
											<tr>
												<?php
													foreach ($items as $item){
														//echo '<tr>';
														//echo'<th>Groceries</th>';
														echo '<tr>';
														echo '<td>' . $item['id'] . '</td>';
														echo '<td>' . $item['item_name'] . '</td>';
														echo '<td>' . $item['item_desc'] . '</td>';
														echo '<td>' . $item['curr_qty'] . '</td>';
														echo '</tr>';
													}
												?>
											</tr>
										</tbody>
									</table>
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			<footer class="grid-full"><p>Select Example</p></footer>
		</section>
	</body>
</html>
					
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								