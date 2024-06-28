<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
    // Redireciona para a página de erro de acesso negado
    header('Location: ../acesso_negado.php');
    exit(); 
}
$parametro = isset($_GET['arquivo']) ? $_GET['arquivo'] : '';
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$assinado = isset($_GET['assinado']) ? $_GET['assinado'] : ''; // Novo parâmetro indicando se o documento é assinado ou não
if ($assinado == 'Nassinado') {
    $arquivo = '../documentos_originais/' . $folder . '/' . $parametro . '.pdf';
} else {
    $arquivo = '../documentos_assinados/' . $folder . '/' . $parametro;
}

echo $arquivo;
// Verifica se o arquivo existe
if (file_exists($arquivo)) {
    // Define o tipo de conteúdo como PDF
    header('Content-Type: application/pdf');

    // Definindo o Content-Disposition baseado na assinatura
    $disposition = $assinado !== null ? 'inline' : 'attachment';
    header('Content-Disposition: ' . $disposition . '; filename="' . basename($arquivo) . '"');

    // Envia o arquivo para o navegador
    readfile($arquivo);
} else {
    // Se o arquivo não existir, exibe uma mensagem de erro
    echo "O arquivo PDF não foi encontrado.";
}
?>