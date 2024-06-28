<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {

    header('Location: ../acesso_negado.php');
    exit(); 
}

include('../connect.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);

$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$cep = $_POST['cep'];
$clinica = $_POST['cd_clinica'];



$logoPath = '../medico/imagens/';
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $logoTmpName = $_FILES['logo']['tmp_name'];
    $logoName = $_FILES['logo']['name'];
    $logoPath = '../medico/imagens/' . $logoName; 
    move_uploaded_file($logoTmpName, $logoPath); 
}



mysqli_begin_transaction($conexao);

try {

    $update_query_funcionario = "UPDATE tb_clinica SET nm_clinica=?, ds_endereco=?, cd_cep=?, cd_base64_logo=? WHERE cd_clinica=?";
    $stmt_funcionario = $conexao->prepare($update_query_funcionario);

    if ($stmt_funcionario) {
        $stmt_funcionario->bind_param("ssssi", $nome, $endereco, $cep, $logoPath,$clinica);
        $stmt_funcionario->execute();

        if ($stmt_funcionario->affected_rows === 0) {
            throw new Exception("No rows updated. Please check if the funcionario ID exists.");
        }


        mysqli_commit($conexao);

        $stmt_funcionario->close();
        

        echo "<script>alert('Registro atualizado'); window.location.href = 'Clinicas.php';</script>";
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
