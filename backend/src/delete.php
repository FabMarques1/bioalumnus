<?php

require_once "db_queries.php";
require_once "../config/database.php";
session_start();

if(isset($_POST['user']) || !empty($_POST['user'])){
    $user = strtolower(trim($_POST['user']));
}

if($_SESSION['user'] !== $user){
    $_SESSION['error'] = "Erro ao deletar sua conta.";
}

try{

    $query = $conn->prepare(
        "DELETE FROM tbl_usuarios
        WHERE userprofile = ?"
    );
    $query->bind_param("s", $user);

    if(!$query->execute()){
        throw new Exception("Erro ao deletar sua conta. (Se isso aparecer, contate a administração)");
    }

    header("Location: quit.php");
    exit;

} catch (Exception $e){
    $_SESSION['error'] = $e->getMessage();
    header("Location: ../../profile.php?user=" . $_SESSION['user']);
    exit;
}

?>