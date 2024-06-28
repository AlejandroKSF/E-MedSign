<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/historico.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <title>Informações Paciente - E-MedSign</title>
</head>

<body>
    <?php
    include ("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); // Certifica-se de que o script pare de executar após o redirecionamento
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    ?>

    <main class="cabeca-menu">
        <div class="tables">
            <div class="titulo-paciente">
                <h1>Histórico do paciente</h1>
            </div>

            <button onclick="goBack()" class="back"><i class="bi bi-backspace"></i>Voltar</button>

            <?php
            include ('../main.php');

            // Verifica se o parâmetro ID do agendamento foi passado na URL
            if (isset($_GET['id'])) {
                // Obtém o ID do agendamento
                $idAgendamento = $_GET['id'];
                $paciente = $_GET['paciente'];
            }

            $result = historico($paciente);

            if ($result && $result->num_rows > 0) {
                // Exibe os agendamentos na tabela
                echo "<div class='tabela-body'><table class='tabela-diagnostico'>";
                echo "<thead class='titulo-diagnostico-tabela'><tr class='titulos-tr'>
                <th>Data do atendimento</th>
                <th>Horário de atendimento</th>
                <th>Anamnese</th>
                <th>Diagnóstico</th>
                <th>Prescrição</th>
                <th>Atestado</th>
                <th>Assinar</th>
                </tr></thead><tbody class='titulo-diagnostico-tabela'>";
                while ($row = $result->fetch_assoc()) {

                    if ($row["diag_hash_pdf_assinado"] != null) {
                        echo "<tr class='titulos-tr'>
                        <td>" . $row["data"] . "</td>
                        <td>" . $row["horario"] . "</td>
                        <td class='btnOpenPDFHistorico' data-folder='anamnese' data-assinado='assinado' data-parametro='" . $row["anam_hash_pdf_assinado"] . "'><i class='bi bi-file-earmark-text'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='diagnostico' data-assinado='assinado' data-parametro='" . $row["diag_hash_pdf_assinado"] . "'><i class='bi bi-file-text'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='prescricao' data-assinado='assinado' data-parametro='" . $row["presc_hash_pdf_assinado"] . "'><i class='bi bi-file-earmark-medical'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='atestado' data-assinado='assinado' data-parametro='" . $row["ate_hash_pdf_assinado"] . "'><i class='bi bi-file-earmark-medical'></i></td>
                        <td></td>
                        </tr>";
                    } else {
                        echo "<tr class='titulos-tr'>
                        <td>" . $row["data"] . "</td>
                        <td>" . $row["horario"] . "</td>
                        <td class='btnOpenPDFHistorico' data-folder='anamnese' data-assinado='Nassinado' data-parametro='" . $row["anam_hash_pdf"] . "'><i class='bi bi-file-earmark-text'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='diagnostico' data-assinado='Nassinado' data-parametro='" . $row["diag_hash_pdf"] . "'><i class='bi bi-file-text'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='prescricao' data-assinado='Nassinado' data-parametro='" . $row["presc_hash_pdf"] . "'><i class='bi bi-file-earmark-medical'></i></td>
                        <td class='btnOpenPDFHistorico' data-folder='atestado' data-assinado='Nassinado' data-parametro='" . $row["ate_hash_pdf"] . "'><i class='bi bi-file-earmark-medical'></i></td>
                        <td  id='botaoAssinar' data-agendamento='" . $row["agendamento"] . "><i class='bi bi-file-earmark-medical'></i><button>assinar</button></td>

                        </tr>";
                    }
                }
                echo "</tbody></table></div>";
            } else {
                echo "<h1>Sem consultas anteriores</h1>";
            }
            ?>
        </div>
    </main>

    <script>
        document.getElementById('botaoAssinar').addEventListener('click', function () {
            var idAgendamento = this.getAttribute('data-agendamento');
            var anamHash = this.getAttribute('data-anam');
            var diagHash = this.getAttribute('data-diag');
            var prescHash = this.getAttribute('data-presc');
            var atesHash = this.getAttribute('data-ates');

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'Assinar_documentos.php?id=' + idAgendamento + '&anam=' + anamHash + '&diag=' + diagHash + '&presc=' + prescHash + '&ates=' + atesHash, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        var response = xhr.responseText;
                        alert('Documentos assinados com sucesso!');
                    } else {
                        alert('Erro ao assinar documentos.');
                    }
                }
            };
            xhr.send();
        });
    </script>
</body>

</html>