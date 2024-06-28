<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" rel="stylesheet">
    <link href="../css/cadastroClinica.scss" rel="stylesheet">
    <link href="../css/navibar.scss" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <title>Inicio Médico</title>
</head>

<body>
    <?php
    include("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: ../acesso_negado.php');
        exit();
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);
    echo $menu;

    include('../connect.php');
    $conn = $conexao;

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
                <h1>Cadastrar Médico</h1>
            </div>

            <form class="formulario-cadastro" method="POST" action="cadastro_Medico.php">
                <aside class="informcao-geral">
                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="text" name="nome" id="nome">
                        <label class="user-label">Nome</label>
                    </div>

                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="text" name="login" id="login">
                        <label class="user-label">Login</label>
                    </div>

                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="password" name="senha" id="senha">
                        <label class="user-label">Senha</label>
                    </div>

                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="email" name="email" id="email">
                        <label class="user-label">E-mail</label>
                    </div>

                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="text" name="especializacao" id="especializacao">
                        <label class="user-label">Especialização</label>
                    </div>

                    <div class="input-group">
                        <input required autocomplete="off" class="input" type="text" name="crm" id="crm">
                        <label class="user-label">CRM</label>
                    </div>

                    <div class="input-group">
                        <select required class="input" name="clinica" id="clinica">
                            <option value="" disabled selected>Selecione uma clínica</option>
                            <?php foreach ($clinics as $clinic) { ?>
                                <option value="<?php echo htmlspecialchars($clinic['cd_clinica']); ?>"><?php echo htmlspecialchars($clinic['nm_clinica']); ?></option>
                            <?php } ?>
                        </select>
                        <label class="user-label">Clínica</label>
                    </div>
                </aside>
                
                <div class="botao-final">
                    <button class="buttonPadrao" type="submit">Salvar</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('open_btn').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open-sidebar');
            document.body.classList.toggle('menu-open');
        });
    </script>
</body>

</html>
