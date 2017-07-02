<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:46 PM
 */

$loader = new Twig_Loader_Filesystem("require/twigs");
$environ = new Twig_Environment($loader,array());

$error = array();
$candidates = array();
$numCandidates =0;
$electionName = "";
$winner = "error";
if (!isset($_GET['election'])) {
    $error[] = "No election set";
} else {
    $connection = mysqli_connect(DBHOST, DBLOGIN, DBPASS, DBNAME);

    $query = "SELECT elections.name as electionName, choices.name as candidateName, choices.id as candidateID, votes.rank, votes.userID  FROM elections, choices, votes WHERE elections.id = choices.election AND votes.choiceID = choices.id AND elections.id = ? ";
    $statement = $connection->prepare($query);

    $statement->bind_param("i", $_GET['election']);
    $statement->execute();

    $statement->bind_result($electionN, $candidateName, $candidateID, $rank, $userID);
    $votes = array();
    $candidates = array();
    $voteLevel = array();
    while($statement->fetch()){
        $votes[$userID][$rank] = $candidateID;
        $voteLevel[$userID]=1;
        $candidates[$candidateID] = $candidateName;
        $electionName = $electionN;
    }

    $voteTotals = array();
    foreach($candidates as $candID => $candName)
    {
        $voteTotals[$candID] = 0;

    }
    $stage = 1;
    $numVotes = count($votes);


    $done = false;
    $max = -1;
    $maxIndex = -1;

    $min = $numVotes +1;
    $minIndex = -1;
    while(!$done && $stage < count($candidates)){
        $stage++;
        $max = -1;
        $maxIndex = -1;

        $min = $numVotes +1;
        $minIndex = -1;

        foreach($votes as $voteID => $vote){
            $voteTotals[$vote[$voteLevel[$voteID]]]++;
            if($voteTotals[$vote[$voteLevel[$voteID]]] > $max){
                $max = $voteTotals[$vote[$voteLevel[$voteID]]];
                $maxIndex = $vote[$voteLevel[$voteID]];
            }

            if($voteTotals[$vote[$voteLevel[$voteID]]] < $min){
                $min = $voteTotals[$vote[$voteLevel[$voteID]]];
                $minIndex = $vote[$voteLevel[$voteID]];
            }

        }
        if($max > $numVotes/2){
            $done = true;
            break;

        }else{
            foreach($votes as $voteID => $vote){
                if($vote[$voteLevel[$voteID]] == $minIndex){
                    $voteLevel[$voteID] ++;
                }
            }
        }




    }
    $winner = $candidates[$maxIndex];

}

echo $environ->render("viewVotes.twig", array("userNum" => $_SESSION['vid'], "errors" => $error, "winner"=>$winner));
