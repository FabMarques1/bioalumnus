<?php

require_once "../config/database.php";
session_start();

$id = $_SESSION['id'];
$current_pass = $_POST['current_password'];

$new_pass = $_POST['new_password'];
$new_pass_hash = password_hash($new_pass, PASSWORD_ARGON2ID);

$query = $conn->prepare(
    "SELECT pass
    FROM tbl_usuarios
    WHERE id = ?"
);
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();

$row = $result->fetch_assoc();

if(!password_verify($current_pass, $row['pass'])){
    $_SESSION['error'] = "A senha atual não corresponde.";
    header("Location: ../../profile.php?user=" . $_SESSION['user']);
    exit;
}

$query->close();

try{

    $query = $conn->prepare(
        "UPDATE tbl_usuarios
        SET pass = ?
        WHERE id = ?"
    );
    $query->bind_param("si", $new_pass_hash, $id);
    if(!$query->execute()){
        throw new Exception("Erro ao atualizar sua senha.");
    }

    $_SESSION['success'] = "Senha alterada com sucesso!";

    header("Location: ../../profile.php?user=" . $_SESSION['user']);
    exit;
} catch (Exception $e){

    $_SESSION['error'] = $e->getMessage();
    header("Location: ../../profile.php?user=" . $_SESSION['user']);
    exit;
}

?>