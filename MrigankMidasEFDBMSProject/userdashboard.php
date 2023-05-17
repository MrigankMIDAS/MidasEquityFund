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
		<a href="userdashboard.php" class="logo"><img src="assets/midasfunds4.png" alt="Midas Funds" class="midas"></a>
		
		<div class="header-right">
			<a class="header-link active" href="userdashboard.php"><img src="assets/user.png" alt="+" class="widg">Account</a>
			<a class="header-link" href="clientorder.php"><img src="assets/order.png" alt="+" class="widg">Order</a>
			<a class="header-link" href="phpscripts/logout.php"><img src="assets/logout.png" alt="+" class="widg">Logout</a>
		</div>
</div>


<div class="userdet">
    <?php
	/*
	echo $_SESSION['email'];
	echo "<br>";
	echo $_SESSION['c_id'];
	echo "<br>Hello world!";
	*/
	$sql = "SELECT * FROM client WHERE email='{$_SESSION['email']}';";
	//echo $sql;
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) === 1){
		$row = mysqli_fetch_assoc($result);
		echo "<h3>Your Account Details</h3>";
		echo "<p><b>USERNAME:</b> ".$row['f_name']." ".$row['l_name']."</p>";
		echo "<p><b>EMAIL: </b>".$row['email']."</p>";
		echo "<p><b>ACCOUNT NUMBER: </b>".$row['c_id']."</p>";
		echo "<p><b>BIRTHDAY: </b>".$row['birth_day']."</p>";
		echo "<p><b>SEX: </b>".$row['sex']."</p>";
		echo "<p><b>Bank IFSC code: </b>".$row['bank_IFSC_code']."</p>";
		echo "<b><p>Bank Account Number : </b>".$row['bank_account_no']."</p><hr>";
		//echo $_SESSION['email'];
	}
	?>
</div>
<br>
<div>
<h3 class="userdet">Your Funds with Midas</h3>
<table class="styled-table">
	
	<tr>
		<th>Fund Id</th>
		<th>Fund</th>
		<th>Amount in Fund</th>
		<th>Fee(%)</th>
		<th>RoR(%)(CAGR 3)</th>
		<th>Start date</th>
		<th>End date</th>
		
	</tr>
	<?php
		$sql="SELECT f_id,f_name,amount,f_fee,RoR,start_date,end_date
		FROM invested_in NATURAL JOIN fund
		WHERE c_id='{$_SESSION['c_id']}'
		ORDER BY start_date,amount;";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0){
			while ($row= mysqli_fetch_assoc($result)){
				echo "<tr><td>".$row['f_id'];
				echo "</td><td>".$row['f_name'];
				echo "</td><td>".$row['amount'];
				echo "</td><td>".$row['f_fee'];
				echo "</td><td>".$row['RoR'];
				echo "</td><td>".$row['start_date'];
				echo "</td><td>".$row['end_date']."</td></tr>";
				/*echo "<h1>".$row['f_id']."</h1>";*/
	
			}
		}
	?>
</table>
</div>
</body>
</html>



