<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:33 PM
 */
$connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    require ("view.php");
}else{
    $loader = new Twig_Loader_Filesystem("require/twigs");
    $environ = new Twig_Environment($loader,array());
    echo $environ->render("create.twig",array("userNum"=>$_SESSION['vid']));
}

