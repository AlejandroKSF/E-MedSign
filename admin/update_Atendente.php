<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
   
    header('Location: ../acesso_negado.php');
    exit(); 
}

include('../connect.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);


$func = $_POST["cd_funcionario"];
$nome = $_POST["nome"];
$login = $_POST["login"];
$email = $_POST["email"];
$clinica = $_POST["clinica"];


if (!isset($func, $nome, $login, $email, $clinica)) {
    die("Missing required form inputs");
}


$hashed_password = password_hash($senha, PASSWORD_DEFAULT);


mysqli_begin_transaction($conexao);

try {

    $update_query_funcionario = "UPDATE tb_funcionario SET nm_funcionario=?, nm_login=?, ds_email=?, cd_clinica=? WHERE cd_funcionario=?";
    $stmt_funcionario = $conexao->prepare($update_query_funcionario);

    if ($stmt_funcionario) {
        $stmt_funcionario->bind_param("ssssi", $nome, $login, $email, $clinica, $func);
        $stmt_funcionario->execute();

        if ($stmt_funcionario->affected_rows === 0) {
            throw new Exception("No rows updated. Please check if the funcionario ID exists.");
        }


        mysqli_commit($conexao);

        $stmt_funcionario->close();
        

        echo "<script>alert('Registro atualizado'); window.location.href = 'Atendentes.php';</script>";
        exit();
    } else {
        throw new Exception("Error preparing the query: " . $conexao->error);
    }
} catch (Exception $e) {

    mysqli_rollback($conexao);
    echo "Error: " . $e->getMessage();
}

$conexao->close();
?>
