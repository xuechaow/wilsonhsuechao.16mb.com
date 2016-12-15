<?php
/**
  Data Transaction Server
  By Xuechao
  2016/03/29
*/

/*----------Globals Declared here----------*/
//Database connection information
$dataBaseType = "mysql";
$serverName = "mysql.hostinger.co.uk";
$userName = "u628991109_xao5";
$passWord = "7665997";
$dataBaseUsed = "u628991109_xao5";
$tableUsed = "CodeChallengeTable";

/*----------Scripts Start Here----------*/
//Open Database connection using PDO
try {

	//Establish a new connection
	$dbConnection = new PDO($dataBaseType . ":host=$serverName;dbname=$dataBaseUsed",$userName,$passWord);
    //set PDO ERROR MODE EXCEPTION
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //print_r("Database connected successfully!");


}
catch(PDOException $errOut){
	
	//Print the failure info
	echo $errOut->getMessage() . "<br>" . "Creating new db with default values";
	
    //Create a new DB and table for loaded csv as default values
	try{
		
		//New PHP DB Obeject without specifying DB
	    $dbConnection = new PDO($dataBaseType . ":host=$serverName",$userName,$passWord);
	    $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
        //Create database and table
		/* Dont use it if db exists
		$sql = "CREATE DATABASE $dataBaseUsed; USE $dataBaseUsed";
		$dbConnection->exec($sql);
		echo "Created db" . $dataBaseUsed;
        */

        $sql = "USE $dataBaseUsed";
		$dbConnection->exec($sql);

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
            throw new Exception("File not found.");
        }
		$handle = fopen($fileName, "r");
        if ( !$handle ) {
            throw new Exception("File open failed.");
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
		
		//Just send out server errors back
		echo $sql . "<br>" . $errOut->getMessage();
		
	}
	
}


//Deal with GET request from clients
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	try {
		$queryString = $_GET["queryString"];
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
		
		//Part 2 of the challenge: calling dll to print helloworld.
		//echo "<h1>" . exec("ExecHelloDLL.exe") . "</h1>";
		
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
		
		$dbConnection = null;
	}
	catch(PDOException $errOut){
			echo $sql . "<br>" . $errOut->getMessage();
	}
}

//Deal with the insertion operation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	try {
		//Receive the form information and drop useless characters
		$sqlDepartment = trimData($_POST["department"]);
		$sqlEmployeeno = trimData($_POST["employeeno"]);
		$sqlName = trimData($_POST["name"]);
		$sqlGender = $_POST["gender"];//No need to verify from radios
		
		//Data Format Validation
		if (!preg_match("/^[a-zA-Z]*$/",$sqlDepartment)) {
            throw new Exception("Department format wrong!");
        }
		if (!preg_match("/^[0-9]*$/",$sqlEmployeeno)) {
            throw new Exception("Employee Number format wrong!");
        }
		if (!preg_match("/^[A-Za-z ]*$/",$sqlName)) {
            throw new Exception("Name format wrong!");
        }
		//No need to verify radio values.
		
		$sql = "INSERT INTO $tableUsed (department,employeeno,name,gender) VALUES ('$sqlDepartment','$sqlEmployeeno','$sqlName','$sqlGender')";
		$dbConnection->exec($sql);
		$dbConnection = null;
		
		echo "Inserted:" . $sqlDepartment . " " .$sqlEmployeeno . " " .$sqlName . " " .$sqlGender;
	} 	catch(Exception $errOut){
			//400:Bad Request
			echo "Bad Request:" . $errOut->getMessage();
			http_response_code(400);
	}
		
}
/*----------Scripts End Here----------*/

/*----------functions defined here----------*/
//Trim the useless parts of data like duplicate blanks tabs...etc.
function trimData($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

/**
 * Check if a table exists in the current database.
 *
 * @param PDO $pdo PDO instance connected to a database.
 * @param string $table Table to search for.
 * @return bool TRUE if table exists, FALSE if no table found.
 */
function tableExists($pdo, $table) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}

























?>	