<?php

require_once "../config/database.php";

session_start();

if(!isset($_POST['user'], $_POST['pass'])){
    header("Location: ../../login.php");
    exit;
}

$user = strtolower(trim($_POST['user']));
$pass = $_POST['pass'];

$stmt = $conn->prepare(
    "SELECT
    cargo,
    username,
    user,
    email,
    pass,
    createdAt
    FROM tbl_usuarios
    WHERE user = ?"
);
$stmt->bind_param("s", $user);

if(!$stmt->execute()){
    $_SESSION['error'] = "Erro ao localizar usuário...";
    header("Location: ../../login.php");
    exit;
}

$result = $stmt->get_result();

if($result->num_rows < 1){
    $_SESSION['error'] = "Usuário ou senha incorretos!";
    header("Location: ../../login.php");
    exit;
}

$row = $result->fetch_assoc();

if(!password_verify($pass, $row['pass'])){
    $_SESSION['error'] = "Usuário ou senha incorretos!";
    header("Location: ../../login.php");
    exit;
}

session_regenerate_id(true);

$_SESSION['auth'] = true;

// RESPECTIVAS INFORMAÇÕES

$_SESSION['cargo'] = $row['cargo'];
$_SESSION['username'] = $row['username'];
$_SESSION['user'] = $row['user'];
$_SESSION['createdAt'] = $row['createdAt'];

header("Location: ../../index.php");
exit;

?>