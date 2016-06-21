<?php
$method = $_SERVER['REQUEST_METHOD'];

//conect to the dreamhost database
$link = mysqli_connect('mysql.wminnovation.xyz', 'aleisha_walmart', 'Sharkey001', 'iamerica_db');
mysqli_set_charset($link, 'utf8');

$key = $_GET['storeID'];
echo $key;
//Create SQL based on HTTP method
switch ($method) {
  case 'GET':
      $sql = "SELECT Message FROM notifications WHERE storeID='$key'";
      break;
  case 'POST':
     $sql = "INSERT into notifications VALUES ('$storeid', '$msg', '$walmarturi')";
      break;
  case 'DELETE':
     $sql = "DELETE FROM  notifications where storeID='$key'";
       break;
}

//execute the SQL statement
$result = mysqli_query($link,$sql);

if (!$result) {
  http_response_code(404);
}
// print the result Message
 if ($method =='GET')
	 {
	 if (!$key) echo '[';
 	 for ($i=0;$i<mysqli_num_rows($result);$i++)
		 {
   		 echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
 		 }
 		 if (!$key) echo ']';
	 }
 elseif ($method =='POST') {
      http_response_code(201);
	 }
   //if $method = 'DELETE'
 else {
       echo mysqli_affected_rows($link);
       http_response_code(204);
 	}
//close  connection
mysqli_close($link);
?>
