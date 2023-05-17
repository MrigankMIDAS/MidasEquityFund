<?php
	include "phpscripts/clientorderbackendfundoptions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signuppage.css"> 
    <title>Fund Order</title>
</head>
<body>
	<h1></h1>
    <!--<h1>Open Your Midas Account</h1>-->
    <form method="POST" action="phpscripts/clientorderbackend.php">
        <div class="headingsContainer">
	    <img src="assets/midasfunds2.png" alt="Midas Funds">
            <p>Place a Fund Order</p>
        </div>

        <!-- Main container for all inputs -->
	<!--<?php
	 foreach ($result as $option){
		echo $option['f_name']."<br>";
		//echo $option['f_name'];
	    }
	?>-->
        <div class="mainContainer">
            <label for="fund">Fund</label>
            <select name="fund" required>
	    <option value="0">Select Fund</option>
	    <?php
            foreach ($result as $option){
	    
		echo "<option value=".$option['f_id'].">".$option['f_name']."</option>";
	    
	    }
	    mysqli_free_result($result);
            ?>
            </select>

            <br><br>
	 
            <label for="amount">Amount</label>
            <input type="number"  min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57));"  placeholder="Amount" name="amount" required>

            <br><br>
	    
	    <label for="maturity_period">Maturity Period</label>	
	    <input type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57));"  placeholder="Maturity" name="period" required>
		
            <!-- Submit button -->
             <button type="submit" name="submit">Place Order</button>

        </div>

    </form>
</body>
</html>
