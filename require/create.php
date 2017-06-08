<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:33 PM
 */
require_once (dirname(__FILE__,2).'/config.php');
$connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);
