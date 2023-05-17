<?php 

	session_start(); 
	include "db_conn.php";
	$sql="SELECT f_id,f_name FROM fund";
	$result=mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0){
		mysqli_fetch_all($result, MYSQLI_ASSOC);
			/*foreach ($result as $option){
                		echo $option['f_name']."<br>";
	    		}*/
	}


