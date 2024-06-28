<?php
include('connect.php');
$conn = $conexao;

function agendamentosHoje($medico)
{
    global $conn;
    $currentDate = date("Y-m-d");
    $result = mysqli_query($conn, "SELECT * FROM tb_agendamento WHERE dt_data = '" . $currentDate . "' and cd_medico = '".$medico."'and ds_status != 'Finalizado'");
    return $result;
}



function agendamentosProx($medico)
{
    global $conn;
    $nextDate = date("Y-m-d", strtotime("+1 day"));

    // Consulta para recuperar os agendamentos para amanhã
    $result = mysqli_query($conn, "SELECT * FROM tb_agendamento WHERE dt_data = '" . $nextDate . "' and cd_medico = '".$medico."'");
    return $result;
}

function MedicosClinica($clinica)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_funcionario as func join tb_medico as med on med.cd_FuncionarioID = func.cd_funcionario where cd_clinica = '".$clinica."' and med.ic_status = 1");
    return $result;
}

function AtendentesClinica($clinica)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_funcionario as func join tb_atendente as ate on ate.cd_FuncionarioID = func.cd_funcionario where cd_clinica = '".$clinica."'");
    return $result;
}


function Pacientes()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_paciente where ic_status = 1");
    return $result;
}

function Medicos(){
    global $conn;
    $result = mysqli_query($conn, "SELECT func.*, med.*, cli.nm_clinica FROM tb_funcionario as func join tb_medico as med on med.cd_FuncionarioID = func.cd_funcionario join tb_clinica as cli on cli.cd_clinica = func.cd_clinica where ic_status = 1");
    return $result;
}

function Atendentes(){
    global $conn;
    $result = mysqli_query($conn, "SELECT func.*, cli.nm_clinica FROM tb_funcionario as func join tb_atendente as ate on ate.cd_FuncionarioID = func.cd_funcionario join tb_clinica as cli on cli.cd_clinica = func.cd_clinica");
    return $result;
}

function Clinicas(){
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_clinica");
    return $result;
}

function infoPaciente($paciente)
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_paciente WHERE cd_paciente = '" . $paciente . "'");
    return $result;
}

function historico($paciente)
{
    global $conn;
    $result = mysqli_query($conn, "select ag.cd_agendamento as agendamento, ag.hr_horario as horario, ag.dt_data as data, anam.cd_hash_pdf as anam_hash_pdf,anam.cd_hash_pdf_assinado as anam_hash_pdf_assinado, diag.cd_hash_pdf as diag_hash_pdf,diag.cd_hash_pdf_assinado as diag_hash_pdf_assinado, presc.cd_hash_pdf as presc_hash_pdf, presc.cd_hash_pdf_assinado as presc_hash_pdf_assinado, ate.cd_hash_pdf as ate_hash_pdf, ate.cd_hash_pdf_assinado as ate_hash_pdf_assinado from tb_agendamento as ag join tb_anamnese as anam on anam.cd_agendamento = ag.cd_agendamento join tb_diagnostico as diag on diag.cd_agendamento = ag.cd_agendamento join tb_prescricao as presc on presc.cd_agendamento = ag.cd_agendamento join tb_atestado as ate on ate.cd_agendamento = ag.cd_agendamento where ag.cd_paciente = '" . $paciente . "' ORDER BY ag.dt_data DESC, ag.hr_horario DESC;");
    return $result;
}
function suggest()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT cpf FROM paciente");
    return $result;
}

function suggestCid()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_CID");
    return $result;
}

function suggestMedicamento()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM tb_medicamento");
    return $result;
}

function FinImprimir($paciente, $idAgendamento)
{
    global $conn;
    $result = mysqli_query($conn, "select an.cd_hash_pdf as anam_hash, diag.cd_hash_pdf as diag_hash, presc.cd_hash_pdf as presc_hash, ate.cd_hash_pdf as ates_hash from tb_agendamento as ag join tb_anamnese as an on an.cd_agendamento = ag.cd_agendamento join tb_diagnostico as diag on diag.cd_agendamento = an.cd_agendamento join tb_prescricao as presc on presc.cd_agendamento = an.cd_agendamento join tb_atestado as ate on ate.cd_agendamento = ag.cd_agendamento where ag.cd_agendamento = '".$idAgendamento."'");
    return $result;
}

function GetMedicosOn($clinica)
{
    global $conn;
    $result = mysqli_query($conn, "select md.cd_FuncionarioID, func.nm_funcionario, md.ds_Especializacao from tb_funcionario as func join tb_medico as md on md.cd_FuncionarioID = func.cd_funcionario where md.ic_expediente = 1 and func.cd_clinica = '" . $clinica . "'");
    return $result;
}

function GetMedicosOff($clinica)
{
    global $conn;
    $result = mysqli_query($conn, "select md.cd_FuncionarioID, func.nm_funcionario, md.ds_Especializacao from tb_funcionario as func join tb_medico as md on md.cd_FuncionarioID = func.cd_funcionario where md.ic_expediente = 0 and func.cd_clinica = '".$clinica."'");
    return $result;
}

?>