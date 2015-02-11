<?php
// Content of database.php
 
$mysqli = new mysqli('localhost', 'username', 'password', 'databasename');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

//we always need to connect with the sql server before doing anything else
//thus this is called "database.php"
?>


<?php
require 'database.php';
//after getting the connection with the sql server


//here we will read the information from the website and then
//get from php global variable which is $_POST
//then get into the mysqli query into the sql server database
$first = $_POST['first'];
$last = $_POST['last'];
$dept = $_POST['dept'];
 

$stmt = $mysqli->prepare("insert into employees (first_name, last_name, department) values (?, ?, ?)");

//check if this is successful
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
//this bind_param is binded with the mysqli preparing 
//which will save the code and time for the user to insert the data into the sql server
$stmt->bind_param('sss', $first, $last, $dept);
/*
The first parameter defines the data types of data to be inserted into the query. 
It is a string with one character per data type. The possible data types are:
i - Integer
d - Decimal
s - String
b - Blob
*/
 
$stmt->execute();
$stmt->close();
 
?>




<?php
//here we need to get the data from the database
//i.e. we would query from the database
require 'database.php';
 
$stmt = $mysqli->prepare("select first_name, last_name from employees order by last_name");
//here prepare the sentence we need to repeatedly do afterwards
/*
insert data we need to bind_param
$stmt = $mysqli->prepare("insert into employees (first_name, last_name, department) values (?, ?, ?)");
$stmt->bind_param('sss', $first, $last, $dept);
$stmt->execute();
$stmt->close();
*/

/*
whenever the sql commands is not determined i.e. we need to get the different data inserted each time
or we need to read the info from the info from the user and thus could not be pre-determined
we should do ? in the prepare and then bind the parameter to it 

$dept = $_GET['dept'];
//here the department data is read from the user 
$stmt = $mysqli->prepare("select first_name, last_name from employees where department=?");
$stmt->bind_param('s', $dept);
//here we bind the department data with ? in our prepared statement
$stmt->execute();
//then we bind the result to the new params and then get the data from the sql database
$stmt->bind_result($first, $last);
while($stmt->fetch()){
	printf("\t<li>%s %s</li>\n",
		htmlspecialchars($first),
		htmlspecialchars($last)
	);
}

*/

/*
query data we need to bind_result and fetch() /  fetch_assoc() which would return the result in array
$stmt = $mysqli->prepare("select first_name, last_name from employees order by last_name");
$stmt->execute();
$stmt->bind_result($first, $last);

while($stmt->fetch()){
	printf("\t<li>%s %s</li>\n",
		htmlspecialchars($first),
		htmlspecialchars($last)
	);
}



or we could do ....
$stmt = $mysqli->prepare("select first_name, last_name from employees order by last_name");
$stmt->execute();
 

echo "<ul>\n"; 
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
	printf("\t<li>%s %s</li>\n",
		htmlspecialchars( $row["first_name"] ),
		htmlspecialchars( $row["last_name"] )
	);
}
echo "<\ul>\n";
*/

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->execute();
$stmt->bind_result($first, $last);
 
echo "<ul>\n";
while($stmt->fetch()){
	printf("\t<li>%s %s</li>\n",
		htmlspecialchars($first),
		htmlspecialchars($last)
	);
}
echo "</ul>\n";
 
$stmt->close();
?>