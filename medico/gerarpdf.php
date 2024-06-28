<?php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit(); // Certifica-se de que o script pare de executar após o redirecionamento
}
require __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

// Instanciar o Dompdf
$dompdf = new Dompdf($options);

include ('../main.php');
include ("../connect.php");
// Captura os dados enviados via POST
$data = $_POST;
$userId = $_SESSION['user_id'];

// Captura o ID do paciente
$paciente = $data['paciente'];
unset($data['paciente']); // Remove o paciente dos dados para evitar duplicação
$query = "SELECT func.nm_funcionario as nome, cli.nm_clinica as clinica, cli.cd_base64_logo as logo, med.cd_Crm as crm, med.ds_especializacao as especialidade FROM tb_medico as med join tb_funcionario as func on func.cd_funcionario = med.cd_FuncionarioID join tb_clinica as cli on cli.cd_clinica = func.cd_clinica WHERE med.cd_FuncionarioID = $userId";
$result = mysqli_query($conexao, $query);

if (!$result) {
    // Se houver erro na consulta, exibe uma mensagem de erro
    echo "Erro ao executar a consulta: " . mysqli_error($conexao);
} else {
    // Se a consulta for bem-sucedida, exibe os dados na página HTML
    $row = mysqli_fetch_assoc($result);
    $nome = $row['nome'];
    $crm = $row['crm'];
    $especialidade = $row['especialidade'];
    $clinica = $row['clinica'];
    $logo = $row['logo'];



    $result1 = infoPaciente($paciente);
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            // Cabeçalho HTML
            $html_header = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>PDF Gerado</title>
            <style>
                /* Estilos para o PDF */
                body { font-family: Arial, sans-serif; }
                h1 { color: #333; text-align: center; }
                h2 { color: #333; }
                p { color: #666; }
            </style>
        </head>
        <body>
        <img src="'.$logo.'" width="50px" height="50px" alt="Sua imagem" />
        <p>'.$clinica.'</p>
        <p> 00.000.000/0000-0</p>
        <p>seuemaildecontato@email.com.br | www.seusite.com.br</p>
        <p>(00) 0000-0000 | (00) 00000-0000</p>
         <hr>
        ';

            // Corpo do HTML
            $html_body = '
            <h2>Médico responsável</h2>
            <p>nome: '.$nome.'</p>
            <p>CRM: '.$crm.'</p>
            <p>Especialidade: '.$especialidade.'</p>
            <hr>
            <h1>Anamnese</h1>
            <h2>Informações do Paciente</h2>
            <p><strong>nome: </strong> ' . $row["nm_paciente"] . '</p>
            <p><strong>data nascimento: </strong> ' . $row["dt_nascimento"] . '</p>
            <p><strong>gênero: </strong> ' . $row["ds_genero"] . '</p>
            <p><strong>estado civil: </strong> ' . $row["ds_estado_civil"] . '</p>
            <p><strong>Nome do pai: </strong> ' . $row["nm_pai"] . '</p>
            <p><strong>Nome da mãe: </strong> ' . $row["nm_mae"] . ' </p>
            <hr>
            <h2>Anamnese</h2>';
            // Adicionando campos dinamicamente
            foreach ($data as $key => $value) {
                $html_body .= '<p><strong>' . htmlspecialchars($key) . ':</strong> <br>' . htmlspecialchars($value) . '</p>';
            }

            $html_body .= '
        </body>
        </html>
        ';

            // HTML completo
            $html = $html_header . $html_body;

            // Carrega o HTML no Dompdf
            $dompdf->loadHtml($html);

            // Renderiza o PDF
            $dompdf->render();
            $pdfOutput = $dompdf->output();
            $pdfFilePath = '../documentos_originais/anamnese/' . hash('sha256', $pdfOutput) . '.pdf'; // Caminho onde o PDF será salvo
            file_put_contents($pdfFilePath, $pdfOutput);

            // Envia o hash do PDF como cabeçalho para o cliente
            header('X-PDF-Hash: ' . hash('sha256', $pdfOutput));
        }
    }
}

exit;

?>