<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

$personid = $_SESSION["person_id"];
$utilityid = $_GET["id"];

require 'database/database.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$personError = null;
	$utilityError = null;
	
	// initialize $_POST variables
	$person = $_POST['person'];    // same as HTML name= attribute in put box
	$utility = $_POST['utility'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose a Student';
		$valid = false;
	}
	
	if (empty($event)) {
		$eventError = 'Please choose an Utility';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO rentals (rent_per_id,rent_utility_id) values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$event));
		Database::disconnect();
		header("Location: rented.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
		<div class="span10 offset1">
			<div class="row">
				<h3>Rent an Utility</h3>
			</div>
			<form class="form-horizontal" action="rent_create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Student</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
						    $sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person' id='person_id'>";
							if($personid) // if $_GET exists restrict person options to logged in user
								foreach ($pdo->query($sql) as $row) {
									if($personid==$row['id'])
									echo "<option selected value='" . $row['person_id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">Utility</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM utilities ORDER BY utility_name ASC';
							echo "<select class='form-control' name='utility' id='utility_id'>";
							if($utilityid) // if $_GET exists restrict event options to selected utility (from $_GET)
								foreach ($pdo->query($sql) as $row) {
									if($utilityid==$row['id'])
									echo "<option selected value='" . $row['id'] . " '> " . $row['utility_name'] .
									"</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . $row['utility_name'].
									"</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="rented.php">Back</a>
				</div>
				
			</form>

  </body>
</html>