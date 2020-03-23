<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
require 'database/database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$nameError = null;
	$costError = null;
	$usabilityError = null;
	
	// initialize $_POST variables
	$name = $_POST['name'];
	$cost = $_POST['cost'];
	$usability = $_POST['availability'];

	// validate user input
	$valid = true;
	if (empty($name)) {
		$nameError = 'Please enter Utility Name';
		$valid = false;
	}
	if (empty($cost)) {
		$costError = 'utility Cost';
		$valid = false;
	}
	if (empty($usability)) {
		$usabilityError = 'Please Enter Availability';
		$valid = false;
	}
// 	$s1 = "Available";
// 	$s2 = "Unavailable";
	
// 	if ($usability == $s1 || $usability == $s2){
// 	    $usabilityError = 'Please Enter "Available" or "Unavailable"';
// 		$valid = false;
// 	}


	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO utilities (utility_name,utility_cost,utility_usability) values(?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$cost,$usability));
		Database::disconnect();
		header("Location: utilities.php");
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
				<h3>Add New Utility</h3>
			</div>
	
			<form class="form-horizontal" action="utility_create.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					<label class="control-label">First Name</label>
					<div class="controls">
						<input name="name" type="text"  placeholder="Utility Name" value="<?php echo !empty($name)?$name:'';?>">
						<?php if (!empty($nameError)): ?>
							<span class="help-inline"><?php echo $nameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($costError)?'error':'';?>">
					<label class="control-label">Cost</label>
					<div class="controls">
						<input name="cost" type="text"  placeholder="Utility Cost" value="<?php echo !empty($cost)?$cost:'';?>">
						<?php if (!empty($costError)): ?>
							<span class="help-inline"><?php echo $costError;?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="control-group <?php echo !empty($usabilityError)?'error':'';?>">
					<label class="control-label">Availability</label>
					<div class="controls">
						<input name="availability" type="text" placeholder="Availability" value="<?php echo !empty($usability)?$usability:'';?>">
						<?php if (!empty($usabilityError)): ?>
							<span class="help-inline"><?php echo $usabilityError;?></span>
						<?php endif;?>
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="utilities.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>