<?php 
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
require 'database/database.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form
	
	# same as create

	// initialize user input validation variables
	$personError = null;
	$utilitytError = null;
	
	// initialize $_POST variables
	$person = $_POST['person_id'];    // same as HTML name= attribute in put box
	$utility = $_POST['utility_id'];
	
	// validate user input
	$valid = true;
	if (empty($person)) {
		$personError = 'Please choose a student';
		$valid = false;
	}
	if (empty($event)) {
		$utilityError = 'Please choose a utility';
		$valid = false;
	} 
		
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE rentals set rent_per_id = ?, rent_utility_id = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$utility,$id));
		Database::disconnect();
		header("Location: rented.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM rentals where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$person = $data['rent_per_id'];
	$event = $data['rent_utility_id'];
	Database::disconnect();
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
				<h3>Update Rental</h3>
			</div>
	
			<form class="form-horizontal" action="rent_update.php?id=<?php echo $id?>" method="post">
		
				<div class="control-group">
					<label class="control-label">Student</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='person_id' id='person_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$person)
									echo "<option selected value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								else
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
							echo "<select class='form-control' name='utility_id' id='utility_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$event) {
									echo "<option selected value='" . $row['id'] . " '> " . $row['utility_name'] . " (" . $row['utility_cost'] . ") " . "</option>";
								}
								else {
									echo "<option value='" . $row['id'] . " '> " . trim($row['utility_name']) . " (" . trim($row['utility_cost']) . ") " . "</option>";
								}
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="rented.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
</html>