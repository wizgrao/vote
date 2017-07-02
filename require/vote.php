<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:47 PM
 */




$loader = new Twig_Loader_Filesystem("require/twigs");
$environ = new Twig_Environment($loader,array());

$error = array();
$candidates = array();
$numCandidates =0;
$electionName = "";
if(!isset($_GET['election'])){
    $error[] = "No election set";
}else{
    $connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);

    $query = "SELECT elections.name as electionName, choices.name as candidateName, choices.id as candidateID  FROM elections, choices WHERE elections.id = choices.election AND elections.id = ? ";
    $statement = $connection->prepare($query);
    $statement->bind_param("i", $_GET['election']);
    $statement->execute();
    $statement->bind_result($electionN, $candidateName, $candidateID);
    $numCandidates =0;
    $candidates = array();


    while($statement->fetch()){
        $numCandidates++;

        $candidates[] = array("name"=>$candidateName, "id"=>$candidateID);
        $electionName = $electionN;
    }


}

echo $environ->render("vote.twig",array("userNum"=>$_SESSION['vid'], "errors"=>$error,"candidates" =>$candidates, "numCandidates"=>$numCandidates, "electionName"=>$electionName));