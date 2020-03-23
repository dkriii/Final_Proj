<?php
session_start();
if(!isset($_SESSION["person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['person_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body style="background-color: lightblue !important";>
    <div class="container">

		<div class="row">
			<h3>Students</h3>
		</div>
		<div class="row">
			<p>
				<?php if($_SESSION['person_title']=='Administrator')
					echo '<a href="per_create.php" class="btn btn-primary">Add Student</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="persons.php">Students</a> &nbsp;
				<a href="utilities.php">Utilities</a> &nbsp;
				<a href="rented.php">AllRentals</a>&nbsp;
				<a href="rented.php?id=<?php echo $sessionid; ?>">MyRentals</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
					    <th>Picture</th>
						<th>Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database/database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `persons`.*, COUNT(`rentals`.rent_per_id) AS countAssigns FROM `persons` LEFT OUTER JOIN `rentals` ON (`persons`.id=`rentals`.rent_per_id) GROUP BY `persons`.id ORDER BY `persons`.lname ASC, `persons`.fname ASC';
						//$sql = 'SELECT * FROM fr_persons ORDER BY `fr_persons`.lname ASC, `fr_persons`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>' .$row['filename'] . '<img width=100 src="data:image/jpeg;base64,'
                                .base64_encode( $row['filecontent'] ).'"/>'
                                . '<br><br></td>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') '.' - Not Renting</td>';
							else
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') - '.$row['countAssigns']. ' Renting</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['mobile'] . '</td>';
							echo '<td width=250>';
							# always allow read
							echo '<a class="btn" href="per_read.php?id='.$row['id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['person_title']=='Administrator'
								|| $_SESSION['person_id']==$row['id'])
								echo '<a class="btn btn-success" href="per_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['person_title']=='Administrator')
								echo '<a class="btn btn-danger" href="per_delete.php?id='.$row['id'].'">Delete</a>';
							if($_SESSION["person_id"] == $row['id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>

