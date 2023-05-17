<?php
session_start();

if(isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['pswrd'];

  // Database connection
  $conn = mysqli_connect("localhost", "root", "", "midas_ef");

  // Prepared statement
  $stmt = mysqli_prepare($conn, "SELECT * FROM client WHERE email = ? AND password = ?");

  // Bind parameters
  mysqli_stmt_bind_param($stmt, "ss", $email, $password);

  // Execute prepared statement
  mysqli_stmt_execute($stmt);

  // Store result
  $result = mysqli_stmt_get_result($stmt);

  // Check if row exists
  if(mysqli_num_rows($result) == 1) {
    // Set session variables
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;

    // Redirect to dashboard
    header("Location:../userdashboard.php");
  } else {
    // Invalid login
    //echo "Invalid email or password";
	header("Location:../loginaccountredo.php?signup=failed");
  }

  // Close prepared statement
  mysqli_stmt_close($stmt);

  // Close database connection
  mysqli_close($conn);
}
?>
