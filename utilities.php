<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: pink !important";>
    <div class="container">

		<div class="row">
			<h3>Utilities</h3>
		</div>
		
		<div class="row">
			<p>Utilities to be Rented</p>
			<p>
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="utility_create.php" class="btn btn-primary">Add Utility</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="persons.php">Students</a> &nbsp;';
				?>
				<a href="utilities.php">Utilities</a> &nbsp;
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="rented.php">AllRents</a>&nbsp;';
				?>
				<?php if($_SESSION['person_title']=='Administrator')
				    echo '<a href="utility_create.php?id=<?php echo $sessionid; ?>">NewUtility</a>&nbsp;';
				?>

			</p>
			
			<table class="table table-striped table-bordered" style="background-color: white !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>Cost ($)</th>
						<th>Usability</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database/database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `utilities`.*, SUM(case when rent_per_id ='. $_SESSION['person_id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`rentals`.rent_utility_id) AS countAssigns FROM `utilities` LEFT OUTER JOIN `rentals` ON (`utilities`.id=`rentals`.rent_utility_id) GROUP BY `utilities`.id';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. $row['utility_name'] . '</td>';
							echo '<td>'. $row['utility_cost'] . '</td>';
							echo '<td>'. $row['utility_usability'] . '</td>';
							echo '</td>';							
							echo '<td width=250>';
							echo '<a class="btn" href="utility_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							if ($_SESSION['person_title']=='Student' )
								echo '<a class="btn btn-primary" href="utility_read.php?id='.$row['id'].'">Rent</a> &nbsp;';
							if ($_SESSION['person_title']=='Administrator' )
								echo '<a class="btn btn-success" href="utility_update.php?id='.$row['id'].'">Update</a>&nbsp;';
				 			if ($_SESSION['person_title']=='Administrator' )
				// 				&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="utility_delete.php?id='.$row['id'].'">Delete</a>';
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
