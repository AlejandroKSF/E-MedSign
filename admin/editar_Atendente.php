<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='../css/cadastroClinica.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Inicio Médico
    </title>

</head>

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
    include ('../connect.php');
    $conn = $conexao;
    $Atendente = $_GET['id'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sqlAtendente = "SELECT func.* FROM tb_atendente as ate join tb_funcionario as func on func.cd_funcionario = ate.cd_FuncionarioID WHERE cd_FuncionarioID = ?";
    $stmt = $conn->prepare($sqlAtendente);
    $stmt->bind_param('i', $Atendente);
    $stmt->execute();
    $result1 = $stmt->get_result()->fetch_assoc();
    $sql = "SELECT cd_clinica, nm_clinica FROM tb_clinica";
    $result = $conn->query($sql);
    $clinics = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clinics[] = $row;
        }
    }

    $conn->close();

    ?>

    <main class="cabeca-cadastro">
        <div class="cadastro-paciente">
            <div class="titulo-cadastro">
                <h1>
                    Cadastrar Atendente
                </h1>
            </div>



            <form class="formulario-cadastro" method="POST" action="update_Atendente.php">

                <aside class="informcao-geral">
                <input type="hidden" name="cd_funcionario" value="<?php echo htmlspecialchars($result1['cd_funcionario']); ?>">
                    <div class="input-group">
                        <input required="" autocomplete="off" class="input" required="" type="text" name="nome"
                            id="nome" value="<?php echo htmlspecialchars($result1['nm_funcionario']); ?>">
                        <label class="user-label">Nome</label>
                    </div>

                    <div class="input-group">
                        <input required="" autocomplete="off" class="input" required="" type="text" name="login"
                            id="login" value="<?php echo htmlspecialchars($result1['nm_login']); ?>">
                        <label class="user-label">Login</label>
                    </div>

                    <div class="input-group">
                        <input required="" autocomplete="off" class="input" required="" type="email" name="email"
                            id="email" value="<?php echo htmlspecialchars($result1['ds_email']); ?>">
                        <label class="user-label">E-mail</label>
                    </div>


                    <div class="input-group">
                        <select required class="input" name="clinica" id="clinica" value="<?php echo htmlspecialchars($result1['cd_clinica']); ?>">
                            <option value="<?php echo htmlspecialchars($result1['cd_clinica']); ?>" disabled selected>Selecione uma clínica</option>
                            <?php foreach ($clinics as $clinic) { ?>
                                <option value="<?php echo $clinic['cd_clinica']; ?>"><?php echo $clinic['nm_clinica']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label class="user-label">Clinica</label>
                    </div>
                </aside>
                <div class="botao-final">
                    <button class="buttonPadrao" value="Agendar"> <span>Salvar</span></button>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open')
        });
    </script>
</body>

</html>