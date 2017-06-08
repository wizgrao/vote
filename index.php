<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:38 AM
 */
require_once (dirname(__FILE__,2).'/config.php');
$connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);
$query = "SELECT id from blog;";
$statement = $connection->prepare($query);
$statement->execute();
$statement->bind_result($id);
while($statement->fetch()){
    echo $id;
}
