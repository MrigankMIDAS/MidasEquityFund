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
    <title>Manager DashBoard Portfolio</title>
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

<h3 class="userdet">Portfolio of 
<?php
//echo $_SESSION['f_id'];
$sql="SELECT f_name FROM fund WHERE f_id='{$_SESSION['f_id']}';";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) === 1){
		$row = mysqli_fetch_assoc($result);
		echo $row['f_name'];
}

?>
 Fund with amount in investment of $ 
<?php
$sql2="SELECT SUM(product) as su FROM managerportfoliointerface WHERE f_id='{$_SESSION['f_id']}';";
$result2 = mysqli_query($conn, $sql2);
if (($row2= mysqli_fetch_assoc($result2))&& (!empty($row2['su']))){
	echo $row2['su'];
}
else{
	$row2['su']=0;
	echo "0";
}

?>
</h3>
<br>
<table class="styled-table">
	
	<tr>
		<th>Ticker</th>
		<th>Company</th>
		<th>EPS</th>
		<th>Holding</th>
		<th>Market Price</th>
		<th>Value of Holding</th>
		<th>Percentage</th>
	</tr>
	<?php
		
		$sql=
		"WITH t1 AS(
			SELECT SUM(product) AS sump FROM 
			managerportfoliointerface
			WHERE f_id='{$_SESSION['f_id']}'
		)
		SELECT ticker,co_name,EPS,holding,mkt_price,product, (product*100/t1.sump) AS 
		cent 
		FROM managerportfoliointerface,t1
		WHERE t1.sump>0;";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0){
			while ($row= mysqli_fetch_assoc($result)){
				echo "<tr><td>".$row['ticker'];
				echo "</td><td>".$row['co_name'];
				echo "</td><td>".$row['EPS'];
				echo "</td><td>".$row['holding'];
				echo "</td><td>".$row['mkt_price'];
				echo "</td><td>".$row['product'];
				echo "</td><td>".$row['cent'];
	
			}
			echo "</td></tr>";
		}	

		
	?>
</table>
</div>




