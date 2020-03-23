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
	
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM persons WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
    $URL="https://djkeiffer.000webhostapp.com/SVSU_RENTAL_355FINAL/persons.php";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM persons where id = ?";
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
		<div class="row">
			<h3>Delete Volunteer</h3>
		</div>
		<form class="form-horizontal" action="per_delete.php" method="post">
			<input type="hidden" name="id" value="<?php echo $id;?>"/>
			<p class="alert alert-error">Are you sure you want to delete ?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Yes</button>
				<a class="btn" href="persons.php">No</a>
			</div>
		</form>
		
		<!-- Display same information as in file: per_read.php -->
		
		<div class="form-horizontal" >
				
			<div class="control-group col-md-6">
			
				<label class="control-label">First Name</label>
				<div class="controls ">
					<label class="checkbox">
						<?php echo $data['fname'];?> 
					</label>
				</div>
				
				<label class="control-label">Last Name</label>
				<div class="controls ">
					<label class="checkbox">
						<?php echo $data['lname'];?> 
					</label>
				</div>
				
				<label class="control-label">Email</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['email'];?>
					</label>
				</div>
				
				<label class="control-label">Mobile</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['mobile'];?>
					</label>
				</div>     
				
				<label class="control-label">Title</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['title'];?>
					</label>
				</div>   
				
				<!-- password omitted on Read/View -->
				
			</div>
				
		</div>  <!-- end div: class="form-horizontal" -->

    </div> <!-- end div: class="container" -->
	
</body>
</html>