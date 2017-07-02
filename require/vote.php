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
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (!isset($_GET['election'])) {
        $error[] = "No election set";
        echo $environ->render("vote.twig", array("userNum" => $_SESSION['vid'], "errors" => $error, "candidates" => $candidates, "numCandidates" => $numCandidates, "electionName" => $electionName, "election"=>$_GET['election']));
    }else{

        $ranks = array_fill(0,count($_POST['candidate']), 0);

        foreach($_POST['candidate'] as $candidateID => $rank){
            $ranks[$rank] ++;

        }
        $flag = 0;
        foreach ($ranks as $ran){
            if($ran != 0){
                $flag = 1;
            }
        }
        if($flag ==1){
            $error[] = "use each number exactly once";
            echo $environ->render("vote.twig", array("userNum" => $_SESSION['vid'], "errors" => $error, "candidates" => $candidates, "numCandidates" => $numCandidates, "electionName" => $electionName, "election"=>$_GET['election']));
        }else{
            $connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);
            $voteQuery = "INSERT INTO votes (rank, electionID, choiceID, userID) VALUES (?,?,?,?)";

            $voteStatement = $connection->prepare($voteQuery);
            $voteStatement->bind_param('iiii',$rank, $_GET['election'], $candidateID, $_SESSION['vid']);


            foreach($_POST['candidate'] as $candidateID => $rank){
                $voteStatement->execute();

            }
        }
    }
}else {
    if (!isset($_GET['election'])) {
        $error[] = "No election set";
    } else {
        $connection = mysqli_connect(DBHOST, DBLOGIN, DBPASS, DBNAME);

        $query = "SELECT elections.name as electionName, choices.name as candidateName, choices.id as candidateID  FROM elections, choices WHERE elections.id = choices.election AND elections.id = ? ";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $_GET['election']);
        $statement->execute();
        $statement->bind_result($electionN, $candidateName, $candidateID);
        $numCandidates = 0;
        $candidates = array();


        while ($statement->fetch()) {
            $numCandidates++;

            $candidates[] = array("name" => $candidateName, "id" => $candidateID);
            $electionName = $electionN;
        }
        if ($numCandidates == 0) $error[] = "Poll doesn't exist!";

    }

    echo $environ->render("vote.twig", array("userNum" => $_SESSION['vid'], "errors" => $error, "candidates" => $candidates, "numCandidates" => $numCandidates, "electionName" => $electionName,"election"=>$_GET['election']));
}