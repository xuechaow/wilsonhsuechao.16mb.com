<html>
 <body>

 Welcome <?php echo $_GET["name"]; ?><br>
 Your email address is: <?php echo $_GET["email"]; ?> <br>
 
<?php

echo "Fucking Yeah!";


?>

<?php
$servername = "localhost";
$username = "root";
 $password = "xizizi620";

try {
    $conn = new PDO("mysql:host=$servername;", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "USE myDBPDO";
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
 </body>
 </html> 