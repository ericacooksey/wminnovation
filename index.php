<?php

//create connection    (host, username, password, dbname)
$conn = mysqli_connect('mysql.wminnovation.xyz', 'aleisha_walmart', '*******', 'iamerica_db');

//check connection
if ($conn)
  { 
  print "<br> Connection to the database successful";
}
else {
 	$err = mysqli_error; 
	print "<br> Connection Failed";
	print htmlentities($err['message']);
	exit;
  } 

$sql ='SELECT * from GeoNotifs';
//$result = mysqli_query($con, $sql);

$result =  mysqli_real_query($con, $sql);

$num_columns = mysqli_field_count($conn);

echo "<BR/>";
echo "</TR><TH>-- Obtaining results<TH>";

// start results formatting
echo "<TABLE BORDER=1 padding=10px>";
echo "<TR><TH>StoreID</TH><TH>Notifcation Message</TH><TH>URI</TH></TH>";

while (mysqli_fetch_field($result)){
echo "<TR>";
 for ($i = 1; $i <= $num_columns; $i++) {
    $column_value = mysqli_fetch_object($sql_statement,$i);
    echo "<TD>$column_value</TD>";
 }
echo "</TR>";
}
echo "</TABLE>";

//free resources and  close connection
mysqli_free_result($result);
mysqli_close($conn);




?>

