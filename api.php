<?php

 // set method to GET, POST, or DELETE -- depending on type of request wanted
$method = $_SERVER['REQUEST_METHOD'];
//break up the url and gives us only the part that we want i.e. /message?storeid=0001
//storeID is given in url which is what we want to find
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

//turn JSON file into a string with file_get_contents
//use json_decode to decode the JSON string
$input = json_decode(file_get_contents('(php://input// JSON FILE NAME GOES HERE)'), true);   //what is php://input supposed to be


//conect to the dreamhost database
$link = mysqli_connect('mysql.wminnovation.xyz', 'user', 'password', 'iamerica_db');
//insert user and password
mysqli_set_charset($link, 'utf8');


//retrieve the table and key from the path
//search $request for matches to the regex and replace with ''
//use array_shift to remove first character - '/'
$table = preg_replace('/[^a-z0-9_]+/i', '', array_shift($request));
$key = array_shift($request)+0;



//escape the columns and values from the input object
$columns = preg_replace('/[^a-z0-9_]+/i', '', array_keys($input))
//use array_map to return an array that has all the elements of $input
//       but return null if empty
$values = array_map(function ($value) use ($link) {
  if ($value===null) return null;
  //use real_escape_string to ensure we are using a legal SQL statement and return an escaped string
  return mysqli_real_escape_string($link, (string)$value);   //mysqli_real_escape_string might have been removed with the new version of php?
}, array_values($input));



//build the SET part of the SQL command    (???)
$set = '';
for (i=0;i<count($columns);i++) {
  $set.=(i>0?',':'')." ''".$columns[$i].'=';
  $set.=($values[$i])===null?'NULL':'"'.$values[$i].'"');
}



//Create SQL based on HTTP method
switch ($method) {
  case 'GET':
      $sql = "SELECT Message FROM notifications WHERE storeID=$key"; //done a little differently in the online example
      break;
  // case 'PUT':
    //  $sql =   ;   --don't think we need this
    //break;
  case 'POST':
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
  die(mysqli_error());
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
