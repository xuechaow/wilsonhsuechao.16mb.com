<?php
 
//Database connection information
$dataBaseType = "mysql";
$serverName = "localhost";
$userName = "root";
$passWord = "xizizi620";
$dataBaseUsed = "EV_EMPLOYEE";



//Open Database connection using PDO
try {
	$conn = new PDO($dataBaseType . ":host=$serverName;dbname=$dataBaseUsed", $userName, $passWord);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	

	echo "Connected";
}
catch(PDOException $errOut){
	echo $errOut->getMessage();
			
}

// Output "no suggestion" if no hint was found or output correct values 
 echo "<br>" . "Finished";
 $dbConnection = null;
?> 