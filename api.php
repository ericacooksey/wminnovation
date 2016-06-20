<?php

 // set method to GET, POST, or DELETE -- depending on type of request wanted
$method = $_SERVER['REQUEST_METHOD'];
	echo "Method: " . $method . "<br>";


echo "Server Path Info: " . $_SERVER['PATH_INFO'] . "<br>";
	echo "StoreID: " . $_GET['storeID'];

//conect to the dreamhost database
$link = mysqli_connect('mysql.wminnovation.xyz', 'aleisha_walmart', 'Ho4j8F!', 'iamerica_db');
//insert user and password

//check connection
if ($link)
  {
  print "<br> Connection to the database successful <br>";
}
else {
        $err = mysqli_error;
        print "<br> Connection Failed";
        print htmlentities($err['message']);
        exit;
  }
mysqli_set_charset($link, 'utf8');

$key = $_GET['storeID'];
	echo "Key (StoreID): " . $key ;




//use array_map to return an array that has all the elements of $input
//       but return null if empty
/*

  $values = array_map(function ($value) use ($link) {
  if ($value===null) return null; 
 //use real_escape_string to ensure we are using a legal SQL statement and return an escaped string
else  
 return mysqli_real_escape_string($link, (string)$value); 
  //mysqli_real_escape_string might have been removed with the new version of php?
 }, array_values($input));

*/

//build the SET part of the SQL command    (???)
//----------$set = '';
//--------- for (i=0;i<count($columns);i++) {
 //-------  $set.=(i>0?',':'')." ''".$columns[$i].'=';
 //--------  $set.=($values[$i
//------- ])===null?'NULL':'"'.$values[$i].'"');
//------- }



//Create SQL based on HTTP method
switch ($method) {
  case 'GET':
      $sql = "SELECT Message FROM notifications WHERE storeID='$key'";
echo "<br> SQL statement: " . $sql . "<br>";
      break;
 //  case 'POST':
      // $sql = "INSERT into notifications set $set";   --not sure we want to set this value to null, in case of other drivers driving by
      //break;
  case 'DELETE':
     $sql = "DELETE notifications where storeID=$key";
       break;
}


//execute the SQL statement
$result = mysqli_query($link,$sql);

//ERROR CHECKING
//die if SQL statement doesn't work
if (!$result) {
  http_response_code(404);   //not found
}

// print the result Message   (??)
 if ($method =='GET') {
	 if (!$key) echo '[';
  //next line should only output 1 row because there's only one msg?
  		 for ($i=0;$i<mysqli_num_rows($result);$i++) {
   //then we would only need json_encode(mysqli_fetch_object($result));
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


//close the mysqli connection
mysqli_close($link);

?>
