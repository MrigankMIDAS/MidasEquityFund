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
		<a href="index.html" class="logo"><img src="assets/midasfunds4.png" alt="Midas Funds" class="midas"></a>
		
		<div class="header-right">
			<a class="header-link active" href="userdashboard.html"><img src="assets/user.png" alt="+" class="widg">Account</a>
			<a class="header-link" href="userorder.html"><img src="assets/order.png" alt="+" class="widg">Order</a>
			<a class="header-link" href="phpscripts/logout.php"><img src="assets/logout.png" alt="+" class="widg">Logout</a>
		</div>
</div>


<div class="userdet">
    <?php
	// Database connection
    $conn = mysqli_connect("localhost", "root", "", "midas_ef");

	// Prepared statement
	/*$sql="SELECT * FROM client WHERE email = ".$_SESSION['email'];*/
	
	$result=mysqli_query($conn,$sql);
	$resultCheck =mysqli_num_rows($result);
	if ($resultCheck>0){
		$row=mysqli_fetch_assoc($result);
		Echo "h2>User details</h2><p><b>USERNAME:</b>";
		Echo $row['fname']." ".$row['lname'];
		Echo "</p><p><b>EMAIL:</b>";
		Echo $row['email']; 
		Echo "</p><p><b>AMOUNT IN FUND:</b>";
		Echo "</p>";
	}
	else{
		Echo ":/";
	}
	?>
</div>
<br>

<table>
	<tr>
		<th>Fund</th>
		<th>Amount in Fund</th>
		<th>Start date</th>
		<th>End date</th>
		<th>Fee(%)</th>
		<th>RoR(%)(CAGR 3)</th>
	</tr>
</table>
</body>
</html>



