<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:41 PM
 */

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem("twigs");
$environ = new Twig_Environment($loader,array());
echo $environ->render("base.twig",array("userNum"=>$_SESSION['vid']));