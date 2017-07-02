<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:47 PM
 */




$loader = new Twig_Loader_Filesystem("require/twigs");
$environ = new Twig_Environment($loader,array());
echo $environ->render("vote.twig",array("userNum"=>$_SESSION['vid']));