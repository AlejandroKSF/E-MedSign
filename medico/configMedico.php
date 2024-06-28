<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

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
    <link href='../css/ConfigMedico.scss' rel='stylesheet'>
    <link href='../css/navibar.scss' rel='stylesheet'>
    <script src="../script/onclick.js" defer></script>
    <script src="../script/navbar.js" defer></script>


</head>


<body>
    <?php
    include ("../navibar.php");

    session_start();
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type']!== 'medico') {
        // Redireciona para a página de erro de acesso negado
        header('Location: ../acesso_negado.php');
        exit(); // Certifica-se de que o script pare de executar após o redirecionamento
    }
    $menu = generateMenu($_SESSION['user_type'], $permissions);

    // Imprimir o menu
    echo $menu;


    ?>

    <?php
    include ("../connect.php");

    $userId = $_SESSION['user_id'];
    $query = "SELECT config_json FROM tb_medico_config WHERE cd_medico = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($configJson);
    $stmt->fetch();

    $config = json_decode($configJson, true);
    $stmt->close();
    $conexao->close();
    ?>
    <section class="home-section">
        <!-- conteudo -->
        <div class="titulo">
        <h2>Configuração de atendimento Anamnese</h2>
        </div>
        <section class="conteudoPrincipal">
            <section class="centro">

            <legend class="titulo-formulario">Dados Pessoais</legend>

                    <table class="exibicao2">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Queixa Principal</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="QPRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="QPPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Hda</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HdaRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HdaPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Hpp</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HppRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HppPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Histórico Familiar</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HistFamRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HistFamPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Medicamentos</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MedicamentosRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MedicamentosPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Alergias</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AlergiasRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AlergiasPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Observações</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObservacoesRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObservacoesPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <legend class="titulo-formulario">Exame fisico</legend>

                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>

                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Informações Gerais</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="InfGeraisRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="InfGeraisPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Cabeça/Pescoço</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CabecaPescoçoRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CabecaPescoçoPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Torax</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ToraxRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ToraxPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Mmss</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MmssRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MmssPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Abdome</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AbdomeRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AbdomePrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">MMII</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MMIIRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="MMIIPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <legend class="titulo-formulario">Aparelho CardioRespiratório</legend>





                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Ausculta Pulmonar</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AuscPulmonarRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AuscPulmonarPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Ausculta Cardíaca</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AuscCardiacaRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="AuscCardiacaPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>

                        </tbody>
                    </table>

                </div>


                <legend class="titulo-formulario">Sinais Vitais</legend>


                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">PA</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="PARepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="PAPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">FC</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="FCRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="FCPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">FR</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="FRRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="FRPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Táx</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="TaxRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="TaxPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Saturação Oxigênio Sangue</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="SaturacaoRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="SaturacaoPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>

                <legend class="titulo-formulario">Exame Antropométrico</legend>



                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Peso/Altura</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="PesoAlturaRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="PesoAlturaPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Obs Finais</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObsFinaisAntroRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObsFinaisAntroPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Cd</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CdRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CdPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">Circunferência Abdominal</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CircunfAbdominalRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="CircunfAbdominalPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>

                <legend class="titulo-formulario">Observações de Resultados de Exames</legend>

                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Informações gerais</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObsFinaisExaRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ObsFinaisExaPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>
                <legend class="titulo-formulario">Hipótese de Diagnóstico</legend>


                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Hipótese Diagnóstica</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HipDiagnosticaRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="HipDiagnosticaPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <legend class="titulo-formulario">Outros</legend>

                    <table class="exibicao2" style="text-align:left;">
                        <thead>
                            <tr>
                                <th scope="col">Campos</th>
                                <th scope="col">Repetir</th>
                                <th scope="col">Primeira vez</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Resumo do Atendimento</th>

                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ResumoAtendimentoRepetir" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" id="ResumoAtendimentoPrimeira" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="botao">
                <button class="buttonPadrao"> <span>Salvar</span></button>
                </div>
            </section>

        </section>

    </section>

</body>
<script>
    document.querySelector('.buttonPadrao').addEventListener('click', function (e) {
        e.preventDefault();

        const fields = ['QP', 'Hda', 'Hpp', 'HistFam', 'Medicamentos', 'Alergias', 'Observacoes', 'InfGerais', 'CabecaPescoço', 'Torax', 'Mmss', 'Abdome', 'MMII', 'AuscPulmonar', 'AuscCardiaca', 'PA', 'FC', 'FR', 'Tax', 'Saturacao', 'PesoAltura', 'ObsFinaisAntro', 'Cd', 'CircunfAbdominal', 'ObsFinaisExa', 'HipDiagnostica', 'ResumoAtendimento'];
        const selections = {};

        fields.forEach(field => {
            selections[field] = {
                Repetir: document.getElementById(`${field}Repetir`).checked,
                Primeira: document.getElementById(`${field}Primeira`).checked,
            };
        });

        fetch('save_config.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(selections),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Configurações salvas com sucesso!');
                } else {
                    alert('Erro ao salvar configurações.');
                }
            })
            .catch(error => console.error('Erro:', error));
    });



    document.addEventListener('DOMContentLoaded', (event) => {
        const config = <?php echo json_encode($config); ?>;

        if (config) {
            for (const [field, settings] of Object.entries(config)) {
                document.getElementById(`${field}Repetir`).checked = settings.Repetir;
                document.getElementById(`${field}Primeira`).checked = settings.Primeira;
            }
        }
    });
</script>

</html>