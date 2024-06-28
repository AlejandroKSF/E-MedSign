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
    <link href='../css/form.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <title>Informações Paciente - E-MedSign
    </title>

</head>

<body>
    <?php
    include ("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit();
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;
    ?>

    <?php
    include('../main.php');
   
    $idAgendamento = isset($_GET['id']) ? $_GET['id'] : null;
    $paciente = isset($_GET['paciente']) ? $_GET['paciente'] : null;

    if ($idAgendamento && $paciente) {
        // Função que verifica se o ID do agendamento corresponde ao paciente
        function verificarAgendamento($idAgendamento, $paciente) {
            include('../connect.php');
            $conn = $conexao;
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT COUNT(*) as count FROM tb_agendamento WHERE cd_agendamento = ? AND cd_paciente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $idAgendamento, $paciente);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt->close();
            $conn->close();

            return $row['count'] > 0;
        }

        if (!verificarAgendamento($idAgendamento, $paciente)) {
            // Redireciona para a página de erro se o agendamento não corresponder ao paciente
            header('Location: ../acesso_negado.php');
            exit();
        }

        $result1 = infoPaciente($paciente);
    }
    ?>
    <main class="tela-inicial">
      <div class="tables">

                
<div class="titulo">
            <h1>Nome do paciente </h1>
            </div>
            <aside class="formulario">

                <?php $result1 = infoPaciente($paciente);
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) { ?>
                                            <div class="infor-formulario">

                        <legend class="titulo-formulario">Informações Pessoal</legend>
                        <label class="title"><?= $row["nm_paciente"] ?></label>
                        <label class="itemVar"><?= $row["ds_genero"] ?></label>
                        <label class="itemVar"><?= $row["ds_estado_civil"] ?></label>
                        </div>
                        <div class="infor-formulario">
                        <legend class="titulo-formulario">Endereço</legend>
                        <label class="itemVar">Rua: <?= $row["nm_rua"] ?></label>
                        <label class="itemVar">Bairro: <?= $row["nm_bairro"] ?></label>

                        
                        <label class="title"> Cidade: <?= $row["nm_cidade"] ?></label>
                        <label class="title">Estado: <?= $row["sg_estado"] ?></label> 
                        <label class="title">CEP: <?= $row["cd_cep"] ?></label>
                        </div>



                        <div class="infor-formulario">
                        <legend class="titulo-formulario">Contato</legend>
                        <label class="itemVar">Telefone: <?= $row["cd_telefone"] ?></label>
                        <label class="itemVar">E-mail: <?= $row["ds_email"] ?></label>
                        </div>

                        <div class="infor-formulario">                        
                            <legend class="titulo-formulario">Informações Paciente</legend>
                                <label class="title">Nome de Registro</label>

                                <label class="itemVar"><?= $row["nm_paciente"] ?></label>
                        </div>

                                <div class="infor-formulario">
                                <legend class="titulo-formulario">Informações Adicionais</legend>
                                <label class="itemVar">Mãe: <?= $row["nm_mae"] ?></label>

                                    <label class="itemVar">Pai: <?= $row["nm_pai"] ?></label>

                                    <label class="itemVar">Data de nascimento: <?= $row["dt_nascimento"] ?></label>

                                    <label class="itemVar">CPF: <?= $row["cd_cpf"] ?></label>
                                    </div>

                                    <div class="infor-formulario">
                                    <legend class="titulo-formulario">Informações Plano</legend>
                            <label>CNS: <?= $row["cd_cns"] ?></label>
                            <label>Convênio: <?= $row["nm_convenio"] ?></label>

                            <label class="itemVar">Código do Paciente: <?= $row["cd_paciente"] ?></label>

                            <label>Plano: <?= $row["nm_plano"] ?></label>
                            <label></label>
                            <label>Número da carteira: <?= $row["cd_numero_carteira"] ?></label>

                    </div>

                    <div class="botao">
                <button class="buttonPadrao" onclick='executarAcaoHistorico("<?php echo $idAgendamento ?>", "<?php echo $paciente ?>")'><span>Historico</span></button>
                </div>

                <div class="botao">
                <button class="buttonPadrao" onclick='executarAcaoInicio("<?php echo $idAgendamento ?>", "<?php echo $paciente ?>")'> <span> Iniciar Consulta</span></button>
                </div>

                    <?php
                    }
                }
                ?>
            </aside>

            </div> 

    </main>
</body>

</html>