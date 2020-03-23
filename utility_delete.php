<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
require 'database/database.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM utilities  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
    $URL="https://djkeiffer.000webhostapp.com/SVSU_RENTAL_355FINAL/utilities.php";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
	
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM utilities where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
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
				<h3>Delete Utility</h3>
			</div>
			
			<form class="form-horizontal" action="utility_delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to delete this utility ?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="utilities.php">No</a>
				</div>
			</form>
			
			<!-- Display same information as in file: fr_event_read.php -->
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label">Name</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['utility_name'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Cost($)</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['utility_cost'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Availability</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['utility_usability'];?>
						</label>
					</div>
				</div>
				
			</div> 
			
		</div> 
				
    </div> 
  </body>
</html>