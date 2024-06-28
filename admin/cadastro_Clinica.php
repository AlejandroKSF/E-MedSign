<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'admin') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit(); // Certifica-se de que o script pare de executar após o redirecionamento
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $cep = $_POST['cep'];

    $logoPath = '../medico/imagens/'; 
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoTmpName = $_FILES['logo']['tmp_name'];
        $logoName = $_FILES['logo']['name'];
        $logoPath = '../medico/imagens/' . $logoName; 
        move_uploaded_file($logoTmpName, $logoPath); 
    }

include('../connect.php');

    $conn = $conexao;


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $stmt = $conn->prepare("INSERT INTO tb_clinica (nm_clinica, cd_base64_logo, ds_endereco, cd_cep) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $logoPath, $endereco, $cep);


    if ($stmt->execute() === TRUE) {
        echo "<script>
        alert('Clinica adicionada com sucesso!');
        window.location.href = 'Medicos.php';
      </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
