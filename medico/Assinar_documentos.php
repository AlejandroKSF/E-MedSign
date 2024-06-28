<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
    header('Location: ../acesso_negado.php');
    exit();
}

include ('../connect.php');
$conn = $conexao;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$idAgendamento = $_GET['id'];

function retrieveFileFromDatabase($conn, $tableName, $idAgendamento) {
    $stmt = $conn->prepare("SELECT cd_hash_pdf FROM tb_$tableName WHERE cd_agendamento = ?");
    $stmt->bind_param("s", $idAgendamento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $filename = '../documentos_originais/' . $tableName . '/' . $row['cd_hash_pdf'] . '.pdf';

        if (file_exists($filename)) {
            return base64_encode(file_get_contents($filename));
        } else {
            return "File does not exist.";
        }
    } else {
        return "Record not found.";
    }
}

function assinarDoc($doc, $outputFilePath) {
    $encodedDocument = $doc;
    $apiKey = ''; // Use environment variable for API key
    $pin = ''; // Use environment variable for PIN

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.cryptocubo.com.br/api/eletronic-signatures/v0/sign/qualified/pdf?profile=adrb&icpbr=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(
            array(
                'alias' => '',
                'pin' => $pin,
                'documents' => array(
                    array('content' => $encodedDocument)
                )
            )
        ),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cache-Control: no-cache',
            'Ocp-Apim-Subscription-Key: ' . $apiKey
        ),
    ));

    $response = curl_exec($curl);
    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        die("cURL Error: $error");
    }

    curl_close($curl);
    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("JSON Decode Error: " . json_last_error_msg());
    }

    $value = '';
    if (isset($decodedResponse['documents'][0]['signatures'][0]['value'])) {
        $value = $decodedResponse['documents'][0]['signatures'][0]['value'];
    } else {
        die("Signature value not found in the response.");
    }

    $decodedValue = base64_decode($value);
    $randomFileName = bin2hex(random_bytes(16)) . '.pdf';
    $outputFilePath = rtrim($outputFilePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $randomFileName;
    $fileSaved = file_put_contents($outputFilePath, $decodedValue);

    if ($fileSaved === false) {
        die("Failed to save the signature as a PDF file.");
    }

    return $randomFileName;
}

function updateDatabase($conn, $tableName, $idAgendamento, $signedFileName) {
    $stmt = $conn->prepare("UPDATE tb_$tableName SET cd_hash_pdf_assinado = ? WHERE cd_agendamento = ?");
    $stmt->bind_param("ss", $signedFileName, $idAgendamento);
    if (!$stmt->execute()) {
        die("Database update failed: " . $stmt->error);
    }
    $stmt->close();
}

$anamnese_base64 = retrieveFileFromDatabase($conn, 'anamnese', $idAgendamento);
$diagnostico_base64 = retrieveFileFromDatabase($conn, 'diagnostico', $idAgendamento);
$prescricao_base64 = retrieveFileFromDatabase($conn, 'prescricao', $idAgendamento);
$atestado_base64 = retrieveFileFromDatabase($conn, 'atestado', $idAgendamento);

$outputFilePathAna = '../documentos_assinados/anamnese/';
$outputFilePathDiag = '../documentos_assinados/diagnostico/';
$outputFilePathPresc = '../documentos_assinados/prescricao/';
$outputFilePathAte = '../documentos_assinados/atestado/';

$anamnese_signed = assinarDoc($anamnese_base64, $outputFilePathAna);
$diagnostico_signed = assinarDoc($diagnostico_base64, $outputFilePathDiag);
$prescricao_signed = assinarDoc($prescricao_base64, $outputFilePathPresc);
$atestado_signed = assinarDoc($atestado_base64, $outputFilePathAte);

updateDatabase($conn, 'anamnese', $idAgendamento, $anamnese_signed);
updateDatabase($conn, 'diagnostico', $idAgendamento, $diagnostico_signed);
updateDatabase($conn, 'prescricao', $idAgendamento, $prescricao_signed);
updateDatabase($conn, 'atestado', $idAgendamento, $atestado_signed);

$conn->close();
?>
