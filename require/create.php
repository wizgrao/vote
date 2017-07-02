<?php
/**
 * Created by PhpStorm.
 * User: wizgr
 * Date: 6/8/2017
 * Time: 3:33 PM
 */
$loader = new Twig_Loader_Filesystem("require/twigs");
$environ = new Twig_Environment($loader,array());
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $connection = mysqli_connect(DBHOST,DBLOGIN,DBPASS,DBNAME);



    $query = "INSERT INTO elections (name, choices) VALUES (?,?)";

    $statement = $connection->prepare($query);
    $statement->bind_param("si", $_POST['name'], $numChoices);
    $numChoices = count($_POST['candidate']);
    $statement->execute();
    $electionID = $statement->insert_id;

    $statement->close();


    $candidateQuery = "INSERT INTO choices (name, election) VALUES (?,?)";

    $candidateStatement = $connection->prepare($candidateQuery);
    $candidateStatement->bind_param('si',$candidateName, $electionID);
    foreach ($_POST['candidate'] as $candidateName)
        $candidateStatement->execute();

    $candidateStatement->close();
    $connection->close();

    echo $environ->render("createLand.twig",array("userNum"=>$_SESSION['vid'], "electionID"=>$electionID));
}else{

    echo $environ->render("create.twig",array("userNum"=>$_SESSION['vid']));
}

