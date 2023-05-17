<?php 
session_start(); 

include "phpscripts/db_conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/userdashboardstyle.css"> 
    <title>Home page</title>
</head>
<body>
<div class="header">
		<!--<img src="assets/midasfunds4.png" alt="Midas Funds">-->
		<a href="managerdashboard.php" class="logo"><img src="assets/midasfunds4.png" alt="Midas Funds" class="midas"></a>
		
		<div class="header-right">
			<a class="header-link active" href="managerdashboard.php"><img src="assets/user.png" alt="+" class="widg">Account</a>
			<a class="header-link" href="managerorder.php"><img src="assets/order.png" alt="+" class="widg">Order</a>
			<a class="header-link" href="phpscripts/logout.php"><img src="assets/logout.png" alt="+" class="widg">Logout</a>
		</div>
</div>


<div class="userdet">
    <?php
	/*
	echo $_SESSION['email'];
	echo "<br>";
	echo $_SESSION['e_id'];
	echo "<br>Hello world!";
	*/
	$sql = "SELECT * FROM employee WHERE e_id='{$_SESSION['e_id']}';";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) === 1){
		$row = mysqli_fetch_assoc($result);
		echo "<h3>Your Account Details</h3>";
		echo "<p><b>USERNAME:</b> ".$row['first_name']." ".$row['last_name']."</p>";
		echo "<p><b>EMAIL: </b>".$row['email']."</p>";
		echo "<p><b>ACCOUNT NUMBER: </b>".$row['e_id']."</p>";
		echo "<p><b>BIRTHDAY: </b>".$row['birth_day']."</p>";
		echo "<p><b>SEX: </b>".$row['sex']."</p>";
	}
	?>
</div>
<br>
<hr>
<div>
<h3 class="userdet">Funds under You</h3>
<table class="styled-table">
	
	<tr>
		<th>Fund Id</th>
		<th>Fund</th>
		<th>Fee(%)</th>
		<th>RoR(%)(CAGR 3)</th>
		<th>Start date</th>

		
	</tr>
	<?php
		$sql="SELECT f_id,f_name,f_fee,RoR,since
		FROM employee_works_on_fund NATURAL JOIN fund
		WHERE e_id='{$_SESSION['e_id']}'
		;";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0){
			while ($row= mysqli_fetch_assoc($result)){
				echo "<tr><td>".$row['f_id'];
				echo "</td><td>".$row['f_name'];
				echo "</td><td>".$row['f_fee'];
				echo "</td><td>".$row['RoR'];
				echo "</td><td>".$row['since'];
	
			}
		}
	?>
</table>
</div>
<br>
<hr>

<div class="userdet">
    <h3>Portfolio you wish to See</h3>
    <form method="POST" action="phpscripts/managerportfoliobackend.php">
	<label for="fund">Fund</label>
        <select name="fund" required>
	<option value="0">Select Fund</option>
    <?php

		$sql="SELECT f_id,f_name,f_fee,RoR,since
		FROM employee_works_on_fund NATURAL JOIN fund
		WHERE e_id='{$_SESSION['e_id']}'
		;";
		$result=mysqli_query($conn, $sql);
		mysqli_fetch_all($result, MYSQLI_ASSOC);
		if (mysqli_num_rows($result) > 0){
			
			
			foreach ($result as $option){
	    
			echo "<option value=".$option['f_id'].">".$option['f_name']."</option>";
	    
	    		}
			
	    		mysqli_free_result($result);
		}
     ?>
	</select>
	<button type="submit" name="submit">Go</button>
     </form>
</body>
</html>



