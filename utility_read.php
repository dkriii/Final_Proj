<?php 

session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database/database.php';


$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM utilities where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
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
				<h3>Utility Details</h3>
			</div>
			
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
					<label class="control-label">Cost</label>
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
				
				<div class="form-actions">
					<a class="btn btn-primary" href="rent_create.php?event_id=<?php echo $id; ?>">Rent this utility</a>
					<a class="btn" href="utilities.php">Back</a>
				</div>
				

			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>
