<?php
/**
  Data Transaction Server
  By Xuechao
  2016/03/29
*/
$queryString = $_REQUEST["queryString"];

//Database connection information
$dataBaseType = "mysql";
$serverName = "localhost";
$userName = "root";
$passWord = "xizizi620";
$dataBaseUsed = "EV_EMPLOYEE";
$tableUsed = "CodeChallengeTable";


//Open Database connection using PDO
try {
	$dbConnection = new PDO($dataBaseType . ":host=$serverName;dbname=$dataBaseUsed",$userName,$passWord);
//set PDO ERROR MODE EXCEPTION
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//print_r("Database connected successfully!");
	echo "Connected DB:" . $dataBaseUsed . "<br>";
	
}
catch(PDOException $errOut){
	echo $errOut->getMessage() . "<br>" . "Creating new db with default values";
//May load csv or manually input default values
	try{
            $dbConnection = null;
	    $dbConnection = new PDO($dataBaseType . ":host=$serverName",$userName,$passWord);
	    $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//Create database and table		
		$sql = "CREATE DATABASE $dataBaseUsed; USE $dataBaseUsed";
		$dbConnection->exec($sql);
		echo "Created db" . $dataBaseUsed;
		$sql = "CREATE TABLE $tableUsed (
		        idemployee INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				department VARCHAR(45),
				employeeno VARCHAR(20),
				name VARCHAR(45),
				gender VARCHAR(10)		
		        )";
		$dbConnection->exec($sql);
		echo "Created table" . $tableUsed;
//Following code for manually set default table values
                
		$dbConnection->beginTransaction();
		$dbConnection->exec("INSERT INTO $tableUsed (idemployee,department,employeeno,name,gender) VALUES (1,'Engineer','1234567890','Tom','Male')");
		$dbConnection->exec("INSERT INTO $tableUsed (idemployee,department,employeeno,name,gender) VALUES (3,'Sales','1472583690','Jenny','Female')");
		$dbConnection->commit();
		
		echo "Created and Connected DB" . $dataBaseUsed;
		
	}
    catch(PDOException $errOut){
		echo $sql . "<br>" . $errOut->getMessage();
	}			
}

//Deal with request from clients
try {
    
	
//Make a query to get all the results
    $sql = "SELECT * FROM $tableUsed WHERE gender='$queryString'";
	$resultStatement = $dbConnection->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$resultStatement->execute();
	
//Output the responseText as an html table element
	echo "<table>
          <tr>
          <th>id</th>
          <th>department</th>
          <th>employeeno</th>
		  <th>name</th>
		  <th>gender</th>
		  </tr>";
    while ($row = $resultStatement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
       echo "<tr>";
       echo "<td>" . $row['id'] . "</td>";
       echo "<td>" . $row['department'] . "</td>";
       echo "<td>" . $row['employeeno'] . "</td>";
       echo "<td>" . $row['name'] . "</td>";
       echo "<td>" . $row['gender'] . "</td>";
       echo "</tr>";
	   }
    echo "</table>";
	
	$conn = null;
}
catch(PDOException $errOut){
		echo $sql . "<br>" . $errOut->getMessage();
	}


























?>