<?php
include('../connect.php');
$nome = $_GET["nome"];
$genero = $_GET["genero"];
$data_nascimento = $_GET["data_nascimento"];
$estado_civil = $_GET["estado_civil"];
$rua = $_GET["rua"];
$cidade = $_GET["cidade"];
$bairro = $_GET["bairro"];
$estado = $_GET["estado"];
$cep = $_GET["cep"];
$cpf = $_GET["cpf"];
$email = $_GET["email"];
$telefone = $_GET["telefone"];
$nome_pai = $_GET["nome_pai"];
$nome_mae = $_GET["nome_mae"];
$cns = $_GET["cns"];
$convenio = $_GET["convenio"];
$plano = $_GET["plano"];
$numero_carteira = $_GET["numero_carteira"];


$insert_query = "INSERT INTO tb_paciente (nm_paciente, ds_genero, dt_nascimento, ds_estado_civil, nm_rua, nm_cidade, nm_bairro, sg_estado, cd_cep, cd_cpf, ds_email, cd_telefone, nm_pai, nm_mae, cd_cns, nm_convenio, nm_plano, cd_numero_carteira, ic_status, ic_primeiro_atend)
VALUES ('$nome', '$genero', '$data_nascimento', '$estado_civil', '$rua', '$cidade', '$bairro', '$estado', '$cep', '$cpf', '$email', '$telefone', '$nome_pai', '$nome_mae', '$cns', '$convenio', '$plano', '$numero_carteira', 1, 1)";

$result_insert = mysqli_query($conexao, $insert_query);

if($result_insert){
    
    if(isset($_GET["id_atendimento"])){
        $atendimento = $_GET["id_atendimento"];
        $id_paciente = mysqli_insert_id($conexao);
        $update_query = "UPDATE tb_agendamento SET cd_paciente = '$id_paciente' WHERE cd_agendamento = '$atendimento'";
        $result_update = mysqli_query($conexao, $update_query);
        
        if(!$result_update){
            // Se a atualização falhou, imprima o erro
            echo "Erro ao atualizar tb_agendamento: " . mysqli_error($conexao);
        }
    }
    echo "<script>alert('Paciente cadastrado'); window.location.href = 'dashboardAtendente.php';</script>";
} else {
    // Se a inserção falhou, imprima o erro
    echo "<script>alert('Usuário já cadastrado'); window.location.href = 'cadastro.php';</script>";
}



?>