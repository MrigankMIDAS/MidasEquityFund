<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/signuppage.css"> 
    <title>Signin page</title>
</head>
<body>
	<h1></h1>
    <!--<h1>Open Your Midas Account</h1>-->
    <form method="POST" action="phpscripts/managerloginbknd.php">
        <!-- Headings for the form -->
        <div class="headingsContainer">
            <!--<h3>Sign in</h3>-->
	    <img src="assets/midasfunds2.png" alt="Midas Funds">
            <p>Manager,sign in with your username and password</p>
			<p>Incorrect username or password</p>
        </div>

        <!-- Main container for all inputs -->
        <div class="mainContainer">
            <!-- Username -->
            <label for="email">Your email</label>
            <input type="text" placeholder="Enter email" name="email" required>

            <br><br>
			
            <!-- Password -->
            <label for="pswrd">Your password</label>
            <input type="password" placeholder="Enter Password" name="pswrd" required>

            <br>
			
			<!-- sub container for the checkbox and forgot password link -->
            <div class="subcontainer">
                <label>
                  <input type="checkbox" checked="checked" name="remember"> Remember me
                </label>
                <p class="forgotpsd"> <a href="#">Forgot Password?</a></p>
            </div>
			
            <!-- Submit button -->
             <button type="submit" name="submit">Login</button>

            <!-- Sign up link -->
            <!-- <p class="register">Not a member?  <a href="#">Register here!</a></p>-->
        </div>

    </form>
</body>
</html>
