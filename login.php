<?php
include ("connect.php");

session_start(); // Start the session at the beginning

$conn = $conexao;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar entrada
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    // Proteção contra injeção de SQL
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Buscar usuário no banco de dados
    $sql = "SELECT fun.cd_funcionario, fun.nm_login, fun.ds_password_hash, fun.cd_clinica, 'medico' AS user_type 
        FROM tb_funcionario AS fun 
        JOIN tb_medico AS me ON me.cd_funcionarioID = fun.cd_funcionario 
        JOIN tb_clinica AS cli ON cli.cd_clinica = fun.cd_clinica
        WHERE fun.nm_login='$username'
        UNION
        SELECT fun.cd_funcionario, fun.nm_login, fun.ds_password_hash, fun.cd_clinica, 'atendente' AS user_type 
        FROM tb_funcionario AS fun 
        JOIN tb_atendente AS ate ON ate.cd_funcionarioID = fun.cd_funcionario 
        JOIN tb_clinica AS cli ON cli.cd_clinica = fun.cd_clinica
        WHERE fun.nm_login='$username'
        UNION
        SELECT adm.cd_admin AS cd_funcionario, adm.nm_login, adm.ds_password_hash, NULL AS cd_clinica, 'admin' AS user_type 
        FROM tb_admin AS adm
        WHERE adm.nm_login='$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["ds_password_hash"])) {
            // Login bem-sucedido, salvar informações do usuário na sessão
            $_SESSION['user_id'] = $row['cd_funcionario'];
            $_SESSION['username'] = $row['nm_login'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['clinica'] = $row['cd_clinica'];
            if ($row['user_type'] === 'medico') {
                $update_sql = "UPDATE tb_medico SET ic_expediente = 1 WHERE cd_FuncionarioID = '".$_SESSION['user_id']."'";
            }
            echo json_encode(array("success" => true, "user_type" => $row['user_type']));
        } else {
            // Senha incorreta
            echo json_encode(array("success" => false, "message" => "Usuário ou senha incorreto"));
        }
    } else {
        // Usuário não encontrado
        echo json_encode(array("success" => false, "message" => "Usuário ou senha incorreto"));
    }

    $conn->close();
}
?>