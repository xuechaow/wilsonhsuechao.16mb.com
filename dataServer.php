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
        //Following code for importing csv file
		//load the file. Throw exception if failed
        $fileName = 'employee.csv'; 
		if ( !file_exists($fileName) ) {
            throw new Exception('File not found.');
        }
		$handle = fopen($fileName, "r");
        if ( !$handle ) {
            throw new Exception('File open failed.');
        }
		//Get the column names. For auto column names, need store those info and use them as SQL elements.
        $dataNames = fgetcsv($handle,1000,';');
        $num = count($dataNames);
		//Insert the data rows of csv file into Database
        $dbConnection->beginTransaction();		
		while ($data = fgetcsv($handle,1000,';')) {
            $dbConnection->exec("INSERT INTO $tableUsed (idemployee,department,employeeno,name,gender) VALUES ($data[0],'$data[1]','$data[2]','$data[3]','$data[4]')");
        }
		$dbConnection->commit();
    }
    catch(Exception $errOut){
		echo $sql . "<br>" . $errOut->getMessage();
	}			
}

//Deal with request from clients
try {
    
	
    //Make a query to get all the results
	if($queryString === "All"){
        $sql = "SELECT * FROM $tableUsed ";
	} else if ($queryString === "Engineer" or $queryString === "Sales" or $queryString === "Marketing") {
		$sql = "SELECT * FROM $tableUsed WHERE department='$queryString'";
	} else if ($queryString === "Male" or $queryString === "Female") {
		$sql = "SELECT * FROM $tableUsed WHERE gender='$queryString'";
	}
	
	$resultStatement = $dbConnection->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$resultStatement->execute();
	
    //Output the responseText as an html table element
	echo "<table>
          <tr>
          <th>idemployee</th>
          <th>department</th>
          <th>employeeno</th>
		  <th>name</th>
		  <th>gender</th>
		  </tr>";
    while ($row = $resultStatement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)){
       echo "<tr>";
       echo "<td>" . $row['idemployee'] . "</td>";
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