<?php
session_start();


/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:38 AM
 */
require_once (dirname(__FILE__,2).'/config.php');

if(!isset($_SESSION['vid'])){
    $connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);
    $query = "INSERT INTO VoteUsers (name) VALUES ('none')";
    $statement = $connection->prepare($query);
    $statement->execute();
    $_SESSION['vid'] = $statement->insert_id;
    $statement->close();
    $connection->close();

}
if(isset($_GET["p"])){
    switch ($_GET["p"]){
        case "create":
            require_once ("require/create.php");
            break;
        case "view":
            require_once ("require/view.php");
            break;
        case "vote":
            require_once ("require/vote.php");
            break;
        default:
            require_once ("require/land.php");
    }
}else{
    require_once ("require/land.php");
}
