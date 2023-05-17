<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signuppage.css"> 
    <title>Signup page</title>
</head>
<body>
	<h1></h1>
    <!--<h1>Open Your Midas Account</h1>-->
    <form action="">
        <!-- Headings for the form -->
        <div class="headingsContainer">
            <!--<h3>Sign up</h3>-->
			<img src="assets/midasfunds2.png" alt="Midas Funds">
            <p>Create an Account with Midas Funds</p>
        </div>

        <!-- Main container for all inputs -->
        <div class="mainContainer">
            <!-- Username -->
            <label for="email">Your email</label>
            <input type="text" placeholder="Enter Email" name="email" required>

            <br><br>
			<label for="firstname">Firstname</label>
			<input type="text" placeholder="Firstname" name="fname" required>
			<label for="lastname">Lastname</label>
			<input type="text" placeholder="Lastname" name="lname" required>
			
			<label for="phno">Contact Number(Optional)</label>
			<input type="text" placeholder="Enter Contact Number" name="phno">
			
			<label for="gender">Gender</label>
			<input list="genders" name="gender" id="gender" placeholder="Enter Gender">
			<datalist id="genders">
			  <option value="Male">
			  <option value="Female">
			  <option value="Others">
			</datalist>
			
			<label for="birthday">Birth date</label>
			<input type="date" placeholder="Enter Birthday" name="birth_day" required>
			
			<label for="ifsccode">Bank IFSC Code</label>
			<input type="text" placeholder="IFSC code of your Bank" name="ifsc" required>
			
			<label for="bankno">Bank Account Number</label>
			<input type="text" placeholder="Your Bank Account Number" name="bankacountno" required>
			
            <!-- Password -->
            <label for="pswrd">Your password</label>
            <input type="password" placeholder="Enter Password" name="pswrd" required>

            <br><br>
			
			<!-- sub container for the checkbox and forgot password link
            <div class="subcontainer">
                <label>
                  <input type="checkbox" checked="checked" name="remember"> Remember me
                </label>
                <p class="forgotpsd"> <a href="#">Forgot Password?</a></p>
            </div> -->
			
            <!-- Submit button -->
             <button type="submit">Signup</button>

             <!--Sign up link--> 
            <p class="register">Already have an account?  <a href="loginaccount.php">Login here!</a></p>
        </div>

    </form>
</body>
</html>
