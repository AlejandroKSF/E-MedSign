<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit();
}

include ('../connect.php');

$nome = $_GET["nome"];
$login = $_GET["login"];
$senha = $_GET["senha"];
$email = $_GET["email"];
$clinica = $_GET["clinica"];


$hashed_password = password_hash($senha, PASSWORD_DEFAULT);

mysqli_begin_transaction($conexao);

try {

    $insert_query_funcionario = "INSERT INTO tb_funcionario (nm_funcionario, nm_login, ds_password_hash, ds_email, cd_clinica) VALUES (?, ?, ?, ?, ?)";
    $stmt_funcionario = $conexao->prepare($insert_query_funcionario);

    if ($stmt_funcionario) {
        $stmt_funcionario->bind_param("sssss", $nome, $login, $hashed_password, $email, $clinica);
        $stmt_funcionario->execute();

        if ($stmt_funcionario->affected_rows > 0) {

            $cd_funcionario = $conexao->insert_id;


            $insert_query_medico = "INSERT INTO tb_atendente (cd_FuncionarioID) VALUES (?)";
            $stmt_medico = $conexao->prepare($insert_query_medico);

            if ($stmt_medico) {
                $stmt_medico->bind_param("i", $cd_funcionario);
                $stmt_medico->execute();

                if ($stmt_medico->affected_rows > 0) {

                    mysqli_commit($conexao);
                    echo "<script>
                    alert('Atendente adicionado com sucesso!');
                    window.location.href = 'Medicos.php';
                  </script>";
                } else {
                    throw new Exception("Erro ao adicionar médico: " . $stmt_medico->error);
                }

                $stmt_medico->close();
            } else {
                throw new Exception("Erro ao preparar a consulta de médico: " . $conexao->error);
            }
        } else {
            throw new Exception("Erro ao adicionar funcionário: " . $stmt_funcionario->error);
        }

        $stmt_funcionario->close();
    } else {
        throw new Exception("Erro ao preparar a consulta de funcionário: " . $conexao->error);
    }
} catch (Exception $e) {

    mysqli_rollback($conexao);
    echo $e->getMessage();
}

$conexao->close();
?>