<?php
$dataBaseType = "mysql";
$serverName = "localhost";
$userName = "root";
$passWord = "xizizi620";
$dataBaseUsed = "EV_EMPLOYEE";

try {
    $conn = new PDO($dataBaseType . ":host=$serverName;dbname=$dataBaseUsed", $userName, $passWord);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "USE $dataBaseUsed";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database created successfully<br>";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
	

$conn = null;
 ?>