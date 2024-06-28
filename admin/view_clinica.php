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
    <link href='../css/dashboardMedico.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Inicio Médico
    </title>
</head>
<style>
    .titulo-diagnostico-tabela{
        height: 200px;
    }
</style>

<body>
    <?php
    include ("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); 
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;

    $pacienteId = $_GET['id'];
    ?>

    <main class="cabecalho">
        <aside class="tabela">
            <div class="tablesInd">
                <div class="titulos">
                    <h1>Médicos</h1>
                </div>

                <?php

                include ('../main.php');
                $startIcon = "bi bi-caret-right-fill";
                $cancelIcon = "";
                $buttonClass = "buttonsTable";
                $result1 = MedicosClinica($pacienteId);
                if ($result1->num_rows > 0) {
                    // Exibe os agendamentos na tabela
                    echo "<table  class='tabela-diagnostico'>";
                    echo "<thead class='titulo-medico-tabela'><tr class='titulos-tr'><th>Médico</th><th>Especializacao</th><th>Email</th></tr></thead><tbody class='titulo-diagnostico-tabela'>";
                    while ($row = $result1->fetch_assoc()) {
                        echo "<tr class='titulos-td'>
                    <td>" . $row["nm_funcionario"] . " </td>
                    <td>" . $row["ds_Especializacao"] . "</td>
                    <td>" . $row["ds_email"] . "</td>
                    </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo " <h3> Nenhum médico registrado </h3>";
                }

                ?>
            </div>
            <div class="tablesInd">
                <div class="titulos">
                    <h1>Atendentes</h1>
                </div>
                <?php
                $result = AtendentesClinica($pacienteId);
                // Verifica se há resultados
                if ($result->num_rows > 0) {
                    // Exibe os agendamentos na tabela
                    echo "<table class='tabela-diagnostico'>";
                    echo "<thead class='titulo-medico-tabela'> <tr class='titulos-tr'><th>Atendente</th><th>Email</th></tr></thead>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tbody class='titulo-diagnostico-tabela'>
                        <tr tr class='titulos-td'>
                        <td>" . $row["nm_funcionario"] . "</td>
                        <td>" . $row["ds_email"] . "</td>
                        </tr></tbody>";
                    }
                    echo "</table>";
                } else {
                    echo " <h3>Nenhum atendente registrado </h3>";
                }
                ?>
            </div>
        </aside>

    </main>
</body>

</html>