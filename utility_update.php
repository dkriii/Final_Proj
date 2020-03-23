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

	# initialize/validate (same as file: fr_event_create.php)

	// initialize user input validation variables
	$nameError = null;
	$costError = null;
	$usabilityError = null;

	
	// initialize $_POST variables
	$name = $_POST['name'];
	$cost = $_POST['cost'];
	$usability = $_POST['usability'];

	
	// validate user input
	$valid = true;
	if (empty($name)) {
		$dateError = 'Please enter Name';
		$valid = false;
	}
	if (empty($cost)) {
		$timeError = 'Please enter Cost';
		$valid = false;
	} 		
	if (empty($usability)) {
		$locationError = 'Please enter Availability';
		$valid = false;
	}		

	
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE utilities  set utility_name = ?, utility_cost = ?, utility_usability = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($name,$cost,$usability,$id));
		Database::disconnect();
		header("Location: utilities.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM utilities where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$name = $data['utility_name'];
	$cost = $data['utility_cost'];
	$usability = $data['utility_usability'];

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
		<?php 

		?>	
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Update Utility Details</h3>
			</div>
	
			<form class="form-horizontal" action="utility_update.php?id=<?php echo $id?>" method="post">
			
				<div class="control-group <?php echo !empty($nameError)?'error':'';?>">
					<label class="control-label">Name</label>
					<div class="controls">
						<input name="name" type="name"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
						<?php if (!empty($nameError)): ?>
							<span class="help-inline"><?php echo $nameError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($costError)?'error':'';?>">
					<label class="control-label">Cost ($)</label>
					<div class="controls">
						<input name="cost" type="name" placeholder="Cost" value="<?php echo !empty($cost)?$cost:'';?>">
						<?php if (!empty($costError)): ?>
							<span class="help-inline"><?php echo $costError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($usabilityError)?'error':'';?>">
					<label class="control-label">Availability</label>
					<div class="controls">
						<input name="usability" type="text" placeholder="Availability" value="<?php echo !empty($usability)?$usability:'';?>">
						<?php if (!empty($usabilityError)): ?>
							<span class="help-inline"><?php echo $usabilityError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="utilities.php">Back</a>
				</div>
				
			</form>
			
		</div><!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
</body>
</html>