<?php
$db_sever = "localhost";
$db_user = "root" ;
$db_password = "";
$db_name = "melodise_db";

try{
    $conn = mysqli_connect($db_sever, $db_user, $db_password, $db_name);
}
catch(mysqli_sql_exception){
    echo"Connection to database failed";
}
