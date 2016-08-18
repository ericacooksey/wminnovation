<?php

$method = $_SERVER['REQUEST_METHOD'];

$body = file_get_contents('php://input');
$obj = json_decode($body);

//conect to the dreamhost database
$link = mysqli_connect('mysql.wminnovation.xyz', 'aleisha_walmart', '*******', 'iamerica_db');

//JSON variables
$storeid = $obj->StoreID;
$msg = $obj->Message;
$walmarturi = $obj->WalmartUri;
$cid = $obj->cid;
$itemName = $obj->itemName;
$price = $obj->price;   

//insert user and password
mysqli_set_charset($link, 'utf8');


//Create SQL based on HTTP method
switch ($method) {
  case 'GET':
      $sql = "SELECT Message FROM notifications WHERE cid='$key'";
      break;
  case 'POST':
$sql = "INSERT INTO `notifications`(`StoreID`, `Message`, `WalmartUri`, `cid`, `itemName`, `price`) VALUES ('$storeid','$msg','$walmarturi','$cid','$itemName',$price) ON DUPLICATE KEY UPDATE itemName='$itemName', price=$price;";
   break;
  case 'DELETE':
      $sql = "DELETE notifications where cid='$key'";
      break;
}


//execute the SQL statement
$result = mysqli_query($link,$sql);


//ERROR CHECKING
if (!$result) {
  http_response_code(404);
	}

// print the result Message
if ($method =='GET') {
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
else {
  echo mysqli_affected_rows($link);
}

//close the mysqli connection
mysqli_close($link);
?>

