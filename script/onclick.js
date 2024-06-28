function executarAcao(idAgendamento, cpf) {
    window.location.href = 'form.php?id=' + encodeURIComponent(idAgendamento) + '&paciente=' + encodeURIComponent(cpf);
}

function executarAcaoInicio(idAgendamento, cpf) {
    window.location.href = 'Atendimento_Anamnese.php?id=' + encodeURIComponent(idAgendamento) + '&cpf=' + encodeURIComponent(cpf);
}

function executarAcaoHistorico(idAgendamento, cpf) {
    window.location.href = 'historico.php?id=' + encodeURIComponent(idAgendamento) + '&paciente=' + encodeURIComponent(cpf);
}

function goBack() {
    window.history.back();
}


function gerarPDFAnamnese(id, agendamento) {
    var formData = new FormData();
    formData.append('paciente', id);

    document.querySelectorAll('textarea').forEach(function(textarea) {
        formData.append(textarea.id, textarea.value);
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'gerarpdf.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var pdfHash = xhr.getResponseHeader('X-PDF-Hash');
            console.log(pdfHash);
            enviarPDFParaBancoAnamnese(id, pdfHash, agendamento);
        }
    };
    xhr.send(formData);
}


function gerarPDFAtestado(id, agendamento) {
    var formData = new FormData();
    formData.append('paciente', id);

    document.querySelectorAll('textarea').forEach(function(textarea) {
        formData.append(textarea.id, textarea.value);
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'gerarpdfAtestado.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var pdfHash = xhr.getResponseHeader('X-PDF-Hash');
            console.log(pdfHash);
            enviarPDFParaBancoAtestado(id, pdfHash, agendamento);
        }
    };
    xhr.send(formData);
}



function gerarPDFDiagnostico(id, agendamento) {
    var diagPri = document.getElementById('diagPri').value;
    var tempoDoenca = document.getElementById('TempoDoenca').value;
    
    var diagSecList = listaDiagSec; 

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'gerarpdfDiag.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {

  
            var pdfBase64 = xhr.responseText;
        
            var pdfHash = xhr.getResponseHeader('X-PDF-Hash');

            enviarPDFParaBancoDiag(id, pdfHash, agendamento);
        }
    };
    
    // Monta os dados a serem enviados
    var data = 'diagPri=' + encodeURIComponent(diagPri) +
               '&tempoDoenca=' + encodeURIComponent(tempoDoenca) +
               '&diagSecList=' + encodeURIComponent(JSON.stringify(diagSecList)) +
               '&paciente=' + encodeURIComponent(id);
    
    xhr.send(data);
}


function enviarPDFParaBancoAnamnese(id, pdfHash, agendamento) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserirpdfnobancoAnamnese.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Anamnese salva com sucesso!');
        }
    };
    xhr.send('paciente=' + id + '&pdfHash=' + pdfHash + '&agendamento='+ agendamento);
}

function enviarPDFParaBancoAtestado(id, pdfHash, agendamento) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserirpdfnobancoAtestado.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Atestado salvo com sucesso!');
        }
    };
    xhr.send('paciente=' + id + '&pdfHash=' + pdfHash + '&agendamento='+ agendamento);
}





document.addEventListener("DOMContentLoaded", function() {
    const elementosBtnOpenPDF = document.getElementsByClassName("btnOpenPDF");

    for (let i = 0; i < elementosBtnOpenPDF.length; i++) {
        elementosBtnOpenPDF[i].addEventListener("click", function() {
            const parametro = this.getAttribute("data-parametro");
            const folder = this.getAttribute("data-folder");
            const assinado = this.getAttribute("data-assinado"); // Novo atributo

            if (parametro && folder && assinado !== null) {
                const url = `open_pdf.php?arquivo=${encodeURIComponent(parametro)}&folder=${encodeURIComponent(folder)}&assinado=${encodeURIComponent(assinado)}`; // Passando o novo parâmetro
                window.open(url, "_blank");
            } else {
                console.error("Missing data-parametro, data-folder, or data-assinado attribute.");
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const elementosBtnOpenPDF = document.getElementsByClassName("btnOpenPDFHistorico");

    for (let i = 0; i < elementosBtnOpenPDF.length; i++) {
        elementosBtnOpenPDF[i].addEventListener("click", function() {
            const parametro = this.getAttribute("data-parametro");
            const folder = this.getAttribute("data-folder");
            const assinado = this.getAttribute("data-assinado"); // Novo atributo

            if (parametro && folder && assinado !== null) {
                const url = `open_pdf.php?arquivo=${encodeURIComponent(parametro)}&folder=${encodeURIComponent(folder)}&assinado=${encodeURIComponent(assinado)}`; // Passando o novo parâmetro
                window.open(url, "_blank");
            } else {
                console.error("Missing data-parametro, data-folder, or data-assinado attribute.");
            }
        });
    }
});





function enviarPDFParaBancoDiag(id, pdfHash, agendamento) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserirpdfnobancoDiag.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // PDF inserido no banco de dados com sucesso
            alert('Diagnostico salvo com sucesso!');
        }
    };
    xhr.send('paciente=' + id + '&pdfHash=' + pdfHash + '&agendamento='+ agendamento);
}


