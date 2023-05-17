<?php 

session_start(); 

include "db_conn.php";

if (isset($_POST['email']) && isset($_POST['pswrd'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $uname = validate($_POST['email']);

    $pass = validate($_POST['pswrd']);

    if (empty($uname)) {

        header("Location: ../loginaccountredo.php?error=User Name is required");

        exit();

    }else if(empty($pass)){

        header("Location: ../loginaccountredo.php?error=Password is required");

        exit();

    }else{

        $sql = "SELECT * FROM client WHERE email='$uname' AND password='$pass'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['email'] === $uname && $row['password'] === $pass) {

                echo "Logged in!";

                $_SESSION['email'] = $row['email'];

                $_SESSION['c_id'] = $row['c_id'];

                header("Location: ../userdashboard.php");

                exit();

            }else{

                header("Location: ../loginaccountredo.php?error=Incorect User name or password");

                exit();

            }

        }else{

            header("Location: ../loginaccountredo.php?error=Incorect User name or password");

            exit();

        }

    }

}else{

    header("Location: ../loginaccount.php");

    exit();

}