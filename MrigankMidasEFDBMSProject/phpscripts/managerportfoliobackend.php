<?php 

session_start(); 
include "db_conn.php";
/*
echo $_POST['fund'];
echo $_POST['amount'];
echo $_POST['period'];
*/

if (isset($_POST['fund'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $fund = validate($_POST['fund']);

    if (empty($fund)) {

        header("Location: ../managerdashboard.php?error=Fund Name is required");

        exit();

    }else{

        $sql2 = "SELECT count(f_id) AS cou FROM fund WHERE f_id='$fund'";
        $result2 = mysqli_query($conn, $sql2);
	$row2=mysqli_fetch_assoc($result2);
		if ($row2['cou'] > 0){
			$_SESSION['f_id']=$_POST['fund'];
			header("Location: ../managerdashboardportfolio.php");
			exit();
			

		}else{

			header("Location: ../managerdashboard.php?error=Incorrect Fund name");
			exit();

		}
	}

}else{

    header("Location: ../managerdashboard.php?error=Wrong Parameters");
    exit();

}