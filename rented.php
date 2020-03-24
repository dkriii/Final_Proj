<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$id = $_GET['id'];  
// for MyRentals
$sessionid = $_SESSION['person_id'];
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
			<h3><?php if($id) echo 'My'; ?>Rentals</h3>
		</div>
		
		<div class="row">
			<p>Each Rental is for 24 hours</p>
			<p>
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="rent_create.php" class="btn btn-primary">Add Rental</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="persons.php">Students</a> &nbsp;';
				?>
				<a href="utilities.php">Utilities</a> &nbsp;
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="rented.php">AllRentals</a>&nbsp;';
				?>
				<a href="rented.php?id=<?php echo $sessionid; ?>">MyRentals</a>&nbsp;
				<?php if($_SESSION['person_title']=='Student')
					echo '<a href="rent_create.php" class="btn btn-primary">Rent</a>';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Utilitiy</th>
						<th>Cost</th>
						<th>Student</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database/database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM rentals 
						LEFT JOIN persons ON persons.id = rentals.rent_per_id 
						LEFT JOIN utilities ON utilities.id = rentals.rent_utility_id
						WHERE persons.id = $id 
						ORDER BY lname ASC, lname ASC;";
					else
						$sql = "SELECT * FROM rentals 
						LEFT JOIN persons ON persons.id = rentals.rent_per_id 
						LEFT JOIN utilities ON utilities.id = rentals.rent_utility_id
						ORDER BY lname ASC, lname ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. $row['utility_name'] . '</td>';
						echo '<td>'. $row['utility_cost'] . '</td>';
						echo '<td>'. $row['lname'] . ', ' . $row['fname'] . '</td>';
						echo '<td>'. $row['email'] . '</td>';
						echo '<td width=255>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="rent_read.php?id='.$row[0].'">Details</a>';
						if ($_SESSION['person_title']=='Administrator' )
							echo '&nbsp;<a class="btn btn-success" href="rent_update.php?id='.$row[0].'">Update</a>';
						if ($_SESSION['person_title']=='Administrator' 
							|| $_SESSION['person_id']==$row['rent_per_id'])
							echo '&nbsp;<a class="btn btn-danger" href="rent_delete.php?id='.$row[0].'">Delete</a>';
						if($_SESSION["person_id"] == $row['rent_per_id']) 		echo " &nbsp;&nbsp;Me";
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>
