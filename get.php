<?php

 // set method to GET, POST, or DELETE -- depending on type of request wanted
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');
//conect to the dreamhost database
$link = mysqli_connect('mysql.wminnovation.xyz', 'aleisha_walmart', '********', 'iamerica_db');

mysqli_set_charset($link, 'utf8');

$key = $_GET['cid'];


//Create SQL based on HTTP method
switch ($method) {
  case 'GET':
      $sql = "SELECT * FROM notifications WHERE cid='$key'";
      break;
 // casae 'POST':
	//break;
  case 'DELETE':
     $sql = "DELETE notifications where cid='$key'";
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
      echo mysqli_insert_id($link);
	 }
 else {
       echo mysqli_affected_rows($link);
 	}	


//close  connection
mysqli_close($link);

?>
