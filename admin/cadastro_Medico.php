<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../acesso_negado.php');
    exit();
}

include('../connect.php');

$nome = $_POST["nome"];
$login = $_POST["login"];
$senha = $_POST["senha"];
$email = $_POST["email"];
$crm = $_POST["crm"];
$especializacao = $_POST["especializacao"];
$clinica = $_POST["clinica"];


$hashed_password = password_hash($senha, PASSWORD_DEFAULT);


mysqli_begin_transaction($conexao);

try {

    $insert_query_funcionario = "INSERT INTO tb_funcionario (nm_funcionario, nm_login, ds_password_hash, ds_email, cd_clinica) VALUES (?, ?, ?, ?, ?)";
    $stmt_funcionario = $conexao->prepare($insert_query_funcionario);

    if ($stmt_funcionario === false) {
        throw new Exception("Erro ao preparar a consulta de funcionário: " . $conexao->error);
    }

    $stmt_funcionario->bind_param("sssss", $nome, $login, $hashed_password, $email, $clinica);
    $stmt_funcionario->execute();

    if ($stmt_funcionario->affected_rows <= 0) {
        throw new Exception("Erro ao adicionar funcionário: " . $stmt_funcionario->error);
    }

    $cd_funcionario = $conexao->insert_id;

    $insert_query_medico = "INSERT INTO tb_medico (cd_FuncionarioID, cd_Crm, ds_Especializacao, ic_status, ic_expediente, ds_path_img_perfil) VALUES (?, ?, ?, 1, 0,'../imagens/perfil.png')";
    $stmt_medico = $conexao->prepare($insert_query_medico);

    if ($stmt_medico === false) {
        throw new Exception("Erro ao preparar a consulta de médico: " . $conexao->error);
    }

    $stmt_medico->bind_param("iss", $cd_funcionario, $crm, $especializacao);
    $stmt_medico->execute();

    if ($stmt_medico->affected_rows <= 0) {
        throw new Exception("Erro ao adicionar médico: " . $stmt_medico->error);
    }

    mysqli_commit($conexao);

    echo "<script>
            alert('Funcionário e médico adicionados com sucesso!');
            window.location.href = 'Medicos.php';
          </script>";

    $stmt_medico->close();
    $stmt_funcionario->close();
} catch (Exception $e) {
    mysqli_rollback($conexao);


    if ($conexao->errno === 1062) {
        $errorMessage = "Erro: O login já está em uso. Por favor, escolha um login diferente.";
    } else {
        $errorMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }

    echo "<script>
            alert('$errorMessage');
            window.location.href = 'cadastroMedico.php';
          </script>";
}

$conexao->close();
?>
