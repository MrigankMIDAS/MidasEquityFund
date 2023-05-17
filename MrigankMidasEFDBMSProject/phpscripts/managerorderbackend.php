<?php 

session_start(); 
include "db_conn.php";
/*
echo $_POST['fund'];
echo $_POST['amount'];
echo $_POST['period'];
*/

if (isset($_POST['fund']) && isset($_POST['amount'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $fund = validate($_POST['fund']);

    $amount = validate($_POST['amount']);
    

    if (empty($fund)) {

        header("Location: ../clientorder.php?error=Fund Name is required");

        exit();

    }else if(empty($amount)){

        header("Location: ../clientorder.php?error=Amount is required");

        exit();

    }else if(empty($period)){

        header("Location: ../clientorder.php?error=Period is required");

        exit();

    }else{

        $sql2 = "SELECT count(f_id) AS cou FROM fund WHERE f_id='$fund'";

        $result2 = mysqli_query($conn, $sql2);
		$row2=mysqli_fetch_assoc($result2);
		//echo $row2['cou'];
		echo $period;
		if ($row2['cou'] > 0){
			if ($period ===0){
				$sql2 = "INSERT INTO invested_in VALUES('{$_SESSION['c_id']}','$fund','$amount',CURDATE(),NULL,NULL);";
			}else{
				$sql2 = "INSERT INTO invested_in VALUES('{$_SESSION['c_id']}','$fund','$amount',CURDATE(),ADDDATE(CURDATE(), INTERVAL $period YEAR),NULL);";
			}
			
			if ($conn->query($sql2) === TRUE) {
				header("Location: ../userdashboard.php");

						exit();
			} else {
				 header("Location: ../clientorder.php?error=SEVER ERROR");
				exit();
			}

		}else{

				header("Location: ../clientorder.php?error=Incorrect Fund name");

				exit();

		}
	}

}else{

    //header("Location: ../clientorder.php?error=Wrong Parameters");
	/*echo $_POST['fund'];
	echo $_POST['amount'];
	echo $_POST['period'];*/
    exit();

}