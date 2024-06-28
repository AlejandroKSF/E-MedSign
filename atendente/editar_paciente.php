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
    <link href='../css/cadastro.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <link rel="stylesheet" href="./css/style.css">
    <title>Inicio Médico</title>
</head>

<body>
    <?php
    include ('../connect.php');

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);


        $stmt = $conexao->prepare("SELECT * FROM tb_paciente WHERE cd_paciente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $patient = $result->fetch_assoc();
        } else {
            echo "<script>alert('Paciente não encontrado'); window.location.href = 'pacientes.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Identificação não fornecida'); window.location.href = 'pacientes.php';</script>";;
        exit;
    }

 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = htmlspecialchars($_POST['nome']);
        $genero = htmlspecialchars($_POST['genero']);
        $estado_civil = htmlspecialchars($_POST['estado_civil']);
        $cpf = htmlspecialchars($_POST['cpf']);
        $data_nascimento = htmlspecialchars($_POST['data_nascimento']);
        $cns = htmlspecialchars($_POST['cns']);
        $nome_mae = htmlspecialchars($_POST['nome_mae']);
        $nome_pai = htmlspecialchars($_POST['nome_pai']);
        $rua = htmlspecialchars($_POST['rua']);
        $cidade = htmlspecialchars($_POST['cidade']);
        $bairro = htmlspecialchars($_POST['bairro']);
        $estado = htmlspecialchars($_POST['estado']);
        $cep = htmlspecialchars($_POST['cep']);
        $email = htmlspecialchars($_POST['email']);
        $telefone = htmlspecialchars($_POST['telefone']);
        $convenio = htmlspecialchars($_POST['convenio']);
        $plano = htmlspecialchars($_POST['plano']);
        $numero_carteira = htmlspecialchars($_POST['numero_carteira']);

  
        $stmt = $conexao->prepare(
            "UPDATE tb_paciente 
        SET 
            nm_paciente = ?, 
            ds_genero = ?, 
            ds_estado_civil = ?, 
            cd_cpf = ?, 
            dt_nascimento = ?, 
            cd_cns = ?, 
            nm_mae = ?, 
            nm_pai = ?, 
            nm_rua = ?, 
            nm_cidade = ?, 
            nm_bairro = ?, 
            sg_estado = ?, 
            cd_cep = ?, 
            ds_email = ?, 
            cd_telefone = ?, 
            nm_convenio = ?, 
            nm_plano = ?, 
            cd_numero_carteira = ? 
        WHERE cd_paciente = ?"
        );

        $stmt->bind_param(
            "ssssssssssssssssssi",
            $nome,
            $genero,
            $estado_civil,
            $cpf,
            $data_nascimento,
            $cns,
            $nome_mae,
            $nome_pai,
            $rua,
            $cidade,
            $bairro,
            $estado,
            $cep,
            $email,
            $telefone,
            $convenio,
            $plano,
            $numero_carteira,
            $id
        );

        if ($stmt->execute()) {
            echo "<script>alert('Cadastro atualizado'); window.location.href = 'pacientes.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar paciente'); window.location.href = 'pacientes.php';</script>";
        }
    }
    ?>
    <?php
    include ("../navibar.php");

    session_start();
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;

    if (isset($_GET['id'])) {
        $atendimento = $_GET['id'];
    }
    ?>

    <main class="cabeca-cadastro">
        <div class="cadastro-paciente">
            <div class="titulo-cadastro">
                <h1>Cadastro de paciente</h1>
            </div>

            <form class="formulario-cadastro" method="post" action="editar_paciente.php?id=<?php echo $id; ?>">
                <legend class="titulo-formulario">Dados Pessoais</legend>

                <input type="hidden" name="id_atendimento" value="<?php echo htmlspecialchars($atendimento); ?>">
                <div class="input-group">
                    <input type="text" class="input" id="nome" name="nome"
                        value="<?php echo htmlspecialchars($patient['nm_paciente']); ?>" required>
                    <label for="nome" class="user-label">Nome</label>
                </div>

                <div class="input-group">
                    <label for="genero" class="user-label-sem-efeito">Gênero</label>
                    <select class="seletor" id="genero" name="genero" required>
                        <option value="Masculino" <?php echo $patient['ds_genero'] == 'Masculino' ? 'selected' : ''; ?>>
                            Masculino</option>
                        <option value="Feminino" <?php echo $patient['ds_genero'] == 'Feminino' ? 'selected' : ''; ?>>
                            Feminino</option>
                        <option value="Outro" <?php echo $patient['ds_genero'] == 'Outro' ? 'selected' : ''; ?>>Outro
                        </option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="estado_civil" class="user-label-sem-efeito">Estado Civil</label>
                    <select class="seletor" id="estado_civil" name="estado_civil" required>
                        <option value="Solteiro(a)" <?php echo $patient['ds_estado_civil'] == 'Solteiro(a)' ? 'selected' : ''; ?>>Solteiro(a)</option>
                        <option value="Casado(a)" <?php echo $patient['ds_estado_civil'] == 'Casado(a)' ? 'selected' : ''; ?>>Casado(a)</option>
                        <option value="Divorciado(a)" <?php echo $patient['ds_estado_civil'] == 'Divorciado(a)' ? 'selected' : ''; ?>>Divorciado(a)</option>
                        <option value="Viúvo(a)" <?php echo $patient['ds_estado_civil'] == 'Viúvo' ? 'selected' : ''; ?>>
                            Viúvo(a)</option>
                        <option value="Outro" <?php echo $patient['ds_estado_civil'] == 'Outro' ? 'selected' : ''; ?>>
                            Outro</option>
                    </select>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="cpf" name="cpf"
                        value="<?php echo htmlspecialchars($patient['cd_cpf']); ?>" required>
                    <label for="cpf" class="user-label">CPF</label>
                </div>

                <div class="input-group">
                    <input type="date" autocomplete="off" class="input-data" id="data_nascimento" name="data_nascimento"
                        value="<?php echo htmlspecialchars($patient['dt_nascimento']); ?>" required>
                    <label for="data_nascimento" class="user-label-sem-efeito">Data de Nascimento</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="cns" name="cns"
                        value="<?php echo htmlspecialchars($patient['cd_cns']); ?>" required>
                    <label for="cns" class="user-label">CNS</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="nome_mae" name="nome_mae"
                        value="<?php echo htmlspecialchars($patient['nm_mae']); ?>" required>
                    <label for="nome_mae" class="user-label">Nome da Mãe</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="nome_pai" name="nome_pai"
                        value="<?php echo htmlspecialchars($patient['nm_pai']); ?>" required>
                    <label for="nome_pai" class="user-label">Nome do Pai</label>
                </div>

                <legend class="titulo-formulario">Endereço</legend>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="rua" name="rua"
                        value="<?php echo htmlspecialchars($patient['nm_rua']); ?>" required>
                    <label for="rua" class="user-label">Rua</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="cidade" name="cidade"
                        value="<?php echo htmlspecialchars($patient['nm_cidade']); ?>" required>
                    <label for="cidade" class="user-label">Cidade</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="bairro" name="bairro"
                        value="<?php echo htmlspecialchars($patient['nm_bairro']); ?>" required>
                    <label for="bairro" class="user-label">Bairro</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="estado" name="estado"
                        value="<?php echo htmlspecialchars($patient['sg_estado']); ?>" required>
                    <label for="estado" class="user-label">Estado</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="cep" name="cep"
                        value="<?php echo htmlspecialchars($patient['cd_cep']); ?>" required>
                    <label for="cep" class="user-label">CEP</label>
                </div>

                <legend class="titulo-formulario">Contato</legend>

                <div class="input-group">
                    <input type="email" autocomplete="off" class="input" id="email" name="email"
                        value="<?php echo htmlspecialchars($patient['ds_email']); ?>" required>
                    <label for="email" class="user-label">Email</label>
                </div>

                <div class="input-group">
                    <input type="tel" autocomplete="off" class="input" id="telefone" name="telefone"
                        value="<?php echo htmlspecialchars($patient['cd_telefone']); ?>" required>
                    <label for="telefone" class="user-label">Telefone</label>
                </div>

                <legend class="titulo-formulario">Plano</legend>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="convenio" name="convenio"
                        value="<?php echo htmlspecialchars($patient['nm_convenio']); ?>" required>
                    <label for="convenio" class="user-label">Convênio</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="plano" name="plano"
                        value="<?php echo htmlspecialchars($patient['nm_plano']); ?>" required>
                    <label for="plano" class="user-label">Plano</label>
                </div>

                <div class="input-group">
                    <input type="text" autocomplete="off" class="input" id="numero_carteira" name="numero_carteira"
                        value="<?php echo htmlspecialchars($patient['cd_numero_carteira']); ?>" required>
                    <label for="numero_carteira" class="user-label">Número da Carteira</label>
                </div>

                <button class="buttonPadrao" type="submit"> <span>Salvar</span></button>
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