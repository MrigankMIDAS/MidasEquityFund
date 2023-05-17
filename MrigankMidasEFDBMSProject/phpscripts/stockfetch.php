<?php

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midas_ef";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// API endpoint URL
$url = "https://api.bseindia.com/BseIndiaAPI/api/AnnGetData/w?strCat=-1&strPrevDate=&strScrip=&strSearch=P&strToDate=".date("d-m-Y")."&strType=C";

// Fetch data from API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

// Parse data from API response
$data = json_decode($response, true);

if ($data["Table"] && count($data["Table"]) > 0) {
  // Prepare insert statement
  $stmt = mysqli_prepare($conn, "INSERT INTO stock (ticker, co_name, mkt_price, EPS) VALUES (?, ?, ?, ?)");

  mysqli_stmt_bind_param($stmt, "ssdd", $ticker, $co_name, $mkt_price, $EPS);

  foreach ($data["Table"] as $row) {
    $ticker = $row["SecurityID"];
    $co_name = $row["SecurityName"];
    $mkt_price = floatval($row["ClosePrice"]);
    $EPS = floatval($row["EPS"]);

    // Execute insert statement
    mysqli_stmt_execute($stmt);
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>
